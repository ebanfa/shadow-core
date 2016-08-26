<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SingleEntityView extends BaseEntityView { 


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
     *
     */
    function get_tabs() {
        $tabs = array();
        foreach ($this->model['related_child_entities'] as $related_child_entity) { 
            $tab = array(
                'tab_type' => 'entity-list',
                'name' => $related_child_entity['name'],
                'description' => $related_child_entity['entity_description'],
                'model' => EntityAPI::get_model(strtolower($related_child_entity['entity_name'])),
                'artifact_name' => strtolower($related_child_entity['entity_name']),
                'type_instances' =>  array(),
            );
            array_push($tabs, $tab);
         }
         return $tabs;
    }

    /**
     * Process the load the model for this artifact
     */
    public function process_model() {
        $artifact_data = ArtifactUtils::$artifacts[$this->artifact];
        if($artifact_data['artifact_type'] == 'entity' && isset($_REQUEST['id'])) {
            $this->model = EntityAPI::get_by_id($this->get_artifact_name(), sanitize_text_field($_REQUEST['id']));
            //var_dump($this->model);
            $tabs = $this->get_tabs();
        }
    }

    /**
     * Render this view
     */
    public function render_impl() {
        // execute default render operation
        CloderiaUIDisplayAPI::display_single_entity($_REQUEST['page_info']);
    }

    /**
     * Render this view
     */
    public function get_edit_url() {
        // execute default render operation
        $additional_seach_options = '';
        foreach ($this->get_form_fields() as $field) { 
            if(isset($field['view_criteria'])) { 
                foreach ($field['view_criteria'] as $criteria_name => $criteria_value) {
                    $additional_seach_options = $additional_seach_options . '&' . $criteria_name . '=' . $criteria_value;
                }
            }
        } 
        return EntityActionProcessor::get_base_url() . 'artifact=' . $this->get_artifact_name() . '&id=' . $this->model['id'] . $this->get_parent_url() . '&page_action=edit' . $additional_seach_options;
    }

    /**
     * Render this view
     */
    public function get_delete_url() {
        // execute default render operation
        return EntityActionProcessor::get_base_url() . 'artifact=' . $this->get_artifact_name() . '&id=' . $this->model['id'] . $this->get_parent_url() . '&page_action=delete';
    }
}

?>