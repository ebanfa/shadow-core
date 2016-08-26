<?php
    /*
     */
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    $view = $_REQUEST['page_info']['view'];
    $model = $view->get_model();
?>

<?php   foreach ($view->get_form_fields() as $field) { 
            do_action('shadowbanker_before_entity_form_field');
            // Non relationship field 
            if(!$field['is_relationship_field']) { 
                if($field['data_type'] == 'name') { 
?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" class="form-control name" 
                                    <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']] . '" '; }?>
                                    id="<?php echo $field['name'];?>" name="<?php echo $field['name']; ?>" 
                                    placeholder="<?php echo $field['description']; ?>" 
                                    data-bv-message="The <?php echo $field['description']; ?> is not valid" 
                                    data-bv-notempty-message="The <?php echo $field['description']; ?> is required and cannot be empty" required>
                            </div>
                        </div>
                    </div>
        <?php   }  if($field['data_type'] == 'email') { ?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="email" class="form-control email" 
                                    id="<?php echo $field['name'];?>" name="<?php echo $field['name'];?>" 
                                    <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']]. '" '; }?> 
                                    placeholder="<?php echo $field['description']; ?>" 
                                    data-bv-message="The <?php echo $field['description']; ?> is not valid" 
                                    data-bv-emailaddress-message="The value is not a valid email address" 
                                    data-bv-notempty-message="The <?php echo $field['description']; ?> is required and cannot be empty" required>
                            </div>
                        </div>
                    </div>

        <?php       // Text Large field
                }  if($field['data_type'] == 'text-lg') { ?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <textarea placeholder="<?php echo $field['description']; ?>" 
                                    name="<?php echo $field['name'];?>" rows="7" cols="100" 
                                    class="form-control text-lg" id="<?php echo $field['name'];?>">
                                    <?php if(isset($model['id'])) { echo $model[$field['name']]; }?>
                                </textarea>
                            </div>
                        </div>
                    </div>

        <?php       // text field
                }  if($field['data_type'] == 'text') { ?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" class="form-control text" 
                                    id="<?php echo $field['name'];?>" name="<?php echo $field['name']; ?>" 
                                    <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']]. '" '; }?> 
                                    placeholder="<?php echo $field['description']; ?>" 
                                    data-bv-message="The <?php echo $field['description']; ?> is not valid" 
                                    data-bv-notempty-message="The <?php echo $field['description']; ?> is required and cannot be empty" required>
                            </div>
                        </div>
                    </div>

        <?php       // Alphanumeric field
                }  if($field['data_type'] == 'alphanumeric') { ?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" class="form-control alphanumeric" 
                                    id="<?php echo $field['name'];?>" name="<?php echo $field['name']; ?>" 
                                    <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']]. '" '; }?> 
                                    placeholder="<?php echo $field['description']; ?>" 
                                    data-bv-message="The <?php echo $field['description']; ?> is not valid" 
                                    data-bv-notempty-message="The <?php echo $field['description']; ?> is required and cannot be empty" required>
                            </div>
                        </div>
                    </div>

        <?php       // text field
                }  if($field['data_type'] == 'phone') { ?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" class="form-control phone" 
                                    id="<?php echo $field['name'];?>" name="<?php echo $field['name']; ?>" 
                                    <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']]. '" '; }?> 
                                    placeholder="<?php echo $field['description']; ?>" 
                                    data-bv-message="The <?php echo $field['description']; ?> is not valid" 
                                    data-bv-notempty-message="The <?php echo $field['description']; ?> is required and cannot be empty" required>
                            </div>
                        </div>
                    </div>
        <?php       // Number field
                }   if($field['data_type'] == 'number') { ?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" class="form-control number" 
                                    id="<?php echo $field['name'];?>" name="<?php echo $field['name']; ?>" 
                                    <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']]. '" '; }?> 
                                    placeholder="<?php echo $field['description']; ?>" 
                                    data-bv-message="The <?php echo $field['description']; ?> is not valid" 
                                    data-bv-notempty-message="The <?php echo $field['description']; ?> is required and cannot be empty" required>
                            </div>
                        </div>
                    </div>

        <?php       // Money field
                }  if($field['data_type'] == 'money') { ?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" class="form-control money" 
                                    id="<?php echo $field['name'];?>" name="<?php echo $field['name']; ?>" 
                                    <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']]. '" '; }?> 
                                    placeholder="<?php echo $field['description']; ?>" 
                                    data-bv-message="The <?php echo $field['description']; ?> is not valid" 
                                    data-bv-notempty-message="The <?php echo $field['description']; ?> is required and cannot be empty" required>
                            </div>
                        </div>
                    </div>

        <?php       // flag field
                }  if($field['data_type'] == 'flag') { ?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" class="form-control flag" 
                                    id="<?php echo $field['name'];?>" name="<?php echo $field['name']; ?>" 
                                    <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']]. '" '; }?> 
                                    placeholder="<?php echo $field['description']; ?>" 
                                    data-bv-message="The <?php echo $field['description']; ?> is not valid" 
                                    data-bv-notempty-message="The <?php echo $field['description']; ?> is required and cannot be empty" required>
                            </div>
                        </div>
                    </div>
        <?php       // Date field
                }   if($field['data_type'] == 'date') { ?>
                    <div class="<?php echo $field['col_size']; ?>">
                        <div class="form-group">
                            <div class="fg-line">
                                <span class="input-group-addon"><i class="md md-event"></i></span>
                                <div class="dtp-container dropdown fg-line">
                                    <input type='text' 
                                        id="<?php echo $field['name'];?>" name="<?php echo $field['name'];?>" 
                                        <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']] . '" '; }?> 
                                        class="form-control date-picker" 
                                        data-toggle="dropdown" placeholder="<?php echo $field['description']; ?>" 
                                        data-bv-message="The <?php echo $field['description']; ?> is not valid" 
                                        data-bv-notempty-message="The <?php echo $field['description']; ?> is required and cannot be empty">
                                </div>
                            </div>
                        </div>
                    </div>
        <?php   }  ?>

<?php       } else { // Relationship field ?>
                    <div class="col-xs-11">
                        <div class="form-group">
                            <div class="fg-line">
                                <input type="text" 
                                    class="form-control text related-field-search-link" 
                                    data-related-field-name="<?php echo $field['name'];?>"
                                    id="<?php echo $field['name'];?>_txt" name="<?php echo $field['name'];?>_txt" 
                                    <?php if(isset($model['id'])) { $value = $field['name'] . '_txt'; echo 'value="' . $model[$value] . '" '; }?>
                                    placeholder="<?php echo $field['description']; ?>" 
                                    data-bv-message="<?php echo $field['description']; ?> is not valid" 
                                    data-bv-notempty-message="<?php echo $field['description']; ?> is required and cannot be empty">

                                <input type="hidden" id="<?php echo $field['name'];?>" 
                                    name="<?php echo $field['name'];?>" 
                                    <?php if(isset($model['id'])) { echo 'value="' . $model[$field['name']]. '" '; }?>>
                            </div>
                        </div>
                    </div>
                    <a data-related-field-name="<?php echo $field['name'];?>" 
                        class="related-field-search-link" 
                        style="font-size:20px" href="#<?php echo $field['name'];?>_modal">
                        <i class="md md-trending-up"></i>
                    </a>
<?php       }
            do_action('shadowbanker_after_entity_form_field');
        } 
?>



