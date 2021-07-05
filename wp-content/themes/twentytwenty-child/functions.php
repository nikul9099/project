<?php
header( 'Access-Control-Allow-Origin: ' . esc_url_raw( site_url() ) );
/* enqueue scripts and style from parent theme */        

function twentytwenty_styles() {
    wp_enqueue_style( 'parent', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'twentytwenty_styles');



function updatemeta(){

    check_ajax_referer('load_more_form', 'security');

    
      //   echo $_FILES["attach"]["name"];
      // $uploadedfile = $_FILES['attach'];
      // $upload_overrides = array('test_form' => false);
      // $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
      //   if ($movefile && !isset($movefile['error'])) {
      //    echo "File Upload Successfully";
      //   } else {
      //       echo $movefile['error'];
      //   }
    if ( is_user_logged_in() ) {
        $date = !empty($_POST['date']) ? $_POST['date'] : 'Date';

        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
        $dd = '';
        if (in_array($_FILES['attach']['type'], $arr_img_ext)) {
            $upload_file_path = wp_upload_bits($_FILES["attach"]["name"], null, file_get_contents($_FILES["attach"]["tmp_name"]));
            $dd = $upload_file_path['url'];
            
        }
        //echo $dd;
        
        $user_id = get_current_user_id(); 

        $the_meta_array = array (
            '_cf_attach' => $dd,
            '_cf_date' => $date,
        );  

        $user_meta_key = '_cf_all';  
        add_user_meta( $user_id, $user_meta_key, $the_meta_array );
       
       
        //echo $date;
    } else {
        echo 'Hello visitor!';
    }

   

wp_die();
}
add_action('wp_ajax_updatemeta', 'updatemeta');
add_action('wp_ajax_nopriv_updatemeta', 'updatemeta');

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
    <h3><?php _e("Exta Field Data", "blank"); ?></h3>
    <table border="2px;" width="30%;"> 
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>File</th>
        </tr>
    </thead>

    <tbody>
        <?php 
           
            $user_id = get_current_user_id(); 

            $results = get_user_meta( $user_id, '_cf_all', true);
            echo "<pre>"; print_r($results);
           // $values = unserialize( $results );
            $tt = array();
            foreach ($results as $key => $value) { 
                $tt[$key] = $value;
            ?>

        <tr>
            <td><?php echo $user_id; ?></td>
            <td><?php echo $tt['_cf_date']; ?></td>
            <td><?php echo $tt['url']; ?></td>
        </tr>
       <?php } ?>
    </tbody>
</table>
<?php }


/*function prefix_add_discount_line( $cart ) {

  $discount = $cart->subtotal * 0.1;

  $cart->add_fee( __( 'Down Payment', 'yourtext-domain' ) , -$discount );

}
add_action( 'woocommerce_cart_calculate_fees', 'prefix_add_discount_line' );*/


/*add_action('rest_api_init', function() {
  register_rest_route( 'test/v3', 'product/',array(
                'methods'  => 'GET',
                'callback' => 'callback_url'
      ));
});

function callback_url() {
	   $args = array(
	  'numberposts' => -1,
	  'post_type'   => 'product'
);
 
$latest_books = get_posts( $args );
print_r($latest_books);
}*/


add_shortcode( 'footag', 'wpdocs_footag_func' );
function wpdocs_footag_func() {

require_once( 'lib/woocommerce-api.php' );

$options = array(
	'debug'           => true,
	'return_as_array' => false,
	'validate_url'    => false,
	'timeout'         => 30,
	'ssl_verify'      => false,
);

try {

	$client = new WC_API_Client( 'http://wp.snigre.com/wplearn', 'ck_d786ba0d33e70d23dd82fb2f5ca013061e6c528e', 'cs_160df72c96fdaffd352ef5cae2ab1f8ea4a77016', $options );

	// orders
	echo '<pre>'; print_r( $client->orders->get() );
	//echo '<pre>'; print_r( $client->orders->get( $order_id ) );
	
	
	// trigger an error
	//print_r( $client->orders->get( 0 ) );

} catch ( WC_API_Client_Exception $e ) {

	echo $e->getMessage() . PHP_EOL;
	echo $e->getCode() . PHP_EOL;

	if ( $e instanceof WC_API_Client_HTTP_Exception ) {

		print_r( $e->get_request() );
		print_r( $e->get_response() );
	}
}

}

