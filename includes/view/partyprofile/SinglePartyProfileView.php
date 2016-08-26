<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SinglePartyProfileView extends SingleEntityView { 


    public $tabs;


	/**
     *
     */
    function __construct() {
        parent::__construct();
        if (isset($_REQUEST['id'])) {
        	
    	}
    }

    /**
     * Process the load the model for this artifact
     */
    public function process_model() {
        $party_data = PartyAPI::get_current_user_party();
        $artifact_data = ArtifactUtils::$artifacts[$this->artifact];

        if(isset($_REQUEST['id'])) {
            $this->model = EntityAPI::get_by_id($this->get_artifact_name(), sanitize_text_field($_REQUEST['id']));
            if(isset($party_data['id']))
                $this->model['user_name'] = $party_data['user_name'];
            $tabs = $this->get_tabs();
        }
        else {
            if(isset($party_data['id'])) {
                $profile_data = EntityAPI::get_by_field($this->get_artifact_name(), 'party', $party_data['id']);
                if (isset($profile_data['id'])) 
                    $this->model = $profile_data;
                $this->model['user_name'] = $party_data['user_name'];
                $tabs = $this->get_tabs();
            }
        }
    }

   /**
     * Render this view
     */
    public function render() {
        $custom_render_action = 'shadowbanker_render_' . $this->page_action .'_' . $this->artifact . '_view';

        if(isset($_REQUEST['page_info'])) {
            
            do_action('shadowbanker_before_main_content');

           // do_action('shadowbanker_before_artifact_content');

            if(has_action($custom_render_action)) {
                // action exists so execute it
                do_action($custom_render_action);
            } else {
                // action has not been registered
                // execute default render operation
                //do_action('shadowbanker_render_'. $this->page_action . '_entity_view');
                $this->render_impl();
             }

            //do_action('shadowbanker_after_artifact_content');

            do_action('shadowbanker_after_main_content');

           
        }
    }
}

?>