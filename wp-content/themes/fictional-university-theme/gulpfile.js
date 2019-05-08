'use strict';

const isDev = true;

const $ = require('gulp-load-plugins')();
const gulp = require('gulp');
const multipipe = require('multipipe');

gulp.task('styles', function() {
  return gulp.src('style.scss')
        .pipe($.if(isDev, $.sourcemaps.init()))
        .pipe($.sass())
        .pipe($.csso())
        .pipe($.if(isDev, $.sourcemaps.write()))
        .pipe(gulp.dest('.'))
});

gulp.task('js', function() {
  return gulp.src('./js/scripts.js')
        .pipe($.if(isDev, $.sourcemaps.init()))
        .pipe($.minify())
        .pipe($.if(isDev, $.sourcemaps.write()))
        .pipe(gulp.dest('./js'))
});

gulp.task('build', gulp.series('styles', 'js'));

gulp.watch('style.scss', gulp.series('styles'));