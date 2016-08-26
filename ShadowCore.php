<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/* ------------------------------------------------------------------------------
  This plugin standardizes the custom fields for specified content types, e.g.
  post, page, and any other custom post-type you register via a plugin.

  TO-DO:
  Create a options page and a menu item
  read the $prefix from the database (? -- maybe not... changing it after posts
  have been created would be disasterous)
  read the $content_types_array from the database
  read the $custom_fields from the database
  more form element types?  E.g. date?
  ------------------------------------------------------------------------------ */

class ShadowCore {

    /**
     * @var string
     */
    public $version = '0.0.1';

    /**
     * @var CloderiaPort The single instance of the class
     * @since 0.0.1
     */
    protected static $_instance = null;

    /**
     * The date format string
     * @since 0.0.1
     */
    public static $date_format = 'M j, Y, H:i';

    /**
     * Main CloderiaPort Instance
     *
     * Ensures only one instance of CloderiaPort is loaded or can be loaded.
     *
     * @since 0.0.1
     * @static
     * @return CloderiaPort - Main instance
     */
    public static function initialize() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * CloderiaPort Constructor.
     */
    public function __construct() {
        $this->includes();
        $this->init_scripts();
        $this->init_ajax_hooks();
        $this->init_backend_hooks();
        $this->init_template_hooks();
        $this->init_admin_template_hooks();
    }

