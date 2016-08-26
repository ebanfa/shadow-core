<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class PartyViewFilter extends ViewFilter {

    /**
     *
     */
    public static function init_hooks() { 
        add_filter('shadowbanker_party_view_title', array('PartyViewFilter', 'filter_view_title'), 10, 2);
        add_filter('shadowbanker_party_form_fields', array('PartyViewFilter', 'filter_form_fields'), 10, 2);
        add_filter('shadowbanker_party_action_links', array('PartyViewFilter', 'filter_view_action_links'), 10, 2);

        add_filter('shadowbanker_partygroup_form_fields', array('PartyViewFilter', 'filter_partygroup_form_fields'),10,2);
        add_filter('shadowbanker_partygroup_action_links', array('PartyViewFilter', 'filter_view_action_links'), 10, 2);

        add_filter('shadowbanker_person_action_links', array('PartyViewFilter', 'filter_view_action_links'), 10, 2);
        add_filter('shadowbanker_person_form_fields', array('PartyViewFilter', 'filter_person_form_fields'), 10, 2);
    }

    /**
     *
     */
    public static function filter_form_fields($view, $form_fields) {
        $page_action = $view->get_page_action();
        $role = sanitize_text_field($_REQUEST['role']);
        $form_fields = parent::filter_form_fields($view, $form_fields);
        if(isset($_REQUEST['role'])) {
            if($page_action == 'list') {
                $form_fields = array();
                $field = array();
                $field['view_criteria'] = array('role' => $role);
                $field['options_criteria'] = array('role' => $role);
                array_push($form_fields, $field);
            }
            else {
                $role_field = array();
                $role_field['name'] = 'role';
                $role_field['data_type'] = 'hidden';
                $role_field['is_relationship_field'] = false;
                $role_field['view_criteria'] = array('role' => $role);
                $role_field['options_criteria'] = array('role' => $role);
                $role_field['value'] = $role;
                $form_fields['role'] = $role_field;
            }
        }
        return $form_fields;
    }

    /**
     *
     */
    public static function filter_view_title($view, $title) {
        $title = parent::filter_view_title($view, $title);
        if(isset($_REQUEST['role'])) {
            $role = sanitize_text_field($_REQUEST['role']);
            $role_type_data = EntityAPI::get_by_code('roletype', array('entity_code' => strtoupper($role)));
            if(isset($role_type_data['id']))
                $title = $role_type_data['name'];
        }
        return $title;
    }

    /**
     *
     */
    public static function filter_view_action_links($view, $action_links) {
        $page_action = $view->get_page_action();
        $party_role_param = '';
        $role = '';
        if(isset($_REQUEST['role'])) {
            $role = sanitize_text_field($_REQUEST['role']);
            $party_role_param = '&role=' . $role;
        }
        //$action_links = parent::filter_view_action_links($view, $action_links);
        $action_links = array();
        if($page_action == 'create' || $page_action == 'edit') {
            // Add Person link
            $action_links['list_entity_link'] = array('name' => 'View All', 
                'link' => EntityActionProcessor::get_base_url() . 'page_action=list&artifact=party'. $party_role_param);
        }
        if($page_action == 'list') {
            if($role != 'utility_company')
            // Add Person link
            $action_links['create_person_link'] = array('name' => 'Add New Person', 
                'link' => EntityActionProcessor::get_base_url() . 'page_action=create&artifact=person' . $party_role_param);
            // Add Organization link
            if($role != 'business_user')
                $action_links['create_organization_link'] = array('name' => 'Add New Organization', 
                    'link' => EntityActionProcessor::get_base_url() . 'page_action=create&artifact=partygroup' . $party_role_param);
        }
        if($page_action == 'view') {
            // Add Person link
            $action_links['create_person_link'] = array('name' => 'Add New Person', 
                'link' => EntityActionProcessor::get_base_url() . 'page_action=create&artifact=person' . $party_role_param);
            // Add Organization link
            $action_links['create_organization_link'] = array('name' => 'Add New Organization', 
                'link' => EntityActionProcessor::get_base_url() . 'page_action=create&artifact=partygroup' . $party_role_param);

            $action_links['list_party_link'] = array('name' => 'View All', 
                'link' => EntityActionProcessor::get_base_url() . 'page_action=list&artifact=party' . $party_role_param);
        }
        return $action_links;
    }

    /**
     *
     */
    public static function filter_partygroup_form_fields($view, $form_fields) {
        $page_action = $view->get_page_action();
        $form_fields = parent::filter_form_fields($view, $form_fields);
        if($page_action == 'create' || $page_action == 'edit') {
            
            if(isset($_REQUEST['role'])) {

                $role_field = array();
                $role_field['name'] = 'role';
                $role_field['data_type'] = 'hidden';
                $role_field['is_relationship_field'] = false;
                $role_field['value'] = sanitize_text_field($_REQUEST['role']);
                $form_fields['role'] = $role_field;
            }

        }
        return $form_fields;
    }

    /**
     *
     */
    public static function filter_person_form_fields($view, $form_fields) {
        $page_action = $view->get_page_action();
        $form_fields = parent::filter_form_fields($view, $form_fields);
        if($page_action == 'create' || $page_action == 'edit') {
            foreach ($form_fields as $key => $field) {
                if($field['name'] == 'gender') {
                    $field['has_options'] = true;
                    $field['options'] = array();
                    array_push($field['options'], array('name' => 'Male', 'value' => 'M'));
                    array_push($field['options'], array('name' => 'Female', 'value' => 'F'));
                    $form_fields[$key] = $field;
                }
            }
            // Inject an email field
            $email_field = array();
            $email_field['name'] = 'user_email';
            $email_field['data_type'] = 'email';
            $email_field['is_relationship_field'] = false;
            $email_field['is_visible'] = true;
            $email_field['is_required'] = true;
            $email_field['is_create_field'] = true;
            $email_field['is_edit_field'] = true;
            $email_field['is_edit_field'] = true;
            $email_field['is_form_field']  = true;
            $email_field['col_size'] = 'col-xs-12';
            $email_field['description'] = 'Email';
            //$email_field['value'] = sanitize_text_field($_REQUEST['category']);
            $form_fields['email'] = $email_field;
            
            if(isset($_REQUEST['role'])) {

                $role_field = array();
                $role_field['name'] = 'role';
                $role_field['data_type'] = 'hidden';
                $role_field['is_relationship_field'] = false;
                $role_field['value'] = sanitize_text_field($_REQUEST['role']);
                $form_fields['role'] = $role_field;
            }

        }
        return $form_fields;
    }

}

?>