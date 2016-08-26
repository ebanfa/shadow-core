<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SingleNotificationView extends SingleEntityView { 

	/**
     *
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Process the load the model for this artifact
     */
    public function process_model() {
        parent::process_model();
        NotificationAPI::update_notification_status($this->model['id'], NotificationAPI::$viewed);
        
        
    }

    
}

?>