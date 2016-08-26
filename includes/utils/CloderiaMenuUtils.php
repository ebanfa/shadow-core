<?php
class CloderiaMenuUtils {

	// Used to uniquely identify this plugin's menu page in the WP manager
	const admin_menu_slug = 'shadow-core';

	/**
	 * Create Content Port plugin admin menu
	 */
	public static function create_admin_menu()
	{
	 	add_menu_page('Shadow Banker', 'Shadow Banker', 'manage_options',	self::admin_menu_slug, 'CloderiaMenuUtils::get_admin_page');
	 	self::add_plugin_admin_submenus();
	 	self::remove_toplevel_cpt_menus();
	}

	/**
	 * Create Content Port plugin admin sub menus
	 */
	public static function add_plugin_admin_submenus() {
		// Add Currency CPT sub menu
	 	add_submenu_page( self::admin_menu_slug , 'Currency', 'Currency', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_currency', 'CloderiaMenuUtils::render_sb_currency');
	 	add_submenu_page( self::admin_menu_slug , 'Location Type', 'Location Type', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_loctype', 'CloderiaMenuUtils::render_sb_loctype');
	 	add_submenu_page( self::admin_menu_slug , 'Location', 'Location', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_location', 'CloderiaMenuUtils::render_sb_location');
	 	add_submenu_page( self::admin_menu_slug , 'Business', 'Business', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_business', 'CloderiaMenuUtils::render_sb_business');
	 	add_submenu_page( self::admin_menu_slug , 'Business Unit', 'Business Unit', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_businessunit', 'CloderiaMenuUtils::render_sb_businessunit');
	 	add_submenu_page( self::admin_menu_slug , 'Party Category', 'Party Category', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_partycat', 'CloderiaMenuUtils::render_sb_partycat');
	 	add_submenu_page( self::admin_menu_slug , 'Party Type', 'Party Type', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_partytype', 'CloderiaMenuUtils::render_sb_partytype');
	 	add_submenu_page( self::admin_menu_slug , 'Role Type', 'Role Type', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_roletype', 'CloderiaMenuUtils::render_sb_roletype');
	 	add_submenu_page( self::admin_menu_slug , 'Party', 'Party', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_party', 'CloderiaMenuUtils::render_sb_party');
	 	add_submenu_page( self::admin_menu_slug , 'Party Role', 'Party Role', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_partyrole', 'CloderiaMenuUtils::render_sb_partyrole');
	 	add_submenu_page( self::admin_menu_slug , 'Relationship Type', 'Relationship Type', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_reltype', 'CloderiaMenuUtils::render_sb_reltype');
	 	add_submenu_page( self::admin_menu_slug , 'Relationship Status', 'Relationship Status', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_relstatus', 'CloderiaMenuUtils::render_sb_relstatus');
	 	add_submenu_page( self::admin_menu_slug , 'Party Relationship', 'Party Relationship', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_partyrel', 'CloderiaMenuUtils::render_sb_partyrel');
	 	add_submenu_page( self::admin_menu_slug , 'Party Group', 'Party Group', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_partygroup', 'CloderiaMenuUtils::render_sb_partygroup');
	 	add_submenu_page( self::admin_menu_slug , 'Person', 'Person', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_person', 'CloderiaMenuUtils::render_sb_person');
	 	add_submenu_page( self::admin_menu_slug , 'Party Profile', 'Party Profile', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_partyprofile', 'CloderiaMenuUtils::render_sb_partyprofile');
	 	add_submenu_page( self::admin_menu_slug , 'Billing Account', 'Billing Account', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_billaccount', 'CloderiaMenuUtils::render_sb_billaccount');
	 	add_submenu_page( self::admin_menu_slug , 'Conversation', 'Conversation', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_conversation', 'CloderiaMenuUtils::render_sb_conversation');
	 	add_submenu_page( self::admin_menu_slug , 'Conversation User', 'Conversation User', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_conuser', 'CloderiaMenuUtils::render_sb_conuser');
	 	add_submenu_page( self::admin_menu_slug , 'Message', 'Message', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_message', 'CloderiaMenuUtils::render_sb_message');
	 	add_submenu_page( self::admin_menu_slug , 'Message Files', 'Message Files', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_messagesfiles', 'CloderiaMenuUtils::render_sb_messagesfiles');
	 	add_submenu_page( self::admin_menu_slug , 'Notification Type', 'Notification Type', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_notifytype', 'CloderiaMenuUtils::render_sb_notifytype');
	 	add_submenu_page( self::admin_menu_slug , 'Notification Status', 'Notification Status', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_notifystatus', 'CloderiaMenuUtils::render_sb_notifystatus');
	 	add_submenu_page( self::admin_menu_slug , 'Notification Level', 'Notification Level', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_notifylevel', 'CloderiaMenuUtils::render_sb_notifylevel');
	 	add_submenu_page( self::admin_menu_slug , 'Notification', 'Notification', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_notification', 'CloderiaMenuUtils::render_sb_notification');
	 	add_submenu_page( self::admin_menu_slug , 'Contact Us', 'Contact Us', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_contactus', 'CloderiaMenuUtils::render_sb_contactus');
	 	add_submenu_page( self::admin_menu_slug , 'Document Type', 'Document Type', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_doctype', 'CloderiaMenuUtils::render_sb_doctype');
	 	add_submenu_page( self::admin_menu_slug , 'Urgency', 'Urgency', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_docurgency', 'CloderiaMenuUtils::render_sb_docurgency');
	 	add_submenu_page( self::admin_menu_slug , 'Number Of Pages', 'Number Of Pages', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_noofpages', 'CloderiaMenuUtils::render_sb_noofpages');
	 	add_submenu_page( self::admin_menu_slug , 'Subject Area', 'Subject Area', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_subarea', 'CloderiaMenuUtils::render_sb_subarea');
	 	add_submenu_page( self::admin_menu_slug , 'Academic Level', 'Academic Level', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_alevel', 'CloderiaMenuUtils::render_sb_alevel');
	 	add_submenu_page( self::admin_menu_slug , 'Writing Style', 'Writing Style', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_wstyle', 'CloderiaMenuUtils::render_sb_wstyle');
	 	add_submenu_page( self::admin_menu_slug , 'Content Order', 'Content Order', 'manage_options', 
	 		self::admin_menu_slug . '_show_sb_corder', 'CloderiaMenuUtils::render_sb_corder');
	}

	/**
	 * Remove Content Port plugin CPT default menus
	 */
	public static function remove_toplevel_cpt_menus() {
       remove_menu_page( 'edit.php?post_type=sb_currency');
       remove_menu_page( 'edit.php?post_type=sb_loctype');
       remove_menu_page( 'edit.php?post_type=sb_location');
       remove_menu_page( 'edit.php?post_type=sb_business');
       remove_menu_page( 'edit.php?post_type=sb_businessunit');
       remove_menu_page( 'edit.php?post_type=sb_partycat');
       remove_menu_page( 'edit.php?post_type=sb_partytype');
       remove_menu_page( 'edit.php?post_type=sb_roletype');
       remove_menu_page( 'edit.php?post_type=sb_party');
       remove_menu_page( 'edit.php?post_type=sb_partyrole');
       remove_menu_page( 'edit.php?post_type=sb_reltype');
       remove_menu_page( 'edit.php?post_type=sb_relstatus');
       remove_menu_page( 'edit.php?post_type=sb_partyrel');
       remove_menu_page( 'edit.php?post_type=sb_partygroup');
       remove_menu_page( 'edit.php?post_type=sb_person');
       remove_menu_page( 'edit.php?post_type=sb_partyprofile');
       remove_menu_page( 'edit.php?post_type=sb_billaccount');
       remove_menu_page( 'edit.php?post_type=sb_conversation');
       remove_menu_page( 'edit.php?post_type=sb_conuser');
       remove_menu_page( 'edit.php?post_type=sb_message');
       remove_menu_page( 'edit.php?post_type=sb_messagesfiles');
       remove_menu_page( 'edit.php?post_type=sb_notifytype');
       remove_menu_page( 'edit.php?post_type=sb_notifystatus');
       remove_menu_page( 'edit.php?post_type=sb_notifylevel');
       remove_menu_page( 'edit.php?post_type=sb_notification');
       remove_menu_page( 'edit.php?post_type=sb_contactus');
       remove_menu_page( 'edit.php?post_type=sb_doctype');
       remove_menu_page( 'edit.php?post_type=sb_docurgency');
       remove_menu_page( 'edit.php?post_type=sb_noofpages');
       remove_menu_page( 'edit.php?post_type=sb_subarea');
       remove_menu_page( 'edit.php?post_type=sb_alevel');
       remove_menu_page( 'edit.php?post_type=sb_wstyle');
       remove_menu_page( 'edit.php?post_type=sb_corder');
	}

	/**
	 * Adds a link to the settings directly from the plugins page.  This filter is 
	 * called for each plugin, so we need to make sure we only alter the links that
	 * are displayed for THIS plugin.
	 *
	 * The inputs here come directly from WordPress:
	 * @param	array	$links - a hash in theformat of name => translation e.g.
	 *		array('deactivate' => 'Deactivate') that describes all links available to a plugin.
	 * @param	string	$file 	- the path to plugin's main file (the one with the info header), 
	 *		relative to the plugins directory, e.g. 'content-chunks/index.php'
	 * @return	array 	The $links hash.
	 */
	public static function add_plugin_settings_link($links, $file)
	{
		$settings_link = sprintf('<a href="%s">%s</a>'
			, admin_url( 'options-general.php?page='.self::admin_menu_slug )
			, 'Settings'
		);
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
 	 * Prints the administration page for this plugin.
	 */
	public static function get_admin_page()
	{
		if ( !empty($_POST) && check_admin_referer('cp_port_options_update','cp_port_admin_nonce') )
		{
			update_option( 'cp_paypal_url', stripslashes($_POST['cp_paypal']) );
			update_option( 'cp_paypal_id', stripslashes($_POST['cp_paypal_id']) );
			update_option( 'cp_notify_orders', stripslashes($_POST['cp_notify_orders']) );
			update_option( 'cp_notify_accounts', stripslashes($_POST['cp_notify_accounts']) );
			update_option( 'cp_site_domain', stripslashes($_POST['cp_site_domain']) );
			update_option( 'cp_default_currency', stripslashes($_POST['cp_default_currency']) );
			update_option( 'cp_default_partytype', stripslashes($_POST['cp_default_partytype']) );
			$msg = '<div class="updated"><p>Your settings have been <strong>updated</strong></p></div>';
		}
		//"Short code here"; //esc_attr( get_option(self::option_key, self::default_shortcode_name) );
		include('admin_page.php');
	}

	
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_currency()
	{
		$url = admin_url().'edit.php?post_type=sb_currency';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_loctype()
	{
		$url = admin_url().'edit.php?post_type=sb_loctype';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_location()
	{
		$url = admin_url().'edit.php?post_type=sb_location';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_business()
	{
		$url = admin_url().'edit.php?post_type=sb_business';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_businessunit()
	{
		$url = admin_url().'edit.php?post_type=sb_businessunit';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_partycat()
	{
		$url = admin_url().'edit.php?post_type=sb_partycat';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_partytype()
	{
		$url = admin_url().'edit.php?post_type=sb_partytype';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_roletype()
	{
		$url = admin_url().'edit.php?post_type=sb_roletype';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_party()
	{
		$url = admin_url().'edit.php?post_type=sb_party';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_partyrole()
	{
		$url = admin_url().'edit.php?post_type=sb_partyrole';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_reltype()
	{
		$url = admin_url().'edit.php?post_type=sb_reltype';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_relstatus()
	{
		$url = admin_url().'edit.php?post_type=sb_relstatus';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_partyrel()
	{
		$url = admin_url().'edit.php?post_type=sb_partyrel';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_partygroup()
	{
		$url = admin_url().'edit.php?post_type=sb_partygroup';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_person()
	{
		$url = admin_url().'edit.php?post_type=sb_person';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_partyprofile()
	{
		$url = admin_url().'edit.php?post_type=sb_partyprofile';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_billaccount()
	{
		$url = admin_url().'edit.php?post_type=sb_billaccount';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_conversation()
	{
		$url = admin_url().'edit.php?post_type=sb_conversation';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_conuser()
	{
		$url = admin_url().'edit.php?post_type=sb_conuser';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_message()
	{
		$url = admin_url().'edit.php?post_type=sb_message';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_messagesfiles()
	{
		$url = admin_url().'edit.php?post_type=sb_messagesfiles';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_notifytype()
	{
		$url = admin_url().'edit.php?post_type=sb_notifytype';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_notifystatus()
	{
		$url = admin_url().'edit.php?post_type=sb_notifystatus';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_notifylevel()
	{
		$url = admin_url().'edit.php?post_type=sb_notifylevel';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_notification()
	{
		$url = admin_url().'edit.php?post_type=sb_notification';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_contactus()
	{
		$url = admin_url().'edit.php?post_type=sb_contactus';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_doctype()
	{
		$url = admin_url().'edit.php?post_type=sb_doctype';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_docurgency()
	{
		$url = admin_url().'edit.php?post_type=sb_docurgency';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_noofpages()
	{
		$url = admin_url().'edit.php?post_type=sb_noofpages';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_subarea()
	{
		$url = admin_url().'edit.php?post_type=sb_subarea';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_alevel()
	{
		$url = admin_url().'edit.php?post_type=sb_alevel';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_wstyle()
	{
		$url = admin_url().'edit.php?post_type=sb_wstyle';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
    /**
	 * Create Orders post-type sub menu
	 */
	public static function render_sb_corder()
	{
		$url = admin_url().'edit.php?post_type=sb_corder';
		?>
	 	<script>location.href='<?php echo $url;?>';</script>
		<?php
	}
}
?>
