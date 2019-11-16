let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/dist').extract();
mix.sass('resources/scss/app.scss', 'public/dist');

mix.copy('vendor/swagger-api/swagger-ui/dist', 'public/assets/api');
