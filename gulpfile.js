'use strict';

const slug = 'basic-banner';
const { src, dest, series, watch } = require('gulp');
const zip = require('gulp-zip');
const del = require('del');
const postcss = require('gulp-postcss');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const less = require('gulp-less');

const swallowError = function(error) {
	console.log(error.toString());
}

/* Packaging */
function deploy() {
	return src(['**/*',
				'!{.git,.git/**}',
				'!{.wordpress-org,.wordpress-org/**}',
				'!{node_modules,node_modules/**}',
				'!{bin,bin/**}',
				'!{tests,tests/**}',
				'!.editorconfig',
				'!.gitattributes',
				'!.gitignore',
				'!gulpfile.js',
				'!package.json',
				'!package-lock.json',
				'!README.md',
				'!.phpcs.xml.dist',
				'!.travis.yml',
				'!phpunit.xml.dist',
				'!postcss.config.js',
				'!{' + slug + ',' + slug + '/**}',
				'!' + slug + '.zip'])
			.pipe(dest(slug));
}

function archive() {
	return src([slug + '/**/*'], { base: '.' })
		.pipe(zip(slug + '.zip'))
		.pipe(dest('.'));
}

function cleanup() {
	return del(slug);
}

/* CSS */
function css() {
	return src(['assets/css/**/*.less'])
		.pipe(less())
		.on('error', swallowError)
		.pipe(postcss())
		.pipe(dest('assets/css/'));
}

/* Scripts */
function scripts() {
	return src(['assets/js/*.js', '!assets/js/*.min.js'])
		.pipe(uglify())
		.pipe(rename({ suffix: '.min' }))
		.pipe(dest('assets/js/'));
}

/* Default Task */
exports.deploy = deploy;
exports.package = series(deploy, archive, cleanup);

exports.watch = function() {
	watch(['assets/css/**/*.less'], { ignoreInitial: false }, css);
	watch(['assets/js/*.js', '!assets/js/*.min.js'], { ignoreInitial: false }, scripts);
};
exports.default = series(css, scripts);
