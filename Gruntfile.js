module.exports = function(grunt) {

	// configure the tasks
	grunt.initConfig({

		imagemin: {
			all: {
				files: [{
					expand: true,
					cwd: 'public/img/',
					src: ['**/*.{png,gif,PNG,GIF,jpg,jpeg,JPG,JPEG}'],
					dest: 'public/img/'
				}]
			}
		},

		uglify: { // Task
			singles: {
				files: [{
					expand: true,
					cwd: 'resources/assets/javascript',
					src: [ '**/*.js', '!caas/*.js' ],
					dest: 'public/js'
				}]
			},
			caas: { // Target
				options: { // Target Options
					// beautify: true, // DEBUGGING
					mangle: false
				},
				files: { // Dictionary of Files
					'public/js/caas.js': 'resources/assets/javascript/caas/*.js'
				}
			}
		},

		sass: {
			options: {
				cacheLocation: 'node_modules/.sass-cache',
				update: true // Only update changed files
			},
			all: { // Target
				options: {
					style: 'compressed',
					sourcemap: 'none'
				},
				files: [{
					expand: true,
					cwd: 'resources/assets/scss',
					src: [ '**/*.scss', '!**/_*.scss' ],
					dest: 'public/css',
					ext: '.css'
				}]
			},
		},

		watch: {
			// Javascript
			uglify_singles: {
				files: 'resources/assets/javascript/**/*.js',
				tasks: [ 'newer:uglify:singles', 'notify:uglify' ]
			},

			uglify_caas: {
				files: 'resources/assets/javascript/caas/*.js',
				tasks: [ 'newer:uglify:caas', 'notify:uglify' ]
			},
			
			// CSS
			sass: {
				files: 'resources/assets/scss/**/*.scss',
				tasks: [ 'sass:all', 'notify:sass' ]
			},

			// Grunt
			grunt: {
				files: ['Gruntfile.js'],
				tasks: [ 'notify:grunt' ]
			}
		},

		notify: {
			uglify: {
				options: {
					title: 'JS Uglified',
					message: 'Task Complete'
				}
			},
			sass: {
				options: {
					title: 'SASS Compiled',
					message: 'Task Complete'
				}
			},
			imagemin: {
				options: {
					title: 'Images Minified',
					message: 'Task Complete'
				}
			},
			grunt: {
				options: {
					title: 'Grunt Changed',
					message: 'Restart Probably Required'
				}
			}
		},

		notify_hooks: {
			options: {
				enabled: true,
				max_jshint_notifications: 5,
				title: 'CAAS Grunt',
				success: true,
				duration: 3
			}
		}

	});

	// load the loadNpmTasks
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-newer');
	grunt.loadNpmTasks('grunt-notify');
	grunt.loadNpmTasks('grunt-postcss');
 
	// define the tasks
 
	grunt.registerTask('default', [ 'watch' ]);
	grunt.registerTask('images', [ 'newer:imagemin:all', 'notify:imagemin' ]);

	// grunt.registerTask('beep', function() { console.log('\x07'); });

};