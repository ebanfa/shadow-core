<?php 

class NotificationCPT {

    public static $prefix = ''; 

    public static $post_name = 'sb_notification'; 
    public static $is_global_entity = false; 


    public static $is_virtual_entity = false; 
    


    /**
     * These are the wordpress custom post type 
     * specific fields
     */
    public static $custom_fields =  array(
        array('name' => 'entity_code',
            'title' => 'Code',
            'description' => 'The Code field',
            'type' => 'text',
        ),
        array('name' => 'n_owner',
            'title' => 'Owner',
            'description' => 'The Owner field',
            'type' => 'text',
        ),
        array('name' => 'n_type',
            'title' => 'Type',
            'description' => 'The Type field',
            'type' => 'text',
        ),
        array('name' => 'status',
            'title' => 'Status',
            'description' => 'The Status field',
            'type' => 'text',
        ),
        array('name' => 'log_level',
            'title' => 'Level',
            'description' => 'The Level field',
            'type' => 'text',
        ),
        array('name' => 'name',
            'title' => 'Title',
            'description' => 'The Title field',
            'type' => 'text',
        ),
        array('name' => 'description',
            'title' => 'Message',
            'description' => 'The Message field',
            'type' => 'text',
        ),
        array('name' => 'business_unit',
            'title' => 'Business Unit',
            'description' => 'The Business Unit field',
            'type' => 'text',
        ),
    );

    /**
     * These are the shadow banker framework 
     * specific fields. These represent the actual fields
     * defined in the entity mapping.
     */
 public static $entity_fields = array(
        'entity_code' => array('name' => 'entity_code',
            'description' => 'Code',
            'size' => 'medium',
            'data_type' => 'alphanumeric',
            'is_required' => false,
            'is_visible' => false,
            'is_create_field' => false,
            'is_edit_field' => false,
            'is_view_field' => false,
            'is_list_field' => false,
            'is_form_field' => false,
            'is_relationship_field' => false,),
        'n_owner' => array('name' => 'n_owner',
            'description' => 'Owner',
            'size' => 'large',
            'entity_name' => 'Party',
            'entity_description' => 'Party',
            'data_type' => 'sb_party',
            'is_required' => true,
            'is_visible' => true,
            'is_create_field' => true,
            'is_edit_field' => true,
            'is_view_field' => true,
            'is_list_field' => true,
            'is_form_field' => true,
            'is_relationship_field' => true,),
        'n_type' => array('name' => 'n_type',
            'description' => 'Type',
            'size' => 'large',
            'entity_name' => 'NotificationType',
            'entity_description' => 'Notification Type',
            'data_type' => 'sb_notifytype',
            'is_required' => true,
            'is_visible' => true,
            'is_create_field' => true,
            'is_edit_field' => true,
            'is_view_field' => true,
            'is_list_field' => true,
            'is_form_field' => true,
            'is_relationship_field' => true,),
        'status' => array('name' => 'status',
            'description' => 'Status',
            'size' => 'large',
            'entity_name' => 'NotificationStatus',
            'entity_description' => 'Notification Status',
            'data_type' => 'sb_notifystatus',
            'is_required' => true,
            'is_visible' => true,
            'is_create_field' => true,
            'is_edit_field' => true,
            'is_view_field' => true,
            'is_list_field' => true,
            'is_form_field' => true,
            'is_relationship_field' => true,),
        'log_level' => array('name' => 'log_level',
            'description' => 'Level',
            'size' => 'large',
            'entity_name' => 'NotificationLevel',
            'entity_description' => 'Notification Level',
            'data_type' => 'sb_notifylevel',
            'is_required' => true,
            'is_visible' => true,
            'is_create_field' => false,
            'is_edit_field' => false,
            'is_view_field' => true,
            'is_list_field' => true,
            'is_form_field' => false,
            'is_relationship_field' => true,),
        'name' => array('name' => 'name',
            'description' => 'Title',
            'size' => 'large',
            'data_type' => 'name',
            'is_required' => true,
            'is_visible' => true,
            'is_create_field' => true,
            'is_edit_field' => true,
            'is_view_field' => true,
            'is_list_field' => true,
            'is_form_field' => true,
            'is_relationship_field' => false,),
        'description' => array('name' => 'description',
            'description' => 'Message',
            'size' => 'large',
            'data_type' => 'text-lg',
            'is_required' => true,
            'is_visible' => true,
            'is_create_field' => true,
            'is_edit_field' => true,
            'is_view_field' => true,
            'is_list_field' => true,
            'is_form_field' => true,
            'is_relationship_field' => false,),
        'business_unit' => array('name' => 'business_unit',
            'description' => 'Business Unit',
            'size' => 'large',
            'entity_name' => 'BusinessUnit',
            'entity_description' => 'Business Unit',
            'data_type' => 'sb_businessunit',
            'is_required' => true,
            'is_visible' => false,
            'is_create_field' => false,
            'is_edit_field' => false,
            'is_view_field' => false,
            'is_list_field' => true,
            'is_form_field' => false,
            'is_relationship_field' => true,),
   );

