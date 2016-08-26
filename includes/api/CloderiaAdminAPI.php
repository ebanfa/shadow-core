<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
class CloderiaAdminAPI {

    /**
     * Action function to setup Ajax
     */
    public static function do_ajax_setup() {
        wp_enqueue_script('contentport_ajax', plugins_url('/js/init.js', __FILE__) , array('jquery'), true);
        wp_localize_script('contentport_ajax', 'blitzdocument_ajax_script', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    /**
     * Action function to remove the admin bar for
     * non admin users
     */
    public static function do_remove_admin_bar() {
        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }
    }

}

