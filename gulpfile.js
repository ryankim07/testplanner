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

var elixir = require('laravel-elixir');

var bowerDir = './resources/assets/vendor/';

var lessPaths = [
    bowerDir + "bootstrap/less",
    bowerDir + "font-awesome/less",
    bowerDir + "bootstrap-select/less"
];

elixir(function(mix) {

    mix.less(['app.less'], 'public/css', { paths: lessPaths });

    mix.scripts([
            'jquery/dist/jquery.min.js',
            'jquery-ui/jquery-ui.min.js',
            'moment/min/moment.min.js',
            'bootstrap/dist/js/bootstrap.min.js',
            'bootstrap-select/dist/js/bootstrap-select.min.js',
            'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
            'dropzone/jquery.dropzone.min.js',
        ], 'public/js/vendor.js', bowerDir
    );

    mix.copy('resources/assets/js/app.js', 'public/js/app.js')
    mix.copy('resources/assets/js/main.js', 'public/js/main.js')
    mix.copy(bowerDir + 'font-awesome/fonts', 'public/fonts');

});
