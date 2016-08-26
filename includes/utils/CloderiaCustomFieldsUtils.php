<?php
/*------------------------------------------------------------------------------
These are helper functions
------------------------------------------------------------------------------*/
class CloderiaCustomFieldsUtils
{
	public static $prefix = ''; 
	public static $page = 'custom-content';
	// Which types of content do we want to standardize?
	public static $content_types_array = array('sb_currency','sb_loctype','sb_location','sb_business','sb_businessunit','sb_partycat','sb_partytype','sb_roletype','sb_party','sb_partyrole','sb_reltype','sb_relstatus','sb_partyrel','sb_partygroup','sb_person','sb_partyprofile','sb_billaccount','sb_conversation','sb_conuser','sb_message','sb_messagesfiles','sb_notifytype','sb_notifystatus','sb_notifylevel','sb_notification','sb_contactus','sb_doctype','sb_docurgency','sb_noofpages','sb_subarea','sb_alevel','sb_wstyle','sb_corder',);

	/*------------------------------------------------------------------------------
	SYNOPSIS: prints the value of a custom field from the current post.
	INPUT: 
		$field (str) Name of custom field; technically, this string appears in the 
			meta_key column of the wp_postmeta database table.
		$separator (str) optional separator used to join values if a post has more than
			one value for the field named in $field.
	OUTPUT: prints the value of that field for the current post OR an empty string
		If the $field contains an array of values, then they are joined by the separator.
	------------------------------------------------------------------------------*/
	static function get_custom_field($field, $separator=',')
	{
		// the_ID() function won't work because it prints its output
		$post_id = get_the_ID();
		$output_array = get_post_meta($post_id, $field);
		print implode($separator, $output_array);
	}

	/*------------------------------------------------------------------------------
	Adds a menu item inside the WordPress admin
	------------------------------------------------------------------------------*/
	static function add_menu_item()
	{
		add_submenu_page(
			'plugins.php', 							// Menu page to attach to
			'Custom Content',				 		// page title
			'Content Content', 						// menu title
			'manage_options', 						// permissions
			ContentRotator::$page,					// page-name (used in the URL)
			'ContentRotator::generate_admin_page'	// clicking callback function
		);
	}

	/*------------------------------------------------------------------------------
	Controller that generates admin page
	------------------------------------------------------------------------------*/
	static function generate_admin_page()
	{
		$msg = ''; // used to display a success message on updates
		// Maybe update... remember, the string used by check_admin_referer() must match the 
		// string used by wp_nonce_field();
		if ( !empty($_POST) && check_admin_referer('content_rotation_admin_options_update') )
		{
			
			update_option('content_rotation_content_separator', 
				stripslashes($_POST['separator']) );
			update_option('content_rotation_content_block', 
				stripslashes($_POST['content_block']) );	

			$msg = '<div class="updated"><p>Your settings have been <strong>updated</strong></p></div>';
		}
		// Show the page
		include('admin_page.php');
	}


	//! public Functions
	