/*add_action('woocommerce_cart_calculate_fees' , 'discount_based_on_customer_orders', 10, 1);
function discount_based_on_customer_orders( $cart_object ){

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;  

    // Getting "completed" customer orders
    $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => 'shop_order', // WC orders post type
        'post_status' => 'wc-completed' // Only orders with status "completed"
    ) );

    // Orders count
    $customer_orders_count = count($customer_orders);

    // The cart total
    $cart_total = WC()->cart->get_total(); // or WC()->cart->get_total_ex_tax()

    // First customer order
    if( empty($customer_orders) || $customer_orders_count == 0 ){
        $discount_text = __('First Order Discount', 'woocommerce');
        $discount = -50;
    } 
    // 2nd orders discount
    elseif( $customer_orders_count == 1 ){
        $discount_text = __('2nd Order Discount', 'woocommerce');
        $discount = -30;            
    } 
    // 3rd orders discount
    elseif( $customer_orders_count == 2 ){
        $discount_text = __('3rd Order Discount', 'woocommerce');   
        $discount = -10;        
    }

    // Apply discount
    if( ! empty( $discount ) ){
        // Note: Last argument is related to applying the tax (false by default)
        $cart_object->add_fee( $discount_text, $discount, false);
    }
}*/


add_action('rest_api_init', function() {
  register_rest_route( 'test/v2', 'header-menu/',array(
                'methods'  => 'GET',
                'callback' => 'get_navigation_menu'
      ));
});


function get_navigation_menu() {
$menus = wp_get_nav_menus();
//echo '<pre>'; //print_r($menus);

$main_menu = array();
$nav = array();
foreach ($menus as $menu) {
        
        $main_id = $menu->term_id;
        $main_name = $menu->name;
        //print_r($nav);
        $main_menu[$main_name] = wp_get_nav_menu_items($main_id);
        //print_r($main_menu);

        
        
        $nav[$menu->term_id]['name'] = $main_name;
        foreach ($main_menu[$main_name] as $item) {
            if (empty($item->menu_item_parent)) {
                $nav[$item->ID] = array();
                //$nav[$item->ID]['name'] = $main_name;
                $nav[$item->ID]['ID']          =   $item->ID;
                $nav[$item->ID]['title']  =   $item->title;
                $nav[$item->ID]['url']         =   $item->url;
                $nav[$item->ID]['children']    =   array();
            }
        }
        
        $header_submenu = array();
   
        foreach ($main_menu[$main_name] as $child ) {
            if ($child->menu_item_parent){
                $header_submenu[$child ->ID] = array();
                $header_submenu[$child ->ID]['ID']       =   $child->ID;
                $header_submenu[$child ->ID]['parent']       =   $child->post_parent;
                $header_submenu[$child ->ID]['menu_item_parent']    =   $child->menu_item_parent;
                $header_submenu[$child ->ID]['title']    =   $child->title;
                $header_submenu[$child ->ID]['url']  =   $child->url;
                $nav[$child->menu_item_parent]['children'][$child->ID] = $header_submenu[$child->ID];
            }
        }

      
    }
     return $nav;  
}

/*add_action('rest_api_init', function() {
  register_rest_route( 'test/v2', 'footer-menu/',array(
                'methods'  => 'GET',
                'callback' => 'get_footer_menu'
      ));
});

function get_footer_menu() {
 $menu_locations = get_nav_menu_locations();
  $menu_id = $menu_locations['footer'];
  $menu_items_to_return_as_json = array();


  $footer_nav = wp_get_nav_menu_items($menu_id);
    $nav = array();
    foreach ($footer_nav as $m) {
        if (empty($m->menu_item_parent)) {
            $nav[$m->ID] = array();
            $nav[$m->ID]['ID']      =   $m->ID;
            $nav[$m->ID]['title']       =   $m->title;
            $nav[$m->ID]['url']         =   $m->url;
            $nav[$m->ID]['children']    =   array();
        }
    }

    $ft_submenu = array();
    foreach ($footer_nav as $m) {
        if ($m->menu_item_parent) {
            $ft_submenu[$m->ID] = array();
            $ft_submenu[$m->ID]['ID']       =   $m->ID;
            $ft_submenu[$m->ID]['title']    =   $m->title;
            $ft_submenu[$m->ID]['url']  =   $m->url;
            $nav[$m->menu_item_parent]['children'][$m->ID] = $ft_submenu[$m->ID];
        }
    }
    return $nav;
    //print_r($menu);
}*/


