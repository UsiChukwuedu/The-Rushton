<?php
// Define your theme text domain
define('THEME_TEXTDOMAIN', 'foundation-starter');
define('THEME_URL', get_template_directory_uri());

include('form-data-dashboard.php');

/*************************************************************************************
*  Register Menus
**************************************************************************************/

if ( function_exists( 'register_nav_menus' ) ) {
   register_nav_menus( array(
      'main-nav' => __( 'Primary Navigation', THEME_TEXTDOMAIN ),
      'footer-nav' => __( 'Footer Navigation', THEME_TEXTDOMAIN )
   ) );
}

/*************************************************************************************
*  Register Sidebars
*************************************************************************************/

if ( function_exists('register_sidebar') ) {

   function my_widgets_init() {
      register_sidebar( array(
         'name' => __( 'Main Sidebar', THEME_TEXTDOMAIN ),
         'id' => 'main-sidebar',
         'description' => __( 'This is the main sidebar.', THEME_TEXTDOMAIN ),
         'before_widget' => '<div id="%1$s" class="widget %2$s">',
         'after_widget' => '</div>',
         'before_title' => '<h3>',
         'after_title' => '</h3>',
      ) );
      register_sidebar( array(
         'name' => __( 'Blog Sidebar', THEME_TEXTDOMAIN ),
         'id' => 'blog-sidebar',
         'description' => __( 'This is the blog sidebar.', THEME_TEXTDOMAIN ),
         'before_widget' => '<div id="%1$s" class="widget %2$s">',
         'after_widget' => '</div>',
         'before_title' => '<h3>',
         'after_title' => '</h3>',
      ) );

   }

   add_action( 'widgets_init', 'my_widgets_init' );

}
	
/*************************************************************************************
*  Dashboard Reminder Widget
*************************************************************************************/
add_action('wp_dashboard_setup', 'dashboard_reminder_widget');
  
function dashboard_reminder_widget() {
  global $wp_meta_boxes;
  wp_add_dashboard_widget('custom_dashboard_reminder_widget', 'Theme Support', 'custom_dashboard_reminder');
}
 
function custom_dashboard_reminder() {
  echo '<h1>ATTENTION:</h1>';
  echo '<p>Please add a unique set of <a href="https://api.wordpress.org/secret-key/1.1/salt/" target="_blank">Authentication Unique Keys and Salts</a> to <b>wp-config.php</b> first before starting a new project.</p>';
}

/*************************************************************************************
*  Dashboard QA Items Widget
*************************************************************************************/
add_action('wp_dashboard_setup', 'dashboard_qa_items_widget');
  
function dashboard_qa_items_widget() {
  global $wp_meta_boxes;
  wp_add_dashboard_widget('custom_dashboard_qa_items_widget', 'QA Items', 'custom_dashboard_qa_items');
}
 
function custom_dashboard_qa_items() {
  echo '<p><b>It is good practice to have the following items completed before launching a site:</b></p>';

  // Auth and Salts
  echo '<div><input type="checkbox" name="" id="qa1">';
  echo '<label for="qa1">generate and replace <a href="https://api.wordpress.org/secret-key/1.1/salt/" target="_blank">Authentication Unique Keys and Salts</a></label></div>';

  // WP password
  echo '<div><input type="checkbox" name="" id="qa2">';
  echo '<label for="qa2">change WP password</label></div>';

  // optimize database
  echo '<div><input type="checkbox" name="" id="qa3">';
  echo '<label for="qa3">setup and run Optimize Database after Deleting Revisions, then deacivate</label></div>';

  // reset dashboard
  echo '<br/><small><i><b>Note: </b>Once these items are completed, please clear the dashboard and add the Welcome widget to the dashboard.</i></small>';
}

/*************************************************************************************
 *
 * Function: load_scripts
 * 
 * Purpose: Enqueue Scripts to avoid loading muliple times
 * 
 ************************************************************************************/

