<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
?>

<?php do_action('shadowbanker_before_app_menu'); ?>

	<ul class="main-menu">
			<li class="sub-menu">
            <a href=""><i class="md md-person"></i> Profile</a>
            <ul>
				<li><a href="/wordpress/page?page_action=list&artifact=contentorder"><i class="md md-person"></i> Orders</a></li>
				<li><a href="<?php echo wp_logout_url(home_url()); ?>"><i class="md md-input"></i> Sign Out</a></li>
            </ul>
        </li>
	</ul>

<?php do_action('shadowbanker_after_app_menu'); ?>

<?php } ?>