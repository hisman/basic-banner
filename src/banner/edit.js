/**
 * External dependencies.
 */
import {
	InspectorControls,
	ServerSideRender,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	ComboboxControl,
	PanelBody,
	Placeholder,
	TextControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { store as coreStore } from '@wordpress/core-data';

/**
 * Internal dependencies.
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of
 * the editor. See: https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/
 *
 * @param {Object}   props               Block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Update block attributes.
 * @return {JSX.Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	const banners = useSelect( ( select ) => {
		return select( coreStore ).getEntityRecords( 'postType', 'banner', {
			per_page: -1,
			status: 'publish',
		} );
	}, [] );

	const bannerOptions = ( banners || [] ).map( ( banner ) => ( {
		value: banner.slug,
		label: banner.title?.rendered || banner.slug,
	} ) );

	return (
		<div { ...blockProps }>
			<ServerSideRender
				block="basic-banner/banner"
				attributes={ attributes }
			>
			</ServerSideRender>

			{ ! attributes.name && (
				<Placeholder
					icon="format-image"
					label={ __( 'Basic Banner', 'basic_banner' ) }
					instructions={ __(
						'Select a banner to display.',
						'basic_banner'
					) }
				>
					<ComboboxControl
						label={ __( 'Banner', 'basic_banner' ) }
						options={ bannerOptions }
						value={ attributes.name }
						onChange={ ( value ) =>
							setAttributes( { name: value } )
						}
					/>
				</Placeholder>
			) }

			<InspectorControls>
				<PanelBody title={ __( 'Banner settings', 'basic_banner' ) }>
					<ComboboxControl
						label={ __( 'Banner', 'basic_banner' ) }
						options={ bannerOptions }
						value={ attributes.name }
						onChange={ ( value ) =>
							setAttributes( { name: value } )
						}
					/>

					<TextControl
						label={ __( 'Additional CSS class', 'basic_banner' ) }
						value={ attributes.class }
						onChange={ ( value ) =>
							setAttributes( { class: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
		</div>
	);
}