    /**
     * Include all required files
     */
     /**
     * Include all required files
     */
    public function includes() {
        // Model
        include_once('includes/abstracts/CurrencyCPT.php');
        include_once('includes/abstracts/Currency.php');
        include_once('includes/abstracts/LocationTypeCPT.php');
        include_once('includes/abstracts/LocationType.php');
        include_once('includes/abstracts/LocationCPT.php');
        include_once('includes/abstracts/Location.php');
        include_once('includes/abstracts/BusinessCPT.php');
        include_once('includes/abstracts/Business.php');
        include_once('includes/abstracts/BusinessUnitCPT.php');
        include_once('includes/abstracts/BusinessUnit.php');
        include_once('includes/abstracts/PartyCategoryCPT.php');
        include_once('includes/abstracts/PartyCategory.php');
        include_once('includes/abstracts/PartyTypeCPT.php');
        include_once('includes/abstracts/PartyType.php');
        include_once('includes/abstracts/RoleTypeCPT.php');
        include_once('includes/abstracts/RoleType.php');
        include_once('includes/abstracts/PartyCPT.php');
        include_once('includes/abstracts/Party.php');
        include_once('includes/abstracts/PartyRoleCPT.php');
        include_once('includes/abstracts/PartyRole.php');
        include_once('includes/abstracts/RelationshipTypeCPT.php');
        include_once('includes/abstracts/RelationshipType.php');
        include_once('includes/abstracts/RelationshipStatusCPT.php');
        include_once('includes/abstracts/RelationshipStatus.php');
        include_once('includes/abstracts/PartyRelationshipCPT.php');
        include_once('includes/abstracts/PartyRelationship.php');
        include_once('includes/abstracts/PartyGroupCPT.php');
        include_once('includes/abstracts/PartyGroup.php');
        include_once('includes/abstracts/PersonCPT.php');
        include_once('includes/abstracts/Person.php');
        include_once('includes/abstracts/PartyProfileCPT.php');
        include_once('includes/abstracts/PartyProfile.php');
        include_once('includes/abstracts/BillingAccountCPT.php');
        include_once('includes/abstracts/BillingAccount.php');
        include_once('includes/abstracts/ConversationCPT.php');
        include_once('includes/abstracts/Conversation.php');
        include_once('includes/abstracts/ConversationUserCPT.php');
        include_once('includes/abstracts/ConversationUser.php');
        include_once('includes/abstracts/MessageCPT.php');
        include_once('includes/abstracts/Message.php');
        include_once('includes/abstracts/MessageFilesCPT.php');
        include_once('includes/abstracts/MessageFiles.php');
        include_once('includes/abstracts/NotificationTypeCPT.php');
        include_once('includes/abstracts/NotificationType.php');
        include_once('includes/abstracts/NotificationStatusCPT.php');
        include_once('includes/abstracts/NotificationStatus.php');
        include_once('includes/abstracts/NotificationLevelCPT.php');
        include_once('includes/abstracts/NotificationLevel.php');
        include_once('includes/abstracts/NotificationCPT.php');
        include_once('includes/abstracts/Notification.php');
        include_once('includes/abstracts/ContactUsCPT.php');
        include_once('includes/abstracts/ContactUs.php');
        include_once('includes/abstracts/DocumentTypeCPT.php');
        include_once('includes/abstracts/DocumentType.php');
        include_once('includes/abstracts/UrgencyCPT.php');
        include_once('includes/abstracts/Urgency.php');
        include_once('includes/abstracts/NoOfPagesCPT.php');
        include_once('includes/abstracts/NoOfPages.php');
        include_once('includes/abstracts/SubjectAreaCPT.php');
        include_once('includes/abstracts/SubjectArea.php');
        include_once('includes/abstracts/AcademicLevelCPT.php');
        include_once('includes/abstracts/AcademicLevel.php');
        include_once('includes/abstracts/WritingStyleCPT.php');
        include_once('includes/abstracts/WritingStyle.php');
        include_once('includes/abstracts/ContentOrderCPT.php');
        include_once('includes/abstracts/ContentOrder.php');
        // Entity API
        include_once('includes/api/EntityAPI.php');

        // API
        include_once('includes/api/BusinessUnitAPI.php');
        include_once('includes/api/PartyAPI.php');
        include_once('includes/api/PartyGroupAPI.php');
        include_once('includes/api/PersonAPI.php');
        include_once('includes/api/ConversationAPI.php');
        include_once('includes/api/NotificationAPI.php');
        include_once('includes/api/ContactUsAPI.php');
        include_once('includes/api/ContentOrderAPI.php');
        
        include_once('includes/api/EntityPersistenceAPI.php');
        // Entity Controller
        include_once('includes/controller/EntityActionProcessor.php');


        // Entity View and view controllers
        include_once('includes/view/ViewUtils.php');
        include_once('includes/view/ArtifactView.php');
        include_once('includes/view/BaseEntityView.php');
        include_once('includes/view/DashboardView.php');
        include_once('includes/view/ViewFilter.php');
        include_once('includes/view/CreateEntityView.php');
        include_once('includes/view/EditEntityView.php');
        include_once('includes/view/SingleEntityView.php');
        include_once('includes/view/ListEntityView.php');
        include_once('includes/view/FormFieldFilter.php');
        include_once('includes/view/MultiEntityCreateView.php');
        include_once('includes/view/CategorizedViewFilter.php');
        include_once('includes/view/ParamCategorizedViewFilter.php');
        include_once('includes/view/currency/CurrencyViewFilter.php');
        include_once('includes/view/business/BusinessViewFilter.php');
        include_once('includes/view/party/SinglePartyView.php');
        include_once('includes/view/party/PartyViewFilter.php');
        include_once('includes/view/partygroup/CreatePartyGroupView.php');
        include_once('includes/view/person/CreatePersonView.php');
        include_once('includes/view/person/PersonViewFilter.php');
        include_once('includes/view/partyprofile/SinglePartyProfileView.php');
        include_once('includes/view/partyprofile/ListPartyProfileView.php');
        include_once('includes/view/conversation/ListConversationView.php');
        include_once('includes/view/notification/SingleNotificationView.php');
        include_once('includes/view/contentorder/ContentOrderViewFilter.php');

        include_once('includes/view/entity-form-fields.php');

        // Framework API
        include_once('includes/api/CloderiaFileAPI.php');
        include_once('includes/api/CloderiaUserAPI.php');
        include_once('includes/api/ArtifactRequestProcessor.php');
        include_once('includes/api/CloderiaAdminAPI.php');
        include_once('includes/api/CloderiaSecurityAPI.php');
        include_once('includes/api/CloderiaUserLoginAPI.php');
        include_once('includes/api/CloderiaUIDisplayAPI.php');
        include_once('includes/api/CloderiaFileUploadValidatorAPI.php');
        // Services
        include_once('includes/service/DashboardService.php');
        include_once('includes/service/TransactionService.php');
        // Utility Classes
        include_once('includes/utils/EntityStringUtils.php');
        include_once('includes/utils/EntityRequestUtils.php');
        include_once('includes/utils/EntityAPIUtils.php');
        include_once('includes/utils/ArtifactUtils.php');

        include_once('includes/utils/CloderiaLogUtils.php');
        include_once('includes/utils/CloderiaUserUtils.php');
        include_once('includes/utils/CloderiaCustomFieldsUtils.php');
        include_once('includes/utils/CloderiaCustomPostTypesUtils.php');
        include_once('includes/utils/CloderiaMenuUtils.php');
        include_once('includes/utils/CloderiaDateUtils.php');
        include_once('includes/utils/CloderiaTemplateFunctions.php');
        include_once('includes/fpdf.php');
        include_once('includes/font/courier.php');
        include_once('includes/font/courierb.php');

    }
    
