<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class CreatePersonView extends MultiEntityCreateView {


    /**
     *
     */
    function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    function get_tabs() {
        if(isset($_REQUEST['role'])) {
            $role = sanitize_text_field($_REQUEST['role']);
            if($role == 'landlord') return self::get_client_tabs();
            if($role == 'tenant') return self::get_tenant_tabs();
            if($role == 'prospective_tenant') return self::get_prospectivetenant_tabs();
            if($role == 'property_personnel') return self::get_propertypersonnel_tabs();
            if($role == 'service_provider') return self::get_serviceprovider_tabs();
            if($role == 'utility_company') return self::get_utilitycompany_tabs();
        }
        else {
            return array(
                'address' => array(
                    'tab_type' => 'multi-create',
                    'description' => 'Address',
                    'model' => EntityAPI::get_model('partyaddress'),
                    'artifact_name' => 'partyaddress',
                    'type_instances' => array(),
                ),
            );
        }
    }

    /**
     *
     */
    function get_client_tabs() {
        $default_tabs = self::get_default_tabs();
        return $default_tabs;
    }
    
    /**
     *
     */
    function get_tenant_tabs() {
        $default_tabs = self::get_default_tabs();
        return $default_tabs;
    }
    
    /**
     *
     */
    function get_prospectivetenant_tabs() {
        $default_tabs = self::get_default_tabs();
        return $default_tabs;
    }
    
    /**
     *
     */
    function get_propertypersonnel_tabs() {
        $default_tabs = self::get_default_tabs();
        $default_tabs['property'] = array(
                                    'tab_type' => 'multi-select',
                                    'description' => 'Property',
                                    'model' => EntityAPI::get_model('property'),
                                    'artifact_name' => 'property',
                                    'type_instances' => EntityAPI::get_model('propertytype'),
                                );
        return $default_tabs;
    }
    
    /**
     *
     */
    function get_serviceprovider_tabs() {
        $default_tabs = self::get_default_tabs();
        return $default_tabs;
    }
    
    /**
     *
     */
    function get_utilitycompany_tabs() {
        $default_tabs = self::get_default_tabs();
        return $default_tabs;
    }

    /**
     *
     */
    function get_default_tabs() {
        return array(
            'address' => array(
                'tab_type' => 'multi-create',
                'description' => 'Address',
                'model' => EntityAPI::get_model('partyaddress'),
                'artifact_name' => 'partyaddress',
                'type_instances' => array(),
            ),
            /*'images' => array(
                'tab_type' => 'multi-create',
                'description' => 'Images & Files',
                'model' => EntityAPI::get_model('partyfiles'),
                'artifact_name' => 'partyfiles',
                'type_instances' => array(),
            ),*/
        );
    }


}

?>