	/*------------------------------------------------------------------------------
	Get custom fields for this content type.
	INPUT: $content_type (str) the name of the content type, e.g. post, page.
	OUTPUT: array of associative arrays where each associative array describes 
		a custom field to be used for the $content_type specified.
	FUTURE: read these arrays from the database.
	------------------------------------------------------------------------------*/
	public static function get_custom_fields($content_type)
	{
		$custom_fields = '';
		switch ($content_type) 
		{
        case 'sb_currency':
				$custom_fields = CurrencyCPT::$custom_fields;
				break;
        case 'sb_loctype':
				$custom_fields = LocationTypeCPT::$custom_fields;
				break;
        case 'sb_location':
				$custom_fields = LocationCPT::$custom_fields;
				break;
        case 'sb_business':
				$custom_fields = BusinessCPT::$custom_fields;
				break;
        case 'sb_businessunit':
				$custom_fields = BusinessUnitCPT::$custom_fields;
				break;
        case 'sb_partycat':
				$custom_fields = PartyCategoryCPT::$custom_fields;
				break;
        case 'sb_partytype':
				$custom_fields = PartyTypeCPT::$custom_fields;
				break;
        case 'sb_roletype':
				$custom_fields = RoleTypeCPT::$custom_fields;
				break;
        case 'sb_party':
				$custom_fields = PartyCPT::$custom_fields;
				break;
        case 'sb_partyrole':
				$custom_fields = PartyRoleCPT::$custom_fields;
				break;
        case 'sb_reltype':
				$custom_fields = RelationshipTypeCPT::$custom_fields;
				break;
        case 'sb_relstatus':
				$custom_fields = RelationshipStatusCPT::$custom_fields;
				break;
        case 'sb_partyrel':
				$custom_fields = PartyRelationshipCPT::$custom_fields;
				break;
        case 'sb_partygroup':
				$custom_fields = PartyGroupCPT::$custom_fields;
				break;
        case 'sb_person':
				$custom_fields = PersonCPT::$custom_fields;
				break;
        case 'sb_partyprofile':
				$custom_fields = PartyProfileCPT::$custom_fields;
				break;
        case 'sb_billaccount':
				$custom_fields = BillingAccountCPT::$custom_fields;
				break;
        case 'sb_conversation':
				$custom_fields = ConversationCPT::$custom_fields;
				break;
        case 'sb_conuser':
				$custom_fields = ConversationUserCPT::$custom_fields;
				break;
        case 'sb_message':
				$custom_fields = MessageCPT::$custom_fields;
				break;
        case 'sb_messagesfiles':
				$custom_fields = MessageFilesCPT::$custom_fields;
				break;
        case 'sb_notifytype':
				$custom_fields = NotificationTypeCPT::$custom_fields;
				break;
        case 'sb_notifystatus':
				$custom_fields = NotificationStatusCPT::$custom_fields;
				break;
        case 'sb_notifylevel':
				$custom_fields = NotificationLevelCPT::$custom_fields;
				break;
        case 'sb_notification':
				$custom_fields = NotificationCPT::$custom_fields;
				break;
        case 'sb_contactus':
				$custom_fields = ContactUsCPT::$custom_fields;
				break;
        case 'sb_doctype':
				$custom_fields = DocumentTypeCPT::$custom_fields;
				break;
        case 'sb_docurgency':
				$custom_fields = UrgencyCPT::$custom_fields;
				break;
        case 'sb_noofpages':
				$custom_fields = NoOfPagesCPT::$custom_fields;
				break;
        case 'sb_subarea':
				$custom_fields = SubjectAreaCPT::$custom_fields;
				break;
        case 'sb_alevel':
				$custom_fields = AcademicLevelCPT::$custom_fields;
				break;
        case 'sb_wstyle':
				$custom_fields = WritingStyleCPT::$custom_fields;
				break;
        case 'sb_corder':
				$custom_fields = ContentOrderCPT::$custom_fields;
				break;
			default:
				;
				break;
		}
		return $custom_fields;
	}

	public static function get_field_value($content_type, $post_id, $field) {

		$field_value = $field['value'];
		switch ($content_type) {
        case 'sb_currency':
				$field_value = CurrencyCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_loctype':
				$field_value = LocationTypeCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_location':
				$field_value = LocationCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_business':
				$field_value = BusinessCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_businessunit':
				$field_value = BusinessUnitCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_partycat':
				$field_value = PartyCategoryCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_partytype':
				$field_value = PartyTypeCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_roletype':
				$field_value = RoleTypeCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_party':
				$field_value = PartyCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_partyrole':
				$field_value = PartyRoleCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_reltype':
				$field_value = RelationshipTypeCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_relstatus':
				$field_value = RelationshipStatusCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_partyrel':
				$field_value = PartyRelationshipCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_partygroup':
				$field_value = PartyGroupCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_person':
				$field_value = PersonCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_partyprofile':
				$field_value = PartyProfileCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_billaccount':
				$field_value = BillingAccountCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_conversation':
				$field_value = ConversationCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_conuser':
				$field_value = ConversationUserCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_message':
				$field_value = MessageCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_messagesfiles':
				$field_value = MessageFilesCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_notifytype':
				$field_value = NotificationTypeCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_notifystatus':
				$field_value = NotificationStatusCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_notifylevel':
				$field_value = NotificationLevelCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_notification':
				$field_value = NotificationCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_contactus':
				$field_value = ContactUsCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_doctype':
				$field_value = DocumentTypeCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_docurgency':
				$field_value = UrgencyCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_noofpages':
				$field_value = NoOfPagesCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_subarea':
				$field_value = SubjectAreaCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_alevel':
				$field_value = AcademicLevelCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_wstyle':
				$field_value = WritingStyleCPT::get_field_value($content_type, $post_id, $field);
				break;
        case 'sb_corder':
				$field_value = ContentOrderCPT::get_field_value($content_type, $post_id, $field);
				break;
			default:
				$field_value =  $field['value'];
				break;
		}
		return $field_value;
		

	}

	/*------------------------------------------------------------------------------
    Save the new Custom Fields values
    INPUT:
        $post_id (int) id of the post these custom fields are associated with
        $post (obj) the post object
  ------------------------------------------------------------------------------*/
    public static function save_custom_fields($post_id, $post, $custom_fields) 
    {
        // Loop through all the fields
        foreach ( $custom_fields as $field ) 
        {
            // Processing all fields apart except attachment field here
            if (isset( $_POST[ self::$prefix . $field['name'] ] ) ) 
            {
                $value = trim($_POST[ self::$prefix . $field['name'] ]);
                // Auto-paragraphs for any WYSIWYG
                if ( $field['type'] == 'wysiwyg' ) 
                {
                    $value = wpautop( $value );
                }
                update_post_meta($post_id, $field[ 'name' ], $value);
            }
            // if not set, then it's an unchecked checkbox, so blank out the value.
            else {
                //update_post_meta( $post_id, $field[ 'name' ], '' );
            }
        }
    }


