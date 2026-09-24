<?php
/**
 * Plugin Name:       Basic Banner
 * Plugin URI:        https://github.com/hisman/basic-banner
 * Description:       Allows you to create and display banners in WordPress.
 * Version:           1.2.0
 * Author:            Hisman
 * Author URI:        https://hisman.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       basic_banner
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

	/**
	 * Main Basic Banner Class.
	 *
	 * @since  1.0.0
	 */
	class Basic_Banner {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		public $version = '1.2.0';

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
		 * @since 1.0.0
		 * @since 1.1.0 Add widget & shortcode.
		 * @since 1.2.0 Add Gutenberg block.
		 */
		private function includes() {
			include_once 'includes/class-basic-banner-model.php';
			include_once 'includes/basic-banner-functions.php';
			include_once 'includes/class-basic-banner-widget.php';
			include_once 'includes/class-basic-banner-shortcode.php';
			include_once 'includes/class-basic-banner-block.php';
		}

		/**
		 * Hook into actions and filters.
		 *
		 * @since 1.0.0
		 * @since 1.1.0 Add custom columns for banner, register widget.
		 * @since 1.1.1 Change banner image size in admin to full size.
		 */
		private function init_hooks() {
			add_action( 'init', array( $this, 'custom_post_type' ) );

			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
			add_action( 'do_meta_boxes', array( $this, 'change_featured_image' ) );
			add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );

			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

			add_filter( 'manage_banner_posts_columns', array( $this, 'banner_columns' ) );
			add_filter( 'manage_edit-banner_sortable_columns', array( $this, 'banner_sortable_columns' ) );
			add_action( 'manage_banner_posts_custom_column', array( $this, 'render_banner_columns' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_styles' ) );

			add_action( 'widgets_init', array( $this, 'register_widget' ) );

			add_filter( 'admin_post_thumbnail_size', array( $this, 'admin_post_thumbnail_size' ), 10, 3 );
		}

		/**
		 * Register custom post type.
		 *
		 * @since  1.0.0
		 * @since  1.2.0 Enable REST API access for the block editor.
		 */
		public function custom_post_type() {
			register_post_type(
				'banner',
				array(
					'labels'              => array(
						'name'          => __( 'Banners', 'basic_banner' ),
						'singular_name' => __( 'Banner', 'basic_banner' ),
						'add_new_item'  => __( 'Add New Banner', 'basic_banner' ),
						'edit_item'     => __( 'Edit Banner', 'basic_banner' ),
						'new_item'      => __( 'New Banner', 'basic_banner' ),
						'all_items'     => __( 'All Banners', 'basic_banner' ),
					),
					'public'              => true,
					'exclude_from_search' => true,
					'publicly_queryable'  => false,
					'show_in_rest'        => true,
					'rest_base'           => 'basic-banner-banners',
					'menu_icon'           => 'dashicons-format-image',
					'supports'            => array( 'title', 'thumbnail' ),
					'capability_type'     => 'post',
					'menu_position'       => 20,
				)
			);
		}

		/**
		 * Add meta boxes.
		 *
		 * @since  1.0.0
		 */
		public function add_meta_boxes() {
			add_meta_box( 'banner_url', __( 'Banner URL', 'basic_banner' ), array( $this, 'url_meta_box' ), 'banner', 'normal', 'low' );
			add_meta_box( 'banner_caption', __( 'Banner Caption', 'basic_banner' ), array( $this, 'caption_meta_box' ), 'banner', 'normal', 'low' );
		}

		/**
		 * Banner URL meta box
		 *
		 * @since  1.0.0
		 * @since  1.1.1 Fix undefined index when creating a new banner.
		 */
		public function url_meta_box() {
			global $post;

			$custom_fields = get_post_custom( $post->ID );
			$banner_url    = ( array_key_exists( 'banner_url', $custom_fields ) ) ? $custom_fields['banner_url'][0] : '';

			wp_nonce_field( 'basic_banner_save_meta_boxes', 'basic_banner_meta_nonce' );
			?>

			<p><input type="text" style="width: 100%" name="banner_url" value="<?php echo esc_url( $banner_url ); ?>"></p>

			<?php
		}

		/**
		 * Banner Caption meta box
		 *
		 * @since  1.0.0
		 * @since  1.1.1 Fix undefined index when creating a new banner.
		 */
		public function caption_meta_box() {
			global $post;

			$custom_fields  = get_post_custom( $post->ID );
			$banner_caption = ( array_key_exists( 'banner_caption', $custom_fields ) ) ? $custom_fields['banner_caption'][0] : '';
			?>

			<p><input type="text" style="width: 100%" name="banner_caption" value="<?php echo esc_attr( $banner_caption ); ?>"></p>

			<?php
		}

		/**
		 * Change featured image meta box.
		 *
		 * @since  1.0.0
		 */
		public function change_featured_image() {
			remove_meta_box( 'postimagediv', 'banner', 'side' );
			add_meta_box( 'postimagediv', __( 'Banner Image', 'basic_banner' ), 'post_thumbnail_meta_box', 'banner', 'normal', 'high' );
		}

		/**
		 * Save meta boxes.
		 *
		 * @since  1.0.0
		 *
		 * @param int     $post_id Post ID.
		 * @param WP_Post $post   Post object.
		 */
		public function save_meta_boxes( $post_id, $post ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			if ( 'banner' !== $post->post_type ) {
				return $post_id;
			}

			if ( ! isset( $_POST['basic_banner_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['basic_banner_meta_nonce'] ), 'basic_banner_save_meta_boxes' ) ) {
				return $post_id;
			}

			if ( isset( $_POST['banner_url'] ) ) {
				$banner_url = esc_url_raw( wp_unslash( $_POST['banner_url'] ) );
				update_post_meta( $post_id, 'banner_url', $banner_url );
			}

			if ( isset( $_POST['banner_caption'] ) ) {
				$banner_caption = sanitize_text_field( wp_unslash( $_POST['banner_caption'] ) );
				update_post_meta( $post_id, 'banner_caption', $banner_caption );
			}
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since  1.0.0
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'basic_banner', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Define custom columns for banner.
		 *
		 * @since  1.1.0
		 *
		 * @param array $existing_columns Existing columns.
		 * @return array
		 */
		public function banner_columns( $existing_columns ) {
			$columns = array(
				'cb'    => '<input type="checkbox" />',
				'thumb' => '<span class="dashicons-before dashicons-format-image basic-banner-icon"></span>',
				'title' => __( 'Title', 'basic_banner' ),
				'name'  => __( 'Name', 'basic_banner' ),
			);

			return array_merge( $columns, $existing_columns );
		}

		/**
		 * Make custom columns for banner sortable
		 *
		 * @since  1.1.0
		 *
		 * @param array $columns Columns.
		 * @return array
		 */
		public function banner_sortable_columns( $columns ) {
			$custom = array(
				'name' => 'post_name',
			);
			return wp_parse_args( $custom, $columns );
		}

		/**
		 * Output custom columns for banner.
		 *
		 * @since  1.1.0
		 *
		 * @param string $column Column name.
		 */
		public function render_banner_columns( $column ) {
			global $post;

			switch ( $column ) {
				case 'thumb':
					echo '<a href="' . esc_url( get_edit_post_link( $post->ID ) ) . '">' . get_the_post_thumbnail( $post->ID, 'thumbnail' ) . '</a>';
					break;
				case 'name':
					echo esc_html( $post->post_name );
					break;
			}
		}

		/**
		 * Get the plugin url.
		 *
		 * @since 1.1.0
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Load admin scripts.
		 *
		 * @since 1.1.0
		 */
		public function load_admin_scripts() {
			$screen    = get_current_screen();
			$screen_id = !empty( $screen ) ? $screen->id : '';

			$banner_screens = array(
				'banner',
				'edit-banner',
			);

			if ( in_array( $screen_id, $banner_screens, true ) ) {
				wp_register_style( 'basic_banner_admin_styles', $this->plugin_url() . '/assets/build/admin.css', array(), $this->version );
				wp_style_add_data( 'basic_banner_admin_styles', 'rtl', 'replace' );
				wp_enqueue_style( 'basic_banner_admin_styles' );
			}
		}

		/**
		 * Register frontend banner styles.
		 *
		 * Registered here but enqueued on demand from basic_banner_show(), so the
		 * stylesheet loads for every banner output path (block, shortcode, widget).
		 *
		 * @since 1.2.0
		 */
		public function register_frontend_styles() {
			wp_register_style( 'basic_banner_styles', $this->plugin_url() . '/assets/build/style.css', array(), $this->version );
			wp_style_add_data( 'basic_banner_styles', 'rtl', 'replace' );
		}

		/**
		 * Register widget.
		 *
		 * @since 1.1.0
		 */
		public function register_widget() {
			register_widget( 'Basic_Banner_Widget' );
		}

		/**
		 * Change banner image size in admin to full size.
		 *
		 * @since  1.1.1
		 *
		 * @param string|array $size         Image size.
		 * @param int          $thumbnail_id Thumbnail attachment ID.
		 * @param WP_Post|null $post         Post object.
		 * @return string|array
		 */
		public function admin_post_thumbnail_size( $size, $thumbnail_id, $post ) {
			if ( $post && 'banner' === $post->post_type ) {
				return 'full';
			}

			return $size;
		}
	}

endif;

new Basic_Banner();
