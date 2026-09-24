<?php
/**
 * Banner Widget Class.
 *
 * @since       1.1.0
 * @package     Basic_Banner
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basic_Banner_Widget' ) ) :

	/**
	 * Class Basic_Banner_Widget
	 */
	class Basic_Banner_Widget extends WP_Widget {

		/**
		 * Construct banner widget.
		 *
		 * @since  1.1.0
		 */
		public function __construct() {
			$widget_options = array(
				'classname'   => 'basic_banner_widget',
				'description' => __( 'Display a banner', 'basic_banner' ),
			);

			parent::__construct( 'basic_banner_widget', __( 'Basic Banner', 'basic_banner' ), $widget_options );
		}

		/**
		 * Output widget.
		 *
		 * @since  1.1.0
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Widget instance.
		 */
		public function widget( $args, $instance ) {
			echo wp_kses_post( $args['before_widget'] );

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			if ( $title ) {
				echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] );
			}

			if ( ! empty( $instance['banner'] ) ) {
				basic_banner_show( $instance['banner'] );
			}

			echo wp_kses_post( $args['after_widget'] );
		}

		/**
		 * Output widget settings form.
		 *
		 * @since  1.1.0
		 *
		 * @param array $instance Widget instance.
		 */
		public function form( $instance ) {
			$title  = isset( $instance['title'] ) ? $instance['title'] : '';
			$banner = isset( $instance['banner'] ) ? $instance['banner'] : '';

			$banners = get_posts(
				array(
					'posts_per_page' => -1,
					'post_type'      => 'banner',
					'orderby'        => 'title',
					'order'          => 'ASC',
				)
			);
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'basic_banner' ); ?></label>
				<input class="widefat title" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<?php if ( ! empty( $banners ) ) : ?>
					<label for="<?php echo esc_attr( $this->get_field_id( 'banner' ) ); ?>"><?php esc_html_e( 'Banner:', 'basic_banner' ); ?></label>
					<select class="widefat banner" id="<?php echo esc_attr( $this->get_field_id( 'banner' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'banner' ) ); ?>">
						<?php foreach ( $banners as $value ) : ?>
							<option value="<?php echo esc_attr( $value->post_name ); ?>" <?php selected( $value->post_name, $banner ); ?>>
								<?php echo esc_html( $value->post_title ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<label><strong><?php esc_html_e( 'Please create the banner first.', 'basic_banner' ); ?></strong></label>
				<?php endif; ?>
			</p>
			<?php
		}

		/**
		 * Update widget settings.
		 *
		 * @since  1.1.0
		 *
		 * @param array $new_instance New widget instance.
		 * @param array $old_instance Old widget instance.
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']  = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
			$instance['banner'] = isset( $new_instance['banner'] ) ? sanitize_text_field( $new_instance['banner'] ) : '';

			return $instance;
		}
	}

endif;
