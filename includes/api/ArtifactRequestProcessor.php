<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class ArtifactRequestProcessor {

    static $page_actions = array(
        'create' => 'Create',
        'edit' => 'Edit',
        'view' => 'View',
        'list' => 'List',
        'list_modal' => 'List Modal',
    );
    /**
     */
    public static function process_artifact_request() {
        if (!isset($_REQUEST['artifact']) && !isset($_REQUEST['page_action'])) {
            echo '<h2>Invalid request!</h2>';
        }
        $page_info = array();
        $page_info['artifact'] = sanitize_text_field($_REQUEST['artifact']);
        $page_info['page_action'] = sanitize_text_field($_REQUEST['page_action']);
        $page_info['page_action_description'] = self::$page_actions[$page_info['page_action']];
        $page_info['artifact_name'] = ArtifactUtils::$artifacts[$page_info['artifact']]['name'];
        $page_info['artifact_type'] = ArtifactUtils::$artifacts[$page_info['artifact']]['artifact_type'];
        $page_info['artifact_display_name'] = ArtifactUtils::$artifacts[$page_info['artifact']]['description'];

        $_REQUEST['page_info'] = $page_info;

        $view_class = self::get_view_class($page_info);
        $view_instance = new $view_class();
        $page_info['view'] = $view_instance;
        $view_instance->render();
    }

    public static function get_view_class($page_info) {
        $page_action = $page_info['page_action'];
        $artifact_type = $page_info['artifact_type'];
        $artifact_object_nm = ArtifactUtils::$artifacts[$page_info['artifact']]['name'];

        if($artifact_type == 'entity') {
            if($page_action == 'create') $view_class_nm = 'Create' . $page_info['artifact_name']. 'View';
            if($page_action == 'edit') $view_class_nm = 'Edit' . $page_info['artifact_name']. 'View';
            if($page_action == 'view') $view_class_nm = 'Single' . $page_info['artifact_name']. 'View';
            if($page_action == 'list') $view_class_nm = 'List' . $page_info['artifact_name']. 'View';

            if (!class_exists($view_class_nm)) {
                if($page_action == 'create') $view_class_nm = 'CreateEntityView';
                if($page_action == 'edit') $view_class_nm = 'EditEntityView';
                if($page_action == 'view') $view_class_nm = 'SingleEntityView';
                if($page_action == 'list') $view_class_nm = 'ListEntityView';
            }
        }
        else {
            $view_class_nm = $artifact_object_nm . 'View';
        }
        return $view_class_nm;
    }
}
// End Class
?>