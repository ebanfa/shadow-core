<?php

/**
 * 
 */
if (!defined('ABSPATH')) {
    exit; //Exit if accessed directly
}

class ContentFileAPI {

    /**
     * 
     */
    public static function do_files_upload($file_upload_param) {
        $count = 0;
        $files_uploaded = array();
        $current_user = wp_get_current_user();
        if (!empty($_FILES)) {

            foreach ($_FILES[$file_upload_param]['name'] as $filename) {
                if ($_FILES[$file_upload_param]['tmp_name'][$count] != '') {
                    // Use the WordPress API to upload the file
                    $upload = wp_upload_bits($_FILES[$file_upload_param]['name'][$count], null, file_get_contents($_FILES[$file_upload_param]['tmp_name'][$count]));
                    if (isset($upload['error']) && $upload['error'] != 0) {
                        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                    } else {

                        $file_size = $_FILES[$file_upload_param]["size"][$count];
                        $file_size = $file_size / 1024;
                        $file_size = number_format((float) $file_size, 2, '.', '');
                        $date_obj = new DateTime();
                        $file_obj = array(
                            'file_name' => $_FILES[$file_upload_param]['name'][$count],
                            'file_code' => get_token(12),
                            'file_url' => $upload['url'],
                            'file_size' => $file_size,
                            'file_owner' => $current_user->user_login,
                            'file_created_date' => $date_obj->format('M j, Y, H:i'),
                            'file_type' => 'FILE',
                            'file_mime_type' => '',
                            'file_description' => '',);
                        // Post information
                        $post_information = array('post_title' => $file_obj['file_name'],
                            'post_content' => $file_obj['file_description'], 'post_type' => 'content_file', 'post_status' => 'publish');
                        // Insert the order into the database
                        $post_id = wp_insert_post($post_information);

                        update_post_meta($post_id, 'cf_name', $file_obj['file_name']);
                        update_post_meta($post_id, 'cf_file_code', $file_obj['file_code']);
                        update_post_meta($post_id, 'cf_file_url', $file_obj['file_url']);
                        update_post_meta($post_id, 'cf_file_size', $file_obj['file_size']);
                        update_post_meta($post_id, 'cf_user_name', $file_obj['file_owner']);
                        update_post_meta($post_id, 'cf_created_date', $file_obj['file_created_date']);
                        update_post_meta($post_id, 'cf_file_type', $file_obj['file_type']);
                        update_post_meta($post_id, 'cf_mime_type', $file_obj['file_mime_type']);
                        update_post_meta($post_id, 'cf_file_description', $file_obj['file_description']);

                        array_push($files_uploaded, $file_obj);
                    } // end 
                }
                $count++;
            }
        }
        return $files_uploaded;
    }

    /**
     * 
     */
    public static function get_content_files($file_codes) {
        $content_files = array();
        foreach ($file_codes as $file_code) {
            $fileQueryArgs = array('numberposts' => -1, 'post_status' => 'any', 'post_type' => 'content_file',
                'meta_query' => array(array('key' => 'cf_file_code', 'value' => $file_code)));
            $fileQuery = new WP_Query($fileQueryArgs);
            while ($fileQuery->have_posts()) : $fileQuery->the_post();
                $content_file = $fileQuery->post;
                $file_obj = array(
                    'file_name' => get_post_meta($content_file->ID, 'cf_name', true),
                    'file_code' => get_post_meta($content_file->ID, 'cf_file_code', true),
                    'file_url' => get_post_meta($content_file->ID, 'cf_file_url', true),
                    'file_size' => get_post_meta($content_file->ID, 'cf_file_size', true),
                    'file_owner' => get_post_meta($content_file->ID, 'cf_user_name', true),
                    'file_created_date' => get_post_meta($content_file->ID, 'cf_created_date', true),
                    'file_type' => get_post_meta($content_file->ID, 'cf_file_type', true),
                    'file_mime_type' => get_post_meta($content_file->ID, 'cf_mime_type', true),
                    'file_description' => get_post_meta($content_file->ID, 'cf_file_description', true),);
                $order_doctype = get_post_meta($content_file->ID, 'doctype_name', true);
                array_push($content_files, $file_obj);
            endwhile;
            wp_reset_postdata();
        }
        return $content_files;
    }

}
