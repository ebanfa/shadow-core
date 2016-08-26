<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ListEntityView extends BaseEntityView {

    /**
     *
     */
    function __construct() {
        parent::__construct();
    }


    /**
     * Render this view
     */
    public function render_impl() {
        // execute default render operation
        CloderiaUIDisplayAPI::display_entity_archive($_REQUEST['page_info']);
    }
}

?>