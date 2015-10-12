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
 mix.less('app.less', 'public/css', { paths: lessPaths });

 mix.styles([
      'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'
     ],
     'public/css', bowerDir);

 mix.scripts([
      'jquery/dist/jquery.min.js',
      'moment/min/moment.min.js',
      'bootstrap/dist/js/bootstrap.min.js',
      'bootstrap-select/dist/js/bootstrap-select.min.js',
      'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
      'validate/jquery.validate.min.js',
      'dropzone/jquery.dropzone.min.js',
      'modernizr/src/modernizr.js',
      'respond/dest/respond.min.js',
      'html5shiv/dist/html5shiv.min.js'
     ],
     'public/js/vendor.js', bowerDir);

 mix.copy('resources/assets/js/app.js', 'public/js/app.js')
 mix.copy('resources/assets/js/admin.js', 'public/js/admin.js')
 mix.copy(bowerDir + 'font-awesome/fonts', 'public/fonts');
});
