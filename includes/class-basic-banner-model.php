<?php

/**
 * Banner Model Class.
 *
 * @since       1.0.0
 * @package     Basic_Banner
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basic_Banner_Model' ) ) :

class Basic_Banner_Model {

	/**
	 * Construct banner object.
	 *
	 * @since    1.0.0
	 * @since    1.1.2 Fix undefined property error.
	 */
	public function __construct( $name ) {
		$banner_post = get_posts( array(
			'name' => $name,
			'post_type' => 'banner',
			'post_status' => 'publish',
			'numberposts' => 1,
		) );


		if ( empty( $banner_post ) ) {
			$this->error = true;
		}else {
			$this->error = false;
			$this->post = $banner_post[0];
			$this->id = $this->post->ID;
			$this->name = $this->post->post_name;
			$this->title = $this->post->post_title;
			$this->caption = get_post_meta( $this->post->ID, 'banner_caption', true );
			$this->url = get_post_meta( $this->post->ID, 'banner_url', true );
		}
	}

}

endif;
