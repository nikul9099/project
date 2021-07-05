<?php

/**

 * Template Name: Homepage

 * @package WordPress

 * @subpackage Twenty_Twenty

 * @since 1.0

 */



get_header();?>

<?php 

global $current_user;
  wp_get_current_user();
  $currentuser = $current_user->user_login;

require_once( 'lib/woocommerce-api.php' );

$options = array(
    'debug'           => true,
    'return_as_array' => false,
    'validate_url'    => false,
    'timeout'         => 30,
    'ssl_verify'      => false,
);

try {

    $client = new WC_API_Client( 'http://localhost/wpdemo', 'ck_ad96ebb890c9ba82ff7ee3c2d4044af5776c5ae5', 'cs_8053a8a68fa61416fc0ee6513eb7d70097b443fa', $options ); 
       //echo "<pre>";  print_r( $client->index->get() );
    
      //echo "<pre>"; print_r( $client->products->get() );
            echo "<pre>"; print_r( $client->orders->get());


        foreach ($client->products->get() as $key => $product) {
                foreach ($product as $key => $value1) {

                   // echo "<pre>"; print_r( $value1->title );
        
        if($currentuser == $value1->vendor_name){            
        ?>

        <h5>Title : <?php echo $value1->title; ?></h5>
        <h6>Author: <?php echo $value1->vendor_name; ?></h6>
        <p id="stock">Stock: <?php echo $value1->stock_quantity; ?></p>
        <form name="productform" id="productform" method="post" action="">
            <input type="hidden" name="product_id" id="product_id" value="<?php echo $value1->id; ?>">
            <input type="text" name="stock_quantity" id="stock_quantity" value="<?php echo $value1->stock_quantity; ?>" />
            <input type="submit" name="submit" value="submit">
        </form>
   <?php } } } 

   // if(isset($_POST['submit'])){


   //  $product_id = $_POST['product_id'];
   //  $stock_quantity = $_POST['stock_quantity'];

  
   //      $updated_order  = $client->products->update( $product_id, array( 'stock_quantity' => $stock_quantity ) ); 
   //      // print_r( $updated_order );
   //      //die;
   //  }

   ?>

<?php } catch ( WC_API_Client_Exception $e ) {

    echo $e->getMessage() . PHP_EOL;
    echo $e->getCode() . PHP_EOL;

    if ( $e instanceof WC_API_Client_HTTP_Exception ) {

        print_r( $e->get_request() );
        print_r( $e->get_response() );
    }
}


?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
    jQuery('#productform').submit(function(event){
        event.preventDefault();
        var url = jQuery(this).attr('action');
        var product_id = jQuery("#product_id").val();
        var stock_quantity = jQuery("#stock_quantity").val();

        var data = {
                'action':'products_api_form',
                'product_id':product_id,
                'stock_quantity':stock_quantity,
            };

        $.post(ajaxurl, data, function(response) {
           // console.log(response);
            if(response){
                alert("Inserted", response);
                //jQuery("#stock").html(response);
                location = 'http://localhost/project/homepage/';
                
            }
            
        });
    });
});
</script>
<?php 
global $post;
$header_bg_color = get_post_meta( $post->ID, 'header_bg_color', true ); 
if($header_bg_color){
?>
<style type="text/css">
.elementor-element-403b1ae .elementor-background-overlay{
    background-color: <?php echo $header_bg_color; ?> !important;
}
</style>
<?php } ?>
<?php get_footer(); ?>