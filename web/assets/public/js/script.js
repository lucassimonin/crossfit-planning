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
    if($('#calendar').length) {
        var initialLocaleCode = 'fr';
        $('#calendar').fullCalendar({
            header: {
                left: 'title',
                center: 'month,listWeek',
                right: 'prev,next today'
            },
            defaultDate: '2017-09-12',
            locale: initialLocaleCode,
            navLinks: false,
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: [
                {
                    title: 'WOD',
                    start: '2017-09-28',
                    data: {
                        type: 'TABATA',
                        mvts: [
                            {
                                name: 'Test',
                                weight: 12
                            },
                            {
                                name: 'souleve de terre',
                                weight: 12
                            }
                        ]
                    },
                    className: 'wod'
                },
                {
                    title: 'Force',
                    start: '2017-09-20',
                    data: {
                        mvt: 'souleve de terre',
                        weight:60
                    },
                    className: 'strength'
                }
            ]
        });
        
        $(document).on('click', '.wod', function () {
            $('#modalAgenda').modal('show');
            console.log($(this).data('elt'));
        });
    }
});
