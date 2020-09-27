<?php

/*
Plugin Name: Land Lister
Plugin URI:
Description: The plugin for Realtors and Property Investors that allows you to create and list properties.
Version: 1.0
Author: Sam Comstock
Author URI: https://sam-comstock.com
License: GPL2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html 
Text Domain: land-lister
*/

/* !0. TABLE OF CONTENTS */

/* 1. HOOKS */

//Add Property Custom Post Type
add_action('init', 'll_property_post_types');
add_action( 'init', 'll_register_shortcodes');
add_action('wp_enqueue_scripts', 'll_enqueue_js');

//Include ACF Plugin ver 5.9.1 with Land Lister Plugin (see Misc. functions)
define('LL_ACF_PATH', plugin_dir_path(__FILE__) . '/lib/advanced-custom-fields/');
define('LL_ACF_URL', plugin_dir_url(__FILE__) . '/lib/advanced-custom-fields/');
include_once(LL_ACF_PATH . 'acf.php');
add_filter('acf/settings/url', 'll_acf_settings_url');
add_filter('acf/settings/show_admin', 'll_acf_show_admin');


/* 2. SHORTCODES */

//Register shortcodes
function ll_register_shortcodes() {
  add_shortcode('ll_properties', 'll_properties');
  add_shortcode('ll_properties_archive', 'll_properties_archive');
}

//Shortcode to return properties based on criteria
function ll_properties( $atts ) {
  
  global $post;

  //Use $atts to assign a value to variables or set to NULL if none is given
  $state = (isset($atts['state'])) ? $atts['state'] : NULL;
  $county = (isset($atts['county'])) ? $atts['county'] : NULL;
  $min = (isset($atts['min'])) ? $atts['min'] : NULL;
  $max = (isset($atts['max'])) ? $atts['max'] : NULL;
  
  $args = ll_build_args( $state, $county, $min, $max );

  //Assign $ll_query to the WP_query that is performed according to criteria above
  $ll_query = new WP_Query( $args );

  //Print Query
  ob_start();
  if($ll_query->have_posts()) : 
    while($ll_query->have_posts()) : $ll_query->the_post();
      include plugin_dir_path(__FILE__) . '/templates/ll-properties-shortcode.php';
    endwhile;
  endif;
  return ob_get_clean();
  wp_reset_postdata();
}

//Shortcode to show the properties archive page content
function ll_properties_archive() {
  ob_start();
    include plugin_dir_path(__FILE__) . '/templates/ll-archive-properties-shortcode.php';
  return ob_get_clean();
}


/* 3. FILTERS */

//Use the property single post template for the single post page
add_filter('single_template', 'll_single_property_template');

function ll_single_property_template( $single_template ) {

  global $post;

  if ($post->post_type == 'property') {
    $single_template = dirname(__FILE__) . '/templates/ll-single-property.php';
  }
  return $single_template;
}

//Use the property archive template for the archive page
add_filter('archive_template', 'll_archive_property_template');

function ll_archive_property_template( $archive_template ) {

  global $post;
  
  if ($post->post_type == 'property') {
    $archive_template = dirname(__FILE__) . '/templates/ll-archive-property.php';
  }
  return $archive_template;
}


/* 4. EXTERNAL SCRIPTS AND STYLE */

//Add custom css for plugin
wp_register_style('ll-custom-style', plugins_url('style/style.css', __FILE__));
wp_enqueue_style( ('ll-custom-style'));

//Add custom javascript for plugin
function ll_enqueue_js() {
  wp_enqueue_script('ll-js', plugins_url('js/land-lister.js', __FILE__), [], false, true);
}
    

/* 5. ACTIONS */
    

/* 6. HELPERS */

//Build the WP_Query Argument based on input
function ll_build_args($state, $acres, $min, $max) {

  //WP_query arguments
  $args = array(
    'post_type' => 'property',
    'posts_per_page' => -1,
    'orderby' => 'published',
    'meta_query' => array(
      'relation' => 'AND',
    ),
  );

  //Set state to 'Browse All' if not set
  !isset($state) ? $state = 'Browse All' : $state;

  //Push the appropriate meta_query to $args based on what arguments are supplied
  if($state !== 'Browse All') {
    $args['meta_query'][] = array('key' => 'state', 'value' => $state, 'compare' => '=');
  }
    
  if(isset($acres ) && $acres ) {
      $args['meta_query'][] = array('key' => 'acres', 'value' => $acres , 'compare' => '>=', 'type' => 'numeric');
  }

  if (isset($min) && $min) {
    $args['meta_query'][] = array('key' => 'price', 'value' => $min , 'compare' => '>=', 'type' => 'numeric');
  }

  if (isset($max ) && $max) {
    $args['meta_query'][] = array('key' => 'price', 'value' => $max , 'compare' => '<=', 'type' => 'numeric');
  }

  return $args;

}


/* 7. CUSTOM POST TYPES */

function ll_property_post_types() {
  register_post_type('property', array(
    'rewrite' => array('slug' => 'properties'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'Property',
      'add_new_item' => 'Add New Property Listing',
      'edit_item' => 'Edit Property Listing',
      'all_items' => 'All Properties',
      'singular_name' => 'Property'
    ),
    'menu_icon' => 'dashicons-location-alt'
  ));
}
	
/* 8. ADMIN PAGES */
  

/* 9. SETTINGS */
  

/* 10. MISCELLANEOUS  */

//Move the Yoast Plugin to be below the Property Fields
function wpcover_move_yoast() {
  return 'low';
}
add_filter( 'wpseo_metabox_prio', 'wpcover_move_yoast');

//Include ACF Plugin ver 5.9.1 with Land Lister Plugin
function ll_acf_settings_url( $url ) {
  return LL_ACF_URL;
}

//Only show ACF admin menu if installed as a stand alone plugin
$ll_show_acf_admin = false;

if (class_exists('ACF')) {
  $ll_show_acf_admin = true;
}

function ll_acf_show_admin( $show_admin ) {
  global $ll_show_acf_admin;
  return $ll_show_acf_admin;
}

//Print warning if installed ACF version is earlier than 5.9.1
add_action('views_edit-property', 'll_older_acf_warning');

function ll_older_acf_warning( $views ) {
  
  global $acf;

  $acf_ver = (float) $acf->settings['version'];
  $acf_ver_req = 5.9;

  if( $acf_ver < $acf_ver_req ) {
    echo '<p style="color: red";>
      <strong>You\'re using an older version of Advanced Custom Fields Plugin. Version: ('. $acf_ver .')<br>
      Some features of Land Lister may not work unless you pdate or deactivate this plugin</strong>
    </p>';
  }

  return $views;
}
