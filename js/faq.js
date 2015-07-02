'use strict';

var $questions = $(document.querySelectorAll('.question'));

var active = true;
$questions.on('click', function (e) {
    if (!active) { return; }
    active = false;
    var $self = $(this);
    if ($self.hasClass('activated')) {
        $self.removeClass('activated');
        hideOne($self);
    } else {
        var $before = $questions.filter('.activated');
        $before.removeClass('activated');
        $self.addClass('activated');
        showOne($self);
        hideOne($before);
    }
});

function hideOne ($elem) {
    if ($elem.length === 0) { return; }
    $(window).trigger('resize').trigger('scroll');
    $elem.children().last().fadeOut('fast', function () {
        $(window).trigger('resize').trigger('scroll');
        $elem.css('height', '120px').css('width', '150px');
        setTimeout(function () {
            $(window).trigger('resize').trigger('scroll');
            $elem.children().first().fadeIn(function () {
                active = true;
            });
        }, 200);
    });
}

function showOne ($elem) {
    $(window).trigger('resize').trigger('scroll');
    $elem.children().first().fadeOut('fast', function () {
        $(window).trigger('resize').trigger('scroll');
        $elem.css('height', '200px').css('width', '300px');
        setTimeout(function () {
            $(window).trigger('resize').trigger('scroll');
            $elem.children().last().fadeIn(function () {
                $(window).trigger('resize').trigger('scroll');
                active = true;
            });
        }, 200);
    });
}
