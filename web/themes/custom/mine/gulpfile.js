const gulp = require('gulp');
const imagemin = require('gulp-imagemin');

const sourceSVG = "src/svg/**/*";

/*  IMAGES / IMG
==============================================================================*/
module.exports.svg = function () {
  return gulp.src(sourceSVG)
    .pipe(
      imagemin([
        imagemin.svgo({
          plugins: [
            { name: "removeViewBox", active: false },
            { name: "removeDimensions", active: true },
          ],
        }),
      ])
    )
    .pipe(gulp.dest("./dist/svg"));
}