	/*------------------------------------------------------------------------------
	The following '_get_xxx_element' functions each generate a single form element.
	INPUT: $data (array) contains an associative array describing how the element
	should look with keys for name, title, description, and type.
	------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------
	Note: the checked value is hard-coded to 'yes' for simplicity.
	------------------------------------------------------------------------------*/
	public static function get_checkbox_element($data)
	{
		$tpl ='<input type="checkbox" name="[+name+]" id="[+name+]" value="yes" [+is_checked+] style="width: auto;"/> 
		<label for="[+name+]" style="display:inline;"><strong>[+title+]</strong></label>';
		// Special handling to see if the box is checked.
		if ( $data['value'] == "yes" )
		{
			$data['is_checked'] = 'checked="checked"';
		}
		else
		{
			$data['is_checked'] = '';
		}
	
		return self::parse($tpl, $data);
	}

	
	/*------------------------------------------------------------------------------
	The dropdown is special: it requires that you supply an array of options in its
	'options' key.
	The $tpl used internally here uses a custom [+options+] placeholder.
	------------------------------------------------------------------------------*/
	public static function get_dropdown_element($data)
	{
		// Some error messaging.
		if ( !isset($data['options']) || !is_array($data['options']) )
		{
			return '<p><strong>Custom Content Error:</strong> No options supplied for '.$data['name'].'</p>';
		}
		$tpl = '<label for="[+name+]"><strong>[+title+]</strong></label><br/>
			     <select name="[+name+]" id="[+name+]">[+options+]</select>';

		$option_str = '<option value="">Pick One</option>';
		foreach ( $data['options'] as $option )
		{
			$option = htmlspecialchars($option); // Filter the values
			$is_selected = '';
			if ( $data['value'] == $option )
			{
				$is_selected = 'selected="selected"';
			}
			$option_str .= '<option value="'.$option.'" '.$is_selected.'>'.$option.'</option>';
		}
		
		unset($data['options']); // the parse function req's a simple hash.
		$data['options'] = $option_str; // prep for parsing
		
		return self::parse($tpl, $data);
	}
	
	//------------------------------------------------------------------------------
	public static function get_text_element($data)
	{
		$tpl = '<label for="[+name+]"><strong>[+title+]</strong></label><br/>
				<input type="text" name="[+name+]" id="[+name+]" value="[+value+]" /><br/>';
		return self::parse($tpl, $data);
	}
	
	//------------------------------------------------------------------------------
	public static function get_textarea_element($data)
	{
		$tpl = '<label for="[+name+]"><strong>[+title+]</strong></label><br/>
			<textarea name="[+name+]" id="[+name+]" columns="30" rows="3">[+value+]</textarea>';
		return self::parse($tpl, $data);	
	}

	//------------------------------------------------------------------------------
	public static function get_wysiwyg_element($data)
	{
		$tpl = '<label for="[+name+]"><strong>[+title+]</strong></label>
			<textarea name="[+name+]" id="[+name+]" columns="30" rows="3">[+value+]</textarea>
			<script type="text/javascript">
				jQuery( document ).ready( function() {
					jQuery( "[+name+]" ).addClass( "mceEditor" );
					if ( typeof( tinyMCE ) == "object" && typeof( tinyMCE.execCommand ) == "function" ) {
						tinyMCE.execCommand( "mceAddControl", false, "[+name+]" );
					}
				});
			</script>
			';	
		return self::parse($tpl, $data);
	}

	/*------------------------------------------------------------------------------
	SYNOPSIS: a simple parsing function for basic templating.
	INPUT:
		$tpl (str): a string containing [+placeholders+]
		$hash (array): an associative array('key' => 'value');
	OUTPUT
		string; placeholders corresponding to the keys of the hash will be replaced
		with the values and the string will be returned.
	------------------------------------------------------------------------------*/
	public static function parse($tpl, $hash) {
	
	    foreach ($hash as $key => $value) {
	        $tpl = str_replace('[+'.$key.'+]', $value, $tpl);
	    }
	    return $tpl;
	}


	/*------------------------------------------------------------------------------
	Test if a variable is null or an empty string
	------------------------------------------------------------------------------*/
	static function isNullOrEmptyString($variable){
	    return (!isset($variable) || trim($variable)==='');
	}
}
/*EOF*/
?>