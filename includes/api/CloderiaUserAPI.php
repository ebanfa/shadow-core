<?php

/*
 * 
 */
if (!defined('ABSPATH')) {
    exit; //Exit if accessed directly
}

class CloderiaUserAPI {

     /*
     * 
     **/
    public static function do_create_content_user($content_data) {

        $user_data = ContentUserLoginAPI::build_signup_data($content_data);
        $user_id = wp_insert_user($user_data);
        if (is_wp_error($user_id)) return array('hasErrors' => true, 'message' => $user_id->get_error_message());

        self::update_new_user_meta($user_id, $user_data);
        return self::create_shadow_user($user_data);
    }

    /*
     * 
     **/
    public static function update_new_user_meta($user_id, $user_data) {
        update_user_meta($user_id, "last_name", $user_data['first_name']);
        update_user_meta($user_id, 'user_email', $user_data['user_email']);
        update_user_meta($user_id, "first_name", $user_data['first_name']);
        update_user_meta($user_id, "display_name", $user_data['display_name']);
    }

    /**
     * 
     */
    public static function create_shadow_user($user_data) {
        $party_data = array();
        // Validate the passed in data
        if(self::validate_user_data($user_data)) {
            CloderiaLogUtils::shadow_log('Got context1...');
            // 1. Create the default business for the party
            $business_data = self::create_default_user_business($user_data);
            CloderiaLogUtils::shadow_log('Got context2...');
            // 2. Create the default buisness unit for the party
            $businessunit_data = self::create_default_user_businessunit($business_data);
            CloderiaLogUtils::shadow_log('Got context3...');
            // 3. Create the party
            $party_data = self::create_party($user_data, $businessunit_data);
            CloderiaLogUtils::shadow_log('Got context4...');
            // 4. Create the person entity for the party
            $person_data = self::create_party_person($party_data);
            CloderiaLogUtils::shadow_log('Got context5...');
            // 5. Create the default party role
            $partyprofile_data = self::create_party_profile($party_data);
            CloderiaLogUtils::shadow_log('Got context6...');
            // 6. Create the profile for the user
            $partyrole_data = self::create_default_party_role($party_data);
            CloderiaLogUtils::shadow_log('Got context7...');
            // 7. Create the chart of accounts entity
            //$chartofaccounts_data = CloderiaUserAPI::create_default_party_chartofaccounts($businessunit_data, $partyrole_data);
            // 8. Send the user successully created email
            self::send_user_created_email($user_data, $party_data);
        }
        return $party_data;
    }

    /**
     * Each party has a default business.
     * 
     */
    public static function create_default_user_business($user_data){
        $entity_data = EntityAPIUtils::init_entity_data('business');
        $currency_data = EntityAPI::get_by_code('currency', get_option('cp_default_currency'));
        if(isset($currency_data['id'])) {
            
            $entity_data['edit_mode'] = true;
            $entity_data['currency'] = $currency_data['id'];
            $entity_data['name'] = $user_data['business_name'];
            $entity_data['user_name'] = $user_data['user_login'];
            $entity_data['description'] = $user_data['business_name'];

            $entity_data = EntityAPI::create_entity($entity_data);
        }
        return $entity_data;
    }

    /**
     * Each party has a default business unit.
     * 
     */
    public static function create_default_user_businessunit($business_data) {
        $entity_data = EntityAPIUtils::init_entity_data('businessunit');
        if(isset($business_data['id'])) {
            $entity_data['edit_mode'] = true;
            $entity_data['address_1'] = '0000000000'; 
            $entity_data['address_2'] = '0000000000'; 
            $entity_data['name'] = $business_data['name'];
            $entity_data['business'] = $business_data['id'];
            $entity_data['description'] = $business_data['description'];
            $entity_data = EntityAPI::create_entity($entity_data);
        }
        return $entity_data;
    }



