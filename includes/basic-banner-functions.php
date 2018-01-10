<?php
/**
 * Basic_Banner Functions
 *
 * General functions available on both the front-end and admin.
 *
 * @since       1.0.0
 * @package 	Basic_Banner
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get template part and pass variables.
 *
 * @since    1.0.0
 */
function basic_banner_get_template( $template_name, $args = array(), $template_path = 'basic-banner' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$template = locate_template( trailingslashit( $template_path ) . $template_name );

	if ( $template == '' ) {
		$template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/' . $template_name;
	}

	include( $template );
}

/**
 * Get banner object.
 *
 * @since    1.0.0
 */
function basic_banner_get( $name ) {
	$banner = new Basic_Banner_Model( $name );

	if ( $banner->error ) {
		return false;
	}

	return $banner;
}

/**
 * Show banner markup.
 *
 * @since    1.0.0
 * @since    1.1.0 Custom html classes for banner container
 */
function basic_banner_show( $name, $class = '' ) {
	$banner = basic_banner_get( $name );

	basic_banner_get_template( 'banner.php', array( 'banner' => $banner, 'class' => $class ) );
}
