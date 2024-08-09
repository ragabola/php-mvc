const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const terser = require('gulp-terser');
const minifyImg = require('gulp-imagemin');


gulp.task('compile-sass', function () {
    return gulp
        .src('./resources/scss/main.scss')
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .pipe(autoprefixer("last 2 versions"))
        .pipe(gulp.dest('./public/css'))
});
gulp.task('minify-js', function () {
    return gulp        
        .src(['./resources/js/*.js', './resources/js/**/*.js'])
        .pipe(terser())
        .pipe(gulp.dest('./public/js/'))
});

gulp.task('minify-img', function () {
    return gulp.src('./resources/images/*')
        .pipe(minifyImg())
        .pipe(gulp.dest('./public/images'))
});

gulp.task('watch', function () {
    gulp.watch(['./resources/scss/*.scss' ,'./resources/scss/**/*.scss'], gulp.series('compile-sass'));
    gulp.watch(['./resources/js/*.js', './resources/js/**/*.js'], gulp.series('minify-js'));
    gulp.watch(['./resources/images/*'], gulp.series('minify-img'));
});
