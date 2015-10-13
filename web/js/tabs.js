'use strict';

function initTabs (links, contents) {
    var $tabs         = $(document.querySelectorAll(links));
    var $tabsContents = $(document.querySelectorAll(contents));

    $tabs.on('click', function (e) {
        var $self = $(this);
        $tabs.filter('.active').removeClass('active');
        $self.addClass('active');
        selectTab($self.index(), $tabsContents);
        localStorage.setItem("lastTab" + links, $self.index());
    });

    var tab = localStorage.getItem("lastTab" + links);
    if(tab == null)
        tab = 0;

    selectTab(tab, $tabsContents);
}

function selectTab (index, $tabsContents) {
    var $selected = $tabsContents.filter('.active');
    var $target   = $tabsContents.eq(index);
    var $parent   = $tabsContents.parent().parent();

    if ($selected.is($target)) { return; }

    $parent.data('tab', index);

    if ($selected.length === 0) {
        // Instant show first time
        $target.addClass('active').css('display', 'inline-block');
        return;
    }

    $selected.removeClass('active').animate({ opacity: 0 }, 'fast', function () {
        $selected.hide();
        $target
            .addClass('active')
            .css('display', 'inline-block').css('opacity', 0)
            .animate({ opacity: 1 }, 'fast', function () {
                $(window).trigger('resize').trigger('scroll');
            });
        $(window).trigger('resize').trigger('scroll');
    });
}

initTabs('.eventLinks > li', '.event > .content');
initTabs('.infosLinks > li', '.infos > .content');
initTabs('.accountLinks > li', '.account > .content');
