<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class MultiEntityCreateView extends CreateEntityView {


    public $tabs;

    /**
     *
     */
    function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    function set_up() {
        parent::set_up();
        $tabs = $this->get_tabs();
    }

    /**
     * Action method to enqueue the required scripts
     */
    public function enqueue_scripts() {

        wp_register_script('cp_entity_form', 
            plugins_url('/js/entity-form.js', dirname(dirname(__FILE__))), array('jquery'),'', true);
        

        wp_enqueue_script('cp_entity_form');
    }
    /**
     *
     */
    function get_tabs() {
        return array();
    }

    /**
     * Render this view
     */
    public function render() {
        if(isset($_REQUEST['page_info'])) {
            
            do_action('shadowbanker_before_main_content');

            do_action('shadowbanker_before_artifact_content');
            
            do_action('shadowbanker_render_multi_entity_create_view');

            do_action('shadowbanker_after_artifact_content');

            do_action('shadowbanker_after_main_content');

           
        }
    }

}

?>