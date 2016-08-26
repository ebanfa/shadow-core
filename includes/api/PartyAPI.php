<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PartyAPI {

    /**
     *
     */
    public static function do_create_entity($entity_data){
        $entity_data =  EntityAPI::do_create_entity($entity_data);
        $billingaccount_data = self::create_billing_account($entity_data);
        return $entity_data;
    }

    /**
     *
     */
    public static function do_find_entity($entity_data) {
        $role_type = EntityRequestUtils::get_query_string_field('role');
        if($role_type) return  self::find_by_role($entity_data, $role_type); 
        return  EntityAPI::do_find_entity($entity_data); 
    }

    /**
     * Get all parties with a given roles
     */
    public static function find_by_role($entity_data, $role) {

        $search_results = array();
        // Get the role 
        $role_type = EntityAPI::get_by_code('roletype', strtoupper($role));
        // Process only if we got a valid response from the call above
        if(isset($role_type['id']) && isset($role_type['entity_code'])) {
            if($role === 'user_organization') {
                $search_results = self::find_user_organizations();
            } else {
                $party_ids = array();
                // Search for all the party role type associations with the given role
                $party_roles = EntityAPI::find_by_criteria('partyrole', array('role' => $role_type['id']));
                // Loop throug all the return party roles 
                // and push the party id into list of parties
                foreach ($party_roles as $party_role) {
                    array_push($party_ids, $party_role['party']);
                }
                // Doing all this shit cause I am not good at SQL :-(
                if(!empty($party_ids)) {
                    $results = EntityAPI::find_by_ids_and_criteria('party', $party_ids);
                    foreach ($results as $key => $party_data) {
                        foreach ($party_roles as $party_role) {
                            if($party_data['id'] == $party_role['party'])
                                array_push($search_results, $party_data);
                        }
                    }
                }
            }
        }
        return $search_results;
    }

    /**
     * Get all the role types that a party has
     */
    public static function find_user_organizations() {
        $search_results = array();
        $current_user_party = self::get_current_user_party();

        if(isset($current_user_party['id'])) {
            $organization_ids = array();
            // Get the business units of the user party
            $business_units = self::find_user_business_units($current_user_party['id']);
            foreach ($business_units as $business_unit) {
                array_push($organization_ids, $business_unit['party']);
            }
            $organization_ids = array_unique($organization_ids);
            // Find all the roles of the parent party
            // and find1 those that are of role type user_organization
            foreach ($organization_ids as $organization_id) {
                $user_organization_role = EntityAPI::get_by_code('roletype', 'USER_ORGANIZATION');
                if(isset($user_organization_role['id'])) {
                    $party_role = EntityAPI::find_by_criteria('partyrole', array('party' => $organization_id, 'role' => $user_organization_role['id']));
                    if(isset($party_role['id'])) {
                        array_push($search_results, $organization_id);
                    }
                }
            }
            return EntityAPI::find_by_ids('party', $search_results, array());
        }
        return $search_results;
    }

    /**
     * Get all the role types that a party has
     */
    public static function find_user_business_units($party_id) {
        $search_results = array();
        if($party_id) {
            // Fins all the party roles of the current user party
            $party_roles = EntityAPI::find_by_criteria('partyrole', array('party' => $party_id));
            // Get the ids of all the business units of the party roles
            $business_unit_ids = array();
            foreach ($party_roles as $party_role) {
                array_push($business_unit_ids, $party_role['business_unit']);
            }
            // Get the business units 
            $business_unit_ids = array_unique($business_unit_ids);
            return EntityAPI::find_by_ids('businessunit', $business_unit_ids, array());
        }
        return $search_results;
    }

    /**
     * Get the WP_User object of the party witht the provided id
     */
    public static function get_party_user($party_id){
        $user_data = array();
        $party_data = EntityAPI::get_by_id('party', $party_id); 
      
        if(isset($party_data['id'])){       
            $user = get_user_by('login', $party_data['user_name']); 
            if($user){
                $user_data['id'] = $user->ID;
                $user_data['user_name'] = $user->user_login;
            }
        }
       return $user_data;
    }

    /**
     * Get the party of the user with the provided id
     */
    public static function get_user_party($user_id){
        $user = get_user_by('id', $user_id);
        return EntityAPI::get_by_field('party', 'user_name', $user->user_login);
    }

    /**
     * Get the party of the currently logged in user
     */
    public static function get_current_user_party(){
        $user_party = array();
        $current_user = wp_get_current_user();
        if ($current_user) {
           $user_party = self::get_user_party($current_user->ID);
        }
        return $user_party;
    }

    /**
     *
     */
    public static function create_billing_account($party_data) {

        $billingaccount_data = EntityAPIUtils::init_entity_data('billingaccount');
        if(isset($party_data['id'])) {
            $billingaccount_data['balance'] = 0;
            $billingaccount_data['edit_mode'] = true;
            $billingaccount_data['party'] = $party_data['id'];
            $billingaccount_data['name'] = $party_data['name'];
            $billingaccount_data['date_created'] = date("Y-m-d H:i:s");
            $billingaccount_data['description'] = $party_data['description'];
            $billingaccount_data['business_unit'] = $party_data['business_unit'];
            $billingaccount_data =  EntityAPI::create_entity($billingaccount_data); 
        }
        return $billingaccount_data;
    }

    /**
     *
     */
    public static function do_notification($entity_data) {

        if(!$entity_data['has_errors']&& $entity_data['edit_mode']) {
            $notification_data = array();
            $notification_data['data'] = $entity_data;
            $notification_data['code'] = $entity_data['entity_code'];
            $notification_data['name'] = $entity_data['name'];

            $business_unit = EntityAPI::get_by_id('businessunit', $entity_data['business_unit']);
            if(isset($business_unit['id'])) {
                $notification_data['business_unit'] = $business_unit['name'];
            }
            
            /*$notification_data['n_type'] = NotificationAPI::$party_created;
            if(isset($_REQUEST['role'])) {
                $role = sanitize_text_field($_REQUEST['role']);
                $role_type = EntityAPI::get_by_code('roletype', strtoupper($role));
                if(isset($role_type['id'])) {
                    $notification_data['role'] = $role_type['name'];
                }
            } 
            // Set the creator
            $current_user_party = self::get_current_user_party();
            if(isset($current_user_party['id'])) {
                $notification_data['n_owner'] = $current_user_party['id'];
            }
            else { $notification_data['n_owner'] = $entity_data['id']; }
            $notification_data['log_level'] = NotificationAPI::$info;
            NotificationAPI::do_notification($notification_data);*/
        }
    }


}
