<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PartyGroupAPI  {

    /**
     *
     */
    public static function do_create_entity($entity_data){

        $entity_data['has_errors'] = false;

        if ($entity_data['edit_mode']) {
            // Create the order
            if(isset($entity_data['entity_code'])){
                if(EntityStringUtils::is_invalid_string($entity_data['entity_code'])) {
                    $entity_data['entity_code'] = EntityStringUtils::get_token(8);
                }
            }
            else {
                $entity_data['entity_code'] = EntityStringUtils::get_token(8);
            }
            // Set up the business unit
            $business_unit = BusinessUnitAPI::get_current_user_business_unit();
            if (isset($business_unit['id'])) {
                $entity_data['business_unit'] = $business_unit['id'];
            }
            // Create the party
            $party_data = self::create_party($entity_data);
            if(isset($party_data['id'])){ 
                $entity_data['party'] = $party_data['id']; 
                $entity_data['name'] = $party_data['name'];
                $entity_data['description'] = $party_data['description'];
            }
            // Create the party role
            $entity_data = self::create_party_role($entity_data);
            EntityRequestUtils::copy_fields_to_post($entity_data);
            $entity_data = EntityPersistenceAPI::create_entity($entity_data);

        } else {

            $party_data = self::create_party($entity_data);
            if(isset($party_data['id'])) { 
                $entity_data['party'] = $party_data['id']; 
                $entity_data['name'] = $party_data['name'];
                $entity_data['description'] = $party_data['description'];
            }
            EntityRequestUtils::copy_fields_to_post($entity_data);
            // Edit mode dont need redirect...for now
            $entity_data['requires_redirect'] = false;
            $entity_data = EntityPersistenceAPI::update_entity($entity_data);
        }
        
        if(is_wp_error($entity_data['id'])) {
            $entity_data['has_errors'] = true;
            $entity_data['error_message'] = $post_id->get_error_message();
        }

        if(isset($_POST['role'])) {
            $party_data['extra_url_params'] = array(
                'role' => EntityRequestUtils::get_query_string_field('role')
            );
        }
        return $party_data;
    }

    /**
     *
     */
    public static function create_party($entity_data) {

        $party_data = EntityAPIUtils::init_entity_data('party');

        if($entity_data['edit_mode']) {
            $party_data['edit_mode'] = true;
            $party_type = EntityAPI::get_by_code('partytype', 'ORGANIZATION');
            $party_data['party_type'] = $party_type['id'];
            //$party_data['business_unit'] = $entity_data['business_unit'];
        }
        else {
            // First we need to load the entity from the db
            // So we can retrieve the id of the parent party
            if(isset($entity_data['id'])) {
                $saved_entity_data = EntityAPI::get_by_id('partygroup', $entity_data['id']);
                $parent_party_data = EntityAPI::get_by_id('party', $saved_entity_data['party']);
                $parent_party_data['edit_mode'] = false;
                $party_data = array_merge($parent_party_data, $party_data);
                $entity_data['business_unit'] = $saved_entity_data['business_unit'];
            }
        }
        // Set the name on the part and on the entity
        $party_data['name'] = $entity_data['name'];
        $party_data['description'] = $entity_data['description'];
        $party_data['business_unit'] = $entity_data['business_unit'];

        //$party_data = CloderiaAPIUtils::validate_entity_data($party_data);
        $party_data = EntityAPI::create_entity($party_data);
        
        return $party_data;
    }

    /**
     *
     */
    public static function create_party_role($entity_data) {

        if($entity_data['edit_mode']) {
            if(isset($_POST['role'])) {

                $entity_data['role'] = sanitize_text_field($_POST['role']);
                $party_role_data = EntityAPIUtils::init_entity_data('partyrole');
                $role_type = EntityAPI::get_by_code('roletype', strtoupper($entity_data['role']));

                $party_role_data['edit_mode'] = true;
                $party_role_data['role'] = $role_type['id'];
                $party_role_data['party'] = $entity_data['party'];
                $party_role_data['parent_unit'] = $entity_data['business_unit'];
                $party_role_data['business_unit'] = $entity_data['business_unit'];
                $party_role_data['name'] = $entity_data['name'];
                $party_role_data['description'] = $entity_data['name'];

                //$party_role_data = CloderiaAPIUtils::validate_entity_data($party_role_data);
                $party_role_data = EntityAPI::do_create_entity($party_role_data);

            }
        }
        return $entity_data;
    }

}
