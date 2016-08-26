<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class ViewFilter {

    /**
     *
     */
    public static function init_hooks() {
        
    }

    /**
     *
     */
    public static function filter_form_fields($view, $form_fields) {
        return $form_fields;
    }

    /**
     *
     */
    public static function filter_view_title($view, $title) {
        return $title;
    }

    /**
     *
     */
    public static function filter_view_action_title($view, $title) {
        return $title;
    }

    /**
     *
     */
    public static function filter_view_action_description($view, $description) {
        return $description;
    }

    /**
     *
     */
    public static function filter_view_action_links($view, $action_links) {
        return $action_links;
    }

}

?>