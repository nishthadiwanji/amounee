let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

var appJSPath = 'resources/assets/js/';
var appSCSSPath = 'resources/assets/sass/';
var additionaVendors = "node_modules/";

mix.babel([
    additionaVendors + 'bootstrap-fileinput/js/fileinput.js',
],'public/js/additional-vendors.js');

mix.styles([
    additionaVendors + 'bootstrap-fileinput/css/fileinput.css',
    'resources/assets/vendors/font-awesome/css/all.css',
    'resources/assets/css/fonts.css'
], 'public/css/additional-vendors.css');

mix.copyDirectory('resources/assets/vendors/font-awesome/webfonts', 'public/webfonts');

mix.babel([
    appJSPath+'amounee/ajax-setup.js',
    appJSPath+'amounee/additional-validations.js',
    appJSPath+'amounee/amounee-run.js',
    appJSPath+'amounee/lists.js',
    appJSPath+'amounee/forms.js',
    appJSPath+'amounee/tables.js',
    appJSPath+'auth/authorization.js',
    appJSPath+'auth/login.js',
    appJSPath+'auth/reset-password.js',
    appJSPath+'auth/change-password.js',
    appJSPath+'auth/generate-password.js',
    appJSPath+'users/team-member.js',
    appJSPath+'users/artisan.js',
    appJSPath+'category/category.js',
    appJSPath+'product/product.js',
    appJSPath+'amounee/approve-reject.js',


],'public/js/app.js');

mix.sass(appSCSSPath+'style.scss', 'public/css/app.css');

if (mix.inProduction()) {
    mix.minify('public/css/app.css');
    mix.babel('public/js/app.js','public/js/app.min.js');
}
else{
    mix.version();
}
