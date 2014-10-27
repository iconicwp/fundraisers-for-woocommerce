var gulp = require('gulp');

var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var concatCss = require('gulp-concat-css');
var minifyCSS = require('gulp-minify-css');
var zip = require('gulp-zip');

// Project Specific
var pluginZipName = 'jck_woo_fundraisers.zip';
var pluginSlug = 'jckf-';

var paths = {
  frontendscripts: ['source/frontend/js/**/*.js'],
  frontendstyles: ['source/frontend/css/**/*.css'],
  adminscripts: ['source/admin/js/**/*.js'],
  adminstyles: ['source/admin/css/**/*.css'],
  src: ['inc/**/*', 'assets/**/*', 'jck_woo_fundraisers.php'],
  ccsrc: ['**/*']
};

/* 	=============================
   	Tasks 
   	============================= */
   	
	gulp.task('frontendscripts', function() {
	  // Minify and copy all JavaScript (except vendor scripts)
	  return gulp.src(paths.frontendscripts)
	    .pipe(uglify())
	    .pipe(concat(pluginSlug+'scripts.min.js'))
	    .pipe(gulp.dest('assets/frontend/js'));
	});
	
	gulp.task('frontendstyles', function() {
	  // Minify and copy all JavaScript (except vendor scripts)
	  return gulp.src(paths.frontendstyles)
	    .pipe(concatCss(pluginSlug+'styles.min.css'))
	    .pipe(minifyCSS())
	    .pipe(gulp.dest('assets/frontend/css'));
	});
	
	gulp.task('adminscripts', function() {
	  // Minify and copy all JavaScript (except vendor scripts)
	  return gulp.src(paths.adminscripts)
	    .pipe(uglify())
	    .pipe(concat(pluginSlug+'scripts.min.js'))
	    .pipe(gulp.dest('assets/admin/js'));
	});
	
	gulp.task('adminstyles', function() {
	  // Minify and copy all JavaScript (except vendor scripts)
	  return gulp.src(paths.adminstyles)
	    .pipe(concatCss(pluginSlug+'styles.min.css'))
	    .pipe(minifyCSS())
	    .pipe(gulp.dest('assets/admin/css'));
	});
	
	// Rerun the task when a file changes
	gulp.task('watch', function () {
	  gulp.watch(paths.frontendscripts, ['frontendscripts']);
	  gulp.watch(paths.frontendstyles, ['frontendstyles']);
	  gulp.watch(paths.adminscripts, ['adminscripts']);
	  gulp.watch(paths.adminstyles, ['adminstyles']);
	});
	
	// The default task (called when you run `gulp` from cli)
	// gulp.task('default', ['scripts', 'styles', 'watch']);

/* 	=============================
   	Compile for CC 
   	============================= */
	
	// Run to compile plugin zip 
	gulp.task('createPluginZip', function () {
	    return gulp.src(paths.src, {base: "."})
	        .pipe(zip(pluginZipName))
	        .pipe(gulp.dest('dist'));
	});
	
	// Run to compile zip of plugin, readme and licenses
	gulp.task('createMainZip', ['createPluginZip'], function () {
	    return gulp.src(paths.ccsrc, {cwd: __dirname + "/dist"})
	        .pipe(zip('main_files.zip'))
	        .pipe(gulp.dest('codecanyon'));
	});
	
	// RUN THIS TO COMPILE FOR CC (gulp compile)
	gulp.task('compile', ['createMainZip']);