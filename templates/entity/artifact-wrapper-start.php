<?php
    /*
     *
     */
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }
    $view = $_REQUEST['page_info']['view'];
?>

<div class="row">
	<div class="col-sm-12">
		<div class="card">

            <div class="lv-header-alt clearfix">
                <h2 class="lvh-label hidden-xs"><?php echo $view->get_page_action_description(); ?> </h2>
                <div class="lvh-search">
                    <input id="artifact-search-input" type="text" placeholder="Start typing..." class="lvhs-input">
                    <i class="lvh-search-close">&times;</i>
                </div>
                <?php 
                    $additional_seach_options = '';
                    foreach ($view->get_form_fields() as $field) { 
                        if(isset($field['view_criteria'])) { 
                            foreach ($field['view_criteria'] as $criteria_name => $criteria_value) {
                                $additional_seach_options = $additional_seach_options . '&' . $criteria_name . '=' . $criteria_value;
                            }
                        }
                    } 
                ?>
                <ul class="lv-actions actions artifact-actions">
                    <li>
                        <?php if($view->get_page_action() == 'list') { ?>
                        <a href="#" class="lvh-search-trigger">
                            <i class="zmdi zmdi-search"></i>
                        </a>
                        <?php } else  { ?>
                        <a href="<?php echo EntityActionProcessor::get_base_url() . 'artifact=' . $view->get_artifact_name() . '&page_action=list' . $additional_seach_options;?>">
                            <i class="zmdi zmdi-search"></i>
                        </a>
                        <?php } ?>
                    </li>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" aria-expanded="true">
                            <i class="zmdi zmdi-plus zmdi-hc-fw"></i>
                        </a>
                        <?php if (count($view->get_action_links()) > 0) { ?>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <?php foreach ($view->get_action_links() as $link) { ?>
                                <li>
                                    <a href="<?php echo $link['link']; ?>"><?php echo $link['name']; ?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                </ul>
            </div>

			<div class="card-body card-padding">
                <div class="row mg-btm-30">
                    <div class="col-sm-12">
                        <div class="body-section">
                            <div id="success"></div>
<script type="text/javascript">
    
        jQuery(document).ready(function($)
        {
            $('#action-link-trigger').on('click', function (e) {
                e.stopPropagation();
                $(this).prev().find('[data-toggle=dropdown]').dropdown('toggle');
            });
        });
    
</script>