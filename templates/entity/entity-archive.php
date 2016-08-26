<?php
    /*
     */
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }
    $view = new ListEntityView();
?>

<?php do_action('shadowbanker_before_main_content'); ?>

<?php do_action('shadowbanker_before_artifact_content');?>

<?php do_action('shadowbanker_the_artifact_content', $view);?>

<?php do_action('shadowbanker_after_artifact_content');?>

<?php do_action('shadowbanker_after_main_content');?>


