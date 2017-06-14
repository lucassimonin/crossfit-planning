var gulp = require('gulp');
var gulpif = require('gulp-if');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var livereload = require('gulp-livereload');
var env = process.env.GULP_ENV;

var appRootPath = 'web/assets/app/';

var paths = {
    app: {
        js_head: [
            'web/assets/vendor/modernizr/modernizr.js'
        ],
        js: [
            'web/assets/vendor/jquery/dist/jquery.min.js',
            'web/assets/vendor/bootstrap/dist/js/bootstrap.min.js',
            'web/assets/public/js/script.js'
        ],
        js_ie: [
            'web/assets/vendor/html5shiv/dist/html5shiv.js',
            'web/assets/vendor/respond/src/respond.js',
            'web/assets/vendor/classie/classie.js',
        ],
        css: [
            'web/assets/vendor/bootstrap/dist/css/bootstrap.css',
            'web/assets/public/css/main.css',
        ],
        css_print: [
            'web/assets/vendor/bootstrap/dist/css/bootstrap.css',
        ],
        fonts: [
            'web/assets/vendor/bootstrap/fonts/**'
        ]
    }
};

gulp.task('app-js', function () {
    return gulp.src(paths.app.js)
        .pipe(concat('app.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'js/'))
        ;
});

gulp.task('app-js-head', function () {
    return gulp.src(paths.app.js_head)
        .pipe(concat('app_head.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'js/'))
        ;
});

gulp.task('app-js-ie', function () {
    return gulp.src(paths.app.js_head)
        .pipe(concat('app_ie.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'js/'))
        ;
});

gulp.task('app-css', function() {
    return gulp.src(paths.app.css)
        .pipe(concat('style.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'css/'))
        ;
});

gulp.task('app-fonts', function() {
    return gulp.src(paths.app.fonts)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'fonts/'))
        ;
});

gulp.task('app-watch', function() {
    livereload.listen();

    gulp.watch(paths.app.js, ['app-js']);
    gulp.watch(paths.app.js_head, ['app-js-head']);
    gulp.watch(paths.app.js_ie, ['app-js-ie']);
    gulp.watch(paths.app.css, ['app-css']);
    gulp.watch(paths.app.fonts, ['app-fonts']);
});

gulp.task('default', ['app-js', 'app-js-head', 'app-js-ie', 'app-css', 'app-fonts']);
gulp.task('watch', ['default', 'app-watch']);
