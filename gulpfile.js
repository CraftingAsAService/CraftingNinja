/**
 * Alchemists Gulpfile
 *  Webpack is ungodly slow parsing it, Gulp is better at it
 */
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
	global.isWatching = true;

	gulp.watch('resources/scss/alchemists/**/*.scss', debounce(gulp.parallel('alchemists')));
});

gulp.task('alchemists', function(done) {
	if ( ! getStatus())
		return done();

	return gulp.src('resources/scss/alchemists/**/*.scss')
		.pipe(plugins.plumber({ errorHandle: plugins.notify.onError("Error: <%= error.message %>") }))
		// Find files that depend on the files that have changed
		// Also finds files inside of ctgus, as they can depend on common assets
		.pipe(plugins.sassInheritance({ dir: 'resources/scss/alchemists' }))
		// Filter out internal imports (folders and files starting with "_" )
		.pipe(plugins.filter(function (file) {
			return !/\/_/.test(file.path) || !/^_/.test(file.relative);
		}))
		// filter out unchanged scss files, only works when watching
		.pipe(plugins.if(global.isWatching, plugins.cached('sass')))
		// Run SASS and AutoPrefix it
		.pipe(plugins.sass({ outputStyle: 'compressed' }).on('error', plugins.sass.logError))
		.pipe(plugins.autoprefixer())
		// Save file down and notify
		.pipe(gulp.dest('public/css/alchemists'))
		.pipe(plugins.notify({ message: 'Sass compiled <%= file.relative %>' }));
});
