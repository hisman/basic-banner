<?php

/**
 * Banner Shortcode Class.
 *
 * @since       1.1.0
 * @package     Basic_Banner
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basic_Banner_Shortcode' ) ) :

class Basic_Banner_Shortcode {

	/**
	 * Construct banner shortcode.
	 *
	 * @since  1.1.0
	 */
	public function __construct() {
		add_shortcode( 'basicbanner', array( $this, 'add_shortcode' ) );

	}

	/**
	 * Add banner shortcode.
	 *
	 * @since  1.1.0
	 */
	public function add_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'name' => '',
			'class' => '',
		), $atts );

		if ( $atts['name'] == '' ) {
			return '';
		}

		return basic_banner_show( $atts['name'], $atts['class'] );
	}

}

endif;

new Basic_Banner_Shortcode();