add_action('rest_api_init', function() {
  register_rest_route( 'test/v2', 'pages/',array(
                'methods'  => 'GET',
                'callback' => 'get_pages_content'
      ));
});

function get_pages_content() {
   global $post;
    if($post->ID){
        $nav = array(
            'post_type' => 'page',
            'numberposts' => -1,
            'orderby' => 'title',
            'include'   => '2',
            'child_of' => $post->ID
        );
    } else{
        $nav = array(
        'post_type' => 'page',
        'numberposts' => -1,
        'orderby' => 'title',
        'sort_order'      => 'asc',
        'child_of' => $post->ID
        );
    }
    $child_pages = get_posts($nav);
    $pages = array();

    foreach ($child_pages as $value) {
            
        if (empty($value->post_parent)) {
            $image = get_field('hero_image', $value->ID);

            $hero_lists = get_field('hero_list', $value->ID);
            $heros = [];
            if(is_array($hero_lists) || is_object($hero_lists)){
            foreach ($hero_lists as $hero_list) {
                $heros[] = $hero_list['hero_list_text'];
            }
            }
            
            $pages[$value->ID] = array();
            $pages[$value->ID]['ID'] =  $value->ID;
            $pages[$value->ID]['slug']       =   $value->post_name;
            $pages[$value->ID]['Post_title']       =   get_the_title($value->ID);
            $pages[$value->ID]['post_status']         =   $value->post_status;
            $pages[$value->ID]['post_url']         =   get_page_link($value->ID);
            $pages[$value->ID]['post_content']       =   $value->post_content;
            $pages[$value->ID]['template_name']       =   $value->page_template;
            $pages[$value->ID]['excerpt']       =   $value->excerpt;
            $pages[$value->ID]['menu_order']       =   $value->menu_order;
            $pages[$value->ID]['date']       =   get_the_date('d F Y', $value->ID);
            $pages[$value->ID]['thumbnail']       =   get_the_post_thumbnail_url($value->ID);
            $pages[$value->ID]['hero_title']       =   $value->hero_title;
            $pages[$value->ID]['hero_image']       =   $image;
            $pages[$value->ID]['hero_reperter_field']       =   $heros;
            $pages[$value->ID]['child_of']    =   array();
        }
    }

    $ft_subpages = array();
    foreach ($child_pages as $value) {
        if ($value->post_parent) {
            $image = get_field('hero_image', $value->ID);
            $hero_lists = get_field('hero_list', $value->ID );
            $heros = array();
            if(is_array($hero_lists) || is_object($hero_lists)){
            foreach ($hero_lists as $hero_list) {
                $heros[] = $hero_list['hero_list_text'];
            }
            }
            $ft_subpages[$value->ID] = array();
            $ft_subpages[$value->ID]['ID']       =   $value->ID;
            $ft_subpages[$value->ID]['slug']    =   $value->post_name;
            $ft_subpages[$value->ID]['Post_title']       =   get_the_title($value->ID);
            $ft_subpages[$value->ID]['post_status']         =   $value->post_status;
            $ft_subpages[$value->ID]['post_url']  =   get_page_link($value->ID);
            $ft_subpages[$value->ID]['post_content']       =   $value->post_content;
            $ft_subpages[$value->ID]['template_name']       =   $value->page_template;
            $ft_subpages[$value->ID]['excerpt']       =   $value->excerpt;
            $ft_subpages[$value->ID]['menu_order']       =   $value->menu_order;
            $ft_subpages[$value->ID]['date']       =   get_the_date('d F Y', $value->ID);
            $ft_subpages[$value->ID]['thumbnail']       =   get_the_post_thumbnail_url($value->ID);
            $ft_subpages[$value->ID]['hero_title']       =   $value->hero_title;
            $ft_subpages[$value->ID]['hero_image']       =   $image;
            $ft_subpages[$value->ID]['hero_reperter_field']       =   $heros;
            $pages[$value->post_parent]['child_of'][$value->ID] = $ft_subpages[$value->ID];
        }
    }

    return $pages;

}



