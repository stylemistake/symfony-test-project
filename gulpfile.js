var gulp = require('gulp');
var $ = require('gulp-load-plugins')();

var path = {
  app: 'app/Resources',
  bower_components: './bower_components',
};

gulp.task('copy', function() {
  return gulp
    .src(path.app + '/assets/fonts/*.{ttf,woff,eof,svg,eot}')
    .pipe(gulp.dest('web/fonts/'));
});

gulp.task('vendor', function() {
  return gulp
    .src([
      './bower_components/jquery/dist/jquery.js',
      './bower_components/bootstrap/assets/javascripts/bootstrap.js'
    ])
    .pipe($.concat('vendor.js'))
    .pipe($.uglify({ mangle: true }))
    .pipe($.rename({ suffix: '.min' }))
    .pipe(gulp.dest('web/js/'));
});

gulp.task('app', function() {
  return gulp
    .src(path.app + '/js/**/*.js')
    .pipe($.sourcemaps.init())
    .pipe($.concat('app.js'))
    .pipe($.uglify({ mangle: true }).on('error', $.util.log))
    .pipe($.rename({ suffix: '.min' }))
    .pipe($.sourcemaps.write())
    .pipe(gulp.dest('web/js/'));
});

gulp.task('default', [
  'copy',
  'vendor',
  'app',
]);
