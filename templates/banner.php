<?php
/**
 * The Template for displaying banner.
 *
 * @version      1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $banner ) {
	return;
}
?>

<div id="basic-banner-<?php echo $banner->id ?>" class="basic-banner">

	<?php if ( $banner->url ) : ?>

		<a href="<?php echo $banner->url; ?>" title="<?php echo $banner->caption; ?>">
			<?php echo get_the_post_thumbnail( $banner->id, 'full', array( 'alt' => $banner->caption ) ); ?>
		</a>

	<?php else : ?>

		<?php echo get_the_post_thumbnail( $banner->id, 'full', array( 'alt' => $banner->caption ) );  ?>

	<?php endif ?>

</div>