    /**
     * Init JavaScript scripts
     */
    public function init_scripts() {
        //Hooks our custom function into WP's wp_enqueue_scripts function
        add_action('wp_enqueue_scripts', 'ShadowCore::enqueue_scripts');
    }
    
    public function init_ajax_hooks() {
        // Setup Ajax
        add_action('template_redirect', 'CloderiaAdminAPI::do_ajax_setup');

        EntityActionProcessor::init_hooks();
        ConversationAPI::init_hooks();
        //ChartOfAccountsAPI::init_hooks();
        //Order related Ajax functions
        #add_action('wp_ajax_do_content_order_ajax', 'CloderiaOrdersAPI::do_content_order_ajax');
        #add_action('wp_ajax_nopriv_do_content_order_ajax', 'CloderiaOrdersAPI::do_content_order_ajax');
        //User profile related Ajax functions
        #add_action('wp_ajax_find_user_orders_ajax', 'CloderiaOrdersAPI::find_user_orders_ajax');
        #add_action('wp_ajax_nopriv_find_user_orders_ajax', 'CloderiaOrdersAPI::find_user_orders_ajax');

    }


    public function init_backend_hooks() {
        add_action('cloderia_create_shadow_user', 'CloderiaUserAPI::create_shadow_user', 10, 1);
        add_action('cloderia_user_reset_password', 'CloderiaUserAPI::create_shadow_user', 10, 1);
        add_action('shadowbanker_notify_user', 'NotificationAPI::do_notification', 10, 1);
    }

    /**
     * Add template processing related hooks.
     */
    public function init_template_hooks() {
        // UI display actions
        add_action('shadowbanker_before_main_content', 'CloderiaUIDisplayAPI::before_main_content', 10);
        add_action('shadowbanker_after_main_content', 'CloderiaUIDisplayAPI::after_main_content', 10);
        // Menu UI display actions
        add_action('shadowbanker_show_app_menu', 'CloderiaUIDisplayAPI::display_app_menu', 10);
        add_action('shadowbanker_before_app_menu', 'CloderiaUIDisplayAPI::before_app_menu', 10);
        add_action('shadowbanker_after_app_menu', 'CloderiaUIDisplayAPI::after_app_menu', 10);
        // Chat bar display icons
        add_action('shadowbanker_show_chat_bar', 'CloderiaUIDisplayAPI::display_chat_bar', 10);

        add_action('shadowbanker_render_create_entity_view', 'CloderiaUIDisplayAPI::render_create_form', 10);
        add_action('shadowbanker_render_edit_entity_view', 'CloderiaUIDisplayAPI::render_edit_form', 10);
        add_action('shadowbanker_render_view_entity_view', 'CloderiaUIDisplayAPI::render_single', 10);
        add_action('shadowbanker_render_list_entity_view', 'CloderiaUIDisplayAPI::render_list', 10);
        add_action('shadowbanker_render_entity_form_fields', 'CloderiaUIDisplayAPI::render_entity_form_fields', 10);
        add_action('shadowbanker_render_related_entity_field_modals', 'CloderiaUIDisplayAPI::render_related_entity_field_modals', 10);
        add_action('shadowbanker_render_multi_entity_create_view', 'CloderiaUIDisplayAPI::render_multi_entity_create_view', 10);

        add_action('shadowbanker_before_entity_form_field', 'CloderiaUIDisplayAPI::before_entity_form_field', 10);
        add_action('shadowbanker_after_entity_form_field', 'CloderiaUIDisplayAPI::after_entity_form_field', 10);
        
        add_action('shadowbanker_display_notifications_items', 'CloderiaUIDisplayAPI::show_notification_items', 10);
        add_action('shadowbanker_render_dashboard_view', 'CloderiaUIDisplayAPI::render_dashboard_view', 10);
        //add_action('showdow_banker_display_user_conversations', 'CloderiaUIDisplayAPI::show_user_conversations', 10); 
        //add_action('showdow_banker_display_latest_user_conversation', 'CloderiaUIDisplayAPI::show_latest_user_conversation', 10);

        // Page display functions
        add_action('shadowbanker_process_page_request', 'ArtifactRequestProcessor::process_artifact_request', 10);

        // Entity page display actions
        add_action('shadowbanker_before_artifact_content', 'CloderiaUIDisplayAPI::before_artifact_content', 10);
        add_action('shadowbanker_the_artifact_content', 'CloderiaUIDisplayAPI::the_artifact_content', 10, 1);
        add_action('shadowbanker_after_artifact_content', 'CloderiaUIDisplayAPI::after_artifact_content', 10);
        
        // Remove admin bar for non admin users
        //add_action('after_setup_theme', 'CloderiaAdminAPI::do_remove_admin_bar');
        /*add_action('wp_logout', 'ShadowCore::redirect_logout_url');*/
        FormFieldFilter::init_hooks();

        CurrencyViewFilter::init_hooks();
        BusinessViewFilter::init_hooks();
        PartyViewFilter::init_hooks();
        PersonViewFilter::init_hooks();
        ContentOrderViewFilter::init_hooks();

    }

