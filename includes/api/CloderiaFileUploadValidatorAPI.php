<?php

/**
 * 
 */
if (!defined('ABSPATH')) {
    exit; //Exit if accessed directly
}

class ContentFileUploadValidatorAPI {

    /**
     *
     */
    public static function validate_file_upload($file_upload_param) {
        $count = 0;
        $max_file_size = 20000000;
        $validation_errors = array();

        if (!empty($_FILES)) {
            foreach ($_FILES[$file_upload_param]['name'] as $filename) {
                if ($_FILES[$file_upload_param]['tmp_name'][$count] != '') {
                    // Setup the array of supported file types. In this case, it's just PDF.
                    $supported_types = array('application/pdf',
                        'application/vnd.ms-excel', 'application/msword',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                    // Get the file type of the upload
                    $arr_file_type = wp_check_filetype(basename($_FILES[$file_upload_param]['name'][$count]));
                    $uploaded_type = $arr_file_type['type'];
                    // Check if the type is supported. If not, throw an error.
                    if (!in_array($uploaded_type, $supported_types)) {
                        $supported = 'Supported files types are: Ms Word, Ms Excel, PDF';
                        $validation_errors['file_uoload_error_msg'][$count] = 'The type of file you have uploaded is not supported. ' . $filename . '. ' . $supported;
                    }
                    // Check file size
                    if ($_FILES[$file_upload_param]['size'][$count] > $max_file_size) {
                        $validation_errors['file_uoload_error_msg'][$count] = 'The file size is beyond the allowed maximum of 20MB';
                    }
                }
                $count++;
            }
        }
        return $validation_errors;
    }

}
