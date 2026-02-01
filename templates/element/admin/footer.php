<?php
echo $this->Html->script(
	[
		'admin/jquery.validate.min', 
		'admin/custom',
		'admin/jquery-1.11.1.min',
		'admin/jquery-migrate-1.2.1.min',
		'admin/jquery-ui-1.10.3.min',
		'admin/bootstrap.min',
		'admin/modernizr.min',
		'admin/pace.min',
		'admin/retina.min',
		'admin/jquery.cookies',
		'admin/jquery.autogrow-textarea',
		'admin/jquery.mousewheel',
		'admin/jquery.tagsinput.min',
		'admin/toggles.min',
		'admin/bootstrap-timepicker.min',
		'admin/jquery.maskedinput.min',
		'admin/select2.min',
		'admin/colorpicker',
		'admin/dropzone.min'
	]
);
?>
<script>
jQuery(document).ready(function(){		
    jQuery("#basicForm").validate({	
        rules: {
            confirm_password: {
                equalTo: "#password"
            }
        },
        messages: {				
            confirm_password: {
                equalTo: "Please enter the same password as above"
            }
        },			
        highlight: function(element) {
            jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            jQuery(element).closest('.form-group').removeClass('has-error');
            jQuery(element).closest('.error').remove();
        }
    });		
    /*if ($('#status').length) {	  
        jQuery("#status").select2({
            minimumResultsForSearch: -1
        });
    }*/
});	
</script>
