<?php

class CloderiaCustomPostTypesUtils {

	/*
	This prefix helps ensure unique keys in the $_POST array. It is used only to 
	identify the form elements; this prefix is *not* used as part of the meta_key
	when saving the field names to the database. If you want your fields to be 
	hidden from built-in WordPress functions, you can name them individually 
	using "_" as the first character.
	
	If you omit a prefix entirely, your custom field names must steer clear of
	the built-in post field names (e.g. 'content').
	*/
	public static $prefix = ''; 

	// Which types of content do we want to standardize?
	public static $content_types_array = array('sb_currency','sb_loctype','sb_location','sb_business','sb_businessunit','sb_partycat','sb_partytype','sb_roletype','sb_party','sb_partyrole','sb_reltype','sb_relstatus','sb_partyrel','sb_partygroup','sb_person','sb_partyprofile','sb_billaccount','sb_conversation','sb_conuser','sb_message','sb_messagesfiles','sb_notifytype','sb_notifystatus','sb_notifylevel','sb_notification','sb_contactus','sb_doctype','sb_docurgency','sb_noofpages','sb_subarea','sb_alevel','sb_wstyle','sb_corder',);

	/**
	 * Register the custom post type so it shows up in menus
	 */
	public static function register_custom_post_type()
	{
        CurrencyCPT::register_custom_post_type();
        LocationTypeCPT::register_custom_post_type();
        LocationCPT::register_custom_post_type();
        BusinessCPT::register_custom_post_type();
        BusinessUnitCPT::register_custom_post_type();
        PartyCategoryCPT::register_custom_post_type();
        PartyTypeCPT::register_custom_post_type();
        RoleTypeCPT::register_custom_post_type();
        PartyCPT::register_custom_post_type();
        PartyRoleCPT::register_custom_post_type();
        RelationshipTypeCPT::register_custom_post_type();
        RelationshipStatusCPT::register_custom_post_type();
        PartyRelationshipCPT::register_custom_post_type();
        PartyGroupCPT::register_custom_post_type();
        PersonCPT::register_custom_post_type();
        PartyProfileCPT::register_custom_post_type();
        BillingAccountCPT::register_custom_post_type();
        ConversationCPT::register_custom_post_type();
        ConversationUserCPT::register_custom_post_type();
        MessageCPT::register_custom_post_type();
        MessageFilesCPT::register_custom_post_type();
        NotificationTypeCPT::register_custom_post_type();
        NotificationStatusCPT::register_custom_post_type();
        NotificationLevelCPT::register_custom_post_type();
        NotificationCPT::register_custom_post_type();
        ContactUsCPT::register_custom_post_type();
        DocumentTypeCPT::register_custom_post_type();
        UrgencyCPT::register_custom_post_type();
        NoOfPagesCPT::register_custom_post_type();
        SubjectAreaCPT::register_custom_post_type();
        AcademicLevelCPT::register_custom_post_type();
        WritingStyleCPT::register_custom_post_type();
        ContentOrderCPT::register_custom_post_type();
	}

	/*------------------------------------------------------------------------------
	Save the new Custom Fields values
	INPUT:
		$post_id (int) id of the post these custom fields are associated with
		$post (obj) the post object
	------------------------------------------------------------------------------*/
	public static function save_custom_fields( $post_id, $post ) 
	{
		switch ($post->post_type) 
		{
			case 'sb_currency':
				CurrencyCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_loctype':
				LocationTypeCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_location':
				LocationCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_business':
				BusinessCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_businessunit':
				BusinessUnitCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_partycat':
				PartyCategoryCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_partytype':
				PartyTypeCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_roletype':
				RoleTypeCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_party':
				PartyCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_partyrole':
				PartyRoleCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_reltype':
				RelationshipTypeCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_relstatus':
				RelationshipStatusCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_partyrel':
				PartyRelationshipCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_partygroup':
				PartyGroupCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_person':
				PersonCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_partyprofile':
				PartyProfileCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_billaccount':
				BillingAccountCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_conversation':
				ConversationCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_conuser':
				ConversationUserCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_message':
				MessageCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_messagesfiles':
				MessageFilesCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_notifytype':
				NotificationTypeCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_notifystatus':
				NotificationStatusCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_notifylevel':
				NotificationLevelCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_notification':
				NotificationCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_contactus':
				ContactUsCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_doctype':
				DocumentTypeCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_docurgency':
				UrgencyCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_noofpages':
				NoOfPagesCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_subarea':
				SubjectAreaCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_alevel':
				AcademicLevelCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_wstyle':
				WritingStyleCPT::save_custom_fields($post_id, $post);
				break;
			case 'sb_corder':
				ContentOrderCPT::save_custom_fields($post_id, $post);
				break;
			default:
				;
				break;
		}
	}

