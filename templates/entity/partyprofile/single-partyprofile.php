<?php
    /*
     */
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    $view = $_REQUEST['page_info']['view'];
    $model = $view->get_model();
    $tabs = $view->get_tabs();

    $parent_param = '';
    if(isset($_REQUEST['parent_param'])) $parent_param = urldecode($_REQUEST['parent_param']);


?>
<div class="card" id="profile-main">
    <div class="pm-overview c-overflow">
        <div class="pmo-pic">
            <div class="p-relative">
                <a data-toggle="modal" href="#modalWider">
                    <img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/images/user.png" alt=""> 
                </a>
                
                <div class="dropdown pmop-message">
                    <a data-toggle="dropdown" href="profile-about.html" class="btn bgm-white btn-float z-depth-1">
                        <i class="zmdi zmdi-comment-text-alt"></i>
                    </a>
                    
                    <div class="dropdown-menu">
                        <textarea placeholder="Write something..."></textarea>
                        
                        <button class="btn bgm-green btn-float"><i class="zmdi zmdi-mail-send"></i></button>
                    </div>
                </div>
                
                <a  data-toggle="modal" href="#modalWider" class="pmop-edit">
                    <i class="zmdi zmdi-plus"></i> <span class="hidden-xs">Update Profile</span>
                </a>
            </div>
            
           
            <div class="pmo-stat">
                <h2 class="m-0 c-white">1562</h2>
                Total Connections
            </div>
            <div class="pmo-block pmo-contact hidden-xs">
                 <div class="btn-demo m-t-10">
                    <a href="<?php echo $view->get_edit_url(); ?>" class="btn btn-primary btn-block waves-effect">
                       <?php echo $model['user_name']; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="pm-body clearfix">
        <ul class="tab-nav tn-justified"  role="tablist">
            <li class="active waves-effect">
                <a href="#tab-0" aria-controls="tab-0" role="tab" data-toggle="tab">
                    <?php echo $model['entity_description']; ?>
                </a>
            </li>
            <?php  $count = 1; foreach ($tabs as $tab) {  ?>
            <li>
                <a href="#tab-<?php echo $count; ?>" aria-controls="tab-<?php echo $count; ?>" role="tab" data-toggle="tab"> <?php echo $tab['description']; ?></a>
            </li>
            <?php  $count++; } ?>
        </ul>
        
        <div class="tab-content">
        
            <div role="tabpanel" class="tab-pane animated fadeIn in active" id="tab-0">
                <div class="pmb-block">
                    <div class="pmbb-header">
                        <h2><i class="zmdi zmdi-account m-r-5"></i> Basic Information</h2>
                        
                        <ul class="actions">
                            <li class="dropdown">
                                <a href="profile-about.html" data-toggle="dropdown">
                                    <i class="zmdi zmdi-more-vert"></i>
                                </a>
                                
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a data-pmb-action="edit" href="<?php echo $view->get_edit_url(); ?>">Edit</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="pmbb-body">
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                <tbody>
                                    <tr>
                                    <?php foreach ($model['entity_fields'] as $field_name => $field) { if($field['is_view_field']) {  ?>
                                        <th><?php echo $field['description']; ?></th>
                                    <?php } } ?>
                                    </tr>

                                    <tr>
                    <?php foreach ($model['entity_fields'] as $field) { if($field['is_view_field'] && !$field['is_relationship_field']) {  ?>
                                        <td><?php echo $model[$field['name']]; ?></td>
                        <?php } if ($field['is_view_field'] && $field['is_relationship_field']) { ?>
                                        <td><?php echo $model[$field['name'] . '_txt']; ?></td>
                    <?php } } ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="btn-demo m-t-20">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="<?php echo $view->get_edit_url(); ?>" class="btn btn-primary btn-block waves-effect">
                                       <?php _e('Edit', 'framework') ?>
                                    </a>
                                    
                                </div>
                                <div class="col-md-4">
                                    <form id="delete-entity-form" style="display:none" action=""  method="POST">
                                        <input type="hidden" name="id" value="<?php echo $model['id']; ?>">
                                        <input type="hidden" name="artifact" value="<?php echo $view->get_artifact_name(); ?>">
                                        <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                                        <input type="hidden" name="submitted" id="submitted" value="true" />
                                    </form>
                                    <a id="delete-entity-btn" href="<?php echo $view->get_delete_url(); ?>" class="btn btn-warning btn-block waves-effect">
                                       <?php _e('Delete', 'framework') ?>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <?php if(!is_null($view->get_parent_artifact_name())) { ?>
                                <a href="<?php echo EntityActionProcessor::get_base_url() . 'artifact=' . $view->get_parent_artifact_name() . '&id=' . $view->get_parent_id() . $view->get_parent_param(); ?>&page_action=view" 
                                   class="btn btn-primary btn-block waves-effect">
                                   <?php _e('Done', 'framework') ?>
                                </a>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>


                <!-- <div class="pmb-block">
                    <div class="pmbb-header">
                        <h2><i class="zmdi zmdi-equalizer m-r-5"></i> Summary</h2>
                    </div>
                    <div class="pmbb-body p-l-30">
                        <div class="pmbb-view">
                            <?php //echo $model['description']; ?>
                        </div>
                    </div>
                </div> -->
            </div>
            <!-- End first tab -->
            <div role="tabpanel" class="tab-pane animated fadeIn in" id="tab-1">
                <div class="pmb-block">
                    
                </div>
            </div>
            <!-- End second tab -->
        </div>

        
    </div>
</div>
<div class="modal fade" id="modalWider" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sodales orci ante, sed ornare eros vestibulum ut. Ut accumsan vitae eros sit amet tristique. Nullam scelerisque nunc enim, non dignissim nibh faucibus ullamcorper. Fusce pulvinar libero vel ligula iaculis ullamcorper. Integer dapibus, mi ac tempor varius, purus nibh mattis erat, vitae porta nunc nisi non tellus. Vivamus mollis ante non massa egestas fringilla. Vestibulum egestas consectetur nunc at ultricies. Morbi quis consectetur nunc.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link">Save changes</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$('#delete-entity-btn').click(function(e){
    e.preventDefault();
    swal({   
        title: "Are you sure?",   
        text: "You will not be able to undo this action!",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Yes, delete it!",   
        closeOnConfirm: false 
    }, function(){   
        var form = $('#delete-entity-form').ajaxSubmit(
        {/* options */
            url: shadowcore_ajax_script.ajaxurl,
            data: ({action: 'delete_entity_ajax'}),
            success: function (response)
            {
                var success_msg = '';
                if (response.success) {
                    swal({   
                        title: "Deleted!",   
                        text: "The record has been deleted",   
                        type: "success",   
                        showCancelButton: false,   
                        confirmButtonText: "OK",   
                        closeOnConfirm: true 
                    }, function(){   
                        $('#success').html(response.data.message);
                    });
                }
                else {
                    swal("Error!", response.data.message, "warning"); 
                }
            }
        });
        
    });
});
</script>