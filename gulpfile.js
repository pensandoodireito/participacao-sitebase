var gulp = require('gulp');
var less = require('gulp-less');
var minifyCss = require('gulp-minify-css');
var sourcemaps = require('gulp-sourcemaps');
var gutil = require('gulp-util');
var plumber = require('gulp-plumber');
var path = require('path');

var temas = ['blog','dadospessoais','debatepublico','marcocivil','pensandoodireito'];

var plumberHandler = function (err) {
            		console.log(err);
            		this.emit('end');
        		};

temas.forEach(function(item){
		gulp.task(item, function(){
			return gulp.src('src/wp-content/themes/'+item+'-tema/css/less/*.less')
			.pipe(plumber({
        		errorHandler: plumberHandler
    		}))
			.pipe(less())
			.pipe(sourcemaps.init())
		    .pipe(minifyCss())
		    .pipe(sourcemaps.write())
		    .pipe(gulp.dest('src/wp-content/themes/'+item+'-tema/css'));
	});
});

gulp.task('default', temas, function(){
	gulp.src('src/wp-content/themes/participacao-tema/css/less/site-global.less')
			.pipe(plumber({
        		errorHandler: plumberHandler
    		}))
			.pipe(less({
      			paths: [ 'src/wp-content/themes/participacao-tema/css/less' ]
    		}))
			.pipe(sourcemaps.init())
		    .pipe(minifyCss())
		    .pipe(sourcemaps.write())
		    .pipe(gulp.dest('src/wp-content/themes/participacao-tema/css'));

		    gulp.src('src/wp-content/themes/participacao-tema/css/less/theme/theme-participacao.less')
			.pipe(plumber({
        		errorHandler: plumberHandler
    		}))
			.pipe(less({
      			paths: [
      			'src/wp-content/themes/participacao-tema/css/less',
      			]
    		}))
			.pipe(sourcemaps.init())
		    .pipe(minifyCss())
		    .pipe(sourcemaps.write())
		    .pipe(gulp.dest('src/wp-content/themes/participacao-tema/css'));
});


gulp.task('watch', function() {
		temas.forEach(function(item){
			gulp.watch('src/wp-content/themes/'+item+'-tema/css/less/*.less', [item]);
		});
});


