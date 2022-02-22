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

let current_admin_template= 'defaultBS41Backend';//'defaultBS41Backend'
let current_frontend_template= 'cardsBS41Frontend';//'cardsBS41Frontend'
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/' + current_admin_template + '/backend.scss', 'public/css/' + current_admin_template)


    // .sass('resources/sass/' + current_admin_template + '/style_lg.scss', 'public/css/' + current_admin_template)
    // .sass('resources/sass/' + current_admin_template + '/style_md.scss', 'public/css/' + current_admin_template)
    // .sass('resources/sass/' + current_admin_template + '/style_sm.scss', 'public/css/' + current_admin_template)
    // .sass('resources/sass/' + current_admin_template + '/style_xs_320.scss', 'public/css/' + current_admin_template)
    // .sass('resources/sass/' + current_admin_template + '/style_xs_480.scss', 'public/css/' + current_admin_template)
    // .sass('resources/sass/' + current_admin_template + '/style_xs_600.scss', 'public/css/' + current_admin_template)



    
    .sass('resources/sass/' + current_frontend_template + '/frontend.scss', 'public/css/' + current_frontend_template)

    // .sass('resources/sass/' + current_frontend_template + '/style_lg.scss', 'public/css/' + current_frontend_template)
    // .sass('resources/sass/' + current_frontend_template + '/style_md.scss', 'public/css/' + current_frontend_template)
    // .sass('resources/sass/' + current_frontend_template + '/style_sm.scss', 'public/css/' + current_frontend_template)
    // .sass('resources/sass/' + current_frontend_template + '/style_xs_320.scss', 'public/css/' + current_frontend_template)
    // .sass('resources/sass/' + current_frontend_template + '/style_xs_480.scss', 'public/css/' + current_frontend_template)
    // .sass('resources/sass/' + current_frontend_template + '/style_xs_600.scss', 'public/css/' + current_frontend_template)


    .sass('resources/sass/debug.scss',                                          'public/css' )
;

mix.copy('node_modules/font-awesome/fonts', 'public/fonts');

