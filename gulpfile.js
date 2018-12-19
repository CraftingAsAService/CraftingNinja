var gulp = require('gulp'),
	fs = require('fs'),
	debounce = require('debounce'),
	gulpLoadPlugins = require('gulp-load-plugins'),
	plugins = gulpLoadPlugins({
		rename: {
			'gulp-sass-multi-inheritance': 'sassInheritance'
		}
	});

function getStatus() {
	// If the gulp.stop file exists, don't run any gulp commands
	// Used in conjunction with a git alias/function that creates and deletes this file
	if (fs.existsSync('gulp.stop')) {
		console.log('gulp.stop - Gulp Prevented From Running');
		return false;
	}
	return true;
}

gulp.task('watch', function() {
	// Enable LiveReload
	// plugins.livereload.listen();

	global.isWatching = true;

	/**
	 * CSS
	 */
	gulp.watch('resources/scss/**/*.scss', debounce(gulp.parallel('css')));

	/**
	 * JavaScript
	 */
	gulp.watch('resources/js/**/*.js', debounce(gulp.parallel('js')));

	gulp.watch('resources/js/core/**/*.js', debounce(gulp.parallel('jsCore')));
});

/**
 * SASS
 */
gulp.task('css', function (done) {
	if ( ! getStatus())
		return done();

	return gulp.src('resources/scss/**/*.scss')
		.pipe(plugins.plumber({ errorHandle: plugins.notify.onError("Error: <%= error.message %>") }))
		// Filter out unchanged scss files, only works when watching
		.pipe(plugins.if(global.isWatching, plugins.cached('css')))
		// Find files that depend on the files that have changed
		.pipe(plugins.sassInheritance({ dir: 'resources/scss' }))
		// Filter out internal imports (folders and files starting with "_" )
		.pipe(plugins.filter(function (file) {
			return !/\/_/.test(file.path) || !/^_/.test(file.relative);
		}))
		// Run SASS and AutoPrefix it
		.pipe(plugins.sass({ outputStyle: 'compressed', includePaths: 'resources/scss' }).on('error', plugins.sass.logError))
		.pipe(plugins.autoprefixer())
		// Save file down and notify
		.pipe(gulp.dest('public/css'))
		.pipe(plugins.notify({ message: 'Sass compiled <%= file.relative %>' }))
		.pipe(plugins.livereload());
});

/**
 * Scripts
 */

gulp.task('js', function (done) {
	if ( ! getStatus())
		return done();

	return gulp.src(['resources/js/**/*.js', '!./resources/js/core/**/*.js'])
		.pipe(plugins.plumber({ errorHandle: plugins.notify.onError("Error: <%= error.message %>") }))
		// filter out unchanged js files, only works when watching
		.pipe(plugins.if(global.isWatching, plugins.cached('js')))
		.pipe(plugins.jsImport({hideConsole: true}))
		.pipe(plugins.terser())
		.pipe(gulp.dest('public/js'))
		.pipe(plugins.notify({ message: 'JS compiled <%= file.relative %>' }))
		.pipe(plugins.livereload());
});

gulp.task('jsCore', function (done) {
	if ( ! getStatus())
		return done();

	return gulp.src('resources/js/core.js')
		.pipe(plugins.plumber({ errorHandle: plugins.notify.onError("Error: <%= error.message %>") }))
		.pipe(plugins.jsImport({hideConsole: true}))
		.pipe(plugins.terser())
		.pipe(gulp.dest('public/js'))
		.pipe(plugins.notify({ message: 'JS compiled <%= file.relative %>' }))
		.pipe(plugins.livereload());
});
