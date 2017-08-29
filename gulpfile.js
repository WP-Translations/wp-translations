// Requires
var gulp = require('gulp');

// Include plugins
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var minifycss = require('gulp-minify-css');
var sort = require( 'gulp-sort' );
var autoprefixer = require('gulp-autoprefixer');
var wpPot = require( 'gulp-wp-pot' );

// tâche CSS = compile vers style.css et style-unminified.css
gulp.task('css', function () {
	return gulp.src('./assets/scss/admin-style.scss')
		.pipe(sass({
			outputStyle: 'expanded' // CSS non minifiée plus lisible ('}' à la ligne)
		}))
		.pipe(autoprefixer())
		.pipe(rename('admin-style.css'))
		.pipe(gulp.dest('./assets/css'))
		.pipe(rename('admin-style-min.css'))
		.pipe(minifycss())
		.pipe(gulp.dest('./assets/css'));
});

//* Make pot
gulp.task( 'make-pot', function() {

	gulp.src( [ './**/*.php' ] )
		.pipe( sort() )
		.pipe( wpPot({
			domain: "wp-translations",
			team: "WP-Translations"
		}))
		.pipe( gulp.dest( './languages/wp-translations.pot' ));

});


// Watcher
gulp.task('watch', function() {
	gulp.watch(['css']);
});


gulp.task('default', ['css']);
