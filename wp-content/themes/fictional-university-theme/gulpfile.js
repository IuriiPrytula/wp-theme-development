'use strict';

const isDev = true;

const $ = require('gulp-load-plugins')();
const gulp = require('gulp');

gulp.task('styles', function() {
  return gulp.src('./sass/style.scss')
        .pipe($.if(isDev, $.sourcemaps.init()))
        .pipe($.sass())
        .pipe($.autoprefixer())
        .pipe($.csso())
        .pipe($.if(isDev, $.sourcemaps.write()))
        .pipe(gulp.dest('.'))
});

gulp.task('js', function() {
  return gulp.src('./js/dev/**/*.js')
        .pipe($.concat('scripts.js'))
        .pipe($.babel({
          presets: ['@babel/env']
        }))
        .pipe($.minify())
        .pipe(gulp.dest('./js'))
});

gulp.task('build:all', gulp.series('styles', 'js'));

gulp.watch('./sass/**/*.*', gulp.series('styles'));
gulp.watch('./js/dev/**/*.js', gulp.series('js'));