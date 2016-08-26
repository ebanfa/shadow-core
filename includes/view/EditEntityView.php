<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class EditEntityView extends BaseEntityView { 

    /**
     *
     */
    function __construct() {
        parent::__construct();
        if (isset($_REQUEST['id'])) {
            $this->model = EntityAPI::get_by_id($this->get_artifact_name(),sanitize_text_field($_REQUEST['id']));
        }
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
     * 
     */
    public function get_form_fields() {
        $form_fields = parent::get_form_fields();
        $edit_fields = $this->get_edit_form_fields($form_fields);
        return $edit_fields; 
    }

    /**
     * 
     */
    public function get_edit_form_fields($form_fields) {
        $edit_fields = array();
        foreach ($form_fields as $field) {
            if ($field['is_edit_field'] && $field['is_form_field']) {
                array_push($edit_fields, $field);
            }
        }
        return $edit_fields; 
    }

    /**
     * Render this view
     */
    public function render_impl() {
        // execute default render operation
        CloderiaUIDisplayAPI::display_entity_edit_form($_REQUEST['page_info']);
    }

}

?>