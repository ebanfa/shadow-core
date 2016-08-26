<?php
    /*
     */
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    $view = $_REQUEST['page_info']['view'];
    $model = $view->get_model();
    $tabs = $view->get_tabs();
?>
                
        <div class="form-wizard-basic fw-container">
            <ul class="tab-nav text-center">
                <li><a href="#tab0" data-toggle="tab"><?php echo $model['entity_description'];?></a></li>
                <?php  $count = 1; foreach ($tabs as $tab) {  ?>
                <li><a href="#tab<?php echo $count;?>" data-toggle="tab"><?php echo $tab['description'];?></a></li>
                <?php  $count++; } ?>
            </ul>
            
            <form role="form" name="<?php echo $model['entity_post_name'];?>_form" 
                id="<?php echo $model['entity_post_name'];?>_form" action="" 
                method="POST" enctype="multipart/form-data" 
                data-bv-framework="bootstrap"
                data-bv-excluded = ":disabled"
                data-bv-message="This value is not valid"
                data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

                <div class="tab-content">
                        <!-- Main entity tab -->
                        <div class="tab-pane fade" id="tab0"> 

                            <?php do_action('shadowbanker_render_entity_form_fields'); ?>
                        </div>

                        <!-- Multi entity select tabs-->
                        <?php  $count = 1; foreach ($tabs as $tab) {  ?>

                        <div class="tab-pane fade" id="tab<?php echo $count;?>">
                            <div class="col-sm-12 m-b-20 btn-demo">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary">
                                        Select the <?php echo $tab['model']['entity_description'];?> Type
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Select the <?php echo $tab['model']['entity_description'];?> Type</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">

                                    <?php
                                        $multi_entity_types = $tab['type_instances'];
                                        foreach ($multi_entity_types as $multi_entity_type) { ?>
                                        <li>
                                            <a data-dependent-field-name="<?php echo $tab['artifact_name'];?>" data-dependent-field-id="<?php echo $multi_entity_type['id']; ?>" 
                                                class="dependent-field-search-link" href="components.html#"><?php echo $multi_entity_type['description']; ?></a>
                                        </li>
                                    <?php  }  ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="col-sm-12 m-b-20">
                                <div id="<?php echo $tab['artifact_name'];?>_dependent_list_box" class="list-group">
                                    <div class="list-group-item active"><span id="<?php echo $tab['artifact_name'];?>_count"class="badge">0</span> Selected <?php echo $tab['model']['entity_description'];?></div>
                                    <!-- <div class="list-group-item"><span class="badge warning">X</span>Dapibus ac facilisis in</div> -->
                                </div>
                            </div>
                        </div>

                        <?php  $count++; } ?>
                        
                    <ul class="fw-footer pagination wizard">
                        <li class="previous first"><a class="a-prevent" href="components.html"><i class="zmdi zmdi-more-horiz"></i></a></li>
                        <li class="previous"><a class="a-prevent" href="components.html"><i class="zmdi zmdi-chevron-left"></i></a></li>
                        <li class="next"><a class="a-prevent" href="components.html"><i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="next last"><a class="a-prevent" href="components.html"><i class="zmdi zmdi-more-horiz"></i></a></li>
                    </ul>
                </div>

                <!-- This fields are used by the relationship field selection routine -->
                <input type="hidden" id="current-related-field" name="current-related-field" value="">
                <input type="hidden" id="current-relationship-field-id" name="current-relationship-field-id" value="">
                <input type="hidden" id="current-relationship-field-name" name="current-relationship-field-name" value="">

                <div class="btn-demo m-t-10">
                    <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                    <input type="hidden" name="artifact" id="artifact" value="<?php echo $view->get_artifact_name(); ?>" />   
                    <input type="hidden" name="submitted" id="submitted" value="true" />  
                    <input type="hidden" name="edit_mode" value="create" /> 
                    <button id="<?php echo $view->get_artifact_name(); ?>-form-btn" type="submit" class="btn btn-primary waves-effect">
                        <?php _e('Submit', 'framework') ?>
                    </button>
                </div>

            </form>
        </div>

        <!-- Modals for each tab -->  
        <?php  $count = 1; foreach ($tabs as $tab) {  ?>
        <div class="modal fade" id="<?php echo $tab['artifact_name'];?>_modal" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    Select the <?php echo $tab['model']['entity_description'];?>
                                </h2>
                                <ul class="actions actions-alt">
                                    <li class="dropdown">
                                        <a href="widget-templates.html" data-toggle="dropdown" aria-expanded="false">
                                            <i class="md md-more-vert"></i>
                                        </a>
                                        
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="/page?type=entity&page_action=create&artifact=<?php echo $tab['artifact_name'];?>">Add a new record</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body card-padding">
                                <form id="<?php echo $tab['model']['entity_post_name'];?>-list-form">
                                    <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                                    <input type="hidden" name="submitted" id="submitted" value="true" />
                                    <input type="hidden" name="artifact" id="artifact" value="<?php echo $tab['artifact_name'];?>" />
                                    <!-- <input type="hidden" name="u_property" id="u_property" value="" />  -->
                                </form>
                                <div class="table-responsive">
                                    <table id="<?php echo $tab['model']['entity_post_name'];?>-list-table" class="table table-striped table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th><input name="select_all" value="1" type="checkbox"></th>
                            <?php  foreach ($tab['model']['entity_fields'] as $field) { if($field['is_list_field']) { ?>
                                                <th><?php  echo $field['description']  ?></th>
                            <?php  } }  ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a id="add-selected-<?php echo $tab['artifact_name'];?>-list-btn" type="button" data-dismiss="modal" class="btn btn-primary">Add to agreement</a>
                    </div>
                </div>
            </div>
        </div>

        <?php  $count++; } ?>



<script type="text/javascript">
  jQuery(document).ready(function($)
    {
        $('body').on('click', '.data-table-link', function(e){
            e.preventDefault();
            var currentRelatedFieldName = $('#current-related-field').val();
            var currentRelatedInstanceId = $(this).data('related-instance-id');
            var currentRelatedInstanceName = $(this).data('related-instance-name');               
            var currentRelatedArtifactName = $(this).data('related-artifact-name');
            // Set the value of the hidden relationship field. 
            $('#' + currentRelatedArtifactName).val(currentRelatedInstanceId);
            // Set the value of the text field for the relationship field.
            $('#' + currentRelatedFieldName + '_txt').val(currentRelatedInstanceName);
            $('.modal').modal('hide');
        });

        $('body').on('click', '.related-field-search-link', function(e){
            e.preventDefault();
            var currentRelatedFieldName = $(this).data('related-field-name');
            $('#u_property').val(currentRelatedFieldName);
            $('#current-related-field').val(currentRelatedFieldName);
            $('#' + currentRelatedFieldName + '_modal').modal('show');
        });

        $('body').on('click', '.dependent-field-search-link', function(e){
            e.preventDefault();
            var dependentFieldName = $(this).data('dependent-field-name');
            $('#' + dependentFieldName + '_modal').modal('show');
        });

    });

</script>