<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SinglePartyView extends SingleEntityView { 

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
        if($this->model['party_type_code'] == 'INDIVIDUAL') {
            $person_data = EntityAPI::get_by_field('person', 'party', $this->model['id']);
            if(isset($person_data['id'])) {
                if($person_data['gender'] == 'M')
                    $this->model['image'] = 'user.png';
                else
                    $this->model['image'] = 'female.png';
            }
        }
        else {
            $this->model['image'] = 'business.png';
        }
    }

    /**
     *
     */
    function get_tabs() {
        if(!isset($_REQUEST['role'])) {
            return parent::get_tabs();
        }
        $role = sanitize_text_field($_REQUEST['role']);
        if($role == 'landlord') return $this->process_client_view(); 
        if($role == 'tenant') return $this->process_tenant_view(); 
        if($role == 'prospective_tenant') return $this->process_prospectivetenant_view(); 
        if($role == 'service_provider') return $this->process_serviceprovider_view(); 
        if($role == 'utility_company') return $this->process_utilitycompany_view(); 
        if($role == 'property_personnel') return $this->process_propertypersonnel_view(); 
    }

    /**
     * 
     */
    public function process_client_view() {
        $tabs = array(
            array(
                'tab_type' => 'entity-list',
                'name' => 'property',
                'description' => 'Property',
                'model' => EntityAPI::get_model('property'),
                'artifact_name' => 'property',
                'type_instances' =>  array(),
            ),
            array(
                'tab_type' => 'entity-list',
                'name' => 'dispute',
                'description' => 'Dispute',
                'model' => EntityAPI::get_model('dispute'),
                'artifact_name' => 'dispute',
                'type_instances' =>  array(),
            )
        );
        return $tabs;
    }

    /**
     * 
     */
    public function process_tenant_view() {

        $tabs = array();
        $tabs_entities = array('rent', 'rentagreement', 'dispute');

        /*foreach ($this->model['related_child_entities'] as $related_child_entity) {
            $artifact_name = strtolower($related_child_entity['entity_name']);
            if(in_array($artifact_name, $tabs_entities)) {
                $tab = array(
                    'tab_type' => 'entity-list',
                    'name' => $related_child_entity['name'],
                    'description' => $related_child_entity['entity_description'],
                    'model' => EntityAPI::get_model(strtolower($related_child_entity['entity_name'])),
                    'artifact_name' => $artifact_name,
                    'type_instances' =>  array(),
                );
                array_push($tabs, $tab);
            }
        }*/
        return $tabs;
        
    }

    /**
     * 
     */
    public function process_prospectivetenant_view() {
        return array();
    }

    /**
     * 
     */
    public function process_serviceprovider_view() {
        $tabs = array();
        $tabs_entities = array('serviceagreement', 'dispute');

        foreach ($this->model['related_child_entities'] as $related_child_entity) {
            $artifact_name = strtolower($related_child_entity['entity_name']);
            if(in_array($artifact_name, $tabs_entities)) {
                $tab = array(
                    'tab_type' => 'entity-list',
                    'name' => $related_child_entity['name'],
                    'description' => $related_child_entity['entity_description'],
                    'model' => EntityAPI::get_model(strtolower($related_child_entity['entity_name'])),
                    'artifact_name' => $artifact_name,
                    'type_instances' =>  array(),
                );
                array_push($tabs, $tab);
            }
        }
        return $tabs;
    }

    /**
     * 
     */
    public function process_utilitycompany_view() {

        $tabs = array();
        $tabs_entities = array('rent', 'rentagreement', 'dispute');

        foreach ($this->model['related_child_entities'] as $related_child_entity) {
            $artifact_name = strtolower($related_child_entity['entity_name']);
            if(in_array($artifact_name, $tabs_entities)) {
                $tab = array(
                    'tab_type' => 'entity-list',
                    'name' => $related_child_entity['name'],
                    'description' => $related_child_entity['entity_description'],
                    'model' => EntityAPI::get_model(strtolower($related_child_entity['entity_name'])),
                    'artifact_name' => $artifact_name,
                    'type_instances' =>  array(),
                );
                array_push($tabs, $tab);
            }
        }
        return $tabs;
        
    }

    /**
     * 
     */
    public function process_propertypersonnel_view() {

        $tabs = array();
        $tabs_entities = array('rent', 'rentagreement', 'dispute');

        foreach ($this->model['related_child_entities'] as $related_child_entity) {
            $artifact_name = strtolower($related_child_entity['entity_name']);
            if(in_array($artifact_name, $tabs_entities)) {
                $tab = array(
                    'tab_type' => 'entity-list',
                    'name' => $related_child_entity['name'],
                    'description' => $related_child_entity['entity_description'],
                    'model' => EntityAPI::get_model(strtolower($related_child_entity['entity_name'])),
                    'artifact_name' => $artifact_name,
                    'type_instances' =>  array(),
                );
                array_push($tabs, $tab);
            }
        }
        return $tabs;
        
    }

    /**
     * Render this view
     */
    public function get_edit_url() {
        $party_type = EntityAPI::get_by_code('partytype', 'INDIVIDUAL');

        $role_param = '';
        if(isset($_REQUEST['role'])) $role_param = '&role=' . sanitize_text_field($_REQUEST['role']);

        if(isset($party_type['id'])) {
            if($party_type['id'] == $this->model['party_type']) {
                $person_data = EntityAPI::get_by_field('person', 'party', $this->model['id']);
                if(isset($person_data['id'])) 
                    return EntityActionProcessor::get_base_url() . 'artifact=person&id=' . $person_data['id'] . $this->get_parent_url() . '&page_action=edit' . $role_param;
            }
            else {
                $partygroup_data = EntityAPI::get_by_field('partygroup', 'party', $this->model['id']);
                if(isset($partygroup_data['id'])) 
                    return EntityActionProcessor::get_base_url() . 'artifact=partygroup&id=' . $partygroup_data['id'] . $this->get_parent_url() . '&page_action=edit' . $role_param;
            }
        }
        // execute default render operation
        return EntityActionProcessor::get_base_url() . 'artifact=' . $this->get_artifact_name() . '&id=' . $this->model['id'] . $this->get_parent_url() . '&page_action=edit' . $role_param;
    }

    /**
     * Render this view
     */
    public function get_delete_url() {
        $party_type = EntityAPI::get_by_code('partytype', 'INDIVIDUAL');

        $role_param = '';
        if(isset($_REQUEST['role'])) $role_param = '&role=' . sanitize_text_field($_REQUEST['role']);
        
        if(isset($party_type['id'])) {
            if($party_type['id'] == $this->model['party_type']) {
                $person_data = EntityAPI::get_by_field('person', 'party', $this->model['id']);
                if(isset($person_data['id'])) 
                    return EntityActionProcessor::get_base_url() . 'artifact=person&id=' . $person_data['id'] . $this->get_parent_url() . '&page_action=delete' . $role_param;
            }
            else {
                $partygroup_data = EntityAPI::get_by_field('partygroup', 'party', $this->model['id']);
                if(isset($partygroup_data['id'])) 
                    return EntityActionProcessor::get_base_url() . 'artifact=partygroup&id=' . $partygroup_data['id'] . $this->get_parent_url() . '&page_action=delete' . $role_param;
            }
        }
        // execute default render operation
        return EntityActionProcessor::get_base_url() . 'artifact=' . $this->get_artifact_name() . '&id=' . $this->model['id'] . $this->get_parent_url() . '&page_action=delete' . $role_param;
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