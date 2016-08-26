<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class BusinessViewFilter extends ViewFilter {

    /**
     *
     */
    public static function init_hooks() { 
        /*add_filter('shadowbanker_business_form_fields', array('BusinessViewFilter', 'filter_form_fields'), 10, 2);*/
        add_filter('shadowbanker_business_action_links', array('BusinessViewFilter', 'filter_view_action_links'), 10, 2);
    }

    /**
     *
     */
    public static function filter_view_action_links($view, $action_links) {
        return ViewUtils::get_create_entity_action_links($view->get_page_action(), $view->get_artifact_name());
    
    }

}

?>