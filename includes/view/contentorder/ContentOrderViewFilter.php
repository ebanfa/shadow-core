<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class ContentOrderViewFilter extends ViewFilter {


    /**
     *
     */
    public static function init_hooks() { 
        add_filter('shadowbanker_contentorder_form_fields', array('ContentOrderViewFilter', 'filter_form_fields'), 10, 2);
        add_filter('shadowbanker_contentorder_action_links', array('ContentOrderViewFilter', 'filter_view_action_links'), 10, 2);
    }

    /**
     *
     */
    public static function filter_form_fields($view, $form_fields) {
        $page_action = $view->get_page_action();
        $form_fields = parent::filter_form_fields($view, $form_fields);


        if($page_action == 'create') {
           
        }
        return $form_fields;
    }

    /**
     *
     */
    public static function filter_view_action_links($view, $action_links) {
        $page_action = $view->get_page_action();
        
        $action_links = array();
        if($page_action == 'list') {
            $action_links['create_contentorder_link'] = array('name' => 'Add New', 
                'link' => get_site_url() . '/order-now');
        }
        else {
            $action_links['list_contentorder_link'] = array('name' => 'View All', 
                'link' => EntityActionProcessor::get_base_url() . 'page_action=list&artifact=accounttransaction');
        }
        
    
        return $action_links;
    }

}

?>