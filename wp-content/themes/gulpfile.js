var gulp         = require('gulp'), // Подключаем Gulp
    sass         = require('gulp-sass'), //Подключаем Sass пакет,
    concat       = require('gulp-concat'), // Подключаем gulp-concat (для конкатенации файлов)
    uglify       = require('gulp-uglifyjs'), // Подключаем gulp-uglifyjs (для сжатия JS)
    cssnano      = require('gulp-cssnano'), // Подключаем пакет для минификации CSS
    rename       = require('gulp-rename'), // Подключаем библиотеку для переименования файлов
    del          = require('del'), // Подключаем библиотеку для удаления файлов и папок
    imagemin     = require('gulp-imagemin'), // Подключаем библиотеку для работы с изображениями
    pngquant     = require('imagemin-pngquant'), // Подключаем библиотеку для работы с png
    jpegoptim    = require('imagemin-jpegoptim'), // Подключаем библиотеку для работы с jpg
    jpegtran     = require('imagemin-jpegtran'), // Подключаем библиотеку для работы с jpg
    cache        = require('gulp-cache'), // Подключаем библиотеку кеширования
    autoprefixer = require('gulp-autoprefixer'),// Подключаем библиотеку для автоматического добавления префиксов
    sprite       = require('gulp.spritesmith'),// Подключаем библиотеку для автоматической генерации css спрайтов
    spriteStyle  = require('gulp-stylus'),// Подключаем библиотеку для стилей спрайтов
    bower        = require('gulp-bower'),
    bourbon      = require('node-bourbon'),
    sourcemaps   = require('gulp-sourcemaps');
	iconfont     = require('gulp-iconfont'),
    iconfontCss  = require('gulp-iconfont-css'),

    browserSync  = require('browser-sync').create();


var themePath = 'askerweb_theme';
var fullPath = '/wp-content/themes/'+ themePath;

gulp.task('bower-libs', function(){
    return gulp.src([
        'bower_components/jquery/dist/jquery.min.js',
        'bower_components/scrollTo/jquery.scrollTo.js',
        'bower_components/slick-carousel/slick/slick.min.js',
    ])
        .pipe(concat('libs.min.js')) // Собираем их в кучу в новом файле libs.min.js
        .pipe(uglify()) // Сжимаем JS файл
        .pipe(gulp.dest(themePath + '/inc/js')); // Выгружаем в папку inc/js
})

gulp.task('iconfont', function(){
    gulp.src([themePath+'/inc/icons/*.svg'])
        .pipe(iconfontCss({
            fontName: 'icons',
            path: themePath+'/inc/icons/_icons-template.scss',
            targetPath: '../sass/_icons.scss',
            fontPath: fullPath+'/inc/fonts/'
        }))
        .pipe(iconfont({
            fontName: 'icons',
            normalize: true
        }))
        .pipe(gulp.dest(themePath+'/inc/fonts/'));
});

gulp.task('scripts', function() {
    return gulp.src([ // Берем все необходимые библиотеки
        themePath + '/inc/js/template.js'
    ])
        .pipe(concat('template.min.js')) // Собираем их в кучу в новом файле template.min.js
        .pipe(uglify()) // Сжимаем JS файл
        .pipe(gulp.dest(themePath + '/inc/js')); // Выгружаем в папку inc/js
});

gulp.task('clean', function() {
    return del.sync(themePath + '/inc/css'); // Удаляем папку css перед сборкой
});

gulp.task('css-libs',['clean'], function () {
    return gulp.src([
        themePath + '/inc/sass/style.scss'
    ])
        .pipe(sourcemaps.init())
        .pipe(sass({includePaths:bourbon.includePaths}).on('error', sass.logError)) // Преобразуем Sass в CSS посредством gulp-sass
        .pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 8', 'ie 7'], { cascade: true })) // Создаем префиксы
        .pipe(cssnano())
        .pipe(rename({suffix: '.min'}))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(themePath + '/inc/css')) // Выгружаем в папку app/css
})

gulp.task('css-bower-libs', function () {
    return gulp.src([
        'bower_components/fancybox/dist/jquery.fancybox.min.css',
        'bower_components/slick-carousel/slick/slick.css',
        'bower_components/slick-carousel/slick/slick-theme.css'
    ])
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError)) // Преобразуем Sass в CSS посредством gulp-sass
        .pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 8', 'ie 7'], { cascade: true })) // Создаем префиксы
        .pipe(cssnano())
        .pipe(concat('libs.css'))
        .pipe(rename({suffix: '.min'}))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(themePath + '/inc/assets')) // Выгружаем в папку app/css
})

gulp.task('sprite', function () {
    // Generate our spritesheet
    var imgName = 'sprite.png';
    var spriteData = gulp.src(themePath + '/inc/img/sprite/*').pipe(sprite({
        imgName: imgName,
        cssFormat: 'scss',
        algorithm: 'binary-tree',
        imgPath:fullPath+'/inc/img/'+imgName,
        cssVarMap: function(sprite) {
            sprite.name = 'sprite-' + sprite.name
        },
        cssName: '_sprite.scss'
    }));

    spriteData.img.pipe(gulp.dest(themePath + '/inc/img/')); // путь, куда сохраняем картинку
    spriteData.css.pipe(gulp.dest(themePath + '/inc/sass/')); // путь, куда сохраняем стили

});

gulp.task('img', function() {
    return gulp.src(themePath + '/inc/img/**/*') // Берем все изображения из img
        .pipe(cache(imagemin({  // Сжимаем их с наилучшими настройками с учетом кеширования
            interlaced: true,
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant(),jpegtran()]
        })))
        .pipe(gulp.dest(themePath + '/inc/img')); // Выгружаем на продакшен
});

gulp.task('clear', function () {
    return cache.clearAll();
})

gulp.task('watch', ['css-libs','scripts'], function() {
    gulp.watch(themePath + '/inc/sass/**/*.scss', ['css-libs']); // Наблюдение за sass файлами в папке sass
    gulp.watch(themePath + '/inc/js/*.js', ['scripts']);   // Наблюдение за JS файлами в папке js
});

gulp.task('build',['css-libs','css-bower-libs','scripts','bower-libs','sprite','iconfont'])


gulp.task('default', ['watch']);