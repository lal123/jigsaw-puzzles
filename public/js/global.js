page = {
    
    locale: $('html').attr('lang'),
    call_p: {},

    call: function(h, p) {
        if(p) console.log('call p', p);
        page.call_p = p;
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
        if(s.length > 1) {
            h = s.substring(1);
            return page.load(h, 'central-content');
        }
        return true;
    },

    load: function(path, target, refresh) {
        $.ajax({
            type: 'get',
            url: '/' + path,
            success: function (data) {
                $('#' + target).html(data);
                if(page.call_p && page.call_p.p_id) {
                    $(document).scrollTop($('#p_' + page.call_p.p_id).offsetTop);
                    console.log('ready', $('#p_' + page.call_p.p_id).offsetTop);
                    page.call_p = {};
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
    return page.from_hash(document.location.hash);
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