'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var connect = require('gulp-connect');
var concat = require('gulp-concat');
var sourcemaps   = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var rename = require('gulp-rename');


gulp.task('sass', function () {
    return gulp.src('./frontend/scss/*.scss')
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoprefixer('last 5 version', 'ie 9'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./public/css'));
});

gulp.task('html', function () {
    gulp.src('./frontend/*.html')
        .pipe(connect.reload());
});

gulp.task('css', function () {
    gulp.src('./frontend/css/**/*.css')
        .pipe(connect.reload());
});

gulp.task('sass:watch', function () {
    connect.server({livereload: true, host: 'unused-server-host'});

    gulp.watch('./frontend/scss/**/*.scss', ['sass']);
    gulp.watch(['./frontend/*.html'], ['html']);
    gulp.watch(['./frontend/css/**/*.css'], ['css']);
});

gulp.task('default', ['sass:watch']);