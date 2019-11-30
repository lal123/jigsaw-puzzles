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

page = {
    
    call: function(h) {
        console.log('page.call', h);
        document.location.href = '/#' + h.substring(1);
        return false;
    },

    from_hash: function(s) {
        console.log('page.from_hash', s);
        if(s.length > 1) {
            h = s.substring(1);
            return page.load(h, 'central-content');
        }
        return true;
    },

    load: function(path, target) {
        console.log('page.load', path, target);
        $.ajax({
            type: 'get',
            url: '/' + path,
            success: function (data) {
                $('#' + target).html(data);
            }
        });
        return false;
    }
}

$(document).ready(function () {
    page.from_hash(document.location.hash);
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

$(window).on('hashchange', function() {
    console.log('hashchange', document.location.hash);
    return page.from_hash(document.location.hash);
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