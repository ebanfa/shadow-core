<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
 *
 */
 use DB;
class EntityPersistenceAPI {
    
    
    /**
     *
     */
    public static function create_entity($entity_data){
        $entity = new $entity_data['entity_name'];
        foreach ($entity_data['entity_fields'] as $key => $field_data) {
            if(isset($entity_data[$field_data['name']])) {
                
                $entity->setAttribute($field_data['name'], $entity_data[$field_data['name']]);
            }
        }
        $entity->save();
        return EntityAPIUtils::entity_to_data($entity_data, $entity, true);
    }

    /**
     *
     */
    public static function update_entity($entity_data){
        $entity = call_user_func(array($entity_data['entity_name'], 'find'), $entity_data['id']);
        foreach ($entity_data['entity_fields'] as $key => $field_data) {
            if(isset($entity_data[$field_data['name']])) {
                $entity->setAttribute($field_data['name'], $entity_data[$field_data['name']]);
            }
        }
        $entity->save();
        return EntityAPIUtils::entity_to_data($entity_data, $entity, true);
    }

    /**
     *
     */
    public static function delete_entity($entity_data, $id){
        $entity = call_user_func(array($entity_data['entity_name'], 'find'), $id);
        $entity->delete();
        return $entity_data;
    }

    /**
     *
     */
    public static function find_entity($entity_data, $criteria_data) {
        $search_results = array();
        return $search_results;
    }

    /**
     *
     */
    public static function get_entity_by_id($entity_data, $id){
        $entity = call_user_func(array($entity_data['entity_name'], 'find'), $id);
        return EntityAPIUtils::entity_to_data($entity_data, $entity, true);
    }

    /**
     *
     */
    public static function get_entity_by_code($entity_data, $entity_code){
        $query = call_user_func(array($entity_data['entity_name'], 'where'), 'entity_code', $entity_code);
        $entity = $query->first();
        return EntityAPIUtils::entity_to_data($entity_data, $entity, true);
    }

    /**
     *
     */
    public static function get_entity_by_meta($entity_data, $meta_key, $meta_value){
        $query = call_user_func(array($entity_data['entity_name'], 'where'), $meta_key, $meta_value);
        $entity = $query->first();
        return EntityAPIUtils::entity_to_data($entity_data, $entity, true);
    }

    /**
     * Get all parts with id's in the list provided
     */
    public static function find_by_ids($entity_data, $entity_ids) {
        $search_results = array();
        $entities = call_user_func(array($entity_data['entity_name'], 'find'), $entity_ids);
        foreach ($entities as $key => $entity) {
            array_push($search_results, EntityAPIUtils::entity_to_data($entity_data, $entity, false));
        }
        return $search_results;
    }
     /**
     * Get all parts with id's in the list provided
     */
    public static function find_all($entity_data) {
        $search_results = array();
        $entities = call_user_func(array($entity_data['entity_name'], 'all')); 
        foreach ($entities as $key => $entity) {
            array_push($search_results, EntityAPIUtils::entity_to_data($entity_data, $entity, false));
        }
        return $search_results;
    }

    /**
     * 
     */
    public static function find_by_criteria($entity_data, $criteria_data) {
        $search_results = array();
        // Many callers of this function dont support the is_global option
        if(!isset($criteria_data['is_global'])) $criteria_data['is_global'] = false;

        if($criteria_data['is_global'])
            $query = self::build_query_from_global_criteria($entity_data, $criteria_data);
        else
            $query = self::build_query_from_criteria($entity_data, $criteria_data);
        CloderiaLogUtils::shadow_log($query->toSql());
        CloderiaLogUtils::shadow_log($query->getBindings());
        $entities = $query->get();
        CloderiaLogUtils::shadow_log('Found ' . count($entities) . ' entities');
        // Force filter the results to ensure only data from current business unit is 
        // visible
        foreach ($entities as $key => $entity) {
            $result_data = EntityAPIUtils::entity_to_data($entity_data, $entity, false);
            if(!$entity_data['is_global_entity'] && !current_user_can('administrator')) {
                $current_business_unit = BusinessUnitAPI::get_current_user_business_unit();

                if(isset($current_business_unit['id'])) {
                    // If the entity has a business_unit field
                    if(isset($result_data['business_unit'])) {
                        if($result_data['business_unit'] == $current_business_unit['id']) {
                            array_push($search_results, $result_data);
                        }
                    }
                    // Business unit is not global but has no business_unit field
                    elseif ($result_data['entity_name'] == 'BusinessUnit') {
                         if($result_data['business'] == $current_business_unit['business']) {
                            array_push($search_results, $result_data);
                        }
                    }
                }
            }
            else {
                array_push($search_results, $result_data);
            }
        }
        return $search_results;
    }

