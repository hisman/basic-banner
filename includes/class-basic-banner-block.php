<?php
/**
 * Banner Block Class.
 *
 * Registers the Basic Banner Gutenberg block.
 *
 * @since       1.2.0
 * @package     Basic_Banner
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basic_Banner_Block' ) ) :

	/**
	 * Class Basic_Banner_Block
	 */
	class Basic_Banner_Block {

		/**
		 * Construct banner block.
		 *
		 * @since  1.2.0
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_block' ) );
		}

		/**
		 * Register banner block from metadata.
		 *
		 * @since  1.2.0
		 */
		public function register_block() {
			if ( ! function_exists( 'register_block_type_from_metadata' ) ) {
				return;
			}

			register_block_type_from_metadata(
				plugin_dir_path( __DIR__ ) . 'src/banner',
				array(
					'render_callback' => array( $this, 'render_block' ),
				)
			);
		}

		/**
		 * Render banner block on the frontend.
		 *
		 * @since  1.2.0
		 *
		 * @param  array $attributes Block attributes.
		 * @return string             Rendered block output.
		 */
		public function render_block( $attributes ) {
			$attributes = wp_parse_args(
				$attributes,
				array(
					'name'      => '',
					'class'     => '',
					'className' => '',
					'align'     => '',
				)
			);

			if ( empty( $attributes['name'] ) ) {
				return '';
			}

			// Merge custom class, editor-added class, and alignment into one class list.
			$classes = array_filter( array( $attributes['class'], $attributes['className'], $attributes['align'] ? 'align' . $attributes['align'] : '' ) );

			ob_start();
			basic_banner_show( $attributes['name'], trim( implode( ' ', $classes ) ) );

			return ob_get_clean();
		}
	}

endif;

new Basic_Banner_Block();
