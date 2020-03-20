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
    
    locale: $('html').attr('lang'),
    lastYPos: {},
    base: '/',

    call: function(h, m) {
        console.log('call', h);
        var yPos = $(document).scrollTop();
        if(yPos != 0) page.lastYPos[document.location.href] = yPos;
        if(m) page.base = '/' + document.location.hash.substring(1);
        document.location.href = '/#' + h.substring(1);
        return false;
    },

    post: function(form, target, refresh, callback) {
        var path = form.action;
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
                if(callback) {
                    callback();
                }
            }
        });
        return false;
    },

    post2: function(form) {
        console.log('post2', form);
        var path = form.action;
        $.ajax({
            type: 'post',
            url: path,
            data: $(form).serialize(),
            success: function (data) {
                eval(data);
            },
            error:function(msg){
                alert( "Error : " + msg );
            }
        });
        return false;
    },

    from_hash: function(s) {
        console.log('from_hash', s);
        if(s.length > 1) {
            h = s.substring(1);
            return page.load(h, 'central-content');
        }
        return true;
    },

    from_hash2: function(s) {
        console.log('from_hash2', s);
        if(s.length > 1) {
            h = s.substring(1);
            return page.load2('/' + h);
        }
        return true;
    },

    load: function(path, target, refresh) {
        console.log('load', path, target, refresh);
        $.ajax({
            type: 'get',
            url: '/' + path,
            success: function (data) {
                $('#' + target).html(data);
                /*console.log(page.lastYPos, document.location.href)*/
                if(page.lastYPos && page.lastYPos[document.location.href]) {
                    $(document).scrollTop(page.lastYPos[document.location.href]);
                    page.lastYPos[document.location.href] = 0;
                }
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

    load2: function(path) {
        console.log('load2', path);
        $.ajax({
            type: 'get',
            url: path,
            success: function (data) {
                eval(data);
            }
        });
        return false;
    },

    load_navbar: function(path, target) {
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
        var items = $('.menu-item');
        items.removeClass('active');
        $.each(items, function(index, el) {
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
                /*$('#modal-body').html(data);*/
                eval(data);
                /*$('#puzzleEditModal').modal('show');*/
                if(callback) callback();
            }
        });
        return false;        
    }

}

/*$(document).ready(function () {
    if(document.location.hash.length > 1) {
        page.from_hash2(document.location.hash);
    } else {
        var pathname = document.location.pathname;
        if(pathname == '/') pathname = page.locale == 'fr' ? '/accueil' : '/home';
        page.call(pathname);
    }
});*/

$(window).on('hashchange', function() {
    console.log('hashchange');
    return page.from_hash2(document.location.hash);
});

$(window).scroll(function(){
    var scroll = $(window).scrollTop();
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