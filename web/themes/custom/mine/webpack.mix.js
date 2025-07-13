const mix = require("laravel-mix");

mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.scss$/,
        loader: 'import-glob-loader'
      }
    ]
  }
})

mix
  .js("src/scripts/scripts.js", "dist/js")
  .sass("src/styles/styles.scss", "dist/css")
  .sass("src/styles/print.scss", "dist/css")
  .sass("src/styles/ckeditor.scss", "dist/css")
  .options({
    processCssUrls: false,
  })
  .sourceMaps(true, 'source-map')
  .browserSync({
    proxy: 'dsh.lndo.site',
    files: ['dist/js/*.js', 'dist/css/*.css'],
    open: false,
  })
  .disableNotifications()
