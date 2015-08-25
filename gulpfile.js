var gulp = require('gulp');
var $ = require('gulp-load-plugins')();

var path = {
  app: 'app/Resources',
  bower_components: './bower_components',
};

gulp.task('copy', function() {
  gulp.src(path.app + '/assets/fonts/*.{ttf,woff,eof,svg,eot}')
    .pipe(gulp.dest('web/fonts/'));
  gulp.src(path.bower_components + '/semantic-ui/dist/themes/**')
    .pipe(gulp.dest('web/css/themes/'));
});

gulp.task('vendorjs', function() {
  return gulp
    .src([
      'bower_components/jquery/dist/jquery.js',
      'bower_components/semantic-ui/dist/semantic.js'
    ])
    .pipe($.concat('vendor.js'))
    .pipe($.uglify({ mangle: true }))
    .pipe($.rename({ suffix: '.min' }))
    .pipe(gulp.dest('web/js/'));
});

gulp.task('vendorcss', function() {
  return gulp
    .src([
      'bower_components/semantic-ui/dist/semantic.min.css'
    ])
    .pipe($.concat('vendor.css'))
    .pipe($.rename({ suffix: '.min' }))
    .pipe(gulp.dest('web/css/'));
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
  'vendorjs',
  'vendorcss',
  'app',
]);