function tel_florida_no() { 

    $remote_add = $_SERVER['REMOTE_ADDR'];
    $url = 'http://api.ipinfodb.com/v3/ip-city/?key=3cab1d5114814b2eea00ca488a5de685da3ac7fa27e305573fee9de4290acfdf&ip='.$remote_add;
    //print_r(expression)
    $remote_wp = wp_remote_get($url);
    echo '<pre>'; print_r($url['body']);
    

    ?>

       <!-- <script type="text/javascript">
// jQuery cross domain ajax
        jQuery.get("https://api.ipinfodb.com/v3/ip-city/?key=3cab1d5114814b2eea00ca488a5de685da3ac7fa27e305573fee9de4290acfdf&ip=49.36.77.13").done(function (data) {
            console.log(data);
        });

        // using XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "https://api.ipinfodb.com/v3/ip-city/?key=3cab1d5114814b2eea00ca488a5de685da3ac7fa27e305573fee9de4290acfdf&ip=49.36.77.13", true);
        xhr.onload = function () {
            console.log(xhr.responseText);
        };
        xhr.send();

        // using the Fetch API
        fetch("https://api.ipinfodb.com/v3/ip-city/?key=3cab1d5114814b2eea00ca488a5de685da3ac7fa27e305573fee9de4290acfdf&ip=49.36.77.13").then(function (response) {
            return response.json();
        }).then(function (json) {
            console.log(json);
        });
</script> -->

     <?php 
    // $ip = $_SERVER['REMOTE_ADDR'];
 //    $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
 //    $sk = $details->region;
 //    $tel = '';
    // if($sk == 'Florida'){
    //  $tel = '(561) 709 2277';
    // } else {
    //  $tel = '(718) 616-CARS';
    // }
    // return $tel;

    //echo '<pre>'; print_r($_SERVER);
    
    // $rr = array('REMOTE_ADDR' => $remote_add);                                                                    
    // $data = json_encode($rr);
    // print_r($data);
    
   // echo $remote_add;
    // $url = 'http://api.ipinfodb.com/v3/ip-city/?key=3cab1d5114814b2eea00ca488a5de685da3ac7fa27e305573fee9de4290acfdf&ip='.$remote_add;
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    // curl_setopt($ch, CURLOPT_POST, 1);
    //curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // $response  = curl_exec($ch);
    // curl_close($ch);
    // $rr = array('REMOTE_ADDR' => $response); 
    // $data = json_encode($rr);
    // print_r($data);
    
   
}

add_shortcode('tel_florida', 'tel_florida_no');


/**
 * Register all custom fields after the plugin is safely loaded.
 */
add_action('plugins_loaded', 'wpas_user_custom_fields', 10, 2);

if ( function_exists( 'wpas_add_custom_field' ) ) {
    wpas_add_custom_field( 'my_custom_field',  
        array( 
            'title' => __( 'Your Site URL', 'awesome-support' ),
            'field_type' => 'text',
            'placeholder' => __( 'Your Site URL', 'awesome-support' ),
            'required' => true,
            'order' => 2
            )
        );
}



if ( function_exists( 'wpas_add_custom_field' ) ) {
    wpas_add_custom_field( 'my_custom_field',  array( 'title' => __( 'Your Site URL', 'awesome-support' ),
            'field_type' => 'text',
            'placeholder' => __( 'Please enter your .myshopify.com URL', 'awesome-support' ),
            'required' => true,
            'order' => 1) );
}

add_action('plugins_loaded', 'wpas_user_custom_fields_test', 10, 2);

if ( function_exists( 'wpas_user_custom_fields_test' ) ) {
    wpas_add_custom_field( 'my_custom_field',  array( 'title' => __( 'Your Site URL', 'awesome-support'),
            'field_type' => 'text',
            'placeholder' => __( 'Please enter your .myshopify.com URL', 'awesome-support' ),
            'required' => false,
            'order' => 2,
            'sort_order' => 2,
    ));
}

