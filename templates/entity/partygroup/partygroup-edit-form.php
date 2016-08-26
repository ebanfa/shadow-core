<?php
    /*
     */
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    $view = $_REQUEST['page_info']['view'];
    $model = $view->get_model();

    $party_role_param = '';
    if(isset($_REQUEST['role'])) { 
        $party_role_param = '&role=' . sanitize_text_field($_REQUEST['role']);
    }
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

        <?php do_entity_form_fields($view, false, true) ; ?>
        
        <div class="btn-demo m-t-10">   
            <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
            <input type="hidden" name="edit_mode" value="edit" />  
            <input type="hidden" name="id" value="<?php echo $model['id'];?>" /> 
            <input type="hidden" name="submitted" id="submitted" value="true" />  
            <input type="hidden" name="artifact" id="artifact" value="<?php echo $view->get_artifact_name(); ?>" />
            
            <?php if(isset($_REQUEST['role'])) { ?>
            <input type="hidden" id="role" name="role" value="<?php echo sanitize_text_field($_REQUEST['role']); ?>">
            <?php } ?>

            <button id="<?php echo $view->get_artifact_name(); ?>-form-btn" type="submit" class="btn btn-primary waves-effect">
                <?php _e('Update', 'framework') ?>
            </button>
            
            <a href="<?php echo EntityActionProcessor::get_base_url()  . 'artifact=party&id=' . $model['party']; ?>&page_action=view<?php echo $party_role_param; ?>" 
               class="btn bgm-indigo waves-effect"><?php _e('Back', 'framework') ?>
            </a>
    </div>
    
</form>
