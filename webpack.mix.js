const mix = require('laravel-mix')
const path = require('path')
require('laravel-mix-clean')

let paths = {
   chunk: `${process.env.UI_OUTPUT_ASSET_FOLDER}/${process.env.UI_JS}/[name].js?id=[chunkhash]`,
   vendor: {
      bootstrapIcons: 'node_modules/bootstrap-icons',
   },
   in: {
      base: `${process.env.UI_INPUT_FOLDER}`,
      images: `${process.env.UI_INPUT_FOLDER}/${process.env.UI_IMAGES}`,
      js: `${process.env.UI_INPUT_FOLDER}/${process.env.UI_JS}`,
      css: `${process.env.UI_INPUT_FOLDER}/${process.env.UI_CSS}`,
      files: `${process.env.UI_INPUT_FOLDER}/${process.env.UI_FILES}`,
      fonts: `${process.env.UI_INPUT_FOLDER}/${process.env.UI_FONTS}`,
      frontend: `${process.env.UI_INPUT_FOLDER}/${process.env.UI_FRONTEND_FOLDER}`,
      components: `${process.env.UI_INPUT_FOLDER}/${process.env.UI_FRONTEND_FOLDER}/${process.env.UI_FRONTEND_COMPONENTS}`,
   },
   out: {
      assets: `${process.env.UI_OUTPUT_ASSET_FOLDER}`,
      base: `${process.env.UI_OUTPUT_FOLDER}`,
      images: `${process.env.UI_OUTPUT_FOLDER}/${process.env.UI_OUTPUT_ASSET_FOLDER}/${process.env.UI_IMAGES}`,
      js: `${process.env.UI_OUTPUT_FOLDER}/${process.env.UI_OUTPUT_ASSET_FOLDER}/${process.env.UI_JS}`,
      css: `${process.env.UI_OUTPUT_FOLDER}/${process.env.UI_OUTPUT_ASSET_FOLDER}/${process.env.UI_CSS}`,
      files: `${process.env.UI_OUTPUT_FOLDER}/${process.env.UI_OUTPUT_ASSET_FOLDER}/${process.env.UI_FILES}`,
      fonts: `${process.env.UI_OUTPUT_FOLDER}/${process.env.UI_OUTPUT_ASSET_FOLDER}/${process.env.UI_FONTS}`,
   },
}

mix.setPublicPath(`${paths.out.base}/`)
   .babelConfig({
      plugins: ['@babel/plugin-syntax-dynamic-import'],
   })
   .webpackConfig({
      output: {
         chunkFilename: `${paths.chunk}`,
      },
      resolve: {
         alias: {
            vue$: 'vue/dist/vue.runtime.esm.js',
            '@': path.resolve(paths.in.js),
            '@store': `${path.resolve(paths.in.js)}/store`,
            '@templates': `${path.resolve(paths.in.js)}/Templates`,
            '@components': `${path.resolve(paths.in.js)}/Components`,
         },
      },
      // this is only needed if there's a error/warning in a child process that can't
      // be bubbled up for some reason. It happens and is a Webpack issue...
      // stats: {
      //    children: true,
      // },
   })
   .options({
      cssNano: {
         calc: false,
         discardComments: {removeAll: true},
         normalizeWhitespace: false,
      },
      processCssUrls: false,
      postCss: [require('postcss-import')(), require('postcss-sort-media-queries')()],
   })
   .clean({
      cleanOnceBeforeBuildPatterns: [`${paths.out.assets}`],
   })
   // COPY FILES OVER....
   .copy([`${paths.vendor.bootstrapIcons}/bootstrap-icons.svg`, `${paths.in.images}`, `${paths.in.components}/**/*.png`], `${paths.out.images}`)
   .copy(`${paths.in.files}`, `${paths.out.files}`)
   .copy(`${paths.in.fonts}`, `${paths.out.fonts}`)

   // COMPILE THE CSS & MINIFIY IN PRODUCTION....
   .sass(`${paths.in.css}/app.scss`, `${paths.out.css}`)
   .minify([`${paths.out.css}/app.css`])

   // COMPILE THE JAVASCRIPT....
   .js(`${paths.in.js}/app.js`, `${paths.out.js}`)
   .vue({
      extractVueStyles: true,
      globalVueStyles: false,
      runtimeOnly: true,
   })

   // EXTRACT ALL THE VENDOR STUFF....
   .extract({to: `${paths.out.js}/application-vendor.js`})
   .sourceMaps(false)
   .version()
