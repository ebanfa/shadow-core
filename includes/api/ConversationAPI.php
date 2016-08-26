<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ConversationAPI {

    /**
     *
     */
    public static function init_hooks() {

        add_action('wp_ajax_do_start_conversation_ajax', 'ConversationAPI::do_start_conversation_ajax');
        add_action('wp_ajax_nopriv_do_start_conversation_ajax', 'ConversationAPI::do_start_conversation_ajax');

        add_action('wp_ajax_do_send_message_ajax', 'ConversationAPI::do_send_message_ajax');
        add_action('wp_ajax_nopriv_do_send_message_ajax', 'ConversationAPI::do_send_message_ajax');

        add_action('wp_ajax_do_current_user_conversations_ajax', 'ConversationAPI::do_current_user_conversations_ajax');
        add_action('wp_ajax_nopriv_do_current_user_conversations_ajax', 'ConversationAPI::do_current_user_conversations_ajax');

        add_action('wp_ajax_do_add_user_to_conversation_ajax', 'ConversationAPI::do_add_user_to_conversation_ajax');
        add_action('wp_ajax_nopriv_do_add_user_to_conversation_ajax', 'ConversationAPI::do_add_user_to_conversation_ajax');

        add_action('wp_ajax_do_delete_user_conversation_ajax', 'ConversationAPI::do_delete_user_conversation_ajax');
        add_action('wp_ajax_nopriv_do_delete_user_conversation_ajax', 'ConversationAPI::do_delete_user_conversation_ajax');

        add_action('wp_ajax_do_delete_user_message_ajax', 'ConversationAPI::do_delete_user_message_ajax');
        add_action('wp_ajax_nopriv_do_delete_user_message_ajax', 'ConversationAPI::do_delete_user_message_ajax');
    }

    /**
     *
     */
    public static function do_create_entity($entity_data){
        $entity_data =  EntityAPI::do_create_entity($entity_data);
        return $entity_data;
    }

    /**
     *
     */
    public static function do_start_conversation_ajax() {
        // Create the parent conversation
        // Need to get the users in the conversation
        // For each create a conversation user
        return  EntityAPI::do_find_entity($entity_data); 
    }

    /**
     *
     */
    public static function do_send_message_ajax() {
        return  EntityAPI::do_find_entity($entity_data); 
    }

     /**
     *
     */
    public static function do_current_user_conversations_ajax(){
        $user_messages = array();
        $conversations_list = array();
        // Get the current user party
        $party_data = PartyAPI::get_current_user_party();
        if(isset($party_data['id'])) {
            // Find all conversation users for the current party
            $conversation_user_list = EntityAPI::find_by_criteria('conversationuser', array('con_user' => $party_data['id']));
            // For each conversation user get the parent conversation
            foreach ($conversation_user_list as $con_user) {
                $conversation_data = EntityAPI::get_by_id('conversation', $con_user['conversation']);
                // For each conversation get the messages that have the current party as send or receiver 
                $messages_list = EntityAPI::find_by_criteria('message', array('conversation' => $conversation_data['id']));
                foreach ($messages_list as $message_data) {
                   if($message_data['owner'] == $party_data['id'] || 
                    $message_data['counter_party'] == $party_data['id']) {
                        array_push($user_messages, $message_data);
                   }
                }
                $conversation_data['user_messages'] = $user_messages;
                array_push($conversations_list, $conversation_data);
            }
        }
        // build json objects for each conversation along with the user's messages
        wp_send_json_success($conversations_list);
    }

    /**
     *
     */
    public static function do_add_user_to_conversation_ajax() {
        return  EntityAPI::do_find_entity($entity_data); 
    }

    /**
     *
     */
    public static function do_delete_user_conversation_ajax() {
        return  EntityAPI::do_find_entity($entity_data); 
    }

    /**
     *
     */
    public static function do_delete_user_message_ajax() {
        return  EntityAPI::do_find_entity($entity_data); 
    }



}