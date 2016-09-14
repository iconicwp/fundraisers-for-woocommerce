// Project Specific

var plugin_filename = 'iconic-woo-fundraisers',
    plugin_zip_name = plugin_filename+'.zip',
    plugin_main_file = plugin_filename+'.php';


// load plugins

var gulp            = require('gulp'),
    sass            = require('gulp-sass'),
    autoprefixer    = require('gulp-autoprefixer'),
    cleancss        = require('gulp-clean-css'),
    jshint          = require('gulp-jshint'),
    uglify          = require('gulp-uglify'),
    rename          = require('gulp-rename'),
    concat          = require('gulp-concat'),
    notify          = require('gulp-notify'),
    zip             = require('gulp-zip'),
    replace         = require('gulp-replace'),
    del             = require('del');

var paths = {
    frontend_scripts: ['source/frontend/js/**/*.js'],
    frontend_styles: ['source/frontend/scss/**/*.scss'],
    admin_scripts: ['source/admin/js/**/*.js'],
    admin_styles: ['source/admin/scss/**/*.scss'],
    src: ['inc/**/*', 'templates/**/*', 'assets/**/*', 'languages/**/*', 'readme.txt', plugin_main_file],
    cc_src: ['**/*']
};

var deps = {
    // 'src' : 'dest'

    'vendor/jamesckemp/iconic-dashboard/class-dashboard.php' : 'inc/admin/vendor',
    'bower_components/magnific-popup/dist/jquery.magnific-popup.min.js' : 'assets/frontend/vendor',
    'bower_components/magnific-popup/dist/magnific-popup.css' : 'assets/frontend/vendor'

};

/**	=============================
    *
    * Tasks
    *
    ============================= */

	gulp.task('frontend_scripts', function() {

        return gulp.src(paths.frontend_scripts)
            .pipe(jshint('.jshintrc'))
            .pipe(jshint.reporter('default'))
            .pipe(concat('main.js'))
            .pipe(gulp.dest('assets/frontend/js'))
            .pipe(rename({ suffix: '.min' }))
            .pipe(uglify())
            .pipe(gulp.dest('assets/frontend/js'))
            .pipe(notify({ message: 'Frontend scripts task complete' }));

	});

	gulp.task('frontend_styles', function() {

        return gulp.src(paths.frontend_styles)
            .pipe(sass({outputStyle: 'expanded'}))
            .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
            .pipe(gulp.dest('assets/frontend/css'))
            .pipe(rename({ suffix: '.min' }))
            .pipe(cleancss({compatibility: 'ie8'}))
            .pipe(gulp.dest('assets/frontend/css'))
            .pipe(notify({ message: 'Frontend styles task complete' }));

	});

	gulp.task('admin_scripts', function() {

        return gulp.src(paths.admin_scripts)
            .pipe(jshint('.jshintrc'))
            .pipe(jshint.reporter('default'))
            .pipe(concat('main.js'))
            .pipe(gulp.dest('assets/admin/js'))
            .pipe(rename({ suffix: '.min' }))
            .pipe(uglify())
            .pipe(gulp.dest('assets/admin/js'))
            .pipe(notify({ message: 'Frontend scripts task complete' }));

	});

	gulp.task('admin_styles', function() {

        return gulp.src(paths.admin_styles)
            .pipe(sass({outputStyle: 'expanded'}))
            .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
            .pipe(gulp.dest('assets/admin/css'))
            .pipe(rename({ suffix: '.min' }))
            .pipe(cleancss({compatibility: 'ie8'}))
            .pipe(gulp.dest('assets/admin/css'))
            .pipe(notify({ message: 'Admin styles task complete' }));

	});

    // Rerun the task when a file changes
    gulp.task('watch', function () {

        gulp.watch(paths.frontend_scripts, ['frontend_scripts']);
        gulp.watch(paths.frontend_styles, ['frontend_styles']);
        gulp.watch(paths.admin_scripts, ['admin_scripts']);
        gulp.watch(paths.admin_styles, ['admin_styles']);

    });

    /**
     * Move components
     */
    gulp.task('deps', function() {

        for (var key in deps) {
            gulp.src( key )
                .pipe( gulp.dest( deps[key] ) );
        }

    });

	// The default task (called when you run `gulp` from cli)
	gulp.task('default', ['watch']);

/**	=============================
    *
    * Compile for CodeCanyon
    *
    ============================= */

	// Run to compile plugin zip
	gulp.task('prepare_plugin_files', function () {

    	var plugin_src = 'tmp/'+plugin_filename+'/';

    	return gulp.src(paths.src, {base: "."})
            .pipe(gulp.dest(plugin_src));

	});

	// Run to compile plugin zip
	gulp.task('create_plugin_zip', ['prepare_plugin_files'], function () {

    	var plugin_src = 'tmp/'+plugin_filename+'/';

	    return gulp.src(plugin_src+"**/*", {base: "./tmp"})
	        .pipe( zip(plugin_zip_name) )
	        .pipe( gulp.dest('dist') )
	        .pipe( notify({ message: 'Plugin zip Created' }) );

	});

	// Run to compile zip of plugin, readme and licenses
	gulp.task('create_main_zip', ['create_plugin_zip'], function () {

	    return gulp.src(paths.cc_src, {cwd: __dirname + "/dist"})
	        .pipe(zip('main-files-'+plugin_filename+'.zip'))
	        .pipe(gulp.dest('codecanyon'))
	        .pipe(notify({ message: 'Main files zipped for CodeCanyon' }));

	});

	// RUN THIS TO COMPILE FOR CC (gulp compile)
	gulp.task('compile', ['deps', 'create_main_zip'], function(){

        gulp.src('dist/'+plugin_zip_name, { base: './dist' })
            .pipe(gulp.dest('codecanyon'));

    	del(['tmp']);

    });