    /**
     * This creates the party for the current user
     */
    public static function create_party($user_data, $businessunit_data) {
        // Get the default party type (INDIVIDUAL)
        CloderiaLogUtils::shadow_log('create_party1...');
        $party_type = EntityAPI::get_by_code('partytype', get_option('cp_default_partytype'));
        CloderiaLogUtils::shadow_log('create_party2...');
        if(isset($party_type['id']) && isset($businessunit_data['id'])) {
            CloderiaLogUtils::shadow_log('create_party3...');
            $entity_data = EntityAPIUtils::init_entity_data('party');
            CloderiaLogUtils::shadow_log('create_party4...');
            $entity_data['edit_mode'] = true;
            $entity_data['has_errors'] = false;
            $entity_data['party_type'] = $party_type['id'];
            CloderiaLogUtils::shadow_log('create_party5...');
            $entity_data['password'] = $user_data['user_pass'];
            CloderiaLogUtils::shadow_log('create_party6...');
            $entity_data['user_name'] = $user_data['user_login'];
            CloderiaLogUtils::shadow_log('create_party7...');
            $entity_data['business_unit'] = $businessunit_data['id'];
            CloderiaLogUtils::shadow_log('create_party8...');
            $entity_data['name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
            CloderiaLogUtils::shadow_log('create_party9...');
            $entity_data['description'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
            // These two fields are not persistent fields, we just use the to hold the
            // data for first and last name
            $entity_data['first_name'] = $user_data['first_name'];
            CloderiaLogUtils::shadow_log('create_party10...');
            $entity_data['last_name'] = $user_data['last_name'];
            CloderiaLogUtils::shadow_log('create_party11...');
            // Create the party and return the results of the process
            $entity_data = EntityAPI::create_entity($entity_data);
            CloderiaLogUtils::shadow_log('create_party12...');
            return $entity_data;

        }
        return array();
    }

    
    
    /**
     * Only a person can sign up within the system. This creates the person
     * entity for the party
     */
    public static function create_party_person($party_data) {
        $entity_data = EntityAPIUtils::init_entity_data('person');

        if(isset($party_data['id'])) {
            $entity_data['edit_mode'] = true;
            $entity_data['party'] = $party_data['id'];
            $entity_data['name'] = $party_data['name'];
            $entity_data['first_name'] = $party_data['first_name'];
            $entity_data['last_name'] = $party_data['last_name'];
            $entity_data['gender'] = 'X';
            $entity_data['id_number'] = '0000000000';
            $entity_data['date_of_birth'] = date("Y-m-d H:i:s");
            $entity_data['business_unit'] = $party_data['business_unit'];
            // Call do create here to prevent a duplicate party object
            //  from being created by PersonAPI
            $entity_data = EntityAPI::do_create_entity($entity_data);
        }
        return $entity_data;
    }

    /**
     * Each party has a party profile that holds profile related information 
     * for the user. This is stuff like profile picture, display name, status etc.
     * Each profile has a default business unit which dictates the data that the
     * current user is allowed to access. The second business unit parameter is
     * business unit in which this profile is defined
     */
    public static function create_party_profile($party_data) {
        $entity_data = EntityAPIUtils::init_entity_data('partyprofile');

        if(isset($party_data['id'])) {
            $entity_data['edit_mode'] = true;
            $entity_data['party'] = $party_data['id'];
            $entity_data['name'] = $party_data['name'];
            $entity_data['display_name'] = $party_data['name']; 
            $entity_data['date_created'] = date("Y-m-d H:i:s");
            $entity_data['default_unit'] = $party_data['business_unit'];
            $entity_data['business_unit'] = $party_data['business_unit'];
            $entity_data = EntityAPI::create_entity($entity_data);
        }
        return $entity_data;
    }

    /**
     * Upon signup, a business unit is created for the new party. Since this
     * is the business unit of the new user, we create a new party role of role
     * type 'BUSINESS_OWNER' for the new user.
     */
    public static function create_default_party_role($party_data) {
        $entity_data = EntityAPIUtils::init_entity_data('partyrole');
        $owner_role_data = EntityAPI::get_by_code('roletype', 'BUSINESS_OWNER');

        if(isset($owner_role_data['id']) && isset($party_data['id'])) {
            $entity_data['edit_mode'] = true;
            $entity_data['name'] = $party_data['name'];
            $entity_data['role'] = $owner_role_data['id'];
            $entity_data['party'] = $party_data['id'];
            $entity_data['parent_unit'] = $party_data['business_unit'];
            $entity_data['business_unit'] = $party_data['business_unit'];
            $entity_data['description'] = 'Default role ' . $owner_role_data['name'] . ' for party ' . $party_data['name'];
            $entity_data = EntityAPI::create_entity($entity_data);
        }
        return $entity_data;

    }

    /**
     * Create the chart of accounts for a specific party role. Each role type has a mapping to
     * account structure which is used to specify the structure of the chart of account for 
     * the said role type.
     */
    public static function create_default_party_chartofaccounts($partyrole_data) {
        /*$entity_data = array();
        if(isset($partyrole_data['id']) && isset($businessunit_data['id'])) {
            // 1. Use the mapping of party role to account structure to
            // the appropriate account structure to use for the COA we care creating
            $role_type_data = RoleTypeAPI::get_by_code($partyrole_data['role']);
            if(isset($role_type_data['id'])) {
                $role_mapping_data = RoleTypeAccountStructureAPI::get_by_meta('role');
                if(isset($role_mapping_data['id'])) {
                    $entity_data['edit_mode'] = true;
                    $entity_data['role'] = $partyrole_data['id'];
                    $entity_data['name'] = $partyrole_data['name'];
                    $entity_data['structure'] = $role_mapping_data['id'];
                    $entity_data['business_unit'] = $businessunit_data['id'];
                    $entity_data['description'] = 'Chart of accounts for ' . $coa_name;
                    $entity_data = CloderiaAPIUtils::create_entity($entity_data);
                }
            }
        }
        return $entity_data;*/
    }

    /**
     * 
     */
    public static function validate_user_data($user_data) {
        /*if(!isset($user_data['first_name']) || !isset($user_data['last_name'])) {
            return false;
        }
        if(!isset($user_data['user_login']) || !isset($user_data['user_pass'])) {
            return false;
        }
        if(!isset($user_data['display_name'])) {
            return false;
        }*/
        return true;
    }

    /**
     * 
     */
    public static function send_user_created_email($user_data) 
    {   
        // Find the user
        $user = get_user_by('login', $user_data['user_login'] );
        if($user) {
            // Get the user data context {username, password etc}
            $data_context = self::get_user_data_context($user);
            //update_user_meta($user->ID, 'userDataContext', implode("|", $data_context));
            // 2. Send mail to user,
            self::send_email($data_context, $user->user_login, 'site-account-created-subject.tpl', 'site-account-created-message.tpl', array());
            // 3 Send mail to the admin
            self::send_email($data_context, get_option('cp_notify_accounts'), 'site-account-created-subject.tpl', 'site-account-created-message.tpl', array());
        }
    }

    /**
     * 
     */
    public static function send_email($data_context, $address, $subject_templ, $message_templ, $attachment) 
    {
        // Load the subject and message templates
        $msg_templ = file_get_contents(dirname(dirname(dirname(__FILE__))) .'/email_templates/' . $message_templ);
        $msg_sub_templ = file_get_contents(dirname(dirname(dirname(__FILE__))) .'/email_templates/' . $subject_templ);
        // Fill the templates
        $msg_templ = EntityStringUtils::parse($msg_templ, $data_context);
        $msg_sub_templ = EntityStringUtils::parse($msg_sub_templ, $data_context);
        // Send the email
        wp_mail($address, $msg_sub_templ, $msg_templ, '', $attachment);
    }

    /**
     * 
     */
    public static function get_user_data_context($user)
    {
        $data_context = array('user_name' => $user->user_login, 'password' => $user->user_pass,
            'first_name' => $user->first_name, 'last_name' => $user->last_name);
        $data_context['email'] = $user->user_login;
        $data_context['display_name'] = get_user_meta($user->ID, 'display_name', true);
        $data_context['site_url'] = get_site_url();
        $data_context['site_name'] = get_bloginfo('name');
        $data_context['site_descriptions'] = get_bloginfo('descriptions');
        $data_context['site_email'] = get_option('cp_notify_accounts');
        $data_context['site_domain'] = get_option('cp_site_domain');
        $data_context['site_stylesheet_uri'] = get_stylesheet_directory_uri();
        return $data_context;
    }

    

}
