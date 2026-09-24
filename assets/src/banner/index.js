/**
 * WordPress dependencies.
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies.
 */
import Edit from './edit';

registerBlockType( 'basic-banner/banner', {
	icon: 'format-image',

	edit: Edit,

	// Dynamic block rendered server-side via render_callback in PHP.
	save() {
		return null;
	},
} );