    /**
     * These are the shadow banker framework 
     * specific fields. Inferred fields are fields that are not
     * directly defined in the entity mapping of a given entity, but are instead
     * inferred from other entities. As an example a Party entity has a field that
     * points to the PartyType of a party, ie Party points to PartyType but not vice versa.
     * So an array of Party entities will be an inferred field on PartyType.
     */
 public static $related_child_entities = array(
   );
 
    /**
     * Register the custom post type so it shows up in menus
     */
    public static function register_custom_post_type()
    {
       register_post_type('sb_notification', 
            array(
                'label' => 'Notification',
                'labels' => array(
                'add_new'           => 'Add New',
                'add_new_item'      => 'Add New Notification',
                'edit_item'         => 'Edit Notification',
                'new_item'          => 'New Notification',
                'view_item'         => 'View Notification',
                'search_items'      => 'Search Notification',
                'not_found'         => 'No Notification Found ',
                'not_found_in_trash'=> 'Not Found in Trash',
                ),
                'description' => 'Reusable Notification',
                'public' => true,
                'show_ui' => true,
                'menu_position' => 5,
                'supports' => array('title', 'custom-fields'),
                'has_archive'   => true,
                'rewrite'   => true,
            )
        );      
    }


    /*------------------------------------------------------------------------------
    Save the new Custom Fields values
    INPUT:
        $post_id (int) id of the post these custom fields are associated with
        $post (obj) the post object
  ------------------------------------------------------------------------------*/
    public static function save_custom_fields( $post_id, $post) 
    {
        if ( $post->post_type == 'sb_notification') 
        {
            // The 2nd arg here is important because there are multiple nonces on the page
            if ( !empty($_POST))// && check_admin_referer('update_custom_content_fields','custom_content_fields_nonce') )
            {     
                CloderiaCustomFieldsUtils::save_custom_fields($post_id, $post, self::$custom_fields);
            }
        }
    }

    public static function get_field_value($content_type, $post_id, $field){
        return $field['value'];
    }

    public static function sb_notification_table_head($defaults){
        $defaults['n_owner']  = 'Owner';
        $defaults['n_type']  = 'Type';
        $defaults['status']  = 'Status';
        $defaults['log_level']  = 'Level';
        $defaults['name']  = 'Title';
        $defaults['description']  = 'Message';
        $defaults['business_unit']  = 'Business Unit';
        return $defaults;
    }

    public static function sb_notification_table_content($column_name, $post_id){
        if ($column_name == 'entity_code') {
            $field_value = get_post_meta($post_id, 'entity_code', true );
            echo $field_value;
        }
        if ($column_name == 'n_owner') {
            $field_value = get_post_meta($post_id, 'n_owner', true );
            echo $field_value;
        }
        if ($column_name == 'n_type') {
            $field_value = get_post_meta($post_id, 'n_type', true );
            echo $field_value;
        }
        if ($column_name == 'status') {
            $field_value = get_post_meta($post_id, 'status', true );
            echo $field_value;
        }
        if ($column_name == 'log_level') {
            $field_value = get_post_meta($post_id, 'log_level', true );
            echo $field_value;
        }
        if ($column_name == 'name') {
            $field_value = get_post_meta($post_id, 'name', true );
            echo $field_value;
        }
        if ($column_name == 'description') {
            $field_value = get_post_meta($post_id, 'description', true );
            echo $field_value;
        }
        if ($column_name == 'business_unit') {
            $field_value = get_post_meta($post_id, 'business_unit', true );
            echo $field_value;
        }
    }

}

?>