<?php
 
// Change minDate for contact form date picker.  'set_min_date' is a custom hook, see hook documentation here:
// https://formidableforms.com/knowledgebase/frm_date_field_js/
add_action('frm_date_field_js', 'set_min_date');
function set_min_date($field_id){
  if($field_id == 'field_date'){
    echo ",minDate: new Date(2017,10,1,0,0,0)";
  }
}

add_action ('init', 'init_template');

function init_template(){
    setup_template_theme();
    add_action( 'wp_enqueue_scripts', 'enqueue_template_scripts' );
}

// Override 'Howdy' Message
function howdy_message($translated_text, $text, $domain) {
    $new_message = str_replace('Howdy', 'Welcome', $text);
    return $new_message;
}
add_filter('gettext', 'howdy_message', 10, 3);

function setup_template_theme(){

    // Remove added padding from Admin Bar
    add_action('get_header', 'remove_admin_login_header');
    function remove_admin_login_header() {
        remove_action('wp_head', '_admin_bar_bump_cb');
    }

    // Setting up theme
    if (function_exists('add_theme_support')) {
        add_theme_support('menus');
        add_theme_support( 'post-thumbnails' );
    }

    if (function_exists('register_sidebar')){
        register_sidebar(array('name'=>'Sidebar %d'));
    }

    if (function_exists('register_nav_menu')) {
        register_nav_menu( 'main_menu', 'Main Menu' );
        register_nav_menu( 'priority_menu', 'Priority Menu' );
    }

    if (function_exists('add_image_size')){
        add_image_size('gallery-full', 925, 667, true);
        add_image_size('gallery-thumb', 220, 220, true);
        add_image_size('mobile-header', 320, 384, true);
        add_image_size('tablet-header', 768, 602, true);
        add_image_size('tablet-header-tall', 768, 742, true);
        add_image_size('home-body-mobile', 320, 325, true);
        add_image_size('home-body-tablet', 768, 399, true);
        add_image_size('home-body-desktop', 960, 1025, true);
        add_image_size('mobile-cta', 320, 271, true);
        add_image_size('tablet-cta', 389, 397, true);
    }

    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page();
    }

    // Set Google Maps API for Advanced Custom Fields use
    function my_acf_google_map_api( $api ){
        $api['key'] = 'AIzaSyBLGDbL7PkNbZLKg2BRtIb6anAkSnl0Y_Y';
        return $api;
    }
    add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');
}

