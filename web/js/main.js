'use strict';

var $window          = $(window);
var $body            = $(document.body);
var $cookies         = $('#cookies');
var $menu            = $('#menu-opener');
var $realMenu        = $('#real-menu');
var $nav             = $('header nav').first();
var $scrollMenu      = $('#scrollMenu');
var $subscribe       = $('#subscribe');
var $mail            = $('input[name=subscription_email]');
var $subscribeButton = $('#subscribe > input[type=submit]');

// Heartbug
var k = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65],
n = 0;
$(document).on('keydown', function (e) {
    if (e.keyCode === k[n]) {
        n++;
        if (n === k.length) {
            var $heart = $('<div class="heart-shape" />');
            $(document.body).append($heart);
            setTimeout(function () {
                $heart.remove();
            }, 2500);
        }
    } else {
        n = 0;
    }
});

// Parallax
$('.parallax-1').parallax({ imageSrc: '/img/bg1.jpg', position: 'center -70', naturalWidth: 3000, naturalHeight: 1439 });
$('.parallax-2').parallax({ imageSrc: '/img/bg2.jpg', naturalWidth: 2560, naturalHeight: 1600 });
$('.parallax-3').parallax({ imageSrc: '/img/bg3.jpg', naturalWidth: 3024, naturalHeight: 1600 });
$('.parallax-4').parallax({ imageSrc: '/img/bg4.jpg', naturalWidth: 3840, naturalHeight: 2160 });
$('.parallax-5').parallax({ imageSrc: '/img/bg5.jpg', naturalWidth: 3210, naturalHeight: 2166 });
$('.parallax-6').parallax({ imageSrc: '/img/bg6.jpg', naturalWidth: 4134, naturalHeight: 2480 });
$('.parallax-7').parallax({ imageSrc: '/img/bg7.jpg', naturalWidth: 4096, naturalHeight: 1800 });

// Menu
$menu.click(function (e) {
    e.preventDefault();
    $realMenu.slideToggle('fast', function () {
        $(window).trigger('resize').trigger('scroll');
    });
    return false;
});

// iCheck
$('input').iCheck({
    checkboxClass: 'icheckbox_flat-aero',
    radioClass: 'iradio_flat-aero'
});

// Cookies
$cookies.children('a').click(function (e) {
    e.preventDefault();
    localStorage.setItem('cookie', true);
    $cookies.fadeOut();
    return false;
});

// Scroll menu
function checkForScroll () {
    var posOriginalMenu = $nav.offset().top - $window.scrollTop() + $nav.height();

    if (posOriginalMenu < 10) {
        $scrollMenu.css('top', 0);
    } else {
        $scrollMenu.css('top', -75);
    }

    requestAnimationFrame(checkForScroll);
}

checkForScroll();

// Email subscription
$mail.on('keyup', function (e) {
    if (e.keyCode === 13 || e.charCode === 13 || e.which === 13) {
        if (!$subscribeButton.attr('disabled')) {
            return $subscribe.submit();
        }
    } else {
        if ($mail.val().length == 0) {
            $subscribeButton.attr('disabled', '');
        } else {
            $subscribeButton.removeAttr('disabled');
        }
    }
});

$subscribe.on('submit', function (e) {
    e.preventDefault();
    // Don't be a regex hipster: rely on the browser
    // and the server for the email validation.
    var $form = $(this);
    console.log($form.action);
    $.post($form.attr('action'), { 'email': $mail.val() }, function (data) {
        $subscribeButton.val(data.message);
        if (data.status === 'success') {
            $subscribeButton.attr('disabled', 'disabled').css('background', 'green');
        } else {
            $subscribeButton.css('background', 'red');
        }
    });
});
