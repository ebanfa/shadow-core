<?php
/*
 * 
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ContentPortDateUtils {

    public static function resolve_due_date($start_date, $urgency_code) {
        // Process dates
        $urgencyQueryArgs = array('numberposts' => -1, 'post_status' => 'any', 'post_type' => 'content_urgency',
            'meta_query' => array(array('key' => 'urgency_code', 'value' => $urgency_code)));
        $urgencyQuery = new WP_Query($urgencyQueryArgs);
        // Do the loop
        while ($urgencyQuery->have_posts()) : $urgencyQuery->the_post();
            $urgency = $urgencyQuery->post;
            $date_val = get_post_meta($urgency->ID, 'urgency_date_value', true);
            $date_unit = get_post_meta($urgency->ID, 'urgency_date_unit', true);
        endwhile;
        wp_reset_postdata();

        return ContentPortDateUtils::get_due_date($start_date, $date_val, $date_unit);
    }

    public static function get_due_date($order_date, $date_val, $date_unit) {
        // Only process when we have a valid
        if ($order_date != '') {
            // Only process if we have valid values
            if ($date_val != '' && $date_unit != '') {
                // The date interval
                $date_interval = 'P';
                if ($date_unit == 'H') {
                    $date_interval = 'PT';
                }
                $date_interval = $date_interval . $date_val . $date_unit;
                // Order date
                $order_date_time = DateTime::createFromFormat(ContentPort::$date_format, $order_date);
                // Due date
                $due_date_time = $order_date_time->add(new DateInterval($date_interval));
                return $due_date_time->format(ContentPort::$date_format);
            }
        }
        return false;
    }

}

?>
