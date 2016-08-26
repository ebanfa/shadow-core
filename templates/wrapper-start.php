<?php

	/*
	 *
	 */
	if (!defined('ABSPATH')) {
	    exit; // Exit if accessed directly
	}
	$view = $_REQUEST['page_info']['view'];
	//$page_info = $_REQUEST['page_info'];
	//$page_name = sanitize_text_field($page_info['artifact_display_name']);
//	$page_action = sanitize_text_field($page_info['page_action']);
?>
<section id="content">
	<div class="container">
		<div class="block-header">
			<h2><?php echo $view->get_page_name(); ?></h2>

			<ul class="actions ">
				<li>
					<a href="#"> <i class="zmdi zmdi-trending-up"></i></a>
				</li>
				<li>
					<a href="#"> <i class="zmdi zmdi-check-all"></i></a></li>
				<li class="dropdown">
					<a href="#" data-toggle="dropdown"><i class="zmdi zmdi-more-vert"></i></a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="#">Refresh</a></li>
						<li><a href="#">Manage Widgets</a></li>
						<li><a href="#">Widgets Settings</a></li>
					</ul>
				</li>
			</ul>
		</div>
			
