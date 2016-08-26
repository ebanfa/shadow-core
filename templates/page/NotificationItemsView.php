
<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
    $current_user = wp_get_current_user();
    $current_user_party = PartyAPI::get_user_party($current_user->ID);

    $notification_items = array();
    if(isset($current_user_party['id'])) {
    	$notification_items = NotificationAPI::get_notification_items($current_user_party['id']);
    }


?>

                        <li class="dropdown">
                        <a data-toggle="dropdown" class="tm-notification" href="index.html">
                            <?php if(empty($notification_items)){ ?> 
 			    <?php } else { ?>
			    <i class="tmn-counts"><?php echo count($notification_items);?></i>
                            <?php } ?>
                        </a>
                        <div class="dropdown-menu pull-right">
                            <div class="listview" id="notifications">
                                <div class="lv-header">
                                    Notification
                    
                                    <ul class="actions">
                                        <li class="dropdown">
                                            <a href="index.html" data-clear="notification">
                                                <i class="md md-done-all"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="lv-body c-overflow">
				    <?php if(!empty($notification_items)) {
                                              foreach($notification_items as $item) { ?>
                                    <a class="lv-item" href="<?php echo EntityActionProcessor::get_base_url() . 'artifact=notification&id=' . $item['id']; ?>&page_action=view">
                                        <div class="media">
                                            <div class="pull-left">
                                                <img class="lv-img-sm" src="img/profile-pics/1.jpg" alt="">
                                            </div>
                                            <div class="media-body">
                                                <div class="lv-title"><?php echo $item['name']; ?></div>
                                                <small class="lv-small"><?php echo $item['description']; ?></small>
                                            </div>
                                        </div>
                                    </a>
				    <?php } } else {  ?>
                                    <a class="lv-item" href="index.html">
                                        <div class="media">
                                            <div class="media-body">
                                              	<div class="lv-title">No notifications</div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php } ?>
                                </div>
                    
                            </div>
                    
                        </li>
