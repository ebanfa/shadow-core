<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

        </div>
    </div>
</aside>
<script type="text/javascript">
            
        /*
         * Dialogs
         */
        //Warning Message
        $('.add-client-btn').click(function(e){
            e.preventDefault();
            swal({   
                title: "Please set the type of client you wish to create",   
                text: "You will not be able to undo this action!",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Continue",   
                closeOnConfirm: false 
            }, function(){   
                
                
            });
        });
            

</script>
