var elixir = require('laravel-elixir');

//require('laravel-elixir-vue-2');


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

var jqueryPath = __dirname + '/node_modules/jquery/dist/jquery.js';
var bootstrapPath = __dirname + '/node_modules/bootstrap-sass/javacripts/bootstrap.js';

elixir(function(mix) {
    mix.sass('app.scss')
       .webpack([jqueryPath, bootstrapPath, 'Application.js'], __dirname + '/public/js/app.js');
});
