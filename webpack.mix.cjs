const mix = require('laravel-mix');

mix.js('resources/js/index.jsx', 'public/js') // Chỉ định entry point của React
   .react();