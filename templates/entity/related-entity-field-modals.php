<?php
    /*
     */
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    $view = $_REQUEST['page_info']['view'];
    $model = $view->get_model();
?>

<?php   foreach ($view->get_related_form_fields() as $field) { 
            $related_model = $view->get_related_form_field_model(strtolower($field['entity_name'])); ?>
<!-- Modal Default -->  
<div class="modal fade" id="<?php echo $field['name']; ?>_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card">
                    <div class="card-header bgm-lightgreen">
                        <h2>
                            Select <?php echo $field['entity_name']; ?>
                        </h2>
                        <ul class="actions actions-alt">
                            <li class="dropdown">
                                <a href="widget-templates.html" data-toggle="dropdown" aria-expanded="false">
                                    <i class="md md-more-vert"></i>
                                </a>
                                
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="<?php echo EntityActionProcessor::get_base_url() ;?>artifact=<?php echo strtolower($field['entity_name']); ?>&page_action=create">Add a new record</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body card-padding">

                        <form id="<?php echo $field['data_type']; ?>-list-form">
                            <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                            <?php if(isset($field['options_criteria'])) { 
                                foreach ($field['options_criteria'] as $criteria_name => $criteria_value) { ?>
                            <input type="hidden" name="<?php echo $criteria_name; ?>" id="<?php echo $criteria_name; ?>" value="<?php echo $criteria_value; ?>" /> 
                            <?php }} ?>
                            <input type="hidden" name="submitted" id="submitted" value="true" /> 
                        </form>
                        <div class="table-responsive">
                            <table id="<?php echo $field['name']; ?>-table" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <?php foreach ($related_model['entity_fields'] as $model_field) { if($model_field['is_list_field']) { ?>
                                        <th><?php echo $model_field['description']; ?></th>
                                        <?php }} ?>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php   } ?>