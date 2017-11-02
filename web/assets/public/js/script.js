'use strict';
var sizeWidth = $(window).width();
var hideShowMobile = function(first) {
    if(parseInt(sizeWidth) == parseInt($(window).width()) && !first) {
        return true;
    }
    sizeWidth = $(window).width();
    if($(window).width() < 768) {
            $('.btn-openclose').addClass('collapsed').show();
            $('.block-day .list-group').removeClass('in').addClass('collapse');
            if($('#calendar').length) {
                $('.fc-center').hide();
                $('.fc-listWeek-button').click();
            }
    } else {
        $('.btn-openclose').removeClass('collapsed').hide();
        $('.block-day .list-group').addClass('collapse in');
        if ($('#calendar').length) {
            $('.fc-center').show();
            $('.fc-month-button').click();
        }
    }
}

var addMvtForm = function($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

var $collectionHolder;

// setup an "add a tag" link
var $addMvtLink = $('<a href="#" class="add_movement_link btn btn-default">' + $("#label_add").val() + '</a>');
var $newLinkLi = $('<li></li>').append($addMvtLink);

var xhrDelete = null,
    xhrAdd = null,
    xhrRemove = null;

$( document ).ready(function() {
    
    if($('[data-toggle="popover"]').length) {
        $('[data-toggle="popover"]').popover({
            container: 'body',
            html: true,
            placement: 'auto'
        });
    }
    if($("#strength_date").length) {
        $("#strength_date").datepicker({
            format: "dd-mm-yyyy",
            endDate: new Date()
        });
    }
    if($("#wod_date").length) {
        $("#wod_date").datepicker({
            format: "dd-mm-yyyy",
            endDate: new Date()
        });
    }

    if($('.btn-openclose').length) {
        $(document).on('click', '.btn-openclose', function() {
            var id = $(this).data('target');
            if($(id).hasClass('in')) {
                $(id).removeClass('in');
            } else {
                $(id).addClass('in');
            }
        });
    }

    if($(".movements").length) {
        $collectionHolder = $('ul.movements');
        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addMvtLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new tag form (see next code block)
            addMvtForm($collectionHolder, $newLinkLi);
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
            locale: initialLocaleCode,
            navLinks: false,
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: events
        });
        
        $(document).on('click', '.wod, .wodlist', function () {
            $('#date').html($(this).data('elt').date);
            var movementFormat = '<table class="table"><thead><tr><th>Nom</th><th>Poids</th><th>Répétition</th></tr></thead><tbody>';
            $.each($(this).data('elt').movements, function(i, elt) {
                movementFormat = movementFormat + '<tr><td>' + elt.name + '</td><td>' + elt.weight + ' Kg</td><td>' + elt.repetition + '</td></tr>';
            });
            movementFormat = movementFormat + "</tbody></table>";
            $('#modalAgenda .modal-body').html('<p><label>Type:</label>' + $(this).data("elt").type + '</p><p><label>Score:</label>' + $(this).data("elt").score + '</p><p><label>Mouvement(s):</label></p><p>' + movementFormat + '</p><p><label>commentaire:</label>' + $(this).data("elt").comment + '</p>');
            $('#modalAgenda').modal('show');
        });
    }
    $(window).on('resize', function() {
        hideShowMobile(false);
    });
    hideShowMobile(true);
});
