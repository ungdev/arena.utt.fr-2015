'use strict';

var $teeshirt  = $('#teeshirt');
var $modified  = $('.modified');

$teeshirt.on('ifToggled', function () {
    $modified.stop().css('opacity', 0);
    $modified
        .animate({ opacity: 1 }, 'fast')
        .delay(1500)
        .animate({ opacity: 0 }, 'fast');
});

var selectizeTeeShirt = $('#teeshirtSize').selectize()[0].selectize;
var selectizeGame     = $('#game').selectize()[0].selectize;
var $team             = $('.team');

$('.checkbox').iCheck({
    checkboxClass: 'icheckbox_flat-aero',
    radioClass: 'iradio_flat-aero'
}).on('ifChanged', function (s) {
    if ($(this).prop('checked')) {
        selectizeTeeShirt.enable();
    } else {
        selectizeTeeShirt.disable();
    }
});

selectizeGame.on('change', function (v) {
    if (v === 'hearthstone' || v === 'usf4') {
        $team.val('Extaze').attr('disabled', '');
    } else {
        $team.removeAttr('disabled');
    }
});

$('select').selectize()[0].selectize.on('dropdown_open', function ($e) {
    $($e).parent().find('input').blur();
});
$('.selectize-control').find('input').attr('disabled', true);
$('.selectize-input').css('cursor', 'pointer');
