'use strict';

var slug = 'basic-banner',
    gulp = require('gulp'),
	plugins = require('gulp-load-plugins')();

var swallowError = function(error) {
	console.log(error.toString());
}

/* Archiving Tasks */
gulp.task('deploy', function(){
    return gulp.src(['**/*',
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
			'!{' + slug + ',' + slug + '/**}',
            '!' + slug + '.zip'])
        .pipe(gulp.dest(slug));
});

gulp.task('archive', ['deploy'], function(){
    return gulp.src([slug + '/**/*'], { base: '.' })
        .pipe(plugins.zip(slug + '.zip'))
        .pipe(gulp.dest('.'));
});

/* CSS */
gulp.task('css', function(){
    return gulp.src('assets/css/**/*.less')
		.pipe(plugins.less())
		.on('error', swallowError)
        .pipe(plugins.autoprefixer({ browsers: ['last 3 versions'] }))
        .pipe(plugins.cssnano())
        .pipe(gulp.dest('assets/css/'));
});

/* Scripts */
gulp.task('scripts', function(){
    return gulp.src(['assets/js/*.js', '!assets/js/*.min.js'])
        .pipe(plugins.uglify())
        .pipe(plugins.rename({ suffix: '.min' }))
        .pipe(gulp.dest('assets/js/'));
});

/* Default Task */
gulp.task('default', ['css', 'scripts']);

/* Watch Task */
gulp.task('watch', ['css', 'scripts'], function(){
    gulp.watch(['assets/css/**/*.less'], ['css']);
    gulp.watch(['assets/js/*.js', '!assets/js/*.min.js'], ['scripts']);
});
