<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ListConversationView extends ListEntityView {

    /**
     *
     */
    function __construct() {
        parent::__construct();
    }


    /**
     * Render this view
     */
    public function render() {
        $custom_render_action = 'shadowbanker_render_' . $this->page_action .'_' . $this->artifact . '_view';

        if(isset($_REQUEST['page_info'])) {
            
            do_action('shadowbanker_before_main_content');

           // do_action('shadowbanker_before_artifact_content');

            if(has_action($custom_render_action)) {
                // action exists so execute it
                do_action($custom_render_action);
            } else {
                // action has not been registered
                // execute default render operation
                //do_action('shadowbanker_render_'. $this->page_action . '_entity_view');
                $this->render_impl();
             }

            //do_action('shadowbanker_after_artifact_content');

            do_action('shadowbanker_after_main_content');

           
        }
    }
}

?>