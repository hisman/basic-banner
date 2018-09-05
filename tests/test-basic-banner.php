<?php
/**
 * Class Test_Basic_banner
 *
 * @package Basic_Banner
 */

class Test_Basic_banner extends WP_UnitTestCase {

	public function setUp() {
        parent::setUp();

        $post_id = $this->factory->post->create( array(
			'post_name' => 'test',
			'post_title' => 'Test',
			'post_type' => 'banner',
			'post_status' => 'publish',
		) );

		update_post_meta( $post_id, 'banner_url', '#' );
		update_post_meta( $post_id, 'banner_caption', 'Caption' );

		$this->basic_banner = new Basic_Banner();
    }

	/**
	 * Test basic_banner_get() function.
	 */
	public function test_basic_banner_get() {
		$banner = basic_banner_get( 'test' );

		$this->assertEquals( 'test', $banner->name );
		$this->assertEquals( 'Test', $banner->title );
		$this->assertEquals( '#', $banner->url );
		$this->assertEquals( 'Caption', $banner->caption );
	}

	/**
	 * Test basic_banner_get() function with no banner found.
	 */
	public function test_basic_banner_get_not_found() {
		$banner = basic_banner_get( 'nobanner' );

		$this->assertEquals( false, $banner );
	}

	/**
	 * Test Basic_Banner::admin_post_thumbnail_size() for banner post type.
	 */
	public function test_admin_post_thumbnail_size_banner_post_type() {
		$banner = basic_banner_get( 'test' );

		$size = $this->basic_banner->admin_post_thumbnail_size( 'small', 1, $banner->post );

		$this->assertEquals( 'full', $size );
	}

	/**
	 * Test Basic_Banner::admin_post_thumbnail_size() for other post type.
	 */
	public function test_admin_post_thumbnail_size_other_post_type() {
		$post_id = $this->factory->post->create( array(
			'post_name' => 'test-post',
			'post_title' => 'Test Post',
			'post_type' => 'post',
			'post_status' => 'publish',
		) );

		$post = get_post( $post_id );

		$size = $this->basic_banner->admin_post_thumbnail_size( 'small', 1, $post );

		$this->assertEquals( 'small', $size );
	}

}
