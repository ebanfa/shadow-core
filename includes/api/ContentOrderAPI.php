<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ContentOrderAPI  {

    /**
     *
     */
    public static function do_create_entity($entity_data){
        $party_data = array();
        $user = get_user_by('login', $entity_data['email']);
        if($user) { 
            $party_data = EntityAPI::get_by_field('party', 'user_name', $user->user_login);;
            if(!isset($party_data['id'])) {
                $user_data = self::build_signup_data_from_entity($entity_data);
                $party_data = CloderiaUserAPI::create_shadow_user($user_data);
            }
        }
        else {
            // Create the user and signin
            if(isset($entity_data['email'])) {
                $party_data = CloderiaUserAPI::do_create_content_user($entity_data);
                $signin_results = ContentUserLoginAPI::do_signin_user($party_data['user_name'], $party_data['password']);
                if ($signin_results['hasErrors']) wp_send_json_error(array('message' => $signin_results['message']));
            }
        }
        // Create the content order
        if(isset($party_data['id'])) {
            $entity_data['party'] = $party_data['id'];
            $entity_data['business_unit'] = $party_data['business_unit'];
            $entity_data = self::do_create_content_order($entity_data);
            // Send out the order created email
            self::send_order_created_email($entity_data);
            // Process login redirect
            if (!is_user_logged_in()) $entity_data = ContentOrderAPI::process_login_redirect($entity_data) ;
        }

        return $entity_data;
        
    }

    /**
     * 
     */
    public static function do_create_content_order($entity_data) 
    {   
        if(isset($entity_data['document_type'])) {
            $doc_data = EntityAPI::get_by_code('documenttype', $entity_data['document_type']);
            if(isset($doc_data['id'])) $entity_data['document_type'] = $doc_data['id'];
        }
        if(isset($entity_data['urgency'])) {
            $urgency = EntityAPI::get_by_code('urgency', $entity_data['urgency']);
            if(isset($urgency['id'])) $entity_data['urgency'] = $urgency['id'];
        }
        if(isset($entity_data['numpages'])) {
            $numpages = EntityAPI::get_by_code('noofpages', $entity_data['numpages']);
            if(isset($numpages['id'])) $entity_data['numpages'] = $numpages['id'];
        }
        if(isset($entity_data['subject_area'])) {
            $subject_area = EntityAPI::get_by_code('subjectarea', $entity_data['subject_area']);
            if(isset($subject_area['id'])) $entity_data['subject_area'] = $subject_area['id'];
        }
        if(isset($entity_data['academic_level'])) {
            $academic_level = EntityAPI::get_by_code('academiclevel', $entity_data['academic_level']);
            if(isset($academic_level['id'])) $entity_data['academic_level'] = $academic_level['id'];
        }
        if(isset($entity_data['writing_style'])) {
            $writing_style = EntityAPI::get_by_code('writingstyle', $entity_data['writing_style']);
            if(isset($writing_style['id'])) $entity_data['writing_style'] = $urgency['id'];
        }
        $entity_data = EntityAPI::do_create_entity($entity_data);
        if(isset($entity_data['id'])) $entity_data = EntityAPI::get_by_id('contentorder', $entity_data['id']);

        if(isset($entity_data['entity_code'])) 
            $entity_data['redirect_url'] =  get_site_url() . '/order-review?order=' . $entity_data['entity_code'];
        return $entity_data;
    }

    /**
     * 
     */
    public static function send_order_created_email($entity_data) 
    {   
        // Find the user
        $user = get_user_by('login', $entity_data['email'] );
        if($user) {
            // Get the user data context {username, password etc}
            $data_context = self::get_order_data_context($entity_data);

            //update_user_meta($user->ID, 'userDataContext', implode("|", $data_context));
            // 2. Send mail to user,
            CloderiaUserAPI::send_email($data_context, $user->user_login, 'order-created-subject.tpl', 'order-created-message.tpl', array());
            // 3 Send mail to the admin
            CloderiaUserAPI::send_email($data_context, get_option('cp_notify_orders'), 'order-created-subject.tpl', 'order-created-message.tpl', array());
        }
    }

    public static function get_order_data_context($entity_data) {
        $dataContext = array();
        $dataContext['display_name'] = $entity_data['email'];
        $dataContext['order_no'] = $entity_data['entity_code'];
        $dataContext['order_subtotal'] = $entity_data['total'];
        $dataContext['order_discount'] = 0.00;
        $dataContext['order_total'] = $entity_data['total'];
        //$date_now = new DateTime();
        //$dataContext['date_now'] = $date_now->format(ContentPort::$date_format);
        $dataContext['order_date'] = date("Y-m-d H:i:s");
        $dataContext['order_topic'] = $entity_data['name'];
        $dataContext['site_url'] = get_site_url();
        $dataContext['site_name'] = get_bloginfo('name');
        $dataContext['site_descriptions'] = get_bloginfo('descriptions');
        $dataContext['site_email'] = get_option('cp_notify_loans');
        $dataContext['site_domain'] = get_option('cp_site_domain');
        $dataContext['site_stylesheet_uri'] = get_stylesheet_directory_uri();
        $dataContext['order_subject'] = $entity_data['subject_area_txt'];
        $dataContext['order_level'] = $entity_data['academic_level_txt'];
        $dataContext['order_pages'] = $entity_data['numpages_txt'];
        $dataContext['order_type'] = $entity_data['document_type_txt'];
        $dataContext['order_term'] =$entity_data['urgency_txt']; 
        
        return $dataContext;
    }

    /**
     *
     */
    public static function do_find_entity($entity_data) {
        return EntityAPI::do_find_entity($entity_data);
    }

    /**
     *
     */
    public static function do_delete_entity($entity_data) {
        return EntityAPI::do_delete_entity($entity_data);
    }

    

    /*
     *
     **/
    public static function do_signin_user($username, $password) {
        $login_data = array();
        $login_data['user_login'] = $username;
        $login_data['user_password'] = $password;

        $user_verify = wp_signon($login_data, true);

        if (is_wp_error($user_verify)) {
            return array('hasErrors' => true,
                'message' => "Invalid username or password. Please try again.");
        } else {
            wp_set_current_user($user_verify->ID);
            wp_set_auth_cookie($user_verify->ID);
            // Build the return
            $content_user = array('user_login' => $username, 'user_password' => $password);
            // Process redirect
            if (isset($_POST['redirect_to'])) {
                $content_user['redirect_url'] = $_POST['redirect_to'];
            } else {
                    $content_user['redirect_url'] = site_url() . '/page?page_action=list&artifact=contentorder';
            }
            return array('hasErrors' => false, 'content_user' => $content_user);
        }
    }

    /*
     * 
     **/
    public static function do_create_content_user($user_data) {
        $user_id = wp_insert_user($user_data);
        if (is_wp_error($user_id)) {
            return array('hasErrors' => true, 'message' => $user_id->get_error_message());
        }
        update_new_user_meta($user_id, $user_data);;
        $user_data['hasErrors'] = false;
        return $user_data;
    }

    /*
     * 
     **/
    public static function process_login_redirect($order_data) 
    {
        
        $order_data['requires_redirect'] = true;
        $login_page_url = get_site_url() . '/signin?redirect_to=';
        $invoice_page_url = get_site_url() . '/order-review?order=' . $order_data['entity_code'];

        if (is_user_logged_in()) 
        { 
            $order_data['redirect_url'] = $invoice_page_url;  
        }
        else {
            $order_data['redirect_url'] = $login_page_url . $invoice_page_url;
        }
        return $order_data;
    }

    /*
 *
 */
function build_signup_data_from_entity($entity_data) {

    $user_name = $entity_data['email'];
    $password = EntityStringUtils::get_token(8);
    $first_name = $entity_data['email'];
    $last_name = $entity_data['email'];
    $user_type = 'INDIVIDUAL';
    // Split the username at the @ sign
    $account_name = $user_name;
    $split_username = explode('@', $account_name);
    if (!empty($split_username)) {
        $account_name = ucfirst($split_username[0]);
    }
    $user_data = array();
    $user_data['user_login'] = $user_name;
    $user_data['user_pass'] = $password;
    $user_data['first_name'] = $first_name;
    $user_data['last_name'] = $last_name;
    $user_data['display_name'] = $account_name;
    $user_data['user_email'] = $user_name;
    $user_data['description'] = '';
    $user_data['role'] = $user_type;
    $user_data['business_name'] = $account_name;
    return $user_data;
}

}
