<?php

/**
 * Plugin Name:       Basic Banner
 * Plugin URI:        https://github.com/hisman/basic-banner
 * Description:       Allows you to create and display banners in WordPress.
 * Version:           1.0.0
 * Author:            Hisman
 * Author URI:        https://hisman.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       basic-banner
 * Domain Path:       /languages
 *
 * @since             1.0.0
 * @package           Basic_Banner
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basic_Banner' ) ) :

class Basic_Banner {

	/**
	 * Basic_Banner Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @since  1.0.0
	 */
	private function includes() {
		include_once( 'includes/class-basic-banner-model.php' );
		include_once( 'includes/basic-banner-functions.php' );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'custom_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'do_meta_boxes', array( $this, 'change_featured_image' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Register custom post type.
	 *
	 * @since  1.0.0
	 */
	public function custom_post_type() {
		register_post_type( 'banner', array(
			'labels' => array(
				'name' => __( 'Banners', 'basic_banner' ),
				'singular_name' => __( 'Banner', 'basic_banner' ),
				'add_new_item' => __( 'Add New Banner', 'basic_banner' ),
				'edit_item' => __( 'Edit Banner', 'basic_banner' ),
				'new_item' => __( 'New Banner', 'basic_banner' ),
				'all_items' => __( 'All Banners', 'basic_banner' ),
			),
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'menu_icon' => 'dashicons-format-image',
			'supports' => array( 'title', 'thumbnail' ),
			'capability_type' => 'post',
			'menu_position' => 20,
		) );
	}

	/**
	 * Add meta boxes.
	 *
	 * @since    1.0.0
	 */
	public function add_meta_boxes() {
		add_meta_box( 'banner_url', __( 'Banner URL', 'basic_banner' ), array( $this, 'url_meta_box' ), 'banner', 'normal', 'low' );
		add_meta_box( 'banner_caption', __( 'Banner Caption', 'basic_banner' ), array( $this, 'caption_meta_box' ), 'banner', 'normal', 'low' );
	}

	/**
	 * Banner URL meta box
	 *
	 * @since    1.0.0
	 */
	public function url_meta_box() {
		global $post;

		$custom_fields = get_post_custom( $post->ID );
		$banner_url = $custom_fields['banner_url'][0];
		?>

		<p><input type="text" style="width: 100%" name="banner_url" value="<?php echo esc_url( $banner_url ); ?>"></p>

		<?php
	}

	/**
	 * Banner Caption meta box
	 *
	 * @since    1.0.0
	 */
	public function caption_meta_box() {
		global $post;

		$custom_fields = get_post_custom( $post->ID );
		$banner_caption = $custom_fields['banner_caption'][0];
		?>

		<p><input type="text" style="width: 100%" name="banner_caption" value="<?php echo esc_attr( $banner_caption ); ?>"></p>

		<?php
	}

	/**
	 * Change featured image meta box.
	 *
	 * @since    1.0.0
	 */
	public function change_featured_image() {
		remove_meta_box( 'postimagediv', 'banner', 'side' );
		add_meta_box( 'postimagediv', __( 'Banner Image', 'basic_banner' ), 'post_thumbnail_meta_box', 'banner', 'normal', 'high' );
	}

	/**
	 * Save meta boxes.
	 *
	 * @since    1.0.0
	 */
	public function save_meta_boxes( $post_id, $post ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( $post->post_type !== 'banner' ) {
			return $post_id;
		}

		if ( isset( $_POST['banner_url'] ) ) {
			$banner_url = esc_url_raw( $_POST['banner_url'] );
			update_post_meta( $post_id, 'banner_url', $banner_url );
		}

		if ( isset( $_POST['banner_caption'] ) ) {
			$banner_caption = sanitize_text_field( $_POST['banner_caption'] );
			update_post_meta( $post_id, 'banner_caption', $banner_caption );
		}
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'basic-banner', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

}

endif;

new Basic_Banner();
