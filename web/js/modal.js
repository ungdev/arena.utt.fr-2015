'use strict';

var $background = $('#background-modal');
var $close      = $('.modal .close');
var $lastModal;

$('[data-modal]').click(function (e) {
    e.preventDefault();

    $background.fadeIn();
    $lastModal = $('#' + $(this).attr('data-modal'));
    $lastModal.css('top', '100px');
});

$background.add($close).click(function () {
    $background.fadeOut();
    $lastModal.removeAttr('style');
});
