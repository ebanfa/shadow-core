<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

    $current_user = wp_get_current_user();
    $current_user_party = PartyAPI::get_user_party($current_user->ID);
?>
<aside id="sidebar" class="sidebar c-overflow">
    <div class="sidebar-inner">
        <div class="si-inner">
            <div class="profile-menu">
                <a href="index.html">
                    <div class="profile-pic">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/profile-pics/1.jpg" alt="">
                    </div>

                    <div class="profile-info">
                        <?php echo $current_user_party['user_name'];?>

                        <i class="md md-arrow-drop-down"></i>
                    </div>
                </a>

                <ul class="main-menu">
                </ul>
            </div>