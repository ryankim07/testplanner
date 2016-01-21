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
var bowerDir = 'vendor/';

elixir(function(mix) {

    mix.sass('app.scss');

    mix.styles([
        "vendor/bootstrap/dist/css/bootstrap.min.css",
        "vendor/bootstrap-select/dist/css/bootstrap-select.min.css",
        "vendor/font-awesome/css/font-awesome.min.css",
        "vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css"
    ], 'public/css/app.css', 'resources/assets');

    mix.scripts([
        "vendor/jquery/dist/jquery.min.js",
        "vendor/jquery-ui/jquery-ui.min.js",
        "vendor/moment/min/moment.min.js",
        "vendor/bootstrap/dist/js/bootstrap.min.js",
        "vendor/bootstrap-select/dist/js/bootstrap-select.min.js",
        "vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js",
        "vendor/dropzone/jquery.dropzone.min.js"
    ], "public/js/app.js", "resources/assets");

    mix.copy("resources/assets/vendor/font-awesome/fonts", "public/fonts");
    mix.copy("resources/assets/js/main.js", "public/js/main.js")
    mix.copy("resources/assets/js/ticketbuilder.js", "public/js/ticketbuilder.js")
});
