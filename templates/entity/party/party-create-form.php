<?php
    /*
     */
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    $view = $_REQUEST['page_info']['view'];
    $model = $view->get_model();

?>
    <form role="form" name="<?php echo $model['entity_post_name'];?>_form" 
        id="<?php echo $model['entity_post_name'];?>_form" action="" 
        method="POST" enctype="multipart/form-data" 
        data-bv-framework="bootstrap"
        data-bv-excluded = ":disabled"
        data-bv-message="This value is not valid"
        data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
        data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
        data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

        <?php do_entity_form_fields($model, true, true) ; ?>

        <!-- This fields are used by the relationship field selection routine -->
        <input type="hidden" id="current-related-field" name="current-related-field" value="">
        <input type="hidden" id="current-relationship-field-id" name="current-relationship-field-id" value="">
        <input type="hidden" id="current-relationship-field-name" name="current-relationship-field-name" value="">
        <?php if(isset($_REQUEST['role'])) { ?>
        <input type="hidden" id="role" name="role" value="<?php sanitize_text_field($_REQUEST['role']); ?>">
        <?php } ?>

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

    <!-- Put this out side the form to prevent illegal nested forms -->
    <?php do_action('shadowbanker_render_related_entity_field_modals'); ?>

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
                $('#current-relationship-field-id').val(currentRelatedInstanceId);
                $('#current-relationship-field-name').val(currentRelatedFieldName);
                $('#' + currentRelatedFieldName).val(currentRelatedInstanceId);
                $('#' + currentRelatedFieldName + '_txt').val(currentRelatedInstanceName);
                
                console.log('currentRelatedFieldName::' + currentRelatedFieldName );
                console.log('currentRelatedInstanceId::' + currentRelatedInstanceId );
                console.log('currentRelatedInstanceName::' + currentRelatedInstanceName );
                console.log('currentRelatedArtifactName::' + currentRelatedArtifactName );

                $('.modal').modal('hide');
            });

            $('body').on('click', '.related-field-search-link', function(e){
                e.preventDefault();
                var currentRelatedFieldName = $(this).data('related-field-name');
                $('#current-related-field').val(currentRelatedFieldName);
                $('#' + currentRelatedFieldName + '_modal').modal('show');

                
            });
        });
    </script>
