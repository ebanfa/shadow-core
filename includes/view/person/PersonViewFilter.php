<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class PersonViewFilter extends ViewFilter {

    /**
     *
     */
    public static function init_hooks() { 
        add_filter('shadowbanker_person_view_title', array('PersonViewFilter', 'filter_view_title'), 10, 2);
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

}

?>