'use strict';

var hideShowMobile = function() {
    if($(window).width() < 768) {
        $('.btn-openclose').addClass('collapsed').show();
        $('.block-day .list-group').removeClass('in').addClass('collapse');
    } else {
        $('.btn-openclose').removeClass('collapsed').hide();
        $('.block-day .list-group').addClass('collapse in');
    }
}

var xhrDelete = null,
    xhrAdd = null,
    xhrRemove = null;

$( document ).ready(function() {
    $(window).on('resize', function() {
        hideShowMobile();
    });
    hideShowMobile();
    if($('[data-toggle="popover"]').length) {
        $('[data-toggle="popover"]').popover({
            container: 'body',
            html: true,
            placement: 'auto'
        });
    }
});
