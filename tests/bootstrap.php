<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Basic_Banner
 */

$basic_banner_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $basic_banner_tests_dir ) {
	$basic_banner_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $basic_banner_tests_dir . '/includes/functions.php' ) ) {
	echo esc_html( "Could not find {$basic_banner_tests_dir}/includes/functions.php, have you run bin/install-wp-tests.sh ?" ) . PHP_EOL;
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $basic_banner_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function basic_banner_manually_load_plugin() {
	require dirname( __DIR__ ) . '/basic-banner.php';
}
tests_add_filter( 'muplugins_loaded', 'basic_banner_manually_load_plugin' );

// Start up the WP testing environment.
require $basic_banner_tests_dir . '/includes/bootstrap.php';
