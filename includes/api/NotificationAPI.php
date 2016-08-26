<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class NotificationAPI {

    public static $info = 'INFO';
    public static $viewed = 'VIEWED';
    public static $party_created = 'PARTY_CREATED';
    public static $property_created = 'PROPERTY_CREATED';
    /**
     *
     */
    public static function do_create_entity($entity_data){
     return EntityAPI::do_create_entity($entity_data);
    }

    /**
     *
     */
    public static function do_find_entity($entity_data) {
        return  EntityAPI::do_find_entity($entity_data); 
    }

     
    /**
     *
     */
    public static function do_notification($notification_data){

        $entity_data = EntityAPIUtils::init_entity_data('notification');
        $entity_data['edit_mode'] = true;
        $entity_data['entity_code'] = $notification_data['code'];
        $entity_data['n_owner'] = $notification_data['n_owner'];
         
        $level = EntityAPI::get_by_code('notificationlevel', $notification_data['log_level']); 
        if(isset($level['id'])){ $entity_data['log_level'] = $level['id'];}
        
        $status = EntityAPI::get_by_code('notificationstatus', 'PENDING');
        $type = EntityAPI::get_by_code('notificationtype', $notification_data['n_type']); 

        if(isset($type['id']) && isset($status['id'])){ 
            $entity_data['n_type'] = $type['id'];
            $entity_data['status'] = $status['id'];
            $entity_data['date'] = date("Y-m-d H:i:s");
            $entity_data['name'] = self::fill_template($notification_data, $type['title_template']);
            $entity_data['description'] = self::fill_template($notification_data, $type['message_template']);
            $entity_data = EntityAPI::do_create_entity($entity_data);
        } 
        return $entity_data;
    }


    /**
     *
     */
    public static function fill_template($entity_data, $template){
        foreach ($entity_data as $key => $value) {
            if($key == 'owner') {
                $user_party = EntityAPI::get_by_id('party', $value);
                if(isset($user_party['id'])) { 
                    $value = $user_party['user_name'];
                }
            }
            if(!is_array($value)) $template = str_replace('[+'.$key.'+]', $value, $template);
        }
        return $template;
    }

    /**
     *
     */
    public static function get_notification_items($party_id){
        // Load the entity
        $entities = array();
        $status = EntityAPI::get_by_code('notificationstatus', 'PENDING');
        
        if(isset($status['id'])) {
            $entityQueryArgs = array('numberposts' => -1, 'post_status' => 'publish', 'post_type' => 'sb_notification',
            'meta_query' => array(array('key' => 'n_owner', 'value' => $party_id), array('key' => 'status', 'value' => $status['id'])));
            $entityQuery = new WP_Query($entityQueryArgs);
            while ($entityQuery->have_posts()) : $entityQuery->the_post();
                $entity = $entityQuery->post;
                $entity_data = EntityAPIUtils::init_entity_data('notification');
                array_push($entities, EntityAPIUtils::entity_to_data($entity_data, $entity, false));
            endwhile;
        }
        
        return $entities;
    }

    /**
     *
     */
    public static function update_notification_status($notification_id, $status){
        $status_data = EntityAPI::get_by_code('notificationstatus', $status);
        $notification_data = EntityAPI::get_by_id('notification', $notification_id);
        if(isset($status_data['id']) && isset($notification_data['id'])) {
            $notification_data['edit_mode'] = false;
            $notification_data['status'] = $status_data['id'];
            EntityAPI::do_create_entity($notification_data);
        }
    }


}
