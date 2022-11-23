const mix = require('laravel-mix');
const path = require('path');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/backend'),
        }
    }
});

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/backend'),
        }
    }
}

console.log(mix.dumpWebpackConfig())

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

mix.js('resources/backend/main.js', 'public/js').extract(['vue', 'axios']);

if (mix.inProduction()) {
    mix.version();
}