function load_scripts(){

  // Deregister the scripts
  wp_deregister_script('jquery');

  // Register Libraries & Helpers
  wp_register_script('modernizr', THEME_URL . '/js/vendor/modernizr.js');
  wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
  wp_register_script('foundation', THEME_URL . '/js/foundation/foundation.min.js', array('modernizr','jquery'), true, true);
  wp_register_script('placeholder', THEME_URL . '/js/vendor/jquery.placeholder.min.js', array('jquery'), true, true);
  //wp_register_script('scrolloverflow', THEME_URL . '/js/vendor/fullpage/scrolloverflow.min.js', array('jquery'), true, true);
  wp_register_script('fullpage', THEME_URL . '/js/vendor/fullpage/jquery.fullpage.min.js', array('jquery'), true, true);
  wp_register_script('slick', THEME_URL . '/js/vendor/slick/slick.min.js', array('jquery'), true, true);
	wp_register_script('swiper', THEME_URL . '/js/vendor/swiper-bundle.min.js', array('jquery'), true, true);
  wp_register_script('lax', THEME_URL . '/js/vendor/lax.min.js', array('jquery'), true, true);
  //wp_register_script('slick-lightbox', THEME_URL . '/js/vendor/slick/slick-lightbox.min.js', array('jquery'), true, true);
      
  // Plugins
  // Only call the fitvids script if videos will be used in sliders
  wp_register_script('myplugins', THEME_URL . '/js/myplugins.js', array('modernizr','jquery'), true, true);
   
  // Scripts
  $myscripts_path = plugin_dir_path( __FILE__ ) . 'js/myscripts.js';
  $myscripts_version = date("Ymd-Gis", filemtime( $myscripts_path ));
  wp_register_script('myscripts', THEME_URL . '/js/myscripts.js', array('modernizr', 'jquery', 'foundation', 'myplugins'), $myscripts_version, true);
  // IE 8 Scripts
  //wp_register_script('myscripts_ie', THEME_URL . '/js/myscripts-ie.js', array('modernizr', 'jquery', 'foundation', 'myplugins'), true, true);
   
  // Enqueue the scripts
  wp_enqueue_script('modernizr');
  wp_enqueue_script('jquery');
  wp_enqueue_script('foundation');
  wp_enqueue_script('placeholder');
//  wp_enqueue_script('lax');
  //wp_enqueue_script('scrolloverflow');
//  wp_enqueue_script('fullpage');
  //wp_enqueue_script('fitvids'); // Only call this script for use with videos in sliders
//  wp_enqueue_script('slick');
  //wp_enqueue_script('slick-lightbox');
  wp_enqueue_script('swiper');	
  wp_enqueue_script('myplugins');
  wp_enqueue_script('myscripts');

  // If IE Support is required - especially for substituting CSS animations
  /*if(preg_match('/(?i)msie [1-9]/',$_SERVER['HTTP_USER_AGENT'])){
    wp_enqueue_script('myscripts_ie');
  }
  else{
    wp_enqueue_script('myscripts');
  }*/

}  

add_action( 'wp_enqueue_scripts', 'load_scripts' );

/*************************************************************************************
 *
 * Function: add_slug_body_class
 * 
 * Purpose: Add page name to body class to overcome different navigations
 * 
 ************************************************************************************/