function custom_field_excerpt_longer() {
    global $post;
    $text = get_field('description');
    if ( '' != $text ) {
        $text = strip_shortcodes( $text );
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
        $excerpt_length = 150; // 20 words
        $excerpt_more = apply_filters('excerpt_more', ' ' . 'Read More...');
        $text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
        //$lines = explode(PHP_EOL, $text);
    }
    return apply_filters('the_excerpt', $text);
}

add_shortcode('read', 'read_main');
function read_main($atts, $content = null) {
    global $post;
    $desc = get_field('description');
    extract(shortcode_atts(array(
        'more' => 'READ MORE',
        'less' => 'READ LESS'
    ), $atts));

    mt_srand((double)microtime() * 1000000);
    $rnum = mt_rand();

    $text = 'Read More';
    $less = "Less";
   
    $new_string = '<span><a onclick="read_toggle(' . $rnum . ', \'' . $text . '\', \'' . $less . '\'); return false;" class="read-link" id="readlink' . $rnum . '" style="readlink" href="#">' . $text . '</a></span>' . "\n";
    $new_string .= '<div class="read_div" id="read' . $rnum . '" style="display: none;">' . $desc . '</div>';

    return $new_string;
 }

add_action('wp_head', 'read_javascript');
function read_javascript() {
    echo '<script>
    function expand(param) {
        param.style.display = (param.style.display == "none") ? "block" : "none";
    }
    function read_toggle(id, more, less) {
        el = document.getElementById("readlink" + id);
        el.innerHTML = (el.innerHTML == more) ? less : more;
        expand(document.getElementById("read" + id));
    }
    </script>';
}

 
add_action('wp_ajax_products_api_form', 'products_api_form');
add_action('wp_ajax_nopriv_products_api_form', 'products_api_form');

function products_api_form(){

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

    $product_id = $_POST['product_id'];
    $stock_quantity = $_POST['stock_quantity'];

    $updated_order  = $client->products->update( $product_id, array( 'stock_quantity' => $stock_quantity ) ); 
    //exit();
       
} catch ( WC_API_Client_Exception $e ) {

    echo $e->getMessage() . PHP_EOL;
    echo $e->getCode() . PHP_EOL;

    if ( $e instanceof WC_API_Client_HTTP_Exception ) {

        print_r( $e->get_request() );
        print_r( $e->get_response() );
    }
}
}


add_action( 'admin_menu', 'misha_add_metabox' );
 
function misha_add_metabox() {
 
    add_meta_box(
        'misha_metabox', // metabox ID
        'Meta Box', // title
        'misha_metabox_callback', // callback function
        'page', // post type or post types in array
        'normal', // position (normal, side, advanced)
        'default' // priority (default, low, high, core)
    );
 
}

function misha_metabox_callback( $post ) {
 
    $header_bg_color = get_post_meta( $post->ID, 'header_bg_color', true );
   
 
    // nonce, actually I think it is not necessary here
    wp_nonce_field( 'somerandomstr', '_mishanonce' );
 
    echo '<table class="form-table">
        <tbody>
            <tr>
                <th><label for="header_bg_color">Header Color</label></th>
                <td><input type="text" id="header_bg_color"  placeholder="Color Code Ex. #000" name="header_bg_color" value="' . esc_attr( $header_bg_color ) . '" class="regular-text"></td>
            </tr>
        </tbody>
    </table>';
 
}

add_action( 'save_post', 'misha_save_meta', 10, 2 );
 
function misha_save_meta( $post_id, $post ) {
 
    // nonce check
    if ( ! isset( $_POST[ '_mishanonce' ] ) || ! wp_verify_nonce( $_POST[ '_mishanonce' ], 'somerandomstr' ) ) {
        return $post_id;
    }
 
    // check current use permissions
    $post_type = get_post_type_object( $post->post_type );
 
    if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
        return $post_id;
    }
 
    // Do not save the data if autosave
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
    }
 
    // define your own post type here
    if( $post->post_type != 'page' ) {
        return $post_id;
    }
 
    if( isset( $_POST[ 'header_bg_color' ] ) ) {
        update_post_meta( $post_id, 'header_bg_color', sanitize_text_field( $_POST[ 'header_bg_color' ] ) );
    } else {
        delete_post_meta( $post_id, 'header_bg_color' );
    }
    
    return $post_id;
 
}

