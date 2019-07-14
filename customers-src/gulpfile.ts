'use strict';

const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const del = require('del');
const sourcemaps = require('gulp-sourcemaps');
const tslint = require('gulp-tslint');
const server = require('gulp-develop-server');
const sequence = require('run-sequence');
const wait = require('gulp-wait');
const exec = require('child_process').exec;
const livereload = require('gulp-livereload');
const svgstore = require('gulp-svgstore');
const svgmin = require('gulp-svgmin');
const rename = require('gulp-rename');
const prefixer = require('gulp-autoprefixer');
const cssmin = require('gulp-minify-css');
const watch = require('gulp-watch');
const path = {
    build: { // Тут мы укажем куда складывать готовые после сборки файлы
        svg: 'src/assets/img',
        styleGlobal: 'src/assets/css'
    },
    src: { // Пути откуда брать исходники
        svg: 'src/svg/*.svg',
        styleGlobal: 'src/scss/main.scss'
    },
    watch: { // Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        svg: 'src/assets/svg/*.svg',
        styleGlobal: 'src/scss/**/*.scss',
    }
};

gulp.task('default', ['watch']);

/**
 * Watch for changes in TypeScript, HTML and CSS files.
 */
gulp.task('watch', [], function () {
    gulp.watch(path.watch.styleGlobal, ['style-global:build']);
});

// css
gulp.task('style-global:build', function () {
    gulp.src(path.src.styleGlobal)
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(prefixer())
        .pipe(cssmin())
        .pipe(gulp.dest(path.build.styleGlobal));
});

// svg
gulp.task('svg:build', function () {
    return gulp
        .src(path.src.svg)
        .pipe(svgmin())
        .pipe(svgstore())
        .pipe(rename({basename: 'sprite'}))
        .pipe(gulp.dest(path.build.svg));
});
