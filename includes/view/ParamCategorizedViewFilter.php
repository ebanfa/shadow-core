<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class ParamCategorizedViewFilter extends ViewFilter {

    public static $type_field = 'type';
    public static $category_field = 'category';
    public static $business_category_field = 'business_category';

    /**
     *
     */
    public static function filter_form_fields($view, $form_fields) {
        $model = $view->get_model();
        $category_data = array();
        $business_category_data = array();
        $page_action = $view->get_page_action();
        $form_fields = parent::filter_form_fields($view, $form_fields);

        if(isset($_REQUEST['bcat'])) 
        {
            $business_category = sanitize_text_field($_REQUEST['bcat']);
            $business_category_data = EntityAPI::get_by_code(
                'businesscategory', array('entity_code' => strtoupper($business_category)));

             if(isset($business_category_data['id'])) {
                $business_category_field = array();
                $business_category_field['value'] = $business_category_data['id'];
                $business_category_field['name'] = 'business_category';
                $business_category_field['data_type'] = 'hidden';
                $business_category_field['is_relationship_field'] = false;
                $business_category_field['view_criteria'] = array(
                    'bcat' => strtolower($business_category_data['entity_code'])
                );
                $business_category_field['options_criteria'] = array(
                    'business_category' => $business_category_data['id']
                );
                $form_fields['business_category'] = $business_category_field;
            }
        }
        
        if(isset($_REQUEST['cat'])) 
        {
            $category = sanitize_text_field($_REQUEST['cat']);
            $category_data = EntityAPI::get_by_code(
                $model['entity_artifact_name'] . 'category', 
                array('entity_code' => strtoupper($category)));
             if(isset($category_data['id'])) {
                $category_field = array();
                $category_field['value'] = $business_category_data['id'];
                $category_field['name'] = 'category';
                $category_field['data_type'] = 'hidden';
                $category_field['is_relationship_field'] = false;
                $category_field['view_criteria'] = array(
                    'cat' => strtolower($category_data['entity_code'])
                );
                $category_field['options_criteria'] = array(
                    'category' => $category_data['id']
                );
                $form_fields['category'] = $category_field;
            }
        }
        // Marker for the type field
        $type_key = -1;
        foreach ($form_fields as $key => $field) {
            if($field['name'] == self::$type_field && isset($category_data['id']) && isset($business_category_data['id'])) {

                $field['options_criteria'] = array(
                    'category' => $category_data['id'],
                    'business_category' => $business_category_data['id']
                );
                $form_fields[$key] = $field;
                $type_key = $key;
            }
        }
        // Categorized entities have their type specified in the $_REQUEST,
        // so we modify it into a hidden field
        if(($page_action == 'create' || $page_action == 'edit') && isset($_REQUEST['atype'])) {
            if(isset($form_fields[$type_key])) {
                $entity_type = sanitize_text_field($_REQUEST['atype']);
                $type_data = EntityAPI::get_by_code(
                    $model['entity_artifact_name'] . 'type', array('entity_code' => strtoupper($entity_type)));

                if(isset($type_data['id'])) {
                    $entity_type_field = array();
                    $entity_type_field['name'] = 'type';
                    $entity_type_field['data_type'] = 'hidden';
                    $entity_type_field['value'] = $type_data['id'];
                    $entity_type_field['is_relationship_field'] = false;
                    $form_fields[$type_key] = $entity_type_field;
                }
            }
        }
        
        return $form_fields;
    }

    /**
     *
     */
    public static function filter_view_action_links($view, $action_links) {
        $page_action = $view->get_page_action();
        
        if($page_action == 'create') {
            return self::filter_create_view_action_links($view, $action_links);
        }
        elseif($page_action == 'edit') {
            return self::filter_edit_view_action_links($view, $action_links);
        }
        elseif($page_action == 'view') {
            return self::filter_view_view_action_links($view, $action_links);
        }
        elseif($page_action == 'list') {
            return self::filter_list_view_action_links($view, $action_links);
        }
        else {
            return $action_links;
        }
    
    }

    /**
     *
     */
    public static function filter_create_view_action_links($view, $action_links) {
        $model = $view->get_model();
        if(isset($_REQUEST['bcat']) && isset($_REQUEST['cat'])) {

            $category = sanitize_text_field($_REQUEST['cat']);
            $business_category = sanitize_text_field($_REQUEST['bcat']);

            if(!EntityStringUtils::is_invalid_string($business_category) && 
                !EntityStringUtils::is_invalid_string($business_category)) {
                $category = '&cat=' . $category;
                $business_category = '&bcat=' . $business_category;
            }
            $action_links['list_entity_link'] = array('name' => 'View All', 
                'link' => EntityActionProcessor::get_base_url() . 'artifact=' . $model['entity_artifact_name'] . '&page_action=list' . $business_category . $category);
        }
        return $action_links;
    }

    /**
     *
     */
    public static function filter_view_view_action_links($view, $action_links) {
        $model = $view->get_model();
        $category_data = self::get_entity_category($model);
        $business_category_data = self::get_entity_business_category();
            
        if(isset($business_category_data['id']) && isset($business_category_data['id'])) {
            $action_links = array();
            $criteria_data = array(
                self::$category_field => $category_data['id'],
                self::$business_category_field => $business_category_data['id']
            );

            $entity_types = EntityAPI::find_by_criteria($model['entity_artifact_name'] . 'type', $criteria_data);
            // for each create a link
            foreach ($entity_types as $key => $type) {
                $action_links[$key] = array('name' => 'New '. $type['name'], 
                'link' => EntityActionProcessor::get_base_url() . 
                'page_action=create&artifact=' . $model['entity_artifact_name'] . 
                '&cat=' . strtolower($category_data['entity_code']) . 
                '&bcat=' . strtolower($business_category_data['entity_code']) . 
                '&atype=' . strtolower($type['entity_code']));
            }
            $action_links['list_entity_link'] = array('name' => 'View All', 
                'link' => EntityActionProcessor::get_base_url() . 'artifact=' . $model['entity_artifact_name'] . '&page_action=list&cat=' . strtolower($category_data['entity_code']) . 
                    '&bcat=' . strtolower($business_category_data['entity_code']));
        }
        return $action_links;
    }

    /**
     *
     */
    public static function filter_list_view_action_links($view, $action_links) {
        $model = $view->get_model();
        $category_data = self::get_entity_category($model);
        $business_category_data = self::get_entity_business_category();

        if(isset($category_data['id']) && isset($business_category_data['id'])) {
            $action_links = array();
            $criteria_data = array(
                self::$category_field => $category_data['id'],
                self::$business_category_field => $business_category_data['id']
            );
            $entity_types = EntityAPI::find_by_criteria($model['entity_artifact_name'] . 'type', $criteria_data);
            // for each create a link
            foreach ($entity_types as $key => $type) {
                $action_links[$key] = array('name' => 'New '. $type['name'], 
                'link' => EntityActionProcessor::get_base_url() . 
                'page_action=create&artifact=' . $model['entity_artifact_name'] . 
                '&cat=' . strtolower($category_data['entity_code'])  . 
                '&bcat=' . strtolower($business_category_data['entity_code']) . 
                '&atype=' . strtolower($type['entity_code']));
            }
        }
        return $action_links;
    }

    /**
     *
     */
    public static function get_entity_category($model) {
        $category_data = array();
        if(isset($_REQUEST['cat'])) {
            $action_links = array();
            $category = sanitize_text_field($_REQUEST['cat']);
            $category_data = EntityAPI::get_by_code(
                $model['entity_artifact_name'] . 'category', array('entity_code' => strtoupper($category)));
        }
        return $category_data;
    }

    /**
     *
     */
    public static function get_entity_business_category() {
        $business_category_data = array();
        if(isset($_REQUEST['bcat'])) {
            $action_links = array();
            $business_category = sanitize_text_field($_REQUEST['bcat']);
            $business_category_data = EntityAPI::get_by_code('businesscategory', array('entity_code' => strtoupper($business_category)));
        }
        return $business_category_data;
    }

}

?>