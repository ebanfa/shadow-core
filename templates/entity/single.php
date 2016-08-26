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
<div class="c-overflow">
    <ul class="tab-nav" role="tablist" style="overflow: visible;">
        <li class="active">
            <a href="#tab-0" aria-controls="tab-0" role="tab" data-toggle="tab">
                <?php echo $model['entity_description']; ?>
            </a>
        </li>

        <?php  $count = 1; foreach ($tabs as $tab) {  ?>
        <li>
            <a
                href="#tab-<?php echo $count; ?>" 
                aria-controls="tab-<?php echo $count; ?>" role="tab" data-toggle="tab">
                <?php echo $tab['description']; ?>
            </a>
        </li>
        <?php  $count++; } ?>
    </ul>
</div>

<div class="tab-content p-20">
    <div role="tabpanel" class="tab-pane animated fadeIn in active" id="tab-0">
        <div id="success"></div>
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
        <?php 
            
        ?>
        <div class="btn-demo m-t-10">
            <a href="<?php echo $view->get_edit_url(); ?>" class="btn btn-primary waves-effect">
               <?php _e('Edit', 'framework') ?>
            </a>
            <form id="delete-entity-form" style="display:none" action=""  method="POST">
                <input type="hidden" name="id" value="<?php echo $model['id']; ?>">
                <input type="hidden" name="artifact" value="<?php echo $view->get_artifact_name(); ?>">
                <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                <input type="hidden" name="submitted" id="submitted" value="true" />
            </form>
            <a id="delete-entity-btn" href="<?php echo $view->get_delete_url(); ?>" class="btn btn-warning waves-effect">
               <?php _e('Delete', 'framework') ?>
            </a>
            <?php if(!is_null($view->get_parent_artifact_name())) { ?>
            <a href="<?php echo EntityActionProcessor::get_base_url() . 'artifact=' . $view->get_parent_artifact_name() . '&id=' . $view->get_parent_id() . $view->get_parent_param(); ?>&page_action=view" 
               class="btn btn-primary waves-effect">
               <?php _e('Done', 'framework') ?>
            </a>
            <?php } ?>
        </div>
    </div>

    <?php $ifield_count = 1; foreach ($tabs as $tab) { ?>
        <div role="tabpanel" class="tab-pane animated fadeIn" id="tab-<?php echo $ifield_count; ?>">
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