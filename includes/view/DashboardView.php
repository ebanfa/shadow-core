<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class DashboardView extends ArtifactView {

    

    /**
     *
     */
    function __construct() {
        $this->set_up();
    }

   
    /**
     * Render this view
     */
    public function render() {
            
        do_action('shadowbanker_before_main_content');

        CloderiaUIDisplayAPI::display_page($_REQUEST['page_info']);
        
        do_action('shadowbanker_after_main_content');
    }

}

?>