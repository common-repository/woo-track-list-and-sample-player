<?php
/**
 * track list and audio file sampler player for WooCommerce album products
 *
 * @author 	Dean Walker
 * @package 	track-list-and-sample-player
 * @version     1.0.2
 */
/*
Plugin Name: Woo Track List and Sample Player
Description: Displays the track list for an album on the WooCommerce products page in the product short description and an audio player for playing a sample of the album track
Author: Dean Walker
Version: 1.0.2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define('WOO_TLASP_PLUGIN_VERSION', '1.0.1');
define('WOO_TLASP_BASE_URL', plugins_url('/', __FILE__));
define('WOO_TLASP_SAMPLE_FILE_PREFIX', 'sample-');
define('WOO_TLASP_DOWNLOAD_SUFFIX', '- Digital Download');
define('WOO_TLASP_PHYSICAL_SUFFIX', '- CD');

define('WOO_TLASP_OPTION_DISPLAY_BEFORE_CART_BUTTON', true);

add_action('init', 'woo_tlasp_init');

function woo_tlasp_enqueue_scripts() {
	wp_enqueue_style( 'woo-tlasp-style', 
  		plugin_dir_url( __FILE__ ) . '/css/woo-tlasp.css',
  		array( 'wp-mediaelement', 'mediaelement' ) );
}

add_action( 'wp_enqueue_scripts', 'woo_tlasp_enqueue_scripts' );


include_once ('includes/add-sample.php');
if (WOO_TLASP_OPTION_DISPLAY_BEFORE_CART_BUTTON) {
	add_filter( 'woocommerce_short_description', 'woo_tlasp_list_product_files');
} else {
	add_filter('woocommerce_product_meta_start' ,'woo_tlasp_list_product_files');
}

function woo_tlasp_init() {
}


?>
