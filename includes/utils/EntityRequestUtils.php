<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
include_once('EntityStringUtils.php');

class EntityRequestUtils {

    /**
     *
     */
    public static function is_valid_form() {
        // Ensure we have a valid form
        if(!isset($_POST['submitted']) && 
        	!isset($_POST['post_nonce_field']) && 
        	!wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    public static function build_entity_data_from_post($entity_data){
        // Extract the edit mode
        $entity_data['edit_mode'] = true;
        if (sanitize_text_field($_POST['edit_mode']) == 'edit') {
            $entity_data['edit_mode'] = false;
        }
        if($entity_data['edit_mode']) {
            //Process entity create form fields
            foreach ($entity_data['entity_fields'] as $field_data) {
                if($field_data['is_form_field'] && $field_data['is_create_field']) {
                    $entity_data = self::build_entity_field_from_post($field_data, $entity_data);
                }
            }
            // Process non global entity data
            if(!$entity_data['is_global_entity']){
                $business_unit = BusinessUnitAPI::get_current_user_business_unit();
                if (isset($business_unit['id'])) {
                    $entity_data['business_unit'] = $business_unit['id'];
                }
            }
        }
        else {
            // Get the id
            if (isset($_POST['id']))
                $entity_data['id'] = sanitize_text_field($_POST['id']);
            //Process entity create form fields
            foreach ($entity_data['entity_fields'] as $field_name => $field_data) {

                if($field_data['is_form_field'] && $field_data['is_edit_field']) {
                    $entity_data = self::build_entity_field_from_post($field_data, $entity_data);
                }
            }
        }
        return $entity_data;
    }
    /**
     *
     */
    public static function build_entity_field_from_post($field_data, $entity_data){
        //Process date field
        if($field_data['data_type'] === 'date' ) {
            if(EntityStringUtils::is_invalid_string($_POST[$field_data['name']])) {
                $entity_data[$field_data['name']] = date("Y-m-d H:i:s");
            }
            else{
                if (isset($_POST[$field_data['name']]))
                    $entity_data[$field_data['name']] = sanitize_text_field($_POST[$field_data['name']]);
                    //$entity_data[$field_data['name']] = date("Y-m-d H:i:s");
            }
        }
        //Process flag field
        elseif($field_data['data_type'] === 'flag' ) {
            if (!isset($_POST[$field_data['name']]))
                $entity_data[$field_data['name']] = 'N';
        }
        //Process money field
        elseif($field_data['data_type'] === 'money' ) {
            if (isset($_POST[$field_data['name']])){
                $string = str_replace(',', '', $_POST[$field_data['name']]);
                $entity_data[$field_data['name']] = floatval($string) ;
            }
        }
        // Process non date fields
        else {
            if (isset($_POST[$field_data['name']])){
                $entity_data[$field_data['name']] = sanitize_text_field($_POST[$field_data['name']]);
            }
        }
        return $entity_data;
    }


    /**
     *
     */
    public static function build_criteria_from_form_data($entity_data) {
        $criteria_data = array();
        /*if(isset($_POST['bcat'])) {
            $category_data = EntityAPI::get_by_code(
                'businesscategory', array('entity_code' => strtoupper(sanitize_text_field($_POST['cat']))));
            if(isset($category_data['id']))
                $criteria_data['type.category'] = $category_data['id'];
        }*/
        // Process global search 
        if(!EntityStringUtils::is_invalid_string($_POST['search']['value'])) {
            $criteria_data['is_global'] = true;
            $criteria_data['search'] = sanitize_text_field($_POST['search']['value']);
        }
        // Non global search
        else {
            if(isset($_POST['columns'])) {
                $columns_data = $_POST['columns'];
                foreach($columns_data as $column_data){
                    $column_name = sanitize_text_field($column_data['data']);
                    if(EntityStringUtils::ends_with($column_name, '_txt')) {
                        $column_name = str_replace('_txt', '', $column_name);
                    }
                    if(array_key_exists($column_name, $entity_data['entity_fields'])){
                        if(isset($column_data['search'])) {
                            if(!EntityStringUtils::is_invalid_string($column_data['search']['value'])) {
                                $field_value = sanitize_text_field($column_data['search']['value']);
                                $criteria_data[$column_name] = $field_value;
                            }
                        }
                    }
                }

                CloderiaLogUtils::shadow_log('Doing global');
                foreach($entity_data['entity_fields'] as $field_name => $field_data){
                    if(isset($_POST[$field_data['name']])){
                        CloderiaLogUtils::shadow_log('Doing global1');
                        $criteria_data[$field_data['name']] = sanitize_text_field($_POST[$field_data['name']]);
                    }
                }
            }
            else {

                if(isset($_POST['form'])) {
                    if(isset($_POST['form'][3])) {
                        $criteria_data[$_POST['form'][3]['name']] = $_POST['form'][3]['value'];
                    }
                }
            }
        }
        return $criteria_data;
    }

    /**
     *
     */
    public static function get_query_form_field($field_name) {
        $columns_data = $_POST['columns'];
        foreach($columns_data as $field){
            $column_name = sanitize_text_field($field['data']);
            if($column_name == $field_name){
                if(isset($column_data['search'])) {
                    return sanitize_text_field($column_data['search']['value']);
                }
            }
        }
        return false;
    }

    /**
     *
     */
    public static function get_query_string_field($field_name) {
        if(isset($_REQUEST[$field_name])) {
            return sanitize_text_field($_REQUEST[$field_name]);
        }
        return false;
    }

    /**
     *
     */
    public static function copy_fields_to_post($entity_data){
        // Add each array element into the POST array
        // This is required the custom post type persistence manager
        foreach ($entity_data as $field_name => $field_value) {
            $_POST[$field_name] = $field_value;
        }
    }

    /**
     *
     */
    public static function get_artifact_name(){
        if(!isset($_REQUEST['artifact'])) {
            return false;
        }
        return sanitize_text_field($_REQUEST['artifact']);
    }
}


?>