    public function init_admin_template_hooks(){
        add_filter('manage_sb_currency_posts_columns', 'CurrencyCPT::sb_currency_table_head');
        add_action('manage_sb_currency_posts_custom_column', 'CurrencyCPT::sb_currency_table_content', 10, 2);
        add_filter('manage_sb_loctype_posts_columns', 'LocationTypeCPT::sb_loctype_table_head');
        add_action('manage_sb_loctype_posts_custom_column', 'LocationTypeCPT::sb_loctype_table_content', 10, 2);
        add_filter('manage_sb_location_posts_columns', 'LocationCPT::sb_location_table_head');
        add_action('manage_sb_location_posts_custom_column', 'LocationCPT::sb_location_table_content', 10, 2);
        add_filter('manage_sb_business_posts_columns', 'BusinessCPT::sb_business_table_head');
        add_action('manage_sb_business_posts_custom_column', 'BusinessCPT::sb_business_table_content', 10, 2);
        add_filter('manage_sb_businessunit_posts_columns', 'BusinessUnitCPT::sb_businessunit_table_head');
        add_action('manage_sb_businessunit_posts_custom_column', 'BusinessUnitCPT::sb_businessunit_table_content', 10, 2);
        add_filter('manage_sb_partycat_posts_columns', 'PartyCategoryCPT::sb_partycat_table_head');
        add_action('manage_sb_partycat_posts_custom_column', 'PartyCategoryCPT::sb_partycat_table_content', 10, 2);
        add_filter('manage_sb_partytype_posts_columns', 'PartyTypeCPT::sb_partytype_table_head');
        add_action('manage_sb_partytype_posts_custom_column', 'PartyTypeCPT::sb_partytype_table_content', 10, 2);
        add_filter('manage_sb_roletype_posts_columns', 'RoleTypeCPT::sb_roletype_table_head');
        add_action('manage_sb_roletype_posts_custom_column', 'RoleTypeCPT::sb_roletype_table_content', 10, 2);
        add_filter('manage_sb_party_posts_columns', 'PartyCPT::sb_party_table_head');
        add_action('manage_sb_party_posts_custom_column', 'PartyCPT::sb_party_table_content', 10, 2);
        add_filter('manage_sb_partyrole_posts_columns', 'PartyRoleCPT::sb_partyrole_table_head');
        add_action('manage_sb_partyrole_posts_custom_column', 'PartyRoleCPT::sb_partyrole_table_content', 10, 2);
        add_filter('manage_sb_reltype_posts_columns', 'RelationshipTypeCPT::sb_reltype_table_head');
        add_action('manage_sb_reltype_posts_custom_column', 'RelationshipTypeCPT::sb_reltype_table_content', 10, 2);
        add_filter('manage_sb_relstatus_posts_columns', 'RelationshipStatusCPT::sb_relstatus_table_head');
        add_action('manage_sb_relstatus_posts_custom_column', 'RelationshipStatusCPT::sb_relstatus_table_content', 10, 2);
        add_filter('manage_sb_partyrel_posts_columns', 'PartyRelationshipCPT::sb_partyrel_table_head');
        add_action('manage_sb_partyrel_posts_custom_column', 'PartyRelationshipCPT::sb_partyrel_table_content', 10, 2);
        add_filter('manage_sb_partygroup_posts_columns', 'PartyGroupCPT::sb_partygroup_table_head');
        add_action('manage_sb_partygroup_posts_custom_column', 'PartyGroupCPT::sb_partygroup_table_content', 10, 2);
        add_filter('manage_sb_person_posts_columns', 'PersonCPT::sb_person_table_head');
        add_action('manage_sb_person_posts_custom_column', 'PersonCPT::sb_person_table_content', 10, 2);
        add_filter('manage_sb_partyprofile_posts_columns', 'PartyProfileCPT::sb_partyprofile_table_head');
        add_action('manage_sb_partyprofile_posts_custom_column', 'PartyProfileCPT::sb_partyprofile_table_content', 10, 2);
        add_filter('manage_sb_billaccount_posts_columns', 'BillingAccountCPT::sb_billaccount_table_head');
        add_action('manage_sb_billaccount_posts_custom_column', 'BillingAccountCPT::sb_billaccount_table_content', 10, 2);
        add_filter('manage_sb_conversation_posts_columns', 'ConversationCPT::sb_conversation_table_head');
        add_action('manage_sb_conversation_posts_custom_column', 'ConversationCPT::sb_conversation_table_content', 10, 2);
        add_filter('manage_sb_conuser_posts_columns', 'ConversationUserCPT::sb_conuser_table_head');
        add_action('manage_sb_conuser_posts_custom_column', 'ConversationUserCPT::sb_conuser_table_content', 10, 2);
        add_filter('manage_sb_message_posts_columns', 'MessageCPT::sb_message_table_head');
        add_action('manage_sb_message_posts_custom_column', 'MessageCPT::sb_message_table_content', 10, 2);
        add_filter('manage_sb_messagesfiles_posts_columns', 'MessageFilesCPT::sb_messagesfiles_table_head');
        add_action('manage_sb_messagesfiles_posts_custom_column', 'MessageFilesCPT::sb_messagesfiles_table_content', 10, 2);
        add_filter('manage_sb_notifytype_posts_columns', 'NotificationTypeCPT::sb_notifytype_table_head');
        add_action('manage_sb_notifytype_posts_custom_column', 'NotificationTypeCPT::sb_notifytype_table_content', 10, 2);
        add_filter('manage_sb_notifystatus_posts_columns', 'NotificationStatusCPT::sb_notifystatus_table_head');
        add_action('manage_sb_notifystatus_posts_custom_column', 'NotificationStatusCPT::sb_notifystatus_table_content', 10, 2);
        add_filter('manage_sb_notifylevel_posts_columns', 'NotificationLevelCPT::sb_notifylevel_table_head');
        add_action('manage_sb_notifylevel_posts_custom_column', 'NotificationLevelCPT::sb_notifylevel_table_content', 10, 2);
        add_filter('manage_sb_notification_posts_columns', 'NotificationCPT::sb_notification_table_head');
        add_action('manage_sb_notification_posts_custom_column', 'NotificationCPT::sb_notification_table_content', 10, 2);
        add_filter('manage_sb_contactus_posts_columns', 'ContactUsCPT::sb_contactus_table_head');
        add_action('manage_sb_contactus_posts_custom_column', 'ContactUsCPT::sb_contactus_table_content', 10, 2);
        add_filter('manage_sb_doctype_posts_columns', 'DocumentTypeCPT::sb_doctype_table_head');
        add_action('manage_sb_doctype_posts_custom_column', 'DocumentTypeCPT::sb_doctype_table_content', 10, 2);
        add_filter('manage_sb_docurgency_posts_columns', 'UrgencyCPT::sb_docurgency_table_head');
        add_action('manage_sb_docurgency_posts_custom_column', 'UrgencyCPT::sb_docurgency_table_content', 10, 2);
        add_filter('manage_sb_noofpages_posts_columns', 'NoOfPagesCPT::sb_noofpages_table_head');
        add_action('manage_sb_noofpages_posts_custom_column', 'NoOfPagesCPT::sb_noofpages_table_content', 10, 2);
        add_filter('manage_sb_subarea_posts_columns', 'SubjectAreaCPT::sb_subarea_table_head');
        add_action('manage_sb_subarea_posts_custom_column', 'SubjectAreaCPT::sb_subarea_table_content', 10, 2);
        add_filter('manage_sb_alevel_posts_columns', 'AcademicLevelCPT::sb_alevel_table_head');
        add_action('manage_sb_alevel_posts_custom_column', 'AcademicLevelCPT::sb_alevel_table_content', 10, 2);
        add_filter('manage_sb_wstyle_posts_columns', 'WritingStyleCPT::sb_wstyle_table_head');
        add_action('manage_sb_wstyle_posts_custom_column', 'WritingStyleCPT::sb_wstyle_table_content', 10, 2);
        add_filter('manage_sb_corder_posts_columns', 'ContentOrderCPT::sb_corder_table_head');
        add_action('manage_sb_corder_posts_custom_column', 'ContentOrderCPT::sb_corder_table_content', 10, 2);
        //add_shortcode('show_single_entity', array('CloderiaUIDisplayAPI', 'display_single_entity'));
    }

