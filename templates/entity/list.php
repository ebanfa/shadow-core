<?php
    /*
     */
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    $view = $_REQUEST['page_info']['view'];
    $model = $view->get_model();


?>
    <form id="<?php echo $model['entity_post_name']; ?>-list-form">
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
        <input type="hidden" name="additional_seach_options" id="additional_seach_options" value="<?php echo $additional_seach_options; ?>" /> 
        <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
        <input type="hidden" name="submitted" id="submitted" value="true" /> 
    </form>
    <div class="table-responsive c-overflow">
        <table id="<?php echo $model['entity_post_name']; ?>-table" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <?php foreach ($model['entity_fields'] as $field_name => $field) { if($field['is_list_field']) { ?>
                    <th><?php echo $field['description']; ?></th>
                    <?php }} ?>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <?php foreach ($model['entity_fields'] as $field_name => $field) { if($field['is_list_field']) { ?>
                    <th><?php echo $field['description']; ?></th>
                    <?php }} ?>
                </tr>
            </tfoot>
        </table>
    </div>

    <script type="text/javascript">
        $(document).ready(function() { 
            // Setup - add a text input to each footer cell 
            $('#<?php echo $model['entity_post_name']; ?>-table tfoot th').each(function () { 
                var title = $('#<?php echo $model['entity_post_name']; ?>-table thead th').eq($(this).index()).text(); 
                $(this).html('<div class="form-group"><div class="fg-line"><input type="text" class="form-control" placeholder="Search '+title+'" /></div></div>'); 
            }); 

            // DataTable 
            var table = $('#<?php echo $model['entity_post_name']; ?>-table').DataTable({                
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
                        d.artifact = '<?php echo $view->get_artifact_name(); ?>';
                        <?php foreach ($view->get_form_fields() as $field) { if(isset($field['options_criteria'])) { 
                                foreach ($field['options_criteria'] as $criteria_name => $criteria_value) {?>
                        d.<?php echo $criteria_name; ?> = '<?php echo $criteria_value; ?>'; <?php }}} ?>
                    },
                },
                'columns': [
                    {'data': 'id' },
                <?php 
                    foreach ($model['entity_fields'] as $field_name => $field) { 
                        if($field['is_list_field'] && !$field['is_relationship_field']) { 
                            echo '{"data": "'.$field_name.'"},'; 
                        }
                        if($field['is_list_field'] && $field['is_relationship_field']) { 
                            echo '{"data": "'.$field_name.'_txt"},'; 
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

                            var parent_params = '';
                            if($('#<?php echo $view->get_artifact_name(); ?>_parent_params').length) {
                                parent_params = parent_params + $('#<?php echo $view->get_artifact_name(); ?>_parent_params').val(); 
                            }
                            return '<a class="data-table-link" href="' + '<?php echo EntityActionProcessor::get_base_url(); ?>' + 'artifact=<?php echo $view->get_artifact_name(); ?>&id=' + row.id + '&page_action=view' + parent_params + additional_seach_options +'" data-related-artifact-name="<?php echo $view->get_artifact_name(); ?>" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                        },
                        "targets": 1
                    }
                ]
            }); 
            
            $('#artifact-search-input').on('keyup change', function () { 
                table.search(this.value).draw(); 
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