function add_slug_body_class( $classes ) {
global $post;
if ( isset( $post ) ) {
$classes[] = $post->post_type . '-' . $post->post_name;
}
return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

/*************************************************************************************
 *
 * Function: load_styles
 * 
 * Purpose: Enqueue Styles to avoid loading muliple times
 * 
 ************************************************************************************/

function load_custom_styles() {
   
  // Load stylesheets
  $style_path = plugin_dir_path( __FILE__ ) . 'style.css';
  $style_version = date("Ymd-Gis", filemtime( $style_path ));
  wp_enqueue_style('style', THEME_URL.'/style.css?', array(), $style_version);// default styles

}

add_action( 'wp_enqueue_scripts', 'load_custom_styles', 2 );

/*************************************************************************************
 *
 * Function: head_cleanup
 * 
 * Purpose: Make WordPress a little more secure and a lot cleaner by removing a few 
 *          links in the <head>
 * 
 ************************************************************************************/

function head_cleanup() {

   remove_action('wp_head', 'rsd_link');
   remove_action('wp_head', 'wp_generator'); //removes WP Version # for security
   remove_action('wp_head', 'feed_links', 2);
   remove_action('wp_head', 'index_rel_link');
   remove_action('wp_head', 'wlwmanifest_link');
   remove_action('wp_head', 'feed_links_extra', 3);
   remove_action('wp_head', 'start_post_rel_link', 10, 0);
   remove_action('wp_head', 'parent_post_rel_link', 10, 0);
   remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
   remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
   remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
   remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
   remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
}

add_action('init', 'head_cleanup');

/*************************************************************************************
* Theme Setup
*************************************************************************************/

// Add support for featured images
add_theme_support( 'post-thumbnails' );

// Set the default attachments 'link to' option to 'None'
function my_attachments_options() {
    update_option('image_default_link_type', 'none' );
}
add_action('after_setup_theme', 'my_attachments_options');

if ( ! isset( $content_width ) ) {
  $content_width = 1000;
}

// Excerpt Read more link
function new_excerpt_more($more) {
    global $post;
    return '... <a class="read_more" title="' . get_the_title($post->ID) . '" href="'. get_permalink($post->ID) . '">Read more</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

// Add support for SVG in media
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

/*-------------------------------------------------------------------------------------------*/
/* Custom Post Type */
/*-------------------------------------------------------------------------------------------*/
/*
add_action( 'init', 'register_customs' );

function register_customs() {
  register_post_type( 'customs',
    array(
      'labels' => array(
        'name' => _x( 'Customs', 'customs' ),
        'singular_name' => _x( 'Custom', 'customs' ),
        'add_new' => _x( 'Add New', 'customs' ),
        'add_new_item' => _x( 'Add New Custom', 'customs' ),
        'edit_item' => _x( 'Edit Custom', 'customs' ),
        'new_item' => _x( 'New Custom', 'customs' ),
        'view_item' => _x( 'View Custom', 'customs' ),
        'search_items' => _x( 'Search Customs', 'customs' ),
        'not_found' => _x( 'No collection found', 'customs' ),
        'not_found_in_trash' => _x( 'No collection found in Trash', 'customs' ),
        'parent_item_colon' => _x( 'Parent collection:', 'customs' ),
        'menu_name' => _x( 'Customs', 'customs' ),
      ),
      'supports' => array(
        'title',
        'editor',
        'page-attributes'
      ),
      'public' => true,
      'has_archive' => true,
      'hierarchical' => true
    )
  );
}

*/

/*-------------------------------------------------------------------------------------------*/
/* Custom Post Type Category */
/*-------------------------------------------------------------------------------------------*/
/*

add_action( 'init', 'register_custom_category' );

function register_custom_category() {

  $labels = array(
    'name'                       => _x( 'Custom Categories', 'Taxonomy General Name', 'text_domain' ),
    'singular_name'              => _x( 'Custom Category', 'Taxonomy Singular Name', 'text_domain' ),
    'menu_name'                  => __( 'Custom Categories', 'text_domain' ),
    'all_items'                  => __( 'All Items', 'text_domain' ),
    'parent_item'                => __( 'Parent Item', 'text_domain' ),
    'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
    'new_item_name'              => __( 'New Item Name', 'text_domain' ),
    'add_new_item'               => __( 'Add New Item', 'text_domain' ),
    'edit_item'                  => __( 'Edit Item', 'text_domain' ),
    'update_item'                => __( 'Update Item', 'text_domain' ),
    'view_item'                  => __( 'View Item', 'text_domain' ),
    'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
    'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
    'popular_items'              => __( 'Popular Items', 'text_domain' ),
    'search_items'               => __( 'Search Items', 'text_domain' ),
    'not_found'                  => __( 'Not Found', 'text_domain' ),
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
  );
  register_taxonomy( 'custom-category', array( 'custom' ), $args );
}
*/

/*************************************************************************************
* Custom login logo, url & title
*************************************************************************************/
/*function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo THEME_URL; ?>/img/logo.png);
            width: 200px;
            height: 133px;
            background-size: 200px 133px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );*/

function displayTrackingCode() {
  if( have_rows('exclude_ip_addresses','option') ):
      while ( have_rows('exclude_ip_addresses','option') ) : the_row();
          if (trim(get_sub_field('ip_address')) == $_SERVER['REMOTE_ADDR']) {
            reset_rows();
            return false;
          }
      endwhile;
      reset_rows();
  endif;  

  return true;
}

if(function_exists('have_rows')):
  acf_add_options_sub_page(array(
    'title'     => __('Options','acf'),
    'menu'      => __('Options','acf'),
    'slug'      => 'acf-options',
    'capability'  => 'edit_posts',
    'pages'     => array(),
  ));

  acf_add_options_sub_page(array(
    'title'     => __('Analytics','acf'),
    'menu'      => __('Analytics','acf'),
    'slug'      => 'acf-analytics',
    'capability'  => 'edit_posts',
    'pages'     => array(),
  ));

  acf_add_options_sub_page(array(
    'title'     => __('Social Media','acf'),
    'menu'      => __('Social Media','acf'),
    'slug'      => 'acf-social-media',
    'capability'  => 'edit_posts',
    'pages'     => array(),
  ));

  acf_add_options_sub_page(array(
    'title'     => __('Instagram','acf'),
    'menu'      => __('Instagram','acf'),
    'slug'      => 'acf-instagram',
    'capability'  => 'edit_posts',
    'pages'     => array(),
  ));
endif;

/*************************************************************************************
* Load More Posts
*************************************************************************************/
wp_localize_script( 'myscripts', 'ajaxpagination', array(
	'ajaxurl' => admin_url( 'admin-ajax.php' )
));

function more_posts_cat(){

    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 6;
    $page = $_POST['page'];
    $cat = $_POST['cat'];

    header("Content-Type: text/html");

    $args = array(
        'suppress_filters' => true,
        'post_type' => 'post',
        'posts_per_page' => $ppp,
        'paged'=> $page,
        'cat'=> $cat
    );

    $loop = new WP_Query($args);
   

    if ($loop -> have_posts()) :  while ($loop -> have_posts()) : $loop -> the_post();
    
        //Grab variables 
        $link = get_permalink();
        $extlink = get_field('link'); 
        $title = get_the_title();
        $date = get_the_date('M j, Y');
        $bg = get_the_post_thumbnail_url();
    
        $out .= '<article class="medium-4 medium-small-6 columns col post type-post status-publish format-standard has-post-thumbnail hentry category-news"><a href="'. $link .'"><div class="bg" style="background-image:url('. $bg .');"></div><div class="info"><h3>'. $title .'</h3><span>'. $date .'</span></div></a></article>';

    endwhile;
    endif;
    wp_reset_postdata();
    die($out);
}

add_action('wp_ajax_nopriv_more_posts_cat', 'more_posts_cat');
add_action('wp_ajax_more_posts_cat', 'more_posts_cat');