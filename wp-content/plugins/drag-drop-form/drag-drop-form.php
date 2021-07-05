<?php
/*
Plugin Name: Drag & Drop Contact Form
Plugin URI: http://example.com
Description: Simple non-bloated WordPress Contact Form
Version: 1.0
Author: Agbonghama Collins
Author URI: http://w3guy.com
*/

add_action('admin_menu', 'test_plugin_setup_menu');
 
function test_plugin_setup_menu(){
    add_menu_page( 'Drag & Drop Form', 'Drag & Drop Form', 'manage_options', 'drag-drop-form', 'html_form_code' );
}


add_action( 'admin_init', 'misha_custom_js_with_dependency' );
 function misha_custom_js_with_dependency() {
 
	wp_register_style('style', plugins_url('style.css',__FILE__ ));
   	wp_enqueue_style('style');

   	wp_register_script( 'drag', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js');
    wp_enqueue_script('drag');

    wp_register_script( 'custom', plugins_url('script.js',__FILE__ ));
    wp_enqueue_script('custom');

    wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js');
    wp_enqueue_script('jquery');

    wp_register_script( 'touch-punch', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js');
    wp_enqueue_script('touch-punch');

    wp_register_style('style','https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_style('style');

}

function html_form_code() { ?>
	<div class="drag-frm-section">
		<h1>Drag & Drop Form</h1>
		<div class="drag-frm-cols">
			<div class="drag-frm-sidebar">
				<h2>Add Field</h2>
				<div class="frm-field-list">
					<ul>
						<li id="textbox">Text Box</li>
						<li id="emailbox">Email Box</li> 
						
					</ul>
				</div>
			</div>
			<div class="drag-frm-panel-body">
				<h2>Form Field</h2>
				<div id="droppable-item">
            <form method="post" id="listsaveform" enctype="multipart/form-data">
             
              <ul id="droppable">
                
              </ul>
               <input type="hidden" name="list" id="list" />
              <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" style="display: none" />
            </form>
          </div>
        <div id="output"></div>
			</div>
		</div>
	</div>
<script type="text/javascript">

jQuery('#textbox, #emailbox').draggable({ 
  appendTo: 'body',
  helper: 'clone'
});


function getNewId(type) {
   var newId = '';
    if (type == "textbox" || type == "emailbox"){
        newId = jQuery('#droppable .drop-item').find(':input').length
    }
    
    return type + (newId + 1);   
}

jQuery('#droppable').droppable({
  //accept: ":not(.ui-sortable-helper)",
  drop: function (e, ui) {
    outputResult(ui.draggable);
  	var field = generateField();
    var draggableId = ui.draggable.attr("id");
    var newid = getNewId(draggableId);

    if (draggableId === "textbox") {
        jQuery(this).append('<li class="ui-state-default drop-item" id="cols_' + newid + '">Text_' + newid + '<input id="' + newid + '" type="text" name="'+ newid +'" readonly /></li>')
    }

    if (draggableId === "emailbox") {
        jQuery(this).append('<li class="ui-state-default drop-item">Email_' + newid + '<input id="' + newid + '" type="email" name="'+ newid +'" readonly /></li>')
    }

    jQuery(".button-primary").css('display','block');
    //updateOrder();

    // var $el = jQuery('<li class="ui-state-default drop-item">Text_' + field + '<input type="text" name="name_'+ field +'" id="field_'+ field +'" readonly  /></li>');
    // $el.append(jQuery('<button type="button" class="btn btn-default btn-xs remove">X</button>').click(function () { jQuery(this).parent().detach(); })); 
   
    //jQuery(this).append(draggableId);
  }
}).sortable({
  items: '.drop-item',
  placeholder : "ui-state-highlight",
  update: function (event, ui) {
      var $data = jQuery(this).sortable('toArray');
        jQuery("#list").val(JSON.stringify($data));
      
      // var itemID = ui.item.data('type');
      // var data = jQuery(this).sortable('serialize', {
      //   attribute: "type"
      // });
      // console.log("itemID: " + itemID);
      // field(data);
    },
  });

function outputResult(){
 var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
  jQuery('#listsaveform').on('submit', function(e) {
    e.preventDefault();

        var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
        var html = jQuery('#droppable-item').html();
        //var type="";
      //   var name="";
      //   var type = new Array();
      //   jQuery('#droppable li input').each(function(i){
      //     type.push(jQuery(this).attr("type"));
      //     if (type==''){
      //         page_id_array.push(jQuery(this).attr("type"));
      //         //type = jQuery(this).attr('type');
      //         //name = jQuery(this).attr('name');
      //     }else{
      //         type += "," + jQuery(this).attr('type');
      //         name += "," + jQuery(this).attr('name');
      //     }
      //   });

      // var your_data = JSON.stringify(type);
      var data = {
          'action':'drag_drop_insert_field',
          'type':html,
          //'name':name,
          
      };
      jQuery.post(ajaxurl, data, function(data, ui) {
           if(data){
            //var draggableId = html;
            //console.log(draggableId);
            jQuery('#droppable-item').append();
           }else{
              alert("Failed");
           }
           
            //jQuery('#droppable li input').html(data);
      });
  }); 
}






// function field(){
//   jQuery('#listsaveform').on('submit', function(){
//         var articleorder="";
//           jQuery("#droppable li input").each(function(i) {
//             if (articleorder=='')
//                 articleorder = jQuery(this).attr('type');
//             else
//                 articleorder += "," + jQuery(this).attr('type');
//         });
//            alert(articleorder);
//      });

// }


function generateField() {
  return Math.floor(Math.random() * (100000 - 1 + 1) + 57);
}

// jQuery('#listsaveform').submit(updateOrder);
// function updateOrder() {
//   var pageVal = jQuery("#hiddenListInput").val();
//   //debugger;
//   jQuery.ajax({
//     type: 'post', 
//     url: '<?php //echo admin_url('admin-ajax.php'); ?>', 
//     data: {pageVal:pageVal}, // stringyfy before passing
//     success: function (data) {
//         console.log(data);
//         }
//     });
// }





// jQuery(function() {
//   var $sortable = jQuery("#droppable");
//   $sortable.disableSelection();
//   jQuery("#hiddenListInput").val(JSON.stringify($sortable.sortable("toArray")));
//   jQuery("#listsaveform").submit(function(e) {
//         console.log("Form Submit, list:", jQuery("#hiddenListInput").val());
//     });
// });

</script>
<?php }

function drag_drop_insert_field(){
  global $wpdb;
  
  $post_order = $_POST["type"];
  //echo $post_order;

  $tableName = 'wp_cs_frm_field';
  $insert_row = $wpdb->insert( 
                $tableName, 
                array( 
                    'type' => $post_order, 
                )
            );

  echo $insert_row;
}

add_action('init', 'drag_drop_insert_field');
add_action('wp_ajax_drag_drop_insert_field', 'drag_drop_insert_field');
add_action('wp_ajax_nopriv_drag_drop_insert_field', 'drag_drop_insert_field');
?>