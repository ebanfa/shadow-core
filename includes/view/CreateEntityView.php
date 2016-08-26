<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class CreateEntityView extends BaseEntityView {

    /**
     *
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Action method to enqueue the required scripts
     */
    public function enqueue_scripts() {

        wp_register_script('cp_entity_form', 
            plugins_url('/js/entity-form.js', dirname(dirname(__FILE__))), array('jquery'),'', true);
        wp_register_script('cp_entity_mask', 
            plugins_url('/js/entity-input-mask.js', dirname(dirname(__FILE__))), array('jquery'),'', true);

        wp_enqueue_script('cp_entity_form');
        wp_enqueue_script('cp_entity_mask');
    }


    /**
     * Render this view
     */
    public function render_impl() {
        // execute default render operation
        CloderiaUIDisplayAPI::display_entity_create_form($_REQUEST['page_info']);
        
    }
}

?>