(function($) {
  'use strict';

  $.pageTabs = function(start_tab, opts) {
    var defaults = {
      containerClass: 'part-tabs',
      partPrefix: 'part-',
      contentClass: 'multi-part',
      activeClass: 'part-tabs-active',
      idTabPrefix: 'part-tabs-'
    };

    $.pageTabs.options = $.extend({}, defaults, opts);
    var active_tab = start_tab || '';
    var hash = $.pageTabs.getLocationHash();
    var subhash = $.pageTabs.getLocationSubhash();

    if (hash !== undefined && hash) {
      window.scrollTo(0, 0);
      active_tab = hash;
    } else if (active_tab == '') { // open first part
      active_tab = $('.' + $.pageTabs.options.contentClass + ':eq(0)').attr('id');
    }

    createTabs();

    $('ul li', '.' + $.pageTabs.options.containerClass).click(function(e) {
      if ($(this).hasClass($.pageTabs.options.activeClass)) {
        return;
      }

      $(this).parent().find('li.' + $.pageTabs.options.activeClass).removeClass($.pageTabs.options.activeClass);
      $(this).addClass($.pageTabs.options.activeClass);
      $('.' + $.pageTabs.options.contentClass + '.active').removeClass('active').hide();

      var part_to_activate = $('#' + $.pageTabs.options.partPrefix + getHash($(this).find('a').attr('href')));

      part_to_activate.addClass('active').show();
      if (!part_to_activate.hasClass('loaded')) {
        part_to_activate.onetabload();
        part_to_activate.addClass('loaded');
      }

      part_to_activate.tabload();
    });

    $(window).bind('hashchange onhashchange', function(e) {
      $.pageTabs.clickTab($.pageTabs.getLocationHash());
    });

    $.pageTabs.clickTab(active_tab);

    if (subhash !== undefined) {
      var elt = document.getElementById(subhash);
      // Tab displayed, now scroll to the sub-part if defined in original document.location (#tab.sub-part)
      elt.scrollIntoView()
      // Give focus to the sub-part if possible
      $('#' + subhash).addClass('focus').focusout(function() {
        $(this).removeClass('focus');
      });
      elt.focus();
    }

    return this;
  };

  var createTabs = function createTabs() {
    var lis = [],
      li_class = '';

    $('.' + $.pageTabs.options.contentClass).each(function() {
      $(this).hide();
      lis.push('<li id="' + $.pageTabs.options.idTabPrefix + $(this).attr('id') + '">' +
        '<a href="#' + $(this).attr('id') + '">' + $(this).attr('title') + '</a></li>');
      $(this).attr('id', $.pageTabs.options.partPrefix + $(this).attr('id')).prop('title', '');
    });

    $('<div class="' + $.pageTabs.options.containerClass + '"><ul>' + lis.join('') + '</ul></div>')
      .insertBefore($('.' + $.pageTabs.options.contentClass).get(0));
  };

  var getHash = function getHash(href) {
    var href = href || '';

    return href.replace(/.*#/, '');
  };

  $.pageTabs.clickTab = function(tab) {
    if (tab == '') {
      tab = getHash($('ul li a', '.' + $.pageTabs.options.containerClass + ':eq(0)').attr('href'));
    } else if ($('#' + $.pageTabs.options.idTabPrefix + tab, '.' + $.pageTabs.options.containerClass).length == 0) {
      // try to find anchor in a .multi-part div
      if ($('#' + tab).length == 1) {
        var div_content = $('#' + tab).parents('.' + $.pageTabs.options.contentClass);
        if (div_content.length == 1) {
          tab = div_content.attr('id').replace($.pageTabs.options.partPrefix, '');
        } else {
          tab = getHash($('ul li a', '.' + $.pageTabs.options.containerClass + ':eq(0)').attr('href'));
        }
      } else {
        tab = getHash($('ul li a', '.' + $.pageTabs.options.containerClass + ':eq(0)').attr('href'));
      }
    }

    $('ul li a', '.' + $.pageTabs.options.containerClass).filter(function() {
      return getHash($(this).attr('href')) == tab;
    }).parent().click();
  };

  $.pageTabs.getLocationHash = function() {
    // Return the URL hash (without subhash — #hash[.subhash])
    var h = getHash(document.location.hash).split('.');
    return h[0];
  };
  $.pageTabs.getLocationSubhash = function() {
    // Return the URL subhash if present (without hash — #hash[.subhash])
    var sh = getHash(document.location.hash).split('.');
    return sh[1];
  };
})(jQuery);

jQuery.fn.tabload = function(f) {
  this.each(function() {
    if (f) {
      chainHandler(this, 'tabload', f)
    } else {
      var h = this.tabload;
      if (h) {
        h.apply(this);
      }
    }
  });
  return this;
};

jQuery.fn.onetabload = function(f) {
  this.each(function() {
    if (f) {
      chainHandler(this, 'onetabload', f);
    } else {
      var h = this.onetabload;
      if (h != null) {
        h.apply(this);
        this.onetabload = null;
      }
    }
  });
  return this;
};
