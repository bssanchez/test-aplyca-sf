/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
require('bootstrap');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('tinymce');

const $ = require('jquery');

// any CSS you import will output into a single css file (app.css in this case)
import '../scss/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

$(document).ready(() => {
  // Initialize the app
  tinymce.init({
    selector: '.tinymce'
  });
});
