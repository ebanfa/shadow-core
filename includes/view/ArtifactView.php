<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ArtifactView {

    public $page_info;
    public $artifact;
    public $page_name;
    public $page_action;
    public $page_action_txt;
    public $page_action_description;

    /**
     *
     */
    function __construct() {
        $this->set_up();
    }

    /**page
     * Set up view
     */
    public function set_up() {
        $this->page_info = $_REQUEST['page_info'];
        $_REQUEST['page_info']['view'] = $this;

        $this->add_actions();
        $this->process_artifact();
    }

    /**
     * Register hooks
     */
    public function add_actions() {
        add_action('wp_footer', array($this, 'enqueue_scripts'));
    }

    /**
     * Process the artifact and page action request parameters
     */
    public function process_artifact() {
        $this->artifact = sanitize_text_field($this->page_info['artifact']);
        $this->page_action = sanitize_text_field($this->page_info['page_action']);
        $this->page_name = sanitize_text_field($this->page_info['artifact_display_name']);
        $this->page_action_description = sanitize_text_field($this->page_info['page_action_description']);
        $this->set_page_action_txt();
    }

    /**
     * Action method to enqueue the required scripts
     */
    public function enqueue_scripts() {
       
    }

    /**
     * Action method to enqueue the required scripts
     */
    public function get_artifact_name() {
       return $this->artifact;
    }

    /**
     * Action method to enqueue the required scripts
     */
    public function get_page_name() {
        $page_name_filter = 'shadowbanker_' . $this->artifact . '_view_title';
        if (has_filter($page_name_filter)) {
            $this->page_name = apply_filters($page_name_filter, $this, $this->page_name);
        }
        return $this->page_name;
    }

    /**
     * Action method to enqueue the required scripts
     */
    public function get_page_action() {
       return $this->page_action;
    }

    /**
     * Action method to enqueue the required scripts
     */
    public function get_page_action_description() {
       return $this->page_action_description;
    }

    /**
     * Action method to enqueue the required scripts
     */
    public function get_page_action_txt() {
        $page_description_filter = 'shadowbanker_' . $this->artifact . '_view_description';
        if (has_filter($page_description_filter)) {
            $this->page_action_txt = apply_filters($page_description_filter, $this, $this->page_action_txt);
        }
       return $this->page_action_txt;
    }

    /**
     * Action method to enqueue the required scripts
     */
    public function set_page_action_txt() {
        $this->page_action_txt = ViewUtils::get_view_header_description($this->page_action, $this->page_name);
    }

    /**
     * 
     */
    public function get_action_links() {
        $action_links = $this->get_view_action_links();
        $action_links_filter = 'shadowbanker_' . $this->artifact . '_action_links';
        if(has_filter($action_links_filter)) {
            $action_links = apply_filters($action_links_filter, $this, $action_links );
        }
        return $action_links; 
    }

    /**
     *
     */
    public function get_view_action_links() {
        return ViewUtils::get_entity_action_links($this->page_action, $this->artifact);
    }

    /**
     * 
     */
    public function get_form_fields() {
        $form_fields = array();
        $fields_filter = 'shadowbanker_' . $this->artifact . '_form_fields';
        if (has_filter($fields_filter)) {
            $form_fields = apply_filters($fields_filter, $this, $form_fields);
        }
        return $form_fields; 
    }

    /**
     * This method add additional information to a field.
     * The added information is required to display the field.
     */
    public function prepare_view_form_field($field) {
        return ViewUtils::prepare_view_form_field($this, $field);
    }

    /**
     * Render this view
     */
    public function render() {
        $custom_render_action = 'shadowbanker_render_' . $this->page_action .'_' . $this->artifact . '_view';

        if(isset($_REQUEST['page_info'])) {
            
            do_action('shadowbanker_before_main_content');

            do_action('shadowbanker_before_artifact_content');

            if(has_action($custom_render_action)) {
                // action exists so execute it
                do_action($custom_render_action);
            } else {
                // action has not been registered
                // execute default render operation
                //do_action('shadowbanker_render_'. $this->page_action . '_entity_view');
                $this->render_impl();
             }

            do_action('shadowbanker_after_artifact_content');

            do_action('shadowbanker_after_main_content');

           
        }
    }

    /**
     * Render this view
     */
    public function render_impl() {
        // execute default render operation
    }

}

?>