jQuery(document).ready(function() {
   /* jQuery("#chkPassport").change(function () {
    if (jQuery('#chkPassport:checked').length > 0) {
            jQuery("#dvPassport").show(400);
        } else {
            jQuery("#dvPassport").hide(400);
        }
    });*/
    
}); 

function valueChanged(){
        if(jQuery('#chkPassport').is(":checked")){
            jQuery("#dvPassport").show(400);
        } else {
            jQuery("#dvPassport").hide(400);
        }
    }   

