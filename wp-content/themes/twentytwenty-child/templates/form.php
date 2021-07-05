<?php
/*
Template Name: Wp Custom Form
*/
get_header();
?>
<form method="post" name="contact-me" id="Form" enctype="multipart/form-data">
     <div class="form-field">    
        <label>File:</label>
        <input name="attach" id="attach" type="file" />
    </div>
    <div class="form-field">    
        <label>Name: </label>
        <input name="date" id="date" type="date" placeholder="Type your name" required>
    </div>
   <input type="submit" name="submit" class="btn_submit" value="Submit" />
</form>

<script type ="text/javascript">
jQuery(document).ready(function($) {
$('#Form').submit(function(e){
     e.preventDefault();
    var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
    var date = $("#date").val();
    var file_data = jQuery('#attach').prop('files')[0];
    var security = "<?php echo wp_create_nonce("load_more_form"); ?>";
    var form_data = new FormData();
    form_data.append('attach', file_data);
    form_data.append('action', 'updatemeta');
    form_data.append('security', security);
    form_data.append('date', date);
    //console.log("attach",attach);
    $.ajax({ 
         data: form_data,
         type: 'POST',
         contentType: false,
         processData: false,
         url: ajaxurl,
         success: function(data) {
              console.log(data);
        }
    });
    //$('#Form')[0].reset();
    })
});


</script>

<?php get_footer(); ?>