    /*public static function redirect_logout_url(){
         wp_redirect(home_url());
    }*/

    /**
     * Enqueue the necessary scripts
     */
    public static function enqueue_scripts() {

        wp_register_script('cp_init', plugins_url('/js/init.js', __FILE__), array('jquery'), true);
        wp_register_script('bootstrap_js', plugins_url('/js/bootstrap.min.js', __FILE__), array('jquery'), true);
        wp_register_script('jquery_form_js', plugins_url('/js/jquery.form.min.js', __FILE__), array('jquery'), true);
        wp_register_script('bootstrap_tabdrop_js', plugins_url('/js/bootstrap-tabdrop.js', __FILE__), array('jquery'), true);
        wp_register_script('datatables_core_js', plugins_url('/js/jquery.dataTables.min.js', __FILE__), array('jquery'), true);
        wp_register_script('datatables_bootstrap_js', plugins_url('/js/dataTables.bootstrap.js', __FILE__), array('jquery'), true);
        wp_register_script('bootstrap_validator_js', plugins_url('/js/bootstrapValidator.min.js', __FILE__), array('jquery'), true);
        wp_register_script('entity_datasource_js', plugins_url('/js/entity-datatables.js', __FILE__), array('jquery'), true);
        wp_register_script('entity_multi_datatables_js', plugins_url('/js/entity-multi-datatables.js', __FILE__), array('jquery'), true);
        wp_register_script('datetimepicker_js', plugins_url('/js/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js', __FILE__), array('jquery'), true);
        wp_register_script('wizard_js', plugins_url('/js/vendors/bootstrap-wizard/jquery.bootstrap.wizard.min.js', __FILE__), array('jquery'), true);

        wp_register_script('input_mask_js', plugins_url('/js/jquery.mask.min.js', __FILE__), array('jquery'), true);
        wp_register_script('jstree_js', plugins_url('/js/jstree.min.js', __FILE__), array('jquery'), true);
        wp_register_script('conversate_js', plugins_url('/js/conversate.js', __FILE__), array('jquery'), true);

        wp_enqueue_script('jquery_form_js');
        wp_enqueue_script('bootstrap_validator_js');
        wp_enqueue_script('bootstrap_tabdrop_js');
        wp_enqueue_script('datatables_core_js');
        wp_enqueue_script('datatables_bootstrap_js');
        wp_enqueue_script('cp_init');
        wp_enqueue_script('conversate_js');

        // Enqueue data tables js for view pages
        if(isset($_REQUEST['page_action'])) {
            $page_action = sanitize_text_field($_REQUEST['page_action']);
            if($page_action == 'view') {
                wp_enqueue_script('entity_datasource_js');
                wp_enqueue_script('entity_multi_datatables_js');
            }
        }

        wp_enqueue_script('jstree_js');
        wp_enqueue_script('input_mask_js');
        //wp_enqueue_script('datetimepicker_js');
        wp_enqueue_script('wizard_js');
        //wp_enqueue_script('conversations_js');
        
        //wp_localize_script('conversations_js', 'shadowcore_ajax_script', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_localize_script('entity_datasource_js', 'shadowcore_ajax_script', array('ajaxurl' => admin_url('admin-ajax.php')));
         wp_localize_script('entity_datasource_js', 'shadowcore_base_url', array('baseUrl' => EntityActionProcessor::get_base_url()));
        wp_localize_script('conversate_js', 'shadowcore_ajax_script', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    /**
     * Get the plugin path.
     * @return string
     */
    public static function plugin_path() {
        return untrailingslashit(plugin_dir_path(__FILE__));
    }

    /**
     * Get the template path.
     * @return string
     */
    public static function template_path() {
        return 'shadow-core/';
    }
}
// End Class
/* EOF */