    /**
     *
     */
    public static function build_query_from_criteria($entity_data, $criteria_data) {
        $query = call_user_func(array($entity_data['entity_name'], 'select'));
        $columns = array();
        $columns = [strtolower($entity_data['entity_name']) . '.' . 'id'];
        // Specify the columns we want to select. This is to prevent ambiguous column name error
        foreach ($entity_data['entity_fields'] as $key => $field_data) {
            array_push($columns, strtolower($entity_data['entity_name']) . '.' . $field_data['name']);
        }
        $query->select($columns);
        // For the special case of a criteria containing the primary key field.
        if(isset($criteria_data['id'])) {
            // Are we dealing with a primary key field
            // The id field requires the = operator and the id field could
            // be an array of ids
            if(is_array($criteria_data['id'])) {
                $query->whereIn(strtolower($entity_data['entity_name']) . '.' . 'id', $criteria_data['id']);
            }
            else {
                $query->where(strtolower($entity_data['entity_name']) . '.' . 'id', '=', $criteria_data['id']);
            }
        }
        // Build the search criteria
        foreach ($entity_data['entity_fields'] as $key => $field_data) {
            if(isset($criteria_data[$field_data['name']])) {
                if(!$field_data['is_relationship_field']){
                    // Field is not a relationship or a primary key field 
                    $query->where(strtolower($entity_data['entity_name']) . '.' . $field_data['name'], 'like', '%'.$criteria_data[$field_data['name']].'%');
                }
                // We are dealing with a relationship field
                if($field_data['is_relationship_field'] && $field_data['is_required']) {
                    if(is_numeric($criteria_data[$field_data['name']])) {
                        $query->where(strtolower($entity_data['entity_name']) . '.' . $field_data['name'], '=', intval($criteria_data[$field_data['name']]));
                    }
                    else {
                        $query->join(
                            strtolower($field_data['entity_name']), strtolower($field_data['entity_name']) . '.id', '=', $entity_data['entity_artifact_name'] . '.' . $field_data['name'])->where(strtolower($field_data['entity_name']) . '.name', 'LIKE', '%' . $criteria_data[$field_data['name']]. '%');
                    }
                }
            }
        }

        // For the special case where we are dealing with categorized entities.
        // Full nested properties currently not supported
        foreach ($criteria_data as $key => $value) {
            if (strpos($key, ".") !== false) {
                $entity = $entity_data['entity_artifact_name'];
                $entity_type = $entity_data['entity_artifact_name'] . 'type';
                $query->join($entity_type, $entity_type . '.id', 
                    '=', $entity. '.type')->where($entity_type . '.category', '=', intval($value));
            }
        }

        if (!$entity_data['is_global_entity'] && !current_user_can('administrator') && ($entity_data['entity_name'] != 'BusinessUnit')) {
            // Filter the results for non admin users
            $business_unit = BusinessUnitAPI::get_current_user_business_unit();
            if(isset($business_unit['id'])) {
                $query->where(strtolower($entity_data['entity_name']) . '.' . 'business_unit', '=', $business_unit['id']); 
            }
        }
        return $query;
    }

    public static function build_query_from_global_criteria($entity_data, $criteria_data) {
        $query = call_user_func(array($entity_data['entity_name'], 'select'));
        $columns = array();
        $columns = [strtolower($entity_data['entity_name']) . '.' . 'id'];
        // Specify the columns we want to select. This is to prevent ambiguous column name error
        foreach ($entity_data['entity_fields'] as $key => $field_data) {
            array_push($columns, strtolower($entity_data['entity_name']) . '.' . $field_data['name']);
        }
        $query->select($columns);
        // For the special case of a criteria containing the primary key field.
        /*if(isset($criteria_data['id'])) {
            // Are we dealing with a primary key field
            // The id field requires the = operator and the id field could
            // be an array of ids
            if(is_array($criteria_data['id'])) {
                $query->whereIn(strtolower($entity_data['entity_name']) . '.' . 'id', $criteria_data['id']);
            }
            else {
                $query->where(strtolower($entity_data['entity_name']) . '.' . 'id', '=', $criteria_data['id']);
            }
        }*/
        // Build the search criteria
        foreach ($entity_data['entity_fields'] as $key => $field_data) {
            if(!$field_data['is_relationship_field']){
                // Field is not a relationship or a primary key field 
                $query->orWhere(strtolower($entity_data['entity_name']) . '.' . $field_data['name'], 'like', '%'.$criteria_data['search'].'%');
            }
            // We are dealing with a relationship field
            if($field_data['is_relationship_field'] && $field_data['is_required']) {
                if(is_numeric($criteria_data[$field_data['name']])) {
                    $query->orWhere(strtolower($entity_data['entity_name']) . '.' . $field_data['name'], '=', intval($criteria_data['search']));
                }
                else {

                    $query->join(strtolower($field_data['entity_name']), strtolower($field_data['entity_name']) . '.id', '=', $entity_data['entity_artifact_name'] . '.' . $field_data['name'])->orWhere(strtolower($field_data['entity_name']) . '.name', 'LIKE', '%'.$criteria_data['search'].'%');
                }
            }
        }

        if (!$entity_data['is_global_entity'] && !current_user_can('administrator') && ($entity_data['entity_name'] != 'BusinessUnit')) {
            // Filter the results for non admin users
            $business_unit = BusinessUnitAPI::get_current_user_business_unit();
            if(isset($business_unit['id'])) {
                $query->where(strtolower($entity_data['entity_name']) . '.' . 'business_unit', '=', $business_unit['id']); 
            }
        }
        return $query;
    }

}
