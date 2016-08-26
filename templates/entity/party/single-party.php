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
                    <img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/images/<?php echo $model['image']; ?>" alt=""> 
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
                       <?php echo $model['name']; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="pm-body clearfix">
        <div class="c-overflow">
            <ul class="tab-nav"  role="tablist" style="padding-left: 40px; overflow: visible;">
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
        </div>
        
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
            <!-- End tab -->
            <?php $ifield_count = 1; foreach ($tabs as $tab) { ?>
            <div role="tabpanel" class="tab-pane animated fadeIn in" id="tab-1">
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

                        <!-- <div id="success"></div> -->
                        <form id="<?php echo $tab['model']['entity_post_name'];?>-list-form">
                            <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                            <input type="hidden" name="submitted" id="submitted" value="true" /> 
                            <input type="hidden" name="<?php echo $tab['name'];?>" value="<?php echo $model['id']; ?>"/>
                        </form>
                        <div class="table-responsive">
                            <table id="<?php echo $tab['model']['entity_post_name'];?>_related-table" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                    <?php foreach ($tab['model']['entity_fields'] as $child_field) { if($child_field['is_list_field']) {?>
                                        <th><?php echo $child_field['description']; ?></th>
                                    <?php } } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <?php 
                            $child_field_name = $tab['name'];
                            $child_artifact_name = $tab['artifact_name'];
                            $child_entity_description = strtolower($tab['model']['entity_description']);
                            $child_parent_url = '&parent_id=' . $model['id'] . '&parent_artifact=' . $view->get_artifact_name() . '&parent_field=' . $child_field_name;
                        ?>
                        <div class="btn-demo m-t-10">
                            <a id="create-<?php echo $child_artifact_name; ?>-btn" 
                                href="<?php echo EntityActionProcessor::get_base_url() ;?>artifact=<?php echo $child_artifact_name; ?>&page_action=create&parent_id=<?php echo $model['id']; ?>&parent_artifact=<?php echo $view->get_artifact_name(); ?>&parent_field=<?php echo $child_field_name; ?>" 
                                class="btn btn-success waves-effect">
                               <?php _e('Add ' . $child_entity_description, 'framework') ?>
                            </a>
                            <input type="hidden" 
                                id="<?php echo $child_artifact_name; ?>_parent_params" 
                                name="<?php echo $child_artifact_name; ?>_parent_params" 
                                value="<?php echo $child_parent_url; ?>" /> 
                        </div>

                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function() { 
                    // Setup - add a text input to each footer cell 
                    $('#<?php echo $tab['model']['entity_post_name']; ?>_related-table tfoot th').each(function () { 
                        var title = $('#<?php echo $tab['model']['entity_post_name']; ?>_related-table thead th').eq($(this).index()).text(); 
                        $(this).html('<div class="form-group"><div class="fg-line"><input type="text" class="form-control" placeholder="Search '+title+'" /></div></div>'); 
                    }); 

                    // DataTable 
                    var table = $('#<?php echo $tab['model']['entity_post_name']; ?>_related-table').DataTable({                
                        "processing": true, // for show processing bar
                        "serverSide": true, // for process on server side
                        "orderMulti": false, // for disable multi column order
                        //"dom": '<"top"i>rt<"bottom"lp><"clear">', // for hide default global search box // little confusion? don't worry I explained in the tutorial website
                        'ajax': {
                            'url':'<?php echo admin_url('admin-ajax.php'); ?>',
                            'type':'POST',
                            'datatype':'json',
                            'data': function(d){
                                d.action = 'find_entity_ajax';
                                d.artifact = '<?php echo $child_artifact_name; ?>';
                            },
                        },
                        'columns': [
                            {'data': 'id' },
                        <?php 
                            $field_model = EntityAPIUtils::init_entity_data(strtolower($field['entity_name']));
                            foreach ($tab['model']['entity_fields'] as $field_name => $entity_field) { 
                                if($entity_field['is_list_field'] && !$entity_field['is_relationship_field']) { 
                                    echo '{"data": "'.$entity_field['name'].'"},'; 
                                }
                                if($entity_field['is_list_field'] && $entity_field['is_relationship_field']) { 
                                    echo '{"data": "'.$entity_field['name'].'_txt"},'; 
                                }
                            } 
                        ?>
                        ],
                        'columnDefs': [
                            { "visible": false,  "targets": 0 },
                            {
                                // The `data` parameter refers to the data for the cell (defined by the
                                // `data` option, which defaults to the column being worked with, in
                                // this case `data: 0`.
                                "render": function ( data, type, row ) {
                                    var additional_seach_options = '';
                                    if($('#additional_seach_options').length) { additional_seach_options = $('#additional_seach_options').val(); }

                                    
                                    return '<a class="data-table-link" href="' + '<?php echo EntityActionProcessor::get_base_url(); ?>' + 'artifact=<?php echo strtolower($tab['model']['entity_name']); ?>&id=' + row.id + '&page_action=view' +  additional_seach_options +'&parent_id=<?php echo $model['id']; ?>&parent_artifact=<?php echo $view->get_artifact_name(); ?>&parent_field=<?php echo $child_field_name; ?>" data-related-artifact-name="<?php echo strtolower($tab['model']['entity_name']); ?>" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                                },
                                "targets": 1
                            }
                        ]
                    }); 
                    // Apply the search 
                    table.columns().eq( 0 ).each( 
                        function ( colIdx ) { 
                            $('input', table.column(colIdx).footer()).on('keyup change', function () { 
                                table.column(colIdx).search(this.value).draw(); 
                            }); 
                        } 
                    ); 
                });
            </script> 
            <?php $ifield_count++; } ?>
            <!-- End  tab -->
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