'use strict';

const fs = require( 'fs' );
const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

class StyleOnlyEntriesPlugin {
	apply( compiler ) {
		compiler.hooks.afterEmit.tap( 'StyleOnlyEntriesPlugin', ( compilation ) => {
			const outputPath = compilation.outputOptions.path;

			// Remove the empty JS/asset files emitted for style-only entries.
			[ 'admin.js', 'admin.asset.php', 'style.js', 'style.asset.php' ].forEach( ( file ) => {
				try {
					fs.unlinkSync( path.join( outputPath, file ) );
				} catch ( error ) {
					if ( error.code !== 'ENOENT' ) {
						throw error;
					}
				}
			} );

			// wp-scripts prefixes CSS from a `style.scss` source with `style-`.
			// Rename the output back to the expected `style.css` / `style-rtl.css`.
			[
				[ 'style-style.css', 'style.css' ],
				[ 'style-style-rtl.css', 'style-rtl.css' ],
			].forEach( ( [ from, to ] ) => {
				try {
					fs.renameSync( path.join( outputPath, from ), path.join( outputPath, to ) );
				} catch ( error ) {
					if ( error.code !== 'ENOENT' ) {
						throw error;
					}
				}
			} );
		} );
	}
}

module.exports = {
	...defaultConfig,
	entry: {
		...( typeof defaultConfig.entry === 'function' ? defaultConfig.entry() : defaultConfig.entry ),
		admin: './assets/src/admin.scss',
		style: './assets/src/style.scss',
	},
	plugins: [
		...defaultConfig.plugins,
		new StyleOnlyEntriesPlugin(),
	],
};
