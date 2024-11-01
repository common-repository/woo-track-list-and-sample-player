<?php
/**
 * track list and audio file sampler player for WooCommerce album products
 *
 * @author 	Dean Walker
 * @package 	woo-track-list-and-sample-player/includes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** list download files */
function woo_tlasp_list_product_files( $post_excerpt ) {

	echo $post_excerpt;

	global $post;
	global $product;

	if ( $product && $product->exists() ) {
		if ( !$product->is_downloadable() ) {
			// find the equivalent downloaded version
			$download_files = woo_tlasp_get_digital_product($product->get_title());
		} else {
			$download_files = $product->get_downloads();
		}

		if ($download_files) {
			foreach ( $download_files as $download_id => $file ) {
			woo_tlasp_do_product_file($file['file'], $file['name']);
			}
		}		
	}
}
//http://alexkatz.me/posts/building-a-custom-html5-audio-player-with-javascript/
			//foreach ( $download_files as $id => $file ) {
			//	$sample_url = woo_tlasp_get_sample_url( $file['file'] );
			//	if ($sample_url) {
			//		echo '<audio id="'.$file['name'].'"><source type="audio/mp3" src="' . $sample_url . '"/></audio>';
			//	}
			//}
		
function woo_tlasp_do_product_file($file, $name) {
	echo '<div class="woo-tlasp">';
	$sample_url = woo_tlasp_get_sample_url( $file );
	if ($sample_url) {
		$url = esc_url($sample_url);
		$player = '[audio src="' . $sample_url . '" controls="false"]';
		echo do_shortcode($player);
	} else {
	}
	echo '<div class="woo-tlasp-file-name">' . esc_html($name) . '</div>';
	echo '</div>';
}


/**
 * Return an ID of sample audio file for a given full audio file.
 *
 * For a given audio file, this methos searchs for a matching 'sample' audio file. It assumes that the
 * sample is of the same file type (eg .mp3) and that the sample has a matching file name but proceeded by
 * a prefix. So the sample for the audio file 'mysong.mp3' would be 'sample-mysong.mp3' if using the default
 * prefix
 * 
 * With Thanks: http://frankiejarrett.com/2013/05/get-an-attachment-id-by-url-in-wordpress/
 *
 * @param string $url The URL of the file whose sample you require
 * 
 * @return int|null $attachment Returns an attachment ID, or null if no attachment is found
 */
function woo_tlasp_get_sample_url( $url ) {
	// get the last past of the URL, e.g the test-music.mp3
	$name = substr(strrchr($url, '/'), 1);

	$sample_name  = '/' . WOO_TLASP_SAMPLE_FILE_PREFIX . $name; 
	// Now we're going to quickly search the DB for any attachment GUID with a match
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT guid FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $sample_name ) );
	// Returns null if no attachment is found
	return $attachment[0];
}

function woo_tlasp_get_digital_product($physical_product_title) {
	$download_product_title = str_ireplace (WOO_TLASP_PHYSICAL_SUFFIX, WOO_TLASP_DOWNLOAD_SUFFIX, $physical_product_title) ;
	// Now we're going to quickly search the DB for any attachment GUID with a match
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_title RLIKE %s;", $download_product_title ) );

	// Returns null if no post is found
	if ($attachment[0]) {
		return get_post_meta($attachment[0], '_downloadable_files', true);
	}
	return null;
}
?>
