<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class EntityActionProcessor {



    /**
     *
     */
    public static function init_hooks() {
        add_action('wp_ajax_create_entity_ajax', 'EntityActionProcessor::create_entity_ajax');
        add_action('wp_ajax_nopriv_create_entity_ajax', 'EntityActionProcessor::create_entity_ajax');

        add_action('wp_ajax_edit_entity_ajax', 'EntityActionProcessor::edit_entity_ajax');
        add_action('wp_ajax_nopriv_edit_entity_ajax', 'EntityActionProcessor::edit_entity_ajax');

        add_action('wp_ajax_view_entity_ajax', 'EntityActionProcessor::view_entity_ajax');
        add_action('wp_ajax_nopriv_view_entity_ajax', 'EntityActionProcessor::view_entity_ajax');

        add_action('wp_ajax_find_entity_ajax', 'EntityActionProcessor::find_entity_ajax');
        add_action('wp_ajax_nopriv_find_entity_ajax', 'EntityActionProcessor::find_entity_ajax');
        
        add_action('wp_ajax_find_all_ajax', 'EntityActionProcessor::find_all_ajax');
        add_action('wp_ajax_nopriv_find_all_ajax', 'EntityActionProcessor::find_all_ajax');

        add_action('wp_ajax_delete_entity_ajax', 'EntityActionProcessor::delete_entity_ajax');
        add_action('wp_ajax_nopriv_delete_entity_ajax', 'EntityActionProcessor::delete_entity_ajax');

        add_action('wp_ajax_get_conversations_ajax', 'EntityActionProcessor::get_conversations_ajax');
        add_action('wp_ajax_nopriv_get_conversations_ajax', 'EntityActionProcessor::get_conversations_ajax');

        add_action('wp_ajax_send_message_ajax', 'EntityActionProcessor::send_message_ajax');
        add_action('wp_ajax_nopriv_send_message_ajax', 'EntityActionProcessor::send_message_ajax');
    }

    /**
     *
     */
    public static function get_base_url() {
        return get_site_url() . '/page?';
    }
    
    /**
     *
     */
    public static function create_entity_ajax() {
        // Check the ajax request
        $entity_data = self::do_before_ajax_edit();
        // Create the entity of we have no errors
        if(!$entity_data['has_errors']) {
            $entity_data = EntityAPI::create_entity($entity_data);
        }
        // Run post edit hooks
        self::do_after_ajax_edit($entity_data);
    }

    /**
     *
     */
    public static function do_before_ajax_edit() {
        // Ensure we have a valid form
        if(!EntityRequestUtils::is_valid_form() || !isset($_POST['edit_mode'])) {
            wp_send_json_error(array('message' => "Invalid artifact operation!"));
        }
        $artifact_name = EntityRequestUtils::get_artifact_name();
        $entity_data = EntityAPIUtils::init_entity_data($artifact_name);
        $entity_data = EntityRequestUtils::build_entity_data_from_post($entity_data);
        $entity_data = EntityAPIUtils::validate_entity_data($entity_data);
        return $entity_data;
    }
    
    /**
     *
     */
    public static function do_after_ajax_edit($entity_data) {
        // Process the results of the order creation
        if(!$entity_data['has_errors']) {

            if(isset($entity_data['redirect_url'])) {
                $redirect_url = $entity_data['redirect_url'];
            } else {
                $redirect_url = self::get_base_url() . 'artifact='. $entity_data['entity_artifact_name'] . '&id=' . $entity_data['id'] . '&page_action=view';
            }
            if(isset($entity_data['extra_url_params'])) {
                foreach ($entity_data['extra_url_params'] as $key => $value) {
                    $redirect_url = $redirect_url . '&' . $key . '=' . $value;
                }
               
            }
            //var_dump($entity_data);
            // Process the parent id, if any
            if(isset($_REQUEST['parent_id']) && isset($_REQUEST['parent_artifact']) && isset($_REQUEST['parent_field'])) 
            {
                $redirect_url = $redirect_url . '&parent_id=' . sanitize_text_field($_REQUEST['parent_id']);
                $redirect_url = $redirect_url . '&parent_artifact=' . sanitize_text_field($_REQUEST['parent_artifact']);
                $redirect_url = $redirect_url . '&parent_field=' . sanitize_text_field($_REQUEST['parent_field']);
                if(isset($_REQUEST['parent_param'])) $redirect_url = $redirect_url . '&parent_param=' .  $_REQUEST['parent_param'];
            }

            $redirect_url = $redirect_url;
            wp_send_json_success(array('message' => $redirect_url));
        } else {
            wp_send_json_error(array('message' => $entity_data['message']));
        }
    }

    /**
     *
     */
    public static function find_entity_ajax() {
        $entity_data = self::do_before_ajax_find();
        $search_results = EntityAPI::find_entity($entity_data);
        self::do_after_ajax_find($entity_data, $search_results);
    }

    /**
     *
     */
    public static function find_all_ajax() {
        $entity_data = self::do_before_ajax_find();
        $search_results = EntityAPI::find_all($entity_data);
        self::do_after_ajax_find($entity_data, $search_results);
    }

    /**
     *
     */
    public static function do_before_ajax_find() {
        /*if(!isset($_POST['form'][2]) && 
            !isset($_POST['form'][0]) && 
            !wp_verify_nonce($_POST['form'][0]['name'], 'post_nonce')) {
            // Nounce field did not validate
            wp_send_json_error(array('message' => "Invalid form operation!"));
        }*/
        $artifact_name = EntityRequestUtils::get_artifact_name();
        if(!$artifact_name) $artifact_name = EntityRequestUtils::get_query_form_field('artifact');
        $entity_data = EntityAPIUtils::init_entity_data($artifact_name);
        return $entity_data;
    }

    /**
     *
     */
    public static function do_after_ajax_find($entity_data, $search_results) {
        $json_data = array(
            "draw"            => intval(sanitize_text_field($_REQUEST['draw'])),   
            "recordsTotal"    => intval(count($search_results)),  
            "recordsFiltered" => intval(count($search_results)),
            "data"            => $search_results  // total data array
        ); 
        wp_send_json($json_data);
    }

    /**
     *
     */
    public static function delete_entity_ajax() {
        $entity_data = self::do_before_ajax_delete();
        $entity_data = EntityAPI::do_delete_entity($entity_data);
        self::do_after_ajax_delete($entity_data);
    }  

    /**
     *
     */
    public static function get_conversations_ajax() {
        wp_send_json_success(ConversationAPI::get_current_user_conversations());
    }

    /**
     *
     */
    public static function send_message_ajax() {
        wp_send_json_success(ConversationAPI::get_current_user_conversations());
    }

    /**
     *
     */
    public static function do_before_ajax_delete() {
        // Ensure we have a valid form
        if (!isset($_POST['submitted']) && 
            !isset($_POST['post_nonce_field']) && 
            !wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
            // Nounce field did not validate
            wp_send_json_error(array('message' => "Invalid form operation!"));
        }
        // Ensure we have a valid ID
        if (!isset($_POST['id']) ) wp_send_json_error(array('message' => "Entity identifier missing"));

        $artifact_name = EntityRequestUtils::get_artifact_name();
        $entity_data = EntityAPIUtils::init_entity_data($artifact_name);
        return $entity_data;
    }

    /**
     *
     */
    public static function do_after_ajax_delete($entity_data) {
        if (!$entity_data['has_errors']) {
            $redirect_url = self::get_base_url() . 'artifact='. $entity_data['entity_artifact_name'] .'&page_action=list';
            wp_send_json_success(array('message' => "<script type='text/javascript'>window.location='" . $redirect_url . "'</script>"));
        } else {
            wp_send_json_error(array('message' => 'Error deleting entity'));
        }
    }
    
   
    
    

}
