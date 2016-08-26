<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class FormFieldFilter extends ViewFilter {

    /**
     *
     */
    public static function init_hooks() { 
        add_filter('shadowbanker_filter_form_field', array('FormFieldFilter', 'filter_form_field_size'), 10, 2);
    }

    /**
     *
     */
    public static function filter_form_field_size($view, $field) {
        if($field['data_type'] != 'hidden') {
            if($field['size'] == 'small') {
                $field['col_size'] = 'col-xs-4';
            }

            if($field['size'] == 'medium') {
                $field['col_size'] = 'col-xs-6';
            }
            
            if($field['size'] == 'large') {
                $field['col_size'] = 'col-xs-12';
            }
        }
        return $field;
    }

}

?>