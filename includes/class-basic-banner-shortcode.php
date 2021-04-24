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
	 * @since  1.1.3 Fix shortcode errors.
	 */
	public function add_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'name' => '',
			'class' => '',
		), $atts );

		if ( $atts['name'] == '' ) {
			return '';
		}

		ob_start();
		basic_banner_show( $atts['name'], $atts['class'] );
		$banner = ob_get_clean();
		return $banner;
	}

}

endif;

new Basic_Banner_Shortcode();