function enqueue_template_scripts() {
    // Theme CSS
    wp_register_style( 'screen-css', get_bloginfo('template_directory') . '/css/screen.css', array(), '1.2' );
    wp_enqueue_style( 'screen-css' );

    wp_register_style( 'icomoon-css', 'https://i.icomoon.io/public/cb74e5520e/MintDenver/style.css' );
    wp_enqueue_style( 'icomoon-css' );

    // Font Awesome
    wp_register_style( 'fontawesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
    wp_enqueue_style( 'fontawesome-css' );

    // Google hosted jQuery
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' );
    wp_enqueue_script( 'jquery' );

    // Main JS
    wp_deregister_script( 'template-js' );
    wp_register_script( 'template-js', get_bloginfo('template_directory') . '/js/template.js', array ( 'jquery' ), '1.2' );
    wp_enqueue_script( 'template-js' );

    // jQuery Viewport
    wp_register_script( 'viewport-js', get_bloginfo('template_directory') . '/js/jquery.viewport.js', array ( 'jquery' ) );
    wp_enqueue_script( 'viewport-js' );

    // Menu Images JS
    wp_register_script( 'menu-images-js', get_bloginfo('template_directory') . '/js/menu-images.min.js' );
    wp_enqueue_script( 'menu-images-js' );


    if(is_front_page()){
        // jQuery UI CSS
        wp_register_style( 'jquery-ui-css', '//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css');
        wp_enqueue_style( 'jquery-ui-css' );

        // jQuery UI js
        wp_register_script( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js', array ( 'jquery' ), false, false );
    }

    elseif(is_page_template('page-templates/contact.php')) {
        // jQuery UI CSS
        wp_register_style( 'jquery-ui-css', '//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css');
        wp_enqueue_style( 'jquery-ui-css' );

        // jQuery UI js
        wp_register_script( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js', array ( 'jquery' ), false, false );
    }


    elseif(is_page_template('page-templates/floor-plans.php')) {
        // jQuery UI CSS
        wp_register_style( 'jquery-ui-css', '//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css');
        wp_enqueue_style( 'jquery-ui-css' );

        // jQuery UI js
        wp_register_script( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js', array ( 'jquery' ), false, false );

        // jQuery Tablesorter js
        wp_register_script( 'jquery-tablesort', get_bloginfo('template_directory') . '/js/jquery.tablesorter.min.js', array ( 'jquery' ), false, false );

        // jQuery history js
        wp_register_script( 'jquery-history', '//cdnjs.cloudflare.com/ajax/libs/history.js/1.7.1/bundled/html5/jquery.history.js', array ( 'jquery' ), false, false );

        // Floor Plans
        wp_register_script( 'floor-plans-js', get_bloginfo('template_directory') . '/js/floor-plans.js', array ( 'jquery', 'jquery-ui', 'jquery-tablesort', 'jquery-history' ), false, true );
        wp_enqueue_script( 'floor-plans-js' );
    }

    elseif(is_page_template('page-templates/neighborhood.php')) {
        // Google Maps API
        wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBLGDbL7PkNbZLKg2BRtIb6anAkSnl0Y_Y');

        // Map JS
        wp_register_script( 'poi-map-js', get_bloginfo('template_directory') . '/js/poi-map.js', array ( 'google-maps' ), false, true);
        wp_enqueue_script( 'poi-map-js' );
    }

    elseif(is_page_template('page-templates/gallery.php')) {
        // Swipe functionality
        wp_deregister_script( 'touch-swipe-js' );
        wp_register_script( 'touch-swipe-js', get_bloginfo('template_directory') . '/js/jquery.touchSwipe.min.js', array ( 'jquery' ), false, true );
        // Gallery JS
        wp_register_script( 'gallery-js', get_bloginfo('template_directory') . '/js/gallery.min.js', array ( 'jquery','touch-swipe-js' ), false, true);
        wp_enqueue_script( 'gallery-js' );
    }
}

// Adds page slug to Body Class function
function add_slug_body_class( $classes ) {
    global $post;
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

// Returns property schema data
function mtc_print_microdata() {
    $microdata = array();
    if (is_page_template('page-templates/contact.php')) {

        // Creating the contact page microdata
        $contactMicrodata = array(
            '@context' => 'http://schema.org',
            '@type' => 'ContactPage'
        );

        // Adding The Elms microdata and then the Contact Page Microdata
        array_push($microdata, mtc_get_microdata());
        array_push($microdata, $contactMicrodata);

    } else {
        $microdata = mtc_get_microdata();
    }

    echo '<script type="application/ld+json">';
    echo json_encode($microdata, JSON_PRETTY_PRINT);
    echo '</script>';
}

function mtc_get_microdata() {
    if (function_exists('get_option')){
        $microdata = array(
            '@context' => 'http://schema.org',
            '@type' => 'Organization',
            'name' => get_field('property_name', 'options'),
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => get_field('address', 'options'),
                'addressLocality' => get_field('city', 'options').', '.get_field('state', 'options'),
                'postalCode' => get_field('zip', 'options'),
            ),
            'telephone' => get_field('phone', 'options'),
            'url' => get_home_url()
        );
        if ($header_logo=get_field('header_logo','options')){
            $microdata['logo']=$header_logo['url'];
        }
        return $microdata;
    }
}

// Takes an alphabetic character and returns the phone numeric equivalent
function alpha_to_phone($char){
    $conversion=array('a' => 2, 'b' => 2, 'c' => '2', 'd' => 3, 'e' => 3, 'f' => 3, 'g' => 4, 'h' => 4, 'i' => 4, 'j' => 5, 'k' => 5, 'l' => 5, 'm' => 6, 'n' => 6, 'o' => 6, 'p' => 7, 'q' => 7, 'r' => 7, 's' => 7, 't' => 8, 'u' => 8, 'v' => 8, 'w' => 9, 'x' => 9, 'y' => 9, 'z' => 9);
    return $conversion[$char];
}

// Takes phone number, returns cleaned numeric equivalent for mobile link functionality
function clean_phone($phone){
    $chars=str_split(strtolower($phone));
    $phone='';
    foreach($chars as $char){
        if (ctype_lower($char)){
            $char=alpha_to_phone($char);
        }
        $phone .= $char;
    }
    $phone=preg_replace("/[^0-9]/", "",$phone);
    return $phone;
}


// Saves gallery data to json file on page save
function save_gallery_json($post_id){
    if (get_page_template_slug($post_id) == 'page-templates/gallery.php'){
        if ($rows = get_field('gallery',$post_id)){
            $gallery = array();
            // Create category for all images
            $gallery[0]=array(
                'category'=>'All',
                'images'=>array()
            );
            // Categories
            foreach($rows as $row){
                // Images
                $images = array();
                if (!empty($row['images'])){
                    foreach ($row['images'] as $row2){
                        $image = array(
                            'url' => $row2['image']['sizes']['gallery-full'],
                            'thumb' => $row2['image']['sizes']['gallery-thumb'],
                            'caption' => $row2['caption']
                        );
                        // Add image to category
                        $images[] = $image;

                        // Add image to all images array
                        $gallery[0]['images'][] = $image;
                    }
                }
                $gallery[] = array(
                    'category' => $row['category'],
                    'images' => $images
                );
            }
            file_put_contents(get_template_directory() . '/JSON/gallery.json',json_encode($gallery));
        }
    }
}
add_action( 'save_post', 'save_gallery_json' );


// Saves area data to json file on page save
function save_neighborhood_json($post_id){
    if (get_page_template_slug($post_id) == 'page-templates/neighborhood.php'){
        if ($rows = get_field('points_of_interest_map',$post_id)){
            $pois = array(
                'categories' => array()
            );
            // Categories
            foreach($rows as $category){

                // POI Location
                $locations = array();
                if (!empty($category['point_of_interest'])){
                    foreach ($category['point_of_interest'] as $poi){
                        $poi_data = array(
                            'name' => $poi['name'],
                            'url' => $poi['website']
                        );
                        if ($poi['location']){
                            $poi_data['address'] = str_replace(', United States','',$poi['location']['address']); // Strip country which ACF Google Maps plugin sometimes appends
                            $poi_data['lat'] = (float) $poi['location']['lat'];
                            $poi_data['lng'] = (float) $poi['location']['lng'];
                        }
                        $locations[] = $poi_data;
                    }
                }

                // Marker information array
                $marker = array();
                if (!empty($category['category_marker_image'])){
                    $marker_data = array(
                        'url' => $category['category_marker_image']['url'],
                        'width' => $category['category_marker_image']['width'],
                        'height' => $category['category_marker_image']['height']
                    );
                    $marker = $marker_data;
                }

                // Assemble Category Array
                $pois['categories'][] = array(
                    'name' => $category['category_name'],
                    'marker' => $marker,
                    'pois' => $locations
                );
            }

            // Property Information
            $property = array(
                'property_name' => get_field('property_name', 'options'),
                'address' => get_field('address', 'options'),
                'city' => get_field('city', 'options'),
                'state' => get_field('state', 'options'),
                'zip' => get_field('zip', 'options'),
                'lat' => (float) get_field('latitude','options'),
                'lng' => (float) get_field('longitude','options')
            );
            if ($property_map_marker = get_field('property_map_marker', 'options')){
                $property['property_map_marker'] = array(
                    'url' => $property_map_marker['url'],
                    'width' => $property_map_marker['width'],
                    'height' => $property_map_marker['height']
                );
            }
            $pois['property'] = $property;

            file_put_contents(get_template_directory() . '/JSON/neighborhood.json',json_encode($pois));
        }
    }
}
add_action( 'save_post', 'save_neighborhood_json' );

// Saves menu image data to JSON files on page save
function save_menu_image_json(){
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => -1
    );
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $images = array();
        while ( $query->have_posts() ) {
            $query->the_post();
            $current_post = get_post();
            if ($image = get_field('menu_image')){
                $images[] = array(
                    'url' => $image['url'],
                    'title' => $current_post->post_name
                );
            }
        }
        file_put_contents(get_template_directory() . '/JSON/menu-images.json',json_encode($images));
        wp_reset_postdata();
    }
}
add_action( 'save_post', 'save_menu_image_json' );

// Update the permalink text for blog post excerpts
function excerpt_more( $more ) {
    return '&nbsp;<a class="read-more ease" href="'. get_permalink( get_the_ID() ) . '">...read&nbsp;more</a>';
}
add_filter( 'excerpt_more', 'excerpt_more' );

// Returns author's full name
function get_author_full_name(){
    $fname = get_the_author_meta('first_name');
    $lname = get_the_author_meta('last_name');
    $full_name = '';

    if( empty($fname)){
        $full_name = $lname;
    } elseif( empty( $lname )){
        $full_name = $fname;
    } else {
        // Both first name and last name are present
        $full_name = "$fname $lname";
    }

    return $full_name;
}


function print_logo(){
    if($logo = get_field('logo', 'options')) {
        echo '<div id="logo">';
        echo '<a href="'. home_url('') .'">';
        echo '<img src="'. $logo['url'] .'" />';
        echo '</a>';
        echo '</div>';
    }
}

function print_mobile_logo(){
    if($logo = get_field('mobile_logo', 'options')) {
        echo '<div id="mobile-logo">';
        echo '<a href="'. home_url('') .'">';
        echo '<img src="'. $logo['url'] .'" />';
        echo '</a>';
        echo '</div>';
    }
}

function print_resident_login() {
    if($resident_link = get_field('resident_login_url','options')) {
        echo '<div class="resident-login menu"><a class="ease menu-item" href="'.$resident_link.'" target="_blank">Residents</a></div>';
    }
}

function print_leasing_login() {
    if($leasing_link = get_field('leasing_login_url','options')) {
        echo '<div class="leasing-login menu"><a class="ease menu-item" href="'.$leasing_link.'" target="_blank">Leasing Login</a></div>';
    }
}

function print_mobile_address_link(){
    $address = get_field('address','options');
    $city = get_field('city','options');
    $state = get_field('state','options');
    $zip = get_field('zip','options');
    echo '<div class="address mobile-icon"><a class="address-link ease" href="https://maps.google.com/?daddr='.$address.', '.$city.', '.$state.' '.$zip.'" target="_blank"><span class="icon-marker" aria-hidden="true"></span></a></div>';
}

function print_mobile_phone_link(){
    if ($phone = get_field('phone','options')){
        echo '<div class="phone mobile-icon"><a href="tel:+1'.clean_phone($phone).'" class="phone-link ease"><span class="icon-phone" aria-hidden="true"></span></a></div>';
    }
}

function print_phone_number(){
    if ($phone = get_field('phone','options')){
        echo '<div class="phone">';
        echo '<a href="tel:+1'.clean_phone($phone).'" class="phone-number ease">'.$phone.'</a>';
        echo '</div>';
    }
}

function print_address(){
    $address = get_field('address','options');
    $city = get_field('city','options');
    $state = get_field('state','options');
    $zip = get_field('zip','options');
    echo '<div class="address">';
    echo '<div class="title">Mint Town Center</div>';
    echo '<a href="https://maps.google.com/?daddr='.$address.', '.$city.', '.$state.' '.$zip.'" target="_blank" class="ease"><span class="top">'.$address.'</span><span class="break">, </span><span class="bottom">'.$city.', '.$state.' '.$zip.'</span></a>';
    echo '</div>';
}


function print_leasing_address(){
    $address = get_field('leasing_address','options');
    $city = get_field('leasing_city','options');
    $state = get_field('leasing_state','options');
    $zip = get_field('leasing_zip','options');
    $phone = get_field('leasing_phone','options');
    echo '<div id="leasing-address">';
    echo '<div class="title">Leasing Office:</div>';
    echo '<a href="https://maps.google.com/?daddr='.$address.', '.$city.', '.$state.' '.$zip.'" target="_blank" class="ease"><span class="top">'.$address.'</span><span class="break">, </span><span class="bottom">'.$city.', '.$state.' '.$zip.'</span></a>';
    echo '<div class="leasing-phone"><a href="tel:+1'.clean_phone($phone).'" class="phone-number ease">'.$phone.'</a></div>';
    echo '</div>';
}


function print_social(){
    if ($rows = get_field('social_media','options')){
        echo '<div class="icons social">';
        foreach($rows as $row){
            echo '<a class="icon ease" href="'.$row['url'].'" target="_blank">';
            echo '<i class="fa '.$row['social_media_type'].'"></i>';
            echo '</a>';
        }
        echo '<button id="pop-calendar" class="ease schedule-a-tour-button" title="Schedule a Tour"><i class="fa fa-calendar"></i></button>';
        echo '</div>';
    }
}

function print_trigger() {
    echo '<div id="nav-trigger" class="inactive ease">';
    echo '<div class="trigger-wrap">';
    echo '<span class="line"></span>';
    echo '<span class="line"></span>';
    echo '<span class="line"></span>';
    echo '</div>';
    echo '<div class="menu-label ease">Menu</div>';
    echo '<div class="close-label ease">Close</div>';
    echo '</div>';
}

function print_privacy_policy() {
    if($privacy = get_field('privacy_policy_link', 'options')){
        echo '<div id="privacy"><a href="'.$privacy.'" target="_blank">Privacy Policy</a></div>';
    }
}

function print_management_logo() {
    if($management_url = get_field('management_url','options')){
        echo '<div id="management"><a href="'.$management_url.'" target="_blank" class="ease"><span class="icon-brookfield ease"></span></a></div>';
    } else {
        echo '<div id="management"><span class="icon-brookfield ease"></span></div>';
    }
}

function print_footer_links() {
    if($links = get_field('footer_links')){
        echo '<div id="footer-links">';
        foreach ($links as $link) {
            if($link['link_text']){
                echo '<div class="link-wrap">';
                echo '<div class="link ease blue"><a href="'.$link['page_link'].'" class="ease"><span>'.$link['link_text'].'</span></a></div>';
                echo '</div>';
            }
        }
        echo '</div>';
    }
}

function print_office_hours(){
    if ($rows = get_field('office_hours','options')) {
        echo '<div id="office-hours">';
        echo '<div class="title">Office Hours</div>';
        echo '<div class="office-hour">';
        foreach ( $rows as $row ) {
            echo '<div class="days"><span class="day">'.$row['day'].': </span><span class="hours">'.$row['hours'].'</span></div>';
        }
        echo '</div>';
        echo '</div>';
    }
}

function print_slidout(){
    if(get_field('activate_slide_out', 'options')) {
        $button = get_field('slide_out_button_title','options');
        $title = get_field('slideout_title','options');
        $copy = get_field('slideout_content','options');
        echo '<div id="slideout" class="ease">';
        echo '<div class="slideout-outer-wrapper vertical-center-parent">';
        echo '<div class="button-wrap vertical-center-child ease">';
        echo '<span class="icon-special-trigger ease"></span>';
        echo '<div class="button-text-wrap">';
        echo '<div class="button ease">'.$button.'</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="slideout-inner-wrap vertical-center-parent">';
        echo '<div class="content vertical-center-child">';
        echo '<div class="title">'.$title.'</div>';
        if($copy = get_field('slideout_content','options')) {
            echo '<div class="copy">'.$copy.'</div>';
        }
        if($disclaimer = get_field('slideout_disclaimer','options')) {
            echo '<div class="disclaimer">'.$disclaimer.'</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}


function print_header_image() {
    if ($header_image = get_field('image')) {

        // MOBILE
        echo '<div class="mobile">';
        echo '<canvas class="canvas" height="384" width="320"></canvas>';
        echo '<svg x="0px" y="0px" viewBox="0 0 320 384" style="enable-background:new 0 0 320 384;" xml:space="preserve">';
        echo '<defs><pattern id="mobile-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['sizes']['mobile-header'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#mobile-image)" d="M319.5,0v332l-160,52L-0.13,332.52V0.02h237.2L319.5,0z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M320,327v5l-160.5,52L0,332.52v-5.5l159.67,52L320,327z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';

        // TABLET
        echo '<div class="tablet">';
        echo '<canvas class="canvas" height="742" width="768"></canvas>';
        echo '<svg x="0px" y="0px" viewBox="0 0 768 742" style="enable-background:new 0 0 768 742;" xml:space="preserve">';
        echo '<defs><pattern id="tablet-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['sizes']['tablet-header-tall'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#tablet-image)" d="M768-0.5V613L384.5,741L0,613.52V-0.98l384.67,0.5L768-0.5z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M768,609v4L384.5,741L0,613.52v-5.5l384.67,128L768,609z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';

        // DESKTOP
        echo '<div class="desktop">';
        echo '<canvas class="canvas" height="1103" width="1920"></canvas>';
        echo '<svg x="0px" y="0px" width="1920px" height="1103px" viewBox="0 0 1920 1103" style="enable-background:new 0 0 1920 1103;" xml:space="preserve">';
        echo '<defs><pattern id="image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['url'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#image)" d="M960,1097c-0.67,0-1.33,0-2,0c-1.47-0.66-2.89-1.46-4.41-1.97c-40.23-13.52-80.48-27.01-120.72-40.5
		c-247-82.84-493.99-165.67-740.98-248.52C61.24,795.73,30.63,785.34,0,775C0,516.67,0,258.33,0,0c640,0,1280,0,1920,0
		c0,259.33,0,518.67,0,778c-0.65,0.13-1.32,0.2-1.94,0.4c-66.4,22.05-132.79,44.12-199.19,66.16
		c-239.52,79.51-479.05,159-718.56,238.53C986.82,1087.58,973.43,1092.36,960,1097z" />';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M1718.87,844.07c-239.52,79.51-479.05,159-718.56,238.53c-13.49,4.48-26.87,9.26-40.31,13.9
		c-0.67,0-1.33,0-2,0c-1.47-0.66-2.89-1.46-4.41-1.97c-40.23-13.52-80.48-27.01-120.72-40.5c-247-82.84-493.99-165.67-740.98-248.52
		C61.24,795.23,30.63,784.84,0,774.5c0,2,0,4,0,6c30.63,10.34,61.24,20.73,91.88,31.01c246.99,82.85,493.99,165.69,740.98,248.52
		c40.24,13.5,80.49,26.98,120.72,40.5c1.52,0.51,2.94,1.31,4.41,1.97c0.67,0,1.33,0,2,0c13.43-4.64,26.82-9.42,40.31-13.9
		c239.51-79.53,479.04-159.02,718.56-238.53c66.4-22.04,132.79-44.12,199.19-66.16c0.62-0.21,1.29-0.27,1.94-0.4c0-2,0-4,0-6
		c-0.65,0.13-1.32,0.2-1.94,0.4C1851.66,799.95,1785.27,822.03,1718.87,844.07z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';
    }
}


function print_small_header_image() {
    if ($header_image = get_field('image')) {
        // MOBILE
        echo '<div class="mobile">';
        echo '<canvas class="canvas" height="384" width="320"></canvas>';
        echo '<svg x="0px" y="0px" viewBox="0 0 320 384" style="enable-background:new 0 0 320 384;" xml:space="preserve">';
        echo '<defs><pattern id="mobile-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['sizes']['mobile-header'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#mobile-image)" d="M319.5,0v332l-160,52L-0.13,332.52V0.02h237.2L319.5,0z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M320,327v5l-160.5,52L0,332.52v-5.5l159.67,52L320,327z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';

        // TABLET
        echo '<div class="tablet">';
        echo '<canvas class="canvas" height="602" width="768"></canvas>';
        echo '<svg x="0px" y="0px" viewBox="0 0 768 602" style="enable-background:new 0 0 768 602;" xml:space="preserve">';
        echo '<defs><pattern id="tablet-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['sizes']['tablet-header'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#tablet-image)" d="M767,0.5V474L384.5,602L0,474.52V0.02l384.67,0.5L767,0.5z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M768,468.5v5.5L384.5,602L0,474.52v-6.5l384.67,128.5L768,468.5z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';

        // DESKTOP
        echo '<div class="desktop">';
        echo '<canvas class="canvas" height="555" width="1920"></canvas>';
        echo '<svg x="0px" y="0px" width="1920px" height="555px" viewBox="0 0 1920 555" style="enable-background:new 0 0 1920 555;" xml:space="preserve">';
        echo '<defs><pattern id="image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['url'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#image)" d="M1920-6c0,78.67,0,157.33,0,236c-0.46,0.18-0.92,0.39-1.39,0.55c-307.64,102.13-615.29,204.24-922.93,306.39
			c-11.61,3.86-23.12,8.03-34.68,12.06c-1.33,0-2.67,0-4,0c-1.46-0.68-2.86-1.51-4.38-2.02C648.3,444.91,343.97,342.86,39.64,240.8
			c-13.18-4.42-26.35-8.91-39.27-13.28c0-78.09,0-155.66,0-233.5c2.66,0,4.98,0,7.29,0c339.97,0,679.94,0,1019.91,0
			C1325.05-5.99,1622.53-5.99,1920-6z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M1717.87,296.07c-239.52,79.51-479.05,159-718.56,238.53c-13.49,4.48-26.87,9.26-40.31,13.9
			c-0.67,0-1.33,0-2,0c-1.47-0.66-2.89-1.46-4.41-1.97c-40.23-13.52-80.48-27.01-120.72-40.5c-247-82.84-493.99-165.67-740.98-248.52
			C60.24,247.23,29.63,236.84-1,226.5c0,2,0,4,0,6c30.63,10.34,61.24,20.73,91.88,31.01c246.99,82.85,493.99,165.69,740.98,248.52
			c40.24,13.5,80.49,26.98,120.72,40.5c1.52,0.51,2.94,1.31,4.41,1.97c0.67,0,1.33,0,2,0c13.43-4.64,26.82-9.42,40.31-13.9
			c239.51-79.53,479.04-159.02,718.56-238.53c66.4-22.04,132.79-44.12,199.19-66.16c0.62-0.21,1.29-0.27,1.94-0.4c0-2,0-4,0-6
			c-0.65,0.13-1.32,0.2-1.94,0.4C1850.66,251.95,1784.27,274.03,1717.87,296.07z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';
    }
}

function print_blog_header_section() {
    if ($headline = get_field('blog_headline','options')) {
        $header_image = get_field('blog_image','options');
        $sub_headline = get_field('blog_sub_headline','options');
        $pre_title = get_field('blog_pre_title','options');
        echo '<div class="page-header blog">';
        // MOBILE
        echo '<div class="mobile">';
        echo '<canvas class="canvas" height="384" width="320"></canvas>';
        echo '<svg x="0px" y="0px" viewBox="0 0 320 384" style="enable-background:new 0 0 320 384;" xml:space="preserve">';
        echo '<defs><pattern id="mobile-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['sizes']['mobile-header'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#mobile-image)" d="M319.5,0v332l-160,52L-0.13,332.52V0.02h237.2L319.5,0z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M320,327v5l-160.5,52L0,332.52v-5.5l159.67,52L320,327z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';

        // TABLET
        echo '<div class="tablet">';
        echo '<canvas class="canvas" height="602" width="768"></canvas>';
        echo '<svg x="0px" y="0px" viewBox="0 0 768 602" style="enable-background:new 0 0 768 602;" xml:space="preserve">';
        echo '<defs><pattern id="tablet-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['sizes']['tablet-header'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#tablet-image)" d="M767,0.5V474L384.5,602L0,474.52V0.02l384.67,0.5L767,0.5z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M768,468.5v5.5L384.5,602L0,474.52v-6.5l384.67,128.5L768,468.5z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';

        // DESKTOP
        echo '<div class="desktop">';
        echo '<canvas class="canvas" height="555" width="1920"></canvas>';
        echo '<svg x="0px" y="0px" width="1920px" height="555px" viewBox="0 0 1920 555" style="enable-background:new 0 0 1920 555;" xml:space="preserve">';
        echo '<defs><pattern id="image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['url'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#image)" d="M1920-6c0,78.67,0,157.33,0,236c-0.46,0.18-0.92,0.39-1.39,0.55c-307.64,102.13-615.29,204.24-922.93,306.39
				c-11.61,3.86-23.12,8.03-34.68,12.06c-1.33,0-2.67,0-4,0c-1.46-0.68-2.86-1.51-4.38-2.02C648.3,444.91,343.97,342.86,39.64,240.8
				c-13.18-4.42-26.35-8.91-39.27-13.28c0-78.09,0-155.66,0-233.5c2.66,0,4.98,0,7.29,0c339.97,0,679.94,0,1019.91,0
				C1325.05-5.99,1622.53-5.99,1920-6z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M1717.87,296.07c-239.52,79.51-479.05,159-718.56,238.53c-13.49,4.48-26.87,9.26-40.31,13.9
				c-0.67,0-1.33,0-2,0c-1.47-0.66-2.89-1.46-4.41-1.97c-40.23-13.52-80.48-27.01-120.72-40.5c-247-82.84-493.99-165.67-740.98-248.52
				C60.24,247.23,29.63,236.84-1,226.5c0,2,0,4,0,6c30.63,10.34,61.24,20.73,91.88,31.01c246.99,82.85,493.99,165.69,740.98,248.52
				c40.24,13.5,80.49,26.98,120.72,40.5c1.52,0.51,2.94,1.31,4.41,1.97c0.67,0,1.33,0,2,0c13.43-4.64,26.82-9.42,40.31-13.9
				c239.51-79.53,479.04-159.02,718.56-238.53c66.4-22.04,132.79-44.12,199.19-66.16c0.62-0.21,1.29-0.27,1.94-0.4c0-2,0-4,0-6
				c-0.65,0.13-1.32,0.2-1.94,0.4C1850.66,251.95,1784.27,274.03,1717.87,296.07z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';
        echo '<div class="pre-title">'.$pre_title.'</div>';
        echo '<div class="header-content fade-in vertical-center-parent">';
        echo '<div class="vertical-center-child">';
        echo '<span class="icon-mint-diamond"></span>';
        echo '<div class="header-headline ease">'.$headline.'</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="header-bottom">';
        echo '<div class="header-line-break ease">';
        echo '<img class="breaks" src="'.get_bloginfo('template_directory').'/images/breaks.png" />';
        echo '<div class="background"></div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="content-arrow fade-in">';
        echo '<div class="background"></div>';
        echo '<img class="arrow" src="'.get_bloginfo('template_directory').'/images/content-arrow.png" />';
        echo '</div>';
        echo '</div>';
    }
}


function print_blog_call_outs() {
    if($ctas = get_field('blog_call_to_actions', 'options')) {
        echo '<div id="call-to-actions">';
        // Background Images
        if($backgrounds = get_field('blog_call_to_action_backgrounds', 'options')) {
            echo '<div class="cta-bg-wrapper responsive-bg" data-m="'.$backgrounds[0]['blog_cta_image']['sizes']['tablet-cta'].'" data-t="'.$backgrounds[0]['blog_cta_image']['sizes']['tablet-cta'].'">';
            $count = 0;
            foreach ($backgrounds as $background) {
                echo '<div class="background '.(++$count%2 ? "left" : "right") .'"><div class="img-wrapper">';
                echo '<div class="border-top"></div>';
                echo '<img class="responsive-img" data-m="'.$background['blog_cta_image']['sizes']['tablet-cta'].'" data-t="'.$background['blog_cta_image']['sizes']['tablet-cta'].'" data-d="'.$background['blog_cta_image']['url'].'" src="" />';
                echo '<div class="border-bottom"></div>';
                echo '</div></div>';
            }
            echo '<span class="icon-mint-triangle"></span>';
            // Links
            echo '<div class="cta-link-wrapper fade-in">';
            foreach ($ctas as $cta) {
                echo '<a href="'.$cta['blog_page'].'" class="ease">';
                echo '<div class="cta-button ease">';
                echo '<div class="cta-link">';
                echo '<div class="vertical-center-parent">';
                echo '<div class="vertical-center-child">';
                echo '<div class="link">';
                echo $cta['blog_link_text'];
                echo '<span class="icon-mint-triangle ease"></span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</a>';
            }
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}

function print_404_header_section() {
    if ($headline = get_field('404_headline', 'options')) {
        $header_image = get_field('404_image', 'options');
        $sub_headline = get_field('404_sub_headline', 'options');
        $pre_title = get_field('404_pre_title', 'options');
        echo '<div class="page-header">';
        // MOBILE
        echo '<div class="mobile">';
        echo '<canvas class="canvas" height="384" width="320"></canvas>';
        echo '<svg x="0px" y="0px" viewBox="0 0 320 384" style="enable-background:new 0 0 320 384;" xml:space="preserve">';
        echo '<defs><pattern id="mobile-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['sizes']['mobile-header'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#mobile-image)" d="M319.5,0v332l-160,52L-0.13,332.52V0.02h237.2L319.5,0z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M320,327v5l-160.5,52L0,332.52v-5.5l159.67,52L320,327z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';

        // TABLET
        echo '<div class="tablet">';
        echo '<canvas class="canvas" height="602" width="768"></canvas>';
        echo '<svg x="0px" y="0px" viewBox="0 0 768 602" style="enable-background:new 0 0 768 602;" xml:space="preserve">';
        echo '<defs><pattern id="tablet-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['sizes']['tablet-header'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#tablet-image)" d="M767,0.5V474L384.5,602L0,474.52V0.02l384.67,0.5L767,0.5z"/>';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M768,468.5v5.5L384.5,602L0,474.52v-6.5l384.67,128.5L768,468.5z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';

        // DESKTOP
        echo '<div class="desktop">';
        echo '<canvas class="canvas" height="1103" width="1920"></canvas>';
        echo '<svg x="0px" y="0px" width="1920px" height="1103px" viewBox="0 0 1920 1103" style="enable-background:new 0 0 1920 1103;" xml:space="preserve">';
        echo '<defs><pattern id="image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">';
        echo '<image xlink:href="'.$header_image['url'].'" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>';
        echo '</pattern></defs>';
        echo '<g class="header-shape">';
        echo '<path fill="url(#image)" d="M960,1097c-0.67,0-1.33,0-2,0c-1.47-0.66-2.89-1.46-4.41-1.97c-40.23-13.52-80.48-27.01-120.72-40.5
		c-247-82.84-493.99-165.67-740.98-248.52C61.24,795.73,30.63,785.34,0,775C0,516.67,0,258.33,0,0c640,0,1280,0,1920,0
		c0,259.33,0,518.67,0,778c-0.65,0.13-1.32,0.2-1.94,0.4c-66.4,22.05-132.79,44.12-199.19,66.16
		c-239.52,79.51-479.05,159-718.56,238.53C986.82,1087.58,973.43,1092.36,960,1097z" />';
        echo '</g>';
        echo '<g class="border">';
        echo '<path style="fill:#FFFFFF;" d="M1718.87,844.07c-239.52,79.51-479.05,159-718.56,238.53c-13.49,4.48-26.87,9.26-40.31,13.9
		c-0.67,0-1.33,0-2,0c-1.47-0.66-2.89-1.46-4.41-1.97c-40.23-13.52-80.48-27.01-120.72-40.5c-247-82.84-493.99-165.67-740.98-248.52
		C61.24,795.23,30.63,784.84,0,774.5c0,2,0,4,0,6c30.63,10.34,61.24,20.73,91.88,31.01c246.99,82.85,493.99,165.69,740.98,248.52
		c40.24,13.5,80.49,26.98,120.72,40.5c1.52,0.51,2.94,1.31,4.41,1.97c0.67,0,1.33,0,2,0c13.43-4.64,26.82-9.42,40.31-13.9
		c239.51-79.53,479.04-159.02,718.56-238.53c66.4-22.04,132.79-44.12,199.19-66.16c0.62-0.21,1.29-0.27,1.94-0.4c0-2,0-4,0-6
		c-0.65,0.13-1.32,0.2-1.94,0.4C1851.66,799.95,1785.27,822.03,1718.87,844.07z"/>';
        echo '</g>';
        echo '</svg>';
        echo '</div>';
        echo '<div class="pre-title">'.$pre_title.'</div>';
        echo '<div class="header-content fade-in vertical-center-parent">';
        echo '<div class="vertical-center-child">';
        echo '<span class="icon-mint-diamond"></span>';
        echo '<div class="header-headline ease">'.$headline.'</div>';
        echo '<div class="header-sub-headline">'.$sub_headline.'</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="header-bottom">';
        echo '<div class="header-line-break ease">';
        echo '<img class="breaks" src="'.get_bloginfo('template_directory').'/images/breaks.png" />';
        echo '<div class="background"></div>';
        echo '</div>';
        echo '<div class="scroll">';
        echo '<span class="scroll-title">Scroll</span>';
        echo '<span class="icon-mint-triangle"></span>';
        echo '</div>';
        echo '</div>';
        echo '<div class="content-arrow">';
        echo '<div class="background"></div>';
        echo '<img class="arrow" src="'.get_bloginfo('template_directory').'/images/content-arrow.png" />';
        echo '</div>';
        echo '</div>';
    }
}

function print_404_call_outs() {
    if($ctas = get_field('404_call_to_actions', 'options')) {
        echo '<div id="call-to-actions">';
        // Background Images
        if($backgrounds = get_field('404_call_to_action_backgrounds', 'options')) {
            echo '<div class="cta-bg-wrapper responsive-bg" data-m="'.$backgrounds[0]['404_cta_image']['sizes']['tablet-cta'].'" data-t="'.$backgrounds[0]['404_cta_image']['sizes']['tablet-cta'].'">';
            $count = 0;
            foreach ($backgrounds as $background) {
                echo '<div class="background '.(++$count%2 ? "left" : "right") .'"><div class="img-wrapper">';
                echo '<div class="border-top"></div>';
                echo '<img class="responsive-img" data-m="'.$background['404_cta_image']['sizes']['tablet-cta'].'" data-t="'.$background['404_cta_image']['sizes']['tablet-cta'].'" data-d="'.$background['404_cta_image']['url'].'" src="" />';
                // echo '<img src="'.$background['404_cta_image']['url'].'" />';
                echo '<div class="border-bottom"></div>';
                echo '</div></div>';
            }
            echo '<span class="icon-mint-triangle"></span>';
            // Links
            echo '<div class="cta-link-wrapper fade-in">';
            foreach ($ctas as $cta) {
                echo '<a href="'.$cta['404_page'].'" class="ease">';
                echo '<div class="cta-button ease">';
                echo '<div class="cta-link">';
                echo '<div class="vertical-center-parent">';
                echo '<div class="vertical-center-child">';
                echo '<div class="link">';
                echo $cta['404_link_text'];
                echo '<span class="icon-mint-triangle ease"></span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</a>';
            }
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}


function print_homepage_header_section() {
    if ($headline = get_field('headline')) {
        echo '<div class="page-header">';
        print_header_image(); // Global Header Image setup.
        echo '<div class="header-content fade-in vertical-center-parent">';
        echo '<div class="vertical-center-child">';
        echo '<span class="icon-mint-logo"></span>';
        echo '<div class="header-headline ease">'.$headline.'</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="header-bottom">';
        echo '<div class="header-line-break ease">';
        echo '<img class="breaks" src="'.get_bloginfo('template_directory').'/images/breaks.png" />';
        echo '<div class="background"></div>';
        echo '</div>';
        echo '<div class="scroll">';
        echo '<span class="scroll-title">Scroll</span>';
        echo '<span class="icon-mint-triangle"></span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

function print_header_section() {
    if ($headline = get_field('headline')) {
        $sub_headline = get_field('sub_headline');
        $pre_title = get_field('pre_title');
        echo '<div class="page-header">';
        print_header_image(); // Global Header Image setup.
        echo '<div class="pre-title fade-in">'.$pre_title.'</div>';
        echo '<div class="header-content fade-in vertical-center-parent">';
        echo '<div class="vertical-center-child">';
        echo '<span class="icon-mint-diamond"></span>';
        echo '<div class="header-headline ease">'.$headline.'</div>';
        echo '<div class="header-sub-headline">'.$sub_headline.'</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="header-bottom">';
        echo '<div class="header-line-break ease">';
        echo '<img class="breaks" src="'.get_bloginfo('template_directory').'/images/breaks.png" />';
        echo '<div class="background"></div>';
        echo '</div>';
        echo '<div class="scroll">';
        echo '<span class="scroll-title">Scroll</span>';
        echo '<span class="icon-mint-triangle"></span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

function print_small_header_section() {
    if ($headline = get_field('headline')) {
        $sub_headline = get_field('sub_headline');
        $pre_title = get_field('pre_title');
        echo '<div class="page-header small">';
        print_small_header_image(); // Global Header Small 	Image setup.
        echo '<div class="pre-title fade-in">'.$pre_title.'</div>';
        echo '<div class="header-content fade-in vertical-center-parent">';
        echo '<div class="vertical-center-child">';
        echo '<div class="header-headline ease">'.$headline.'</div>';
        echo '<div class="header-sub-headline">'.$sub_headline.'</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="header-bottom">';
        echo '<div class="header-line-break ease">';
        echo '<img class="breaks" src="'.get_bloginfo('template_directory').'/images/breaks.png" />';
        echo '<div class="background"></div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

function print_homepage_main_content() {
    if($headline = get_field('body_headline')) {
        $body_copy = get_field('body_copy');
        $body_img	= get_field('body_image');
        $body_img_hover = get_field('body_image_hover');
        $caption = get_field('body_image_caption');
        echo '<div id="home-content" class="homepage-vertical-center-parent">';
        echo '<div class="content-arrow">';
        echo '<div class="background"></div>';
        echo '<img class="arrow" src="'.get_bloginfo('template_directory').'/images/content-arrow.png" />';
        echo '</div>';
        echo '<div class="left vertical-center-child">';
        echo '<div class="inner-wrap fade-in">';
        echo '<h1><span>'.$headline.'</span></h1>';
        echo $body_copy;
        echo '</div>';
        echo '</div>';
        echo '<div class="right">';
        echo '<div class="bg-image responsive-bg" data-m="'.$body_img_hover['sizes']['home-body-mobile'].'" data-t="'.$body_img_hover['sizes']['home-body-tablet'].'" data-d="'.$body_img_hover['sizes']['home-body-desktop'].'">';
        echo '<div class="body-img-bg responsive-bg" data-m="'.$body_img['sizes']['home-body-mobile'].'" data-t="'.$body_img['sizes']['home-body-tablet'].'" data-d="'.$body_img['sizes']['home-body-desktop'].'">';
        echo '<img class="body-img responsive-img" data-m="'.get_bloginfo('template_directory') .'/images/filler-320x325.png" data-t="'.get_bloginfo('template_directory') .'/images/filler-768x399.png" data-d="'.get_bloginfo('template_directory') .'/images/filler-960x1025.png" />';
        echo '</div>';
        echo '</div>';
        echo '<div class="caption ease-slow">'.$caption.'</div>';
        echo '</div>';
        echo '<div class="content-break">';
        echo '<img class="breaks" src="'.get_bloginfo('template_directory').'/images/breaks.png" />';
        echo '<div class="background"></div>';
        echo '</div>';
        echo '</div>';
    }
}

function print_main_content() {
    if($title = get_field('title')) {
        $copy = get_field('copy');
        echo '<div id="page-content">';
        echo '<div class="content-arrow">';
        echo '<div class="background"></div>';
        echo '<img class="arrow" src="'.get_bloginfo('template_directory').'/images/content-arrow.png" />';
        echo '</div>';
        echo '<div class="title fade-in"><h1><span>'.$title.'</span></h1></div>';
        echo '<div class="copy fade-in">'.$copy.'</div>';
        echo '<div class="content-arrow-up">';
        echo '<img class="arrow" src="'.get_bloginfo('template_directory').'/images/up-arrow.png" />';
        echo '<div class="background"></div>';
        echo '</div>';
        echo '</div>';
    }
}

function print_main_image() {
    if($body_image = get_field('body_image')) {
        $body_image_hover = get_field('body_image_hover');
        $copy = get_field('copy');
        echo '<div id="interior-image" class="bg-image responsive-bg" data-m="'.$body_image_hover['sizes']['home-body-mobile'].'" data-t="'.$body_image_hover['sizes']['home-body-tablet'].'" data-d="'.$body_image_hover['url'].'">';
        echo '<div class="img-bg responsive-bg" data-m="'.$body_image['sizes']['home-body-mobile'].'" data-t="'.$body_image['sizes']['home-body-tablet'].'" data-d="'.$body_image['url'].'">';
        echo '<img class="body-img responsive-img" data-m="'.get_bloginfo('template_directory') .'/images/filler-320x325.png" data-t="'.get_bloginfo('template_directory') .'/images/filler-768x399.png" data-d="'.get_bloginfo('template_directory') .'/images/filler-1920x797.png" />';
        echo '</div>';
        echo '</div>';
    }
}

function print_lists() {
    if($lists = get_field('lists')){
        $list_heading = get_field('list_heading');
        echo '<div id="lists">';
        echo '<div class="top-line-break">';
        echo '<div class="background"></div>';
        echo '<img class="breaks" src="'.get_bloginfo('template_directory').'/images/black-breaks.png" />';
        echo '</div>';
        echo '<div class="list-content">';
        echo '<div class="list-heading fade-in">'.$list_heading.'</div>';
        foreach ($lists as $list) {
            echo $list['items'];
        }
        if($disclaimer = get_field('disclaimer')) {
            echo '<div class="disclaimer">'.$disclaimer.'</div>';
        }
        echo '</div>';
        echo '<div class="bottom-background"></div>';
        echo '</div>';
    }
}

function print_map() {
    echo '<div id="map-container">';
    if($mobile_map = get_field('mobile_map_image','options')) {
        $address = get_field('address','options');
        $city = get_field('city','options');
        $state = get_field('state','options');
        $zip = get_field('zip','options');

        echo '<div class="mobile-map"><a class="address" href="https://maps.google.com/?daddr='.$address.', '.$city.', '.$state.' '.$zip.'" target="_blank"><span class="map-image" style="background-image: url('.$mobile_map['url'].');""></span></a></div>';
    }
    echo '<div id="categories-container">';
    echo '<ul id="categories"></ul>';
    echo '</div>';
    echo '<div id="map"></div>';
    echo '</div>';
}

function print_neighborhood_links() {
    if($neighborhoods = get_field('neighborhood_links')) {
        $neighborhood_headline = get_field('neighborhood_links_heading');
        echo '<div id="neighborhood-links">';
        echo '<div class="link-wrapper">';
        echo '<div class="neighborhood-heading fade-in">'.$neighborhood_headline.'</div>';
        echo '<div class="buttons-wrapper fade-in">';
        foreach ($neighborhoods as $neighborhood) {
            echo '<button class="neighborhood-button ease">';
            echo '<a href="'.$neighborhood['url'].'" class="ease-slow" style="background-image: url('.$neighborhood['image']['url'].');">';

            echo '<div class="neighborhood-link">';
            echo '<div class="vertical-center-parent">';
            echo '<div class="vertical-center-child">';
            echo '<div class="link">';
            echo str_replace(' ', '<br />', $neighborhood['name']); // Split name on spaces.
            echo '<span class="icon-mint-triangle ease"></span>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
            echo '</button>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

function print_contact_content() {
    echo '<div id="contact-content">';
    echo '<div class="contact-wrapper">';
    echo '<div class="columns fade-in">';
    if(get_field('address','options')){
        echo '<div class="column">';
        print_address();
        print_phone_number();
        echo '</div>';
    }
    if(get_field('leasing_address','options')) {
        echo '<div class="column">';
        print_leasing_address();
        echo '</div>';
    }
    if(get_field('office_hours','options')){
        echo '<div class="column">';
        print_office_hours();
        echo '</div>';
    }
    echo '</div>';
    if($copy = get_field('copy')) {
        echo '<div class="copy fade-in">'.$copy.'</div>';
    }

    print_form();
    echo '</div>';
    echo '</div>';
}

function print_thank_you_content() {
    if($title = get_field('title')){
        $copy = get_field('copy');
        echo '<div id="thank-you-content">';
        echo '<div class="content-arrow">';
        echo '<div class="background"></div>';
        echo '<img class="arrow" src="'.get_bloginfo('template_directory').'/images/content-arrow.png" />';
        echo '</div>';
        echo '<div class="thank-you-wrapper">';
        echo '<div class="title fade-in">'.$title.'</div>';
        echo '<div class="copy fade-in">'.$copy.'</div>';
        echo '</div>';
        echo '<div class="content-arrow-up">';
        echo '<img class="arrow" src="'.get_bloginfo('template_directory').'/images/up-arrow.png" />';
        echo '<div class="background"></div>';
        echo '</div>';
        echo '</div>';
    }
}

function print_call_outs() {
    if($ctas = get_field('call_to_actions')) {
        echo '<div id="call-to-actions">';
        // Background Images
        if($backgrounds = get_field('call_to_action_backgrounds')) {
            echo '<div class="cta-bg-wrapper responsive-bg" data-m="'.$backgrounds[0]['cta_image']['sizes']['tablet-cta'].'" data-t="'.$backgrounds[0]['cta_image']['sizes']['tablet-cta'].'">';
            $count = 0;
            foreach ($backgrounds as $background) {
                echo '<div class="background '.(++$count%2 ? "left" : "right") .'"><div class="img-wrapper">';
                echo '<div class="border-top ease"></div>';
                echo '<img class="responsive-img" data-m="'.$background['cta_image']['sizes']['tablet-cta'].'" data-t="'.$background['cta_image']['sizes']['tablet-cta'].'" data-d="'.$background['cta_image']['url'].'" src="" />';
                echo '<div class="border-bottom ease"></div>';
                echo '</div></div>';
            }
            echo '<span class="icon-mint-triangle"></span>';
            // Links
            echo '<div class="cta-link-wrapper fade-in">';
            foreach ($ctas as $cta) {
                echo '<a href="'.$cta['page'].'" class="ease">';
                echo '<div class="cta-button ease">';
                echo '<div class="cta-link ease">';
                echo '<div class="vertical-center-parent">';
                echo '<div class="vertical-center-child">';
                echo '<div class="link">';
                echo $cta['link_text'];
                echo '<span class="icon-mint-triangle ease"></span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</a>';
            }
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
    }
}


function print_homepage_form() {
    if($form_snippet = get_field('form_snippet')) {
        $headline = get_field('form_headline');
        $copy = get_field('form_copy');
        echo '<div id="home-contact-form">';
        echo '<div class="fade-in">';
        echo '<div class="mini-border"></div>';
        echo '<span class="icon-mint-logo"></span>';
        echo '<div class="headline">'.$headline.'</div>';
        echo '<div class="copy">'.$copy.'</div>';
        eval($form_snippet);
        echo '</div>';
        echo '</div>';
    }
}

function print_form() {
    if($form_snippet = get_field('form_snippet')) {
        echo '<div id="contact-form" class="fade-in">';
        eval($form_snippet);
        echo '</div>';
    }
}

function print_fc_gaurantee() {
    if($copy = get_field('copy')) {
        echo '<div id="fc-guarantee-content">';
            echo '<div class="content-wrapper">';


            echo '<div class="copy-wrapper">';
                if($headline = get_field('headline')) {
                    echo '<div class="headline">'.$headline.'</div>';
                }
                if($subheadline = get_field('subheadline')) {
                    echo '<div class="subheadline">'.$subheadline.'</div>';
                }
                echo '<div class="copy">'.$copy.'</div>';

                
                echo '</div>';
                echo '<div class="guarantee-wrapper">';
                     echo '<div class="fc-gurantee-logo"><span class="icon-fc-guarantee"></span></div>';
                echo '<div class="guarantee-headline">The Forest City Guarantee Program</div>';
                    echo '<div class="guarantee-copy">';
                    if($line1 = get_field('line_1')) {
                        echo '<div class="guarantee-copy-1">'.$line1.'</div>';
                    }
                    if($line2 = get_field('line_2')) {
                        echo '<div class="guarantee-copy-2">'.$line2.'</div>';
                    }
                echo '</div>';
                if($link = get_field('link')) {
                echo '<div class="link"><a href="'.$link['url'].'" target="'.$link['target'].'">'.$link['title'].'</a></div>';
            }
            echo '</div>';
            echo '</div>';
        echo '</div>';
    }
}