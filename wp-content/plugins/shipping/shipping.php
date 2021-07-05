<?php 
/*
Plugin Name: Day Shipping Method
Description: Day Base Shipping Method 
Author: CS
Version: 1.1
*/

function theme_options_panel(){
  add_menu_page('Shipping Method', 'Shipping Method', 'manage_options', 'theme-options', 'demo_page');
}
add_action('admin_menu', 'theme_options_panel');

add_action( 'admin_init', 'stp_api_settings_init' );
function stp_api_settings_init() {
    register_setting( 'stpPlugin', 'stp_api_settings' );
    register_setting( 'my-cool-plugin-settings-group', 'day_select' );
    register_setting( 'my-cool-plugin-settings-group', 'day_check' );
    register_setting( 'my-cool-plugin-settings-group', 'order_check' );
    register_setting( 'my-cool-plugin-settings-group', 'order_discount' );
    register_setting( 'my-cool-plugin-settings-group', 'hide_price' );
    register_setting( 'my-cool-plugin-settings-group', 'date_range_check' );
    register_setting( 'my-cool-plugin-settings-group', 'start_date' );
    register_setting( 'my-cool-plugin-settings-group', 'end_date' );
    register_setting( 'my-cool-plugin-settings-group', 'date_range_discount' );
}

function my_admin_scripts() {
    wp_enqueue_script( 'script', plugin_dir_url( __FILE__ ) . '/js/script.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'my_admin_scripts' );

function demo_page(){
?>
<div class="wrap">
<h1>Your Plugin Name</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Enable/Disable Day Shipping Method</th>
            <?php $day_checked = get_option( 'day_check'); ?>
            <td><input type='checkbox' name='day_check' value="1" <?php checked(1, $day_checked); ?> /></td>
        </tr>

        <tr valign="top">
            <th scope="row">Day Shipping Method</th>
            <td>
            <?php $days = get_option('day_select');   
            //print_r($options);
            ?>
            <select name='day_select[]' multiple='multiple' size="7">
                <option value='Mon' <?php echo ( !empty( $days ) && in_array( 'Mon', $days ) ? ' selected="selected"' : '' ) ?>>Mondays</option>
                <option value='Tue' <?php echo ( !empty( $days ) && in_array( 'Tue', $days ) ? ' selected="selected"' : '' ) ?>>Tuesdays</option>
                <option value='Wed' <?php echo ( !empty( $days ) && in_array( 'Wed', $days ) ? ' selected="selected"' : '' ) ?>>Wednesdays</option>
                <option value='Thu' <?php echo ( !empty( $days ) && in_array( 'Thu', $days ) ? ' selected="selected"' : '' ) ?>>Thursdays</option>
                <option value='Fri' <?php echo ( !empty( $days ) && in_array( 'Fri', $days ) ? ' selected="selected"' : '' ) ?>>Fridays</option>
                <option value='Sat' <?php echo ( !empty( $days ) && in_array( 'Sat', $days)  ? ' selected="selected"' : '' ) ?>>Saturdays</option>
                <option value='Sun' <?php echo ( !empty( $days ) && in_array( 'Sun', $days ) ? ' selected="selected"' : '' ) ?>>Sundays</option>
            </select>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row">Enable/Disable First Order Discount</th>
            <?php $options = get_option( 'order_check'); ?>
            <td> <input type='checkbox' id="chkPassport" class="order-check" name='order_check' value="1" <?php if($options == 1) { echo "checked='checked'";} ?> onchange="valueChanged();" /></td>
        </tr>
        <?php 
        $order_checked = get_option( 'order_check');
        if($order_checked == 1) {
            $order_discount = get_option( 'order_discount');
        ?>
        <tr valign="top" id="dvPassport">
            <th scope="row">First Order Discount</th>
            <td> <input type='text' name='order_discount' value="<?php echo $order_discount; ?>" /></td>
        </tr>
        <?php } ?>

        <tr valign="top">
            <th scope="row">Hide Prices For Not Logged In Customers</th>
            <?php $options = get_option( 'hide_price'); ?>
            <td> <input type='checkbox' class="order-check" name='hide_price' value="1" <?php if($options == 1) { echo "checked='checked'";} ?> /></td>
        </tr>

        <tr valign="top">
            <th scope="row">Enable/Disable Date Range Percentage Discount</th>
            <?php $date_range_check = get_option( 'date_range_check'); ?>
            <td> <input type='checkbox' class="date-range-check" name='date_range_check' value="1" <?php if($date_range_check == 1) { echo "checked='checked'";} ?> /></td>
        </tr>

        <tr valign="top">
            <th scope="row">Start Date</th>
            <?php 
            $start_date = get_option( 'start_date');
            $end_date = get_option( 'end_date');
            ?>
            <td style="width: 10%;"> <input type='datetime-local' class="start-range-check" name='start_date' value="<?php echo $start_date; ?>"/></td>
            <th scope="row">End Date</th>
            <td> <input type='datetime-local' class="end-range-check" name='end_date' value="<?php echo $end_date; ?>"/></td>
        </tr>
                                                                                                                                                                                                                                                                      
        <tr valign="top">
            <?php $date_range_discount = get_option( 'date_range_discount'); ?>
            <th scope="row">Date Range Percentage Discount</th>
            <td style="width: 10%;"> <input type='text' name='date_range_discount' value="<?php echo $date_range_discount; ?>" /></td>
            <th>Date Range Percentage Discount</th>
            <td> <input type='text' name='date_range_discount' value="<?php echo $date_range_discount; ?>" /></td>
        </tr>
        
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php
} 


function enable_free_shipping_for_days( $rates ) {
    $day_check = get_option('day_check');
    $day_selected = get_option('day_select');
    //$val = implode(',', $day_selected);
    //$valid_days = array($val);

    // valid days
    if ($day_check == 1 && $day_selected){
    $free = array();
    if( !in_array( date('D'), $day_selected ) ) return $rates;

    foreach ( $rates as $rate_id => $rate ) {
        if ( 'free_shipping' !== $rate->method_id ) continue;
        $free[ $rate_id ] = $rate;
    }
    if($free){
        wc_add_notice( __( "Shipping is free today!" ), 'notice');
        return $free;
      } 
    } 
    return $rates;
    
}
add_filter( 'woocommerce_package_rates', 'enable_free_shipping_for_days', 99 );

//First Order Discount

add_action( 'woocommerce_cart_calculate_fees', 'cs_first_time_order_buy_percentage_discount', 10, 1 );
function cs_first_time_order_buy_percentage_discount( $cart ) {
   
    $order_dis = get_option('order_check');
    $percent = get_option('order_discount'); 

    if ( is_admin() && ! defined('DOING_AJAX') )
        return;
    if ($order_dis == '1'){
        if ( WC()->session->get( 'first_purchase_discount' ) && is_checkout() ) {
           
            $first_order_discount = $cart->get_subtotal() * $percent / 100;
            $cart->add_fee( __( 'First Order Discount', 'woocommerce')." ($percent%)", -$first_order_discount );
        }
    }
}


add_action( 'wp_ajax_checkout_billing_email', 'cs_get_ajax_checkout_billing_email' );
add_action( 'wp_ajax_nopriv_checkout_billing_email', 'cs_get_ajax_checkout_billing_email' );
function cs_get_ajax_checkout_billing_email() {
    
    if ( isset($_POST['cb_email']) && filter_var($_POST['cb_email'], FILTER_VALIDATE_EMAIL) ) {
       
        $orders = get_posts( array(
            'numberposts' => 1, // Just one is enough
            'meta_key'    => '_billing_email',
            'meta_value'  => sanitize_email( $_POST['cb_email'] ),
            'post_type'   => 'shop_order',
            'post_status' => array('wc-processing', 'wc-completed')
        ) );

        $value = sizeof($orders) == 0 && ! empty($_POST['cb_email']) ? true : false;
        WC()->session->set('first_purchase_discount', $value );
        echo WC()->session->get('first_purchase_discount');
    }
    die();
}

add_action('wp_footer', 'cs_checkout_billing_email_js_ajax' );
function cs_checkout_billing_email_js_ajax() {
    // Only on Checkout
    if( is_checkout() && ! is_wc_endpoint_url() ) :

    WC()->session->set('first_purchase_discount', false);
    ?>
    <script type="text/javascript">
    jQuery(function($){
        if (typeof wc_checkout_params === 'undefined') 
            return false;

        $( 'input#billing_email' ).on('change blur', function() {
            var value = $(this).val();
           // console.log(value);
            $.ajax({
                type: 'POST',
                url: wc_checkout_params.ajax_url,
                data: {
                    'action': 'checkout_billing_email',
                    'cb_email': value,
                },
                success: function (result) {
                    if( result == 1 ) {
                        $(document.body).trigger('update_checkout');
                    }
                    //console.log(result); // For testing (to be removed)
                }
            });
        });
    });
    </script>
    <?php
    endif;
}

add_action( 'init', 'cs_hide_price_not_logged_in' );
 
function cs_hide_price_not_logged_in() {
$hidden_price = get_option('hide_price');
if($hidden_price == 1){
    if ( !is_user_logged_in() ) {
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
        add_action( 'woocommerce_single_product_summary', 'wwp_print_login_to_see', 31 );
        add_action( 'woocommerce_after_shop_loop_item', 'wwp_print_login_to_see', 11 );
    }
}
}
 
function wwp_print_login_to_see() {
    echo '<a href="' . get_permalink(wc_get_page_id('myaccount')) . '">' . __('Login to see prices', 'theme_name') . '</a>';
}

add_action( 'woocommerce_cart_calculate_fees', 'cs_date_percentage_discount' );
function cs_date_percentage_discount( $cart ) {
    // Your settings:
    $date_range_check = get_option( 'date_range_check');
    if ($date_range_check == '1'){
    date_default_timezone_set('Europe/Paris'); 
    wp_timezone_string();
    $start_date = get_option( 'start_date'); 
    $start = strtotime($start_date);

    $end_date = get_option('end_date');
    $end = strtotime($end_date);

    $date_range_discount = get_option('date_range_discount');
    

    $start_time       = $start; // starting on "2018-10-07"
    $end_time         = $end; // Ending on "2018-10-15" (included)
    $now_time         = strtotime("now"); // Now time
    $percentage       = $date_range_discount; // Discount percentage
    $max_orders_count = 3; // Limit to the first XXX orders

    $subtotal       = $cart->get_subtotal();
    $dicounts_count = get_option('wc-discounted-orders-count') ? get_option('wc-discounted-orders-count') : 0;

    if ( $now_time >= $start_time && $now_time <= $end_time && $dicounts_count <= $max_orders_count ) {
        $discount = $cart->get_subtotal() * $percentage / 100;
        $cart->add_fee( __( 'Week Discount', 'woocommerce' ) . ' (' . $percentage . '%)', -$discount );
    }
    }
}

// Discounted orders count update
add_action('woocommerce_checkout_create_order', 'cs_update_discounted_orders_count', 20, 2);
function cs_update_discounted_orders_count( $order, $data ) {
    if( $orders_count = get_option('wc-discounted-orders-count') ){
        update_option( 'wc-discounted-orders-count', $orders_count + 1 );
    } else {
        update_option( 'wc-discounted-orders-count', 1 );
    }
}