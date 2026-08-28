'use strict';

const fs = require( 'fs' );
const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

class CleanAdminEntriesPlugin {
	apply( compiler ) {
		compiler.hooks.afterEmit.tap( 'CleanAdminEntriesPlugin', ( compilation ) => {
			const outputPath = compilation.outputOptions.path;

			[ 'admin.js', 'admin.asset.php' ].forEach( ( file ) => {
				try {
					fs.unlinkSync( path.join( outputPath, file ) );
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
	},
	plugins: [
		...defaultConfig.plugins,
		new CleanAdminEntriesPlugin(),
	],
};