	/*------------------------------------------------------------------------------
	This plugin is meant to be configured so it acts on a specified list of content
	types, e.g. post, page, or any custom content types that is registered.
	FUTURE: read this from the database.
	------------------------------------------------------------------------------*/
	public static function get_active_content_types()
	{
		return self::$content_types_array;
	}

	//! Public Functions	
	/*------------------------------------------------------------------------------
	* Create the new Custom Fields meta box
	------------------------------------------------------------------------------*/
	public static function create_meta_box() {
		$content_types_array = self::get_active_content_types();
		foreach ($content_types_array as $content_type) {
			add_meta_box( 'my-custom-fields'
				, 'Custom Fields'
				, 'CloderiaCustomPostTypesUtils::print_custom_fields'
				, $content_type
				, 'normal'
				, 'high'
				, $content_type 
			);
		}
	}

	/**
	 * Display the new Custom Fields meta box
	 * INPUT:
	 * $post (the post object is always passed to this callback function). 
	 * $callback_args will always have a copy of this object passed (I'm not sure why),
	 * but in $callback_args['args'] will be the 7th parameter from the add_meta_box() function.
	 *  We are using this argument to pass the content_type.
	 * @param	post $post - The post.
	 * @param	string	$callback_args 	- The names of call backs
	 */
	public static function print_custom_fields($post, $callback_args='') {
		$content_type = $callback_args['args']; // the 7th arg from add_meta_box()
		$custom_fields = CloderiaCustomFieldsUtils::get_custom_fields($content_type);
		$output = '';		
		
		foreach ( $custom_fields as $field ) 
		{
			$output_this_field = '';
			$field['name'] = self::$prefix . $field['name']; // this ensures unique keys in $_POST
			
			$field['value'] = htmlspecialchars( get_post_meta( $post->ID, $field['name'], true ) );
			// This will eventually delegate to each CPT to load the correct field value
			$field['value'] = CloderiaCustomFieldsUtils::get_field_value($content_type, $post->ID, $field);
			switch ( $field['type'] ) 
			{
				case 'checkbox':
					$output_this_field .= CloderiaCustomFieldsUtils::get_checkbox_element($field);
					break;
				case 'dropdown':
					$output_this_field .= CloderiaCustomFieldsUtils::get_dropdown_element($field);
					break;
				case 'textarea':
					$output_this_field .= CloderiaCustomFieldsUtils::get_textarea_element($field);
					break;
				case 'wysiwyg':
					$output_this_field .= CloderiaCustomFieldsUtils::get_wysiwyg_element($field);
					break;
				case 'text':
				default:
					$output_this_field .= CloderiaCustomFieldsUtils::get_text_element($field);
					break;
			}
			// optionally add description
			if ( $field['description'] ) 
			{
				$output_this_field .= '<p>'.$field['description'].'</p>';
			}
			$output .= '<div class="form-field form-required">'.$output_this_field.'</div>';
		}
 		// Print the form
 		print '<div class="form-wrap">';
	 	wp_nonce_field('update_custom_content_fields','custom_content_fields_nonce');
	 	print $output;
	 	print '</div>';
	}
	
	/*------------------------------------------------------------------------------
	Remove the default Custom Fields meta box. Only affects the content types that
	have been activated.
	INPUTS: sent from WordPress
	------------------------------------------------------------------------------*/
	public static function remove_default_custom_fields( $type, $context, $post ) {
		$content_types_array = self::get_active_content_types();
		foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
			foreach ( $content_types_array as $content_type )
			{
				remove_meta_box( 'postcustom', $content_type, $context );
			}
		}
	}
}
?>