/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

$(document).ready(function () {
    $('.puzzle_edit_modal').click(function () {
        $('#modal-title').html("Edit a Puzzle");
        $.ajax({
            type: 'post',
            url: this.href,
            success: function (data) {
                $('#modal-body').html(data);
                $('#puzzleEditModal').modal("show");
            }
        });
    });
});

$(window).scroll(function(){
    var scroll = $(window).scrollTop();
    //console.log('scroll', scroll);
    if(scroll > 100){
        $('.navbar-brand.main').show();
        $('.navbar-brand.secondary').hide();
        $('#top-navbar').addClass('fixed-top');
        $('#central-content').addClass('shifted');
    } else{
        if($('body').width() >= 992) {
            $('.navbar-brand.main').hide();
            $('.navbar-brand.secondary').show();
        }
        $('#top-navbar').removeClass('fixed-top');
        $('#central-content').removeClass('shifted');
    }
});