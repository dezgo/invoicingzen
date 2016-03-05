var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
//    mix.sass('app.scss');

    mix.styles([
        'libs/bootstrap.css',
        'libs/font-awesome.css',
        'app.css',
        'libs/select2.css',
        'libs/jquery-ui.css'
    ]);

    mix.scripts([
        'image_manipulation.js',
        'libs/jquery.js',
        'libs/jquery-ui.js',
        'libs/select2.js',
        'libs/bootstrap.js'
    ])
});
