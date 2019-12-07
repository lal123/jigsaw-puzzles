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
    
    locale : $('html').attr('lang'),

    call: function(h) {
        //return true;
        console.log('page.call', h);
        document.location.href = '/#' + h.substring(1);
        return false;
    },

    post: function(form, target, refresh) {
        var path = form.action;
        console.log('page.post', $(form).serialize(), path, target, refresh);
        $.ajax({
            type: 'post',
            url: path,
            data: $(form).serialize(),
            success: function (data) {
                $('#' + target).html(data);
                if(refresh) {
                    page.load('top-navbar', 'top-navbar', true);
                } else {
                    page.menu_sync(path);
                }
            }
        });
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

    load: function(path, target, refresh) {
        console.log('page.load', path, target);
        $.ajax({
            type: 'get',
            url: '/' + path,
            success: function (data) {
                $('#' + target).html(data);
                if(refresh) { 
                    page.load_navbar('top-navbar', 'top-navbar');
                } else {
                    page.menu_sync('/' + path);
                }
                $('.modal-backdrop').hide();
            }
        });
        return false;
    },

    load_navbar: function(path, target) {
        console.log('page.load_navbar', path, target);
        $.ajax({
            type: 'get',
            url: '/' + path,
            success: function (data) {
                $('#' + target).html(data);
            }
        });
        return false;
    },

    menu_sync: function(path) {
        console.log('menu_sync', path);
        var items = $('.menu-item');
        items.removeClass('active');
        $.each(items, function(index, el) {
            console.log('item', $(el).attr("href"), path)
            if($(el).attr("href") == path) {
                $(el).addClass('active');
            }
        });
    }
}

puzzles = {

    edit_modal: function(post_url, post_data, callback) {
        $('#modal-title').html("Edit a Puzzle");
        $.ajax({
            type: 'post',
            url: post_url,
            data: post_data,
            success: function (data) {
                $('#modal-body').html(data);
                $('#puzzleEditModal').modal("show");
                callback();
            }
        });
        return false;        
    }
}

$(document).ready(function () {
    if(document.location.hash.length > 1) {
        page.from_hash(document.location.hash);
    } else {
        var pathname = document.location.pathname;
        if(pathname == '/') pathname = page.locale == 'fr' ? '/accueil' : '/home';
        page.call(pathname);
    }
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