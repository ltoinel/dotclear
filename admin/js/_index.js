/*global $, dotclear, jsToolBar */
'use strict';

dotclear.dbCommentsCount = function() {
  var params = {
    f: 'getCommentsCount',
    xd_check: dotclear.nonce,
  };
  $.get('services.php', params, function(data) {
    if ($('rsp[status=failed]', data).length > 0) {
      // For debugging purpose only:
      // console.log($('rsp',data).attr('message'));
      window.console.log('Dotclear REST server error');
    } else {
      var nb = $('rsp>count', data).attr('ret');
      if (nb != dotclear.dbCommentsCount_Counter) {
        // First pass or counter changed
        var icon = $('#dashboard-main #icons p a[href="comments.php"]');
        if (icon.length) {
          // Update count if exists
          var nb_label = icon.children('span.db-icon-title');
          if (nb_label.length) {
            nb_label.text(nb);
          }
        }
        // Store current counter
        dotclear.dbCommentsCount_Counter = nb;
      }
    }
  });
};
dotclear.dbPostsCount = function() {
  var params = {
    f: 'getPostsCount',
    xd_check: dotclear.nonce,
  };
  $.get('services.php', params, function(data) {
    if ($('rsp[status=failed]', data).length > 0) {
      // For debugging purpose only:
      // console.log($('rsp',data).attr('message'));
      window.console.log('Dotclear REST server error');
    } else {
      var nb = $('rsp>count', data).attr('ret');
      if (nb != dotclear.dbPostsCount_Counter) {
        // First pass or counter changed
        var icon = $('#dashboard-main #icons p a[href="posts.php"]');
        if (icon.length) {
          // Update count if exists
          var nb_label = icon.children('span.db-icon-title');
          if (nb_label.length) {
            nb_label.text(nb);
          }
        }
        // Store current counter
        dotclear.dbPostsCount_Counter = nb;
      }
    }
  });
};
$(function() {
  function quickPost(f, status) {
    if ($.isFunction('jsToolBar') && (contentTb.getMode() == 'wysiwyg')) {
      contentTb.syncContents('iframe');
    }

    var params = {
      f: 'quickPost',
      xd_check: dotclear.nonce,
      post_title: $('#post_title', f).val(),
      post_content: $('#post_content', f).val(),
      cat_id: $('#cat_id', f).val(),
      post_status: status,
      post_format: $('#post_format', f).val(),
      post_lang: $('#post_lang', f).val(),
      new_cat_title: $('#new_cat_title', f).val(),
      new_cat_parent: $('#new_cat_parent', f).val()
    };

    $('p.qinfo', f).remove();

    $.post('services.php', params, function(data) {
      var msg;
      if ($('rsp[status=failed]', data).length > 0) {
        msg = '<p class="qinfo"><strong>' + dotclear.msg.error +
          '</strong> ' + $('rsp', data).text() + '</p>';
      } else {
        msg = '<p class="qinfo">' + dotclear.msg.entry_created +
          ' - <a href="post.php?id=' + $('rsp>post', data).attr('id') + '">' +
          dotclear.msg.edit_entry + '</a>';
        if ($('rsp>post', data).attr('post_status') == 1) {
          msg += ' - <a href="' + $('rsp>post', data).attr('post_url') + '">' +
            dotclear.msg.view_entry + '</a>';
        }
        msg += '</p>';
        $('#post_title', f).val('');
        $('#post_content', f).val('');
        $('#post_content', f).change();
        if ($.isFunction('jsToolBar') && (contentTb.getMode() == 'wysiwyg')) {
          contentTb.syncContents('textarea');
        }
        $('#cat_id', f).val('0');
        $('#new_cat_title', f).val('');
        $('#new_cat_parent', f).val('0');
      }

      $('fieldset', f).prepend(msg);
    });
  }

  var f = $('#quick-entry');
  if (f.length > 0) {
    if ($.isFunction(jsToolBar)) {
      var contentTb = new jsToolBar($('#post_content', f)[0]);
      contentTb.switchMode($('#post_format', f).val());
    }

    $('input[name=save]', f).click(function() {
      quickPost(f, -2);
      return false;
    });

    if ($('input[name=save-publish]', f).length > 0) {
      var btn = $('<input type="submit" value="' + $('input[name=save-publish]', f).val() + '" />');
      $('input[name=save-publish]', f).remove();
      $('input[name=save]', f).after(btn).after(' ');
      btn.click(function() {
        quickPost(f, 1);
        return false;
      });
    }

    $('#new_cat').toggleWithLegend($('#new_cat').parent().children().not('#new_cat'), {
      // no cookie on new category as we don't use this every day
      legend_click: true
    });
  }

  // allow to hide quick entry div, and remember choice
  $('#quick h3').toggleWithLegend($('#quick').children().not('h3'), {
    legend_click: true,
    user_pref: 'dcx_quick_entry'
  });

  // check if core update available
  var params = {
    f: 'checkCoreUpdate',
    xd_check: dotclear.nonce
  };
  $.post('services.php', params, function(data) {
    if ($('rsp[status=failed]', data).length > 0) {
      // Silently fail as a forced checked my be done with admin update page
    } else {
      if ($('rsp>update', data).attr('check') == 1) {
        // Something has to be displayed
        var xml = $('rsp>update', data).attr('ret');
        $('#content h2').after(xml);
      }
    }
  });

  // check if some news are available
  params = {
    f: 'checkNewsUpdate',
    xd_check: dotclear.nonce
  };
  $.post('services.php', params, function(data) {
    if ($('rsp[status=failed]', data).length > 0) {
      // Silently fail
    } else {
      if ($('rsp>news', data).attr('check') == 1) {
        // Something has to be displayed
        var xml = $('rsp>news', data).attr('ret');
        if ($('#dashboard-boxes').length == 0) {
          // Create the #dashboard-boxes container
          $('#dashboard-main').append('<div id="dashboard-boxes"></div>');
        }
        if ($('#dashboard-boxes div.db-items').length == 0) {
          // Create the #dashboard-boxes div.db-items container
          $('#dashboard-boxes').prepend('<div class="db-items"></div>');
        }
        $('#dashboard-boxes div.db-items').prepend(xml);
      }
    }
  });

  // run counters' update on some dashboard icons
  // Comments (including everything)
  var icon_com = $('#dashboard-main #icons p a[href="comments.php"]');
  if (icon_com.length) {
    // Icon exists on dashboard
    // First pass
    dotclear.dbCommentsCount();
    // Then fired every 60 seconds (1 minute)
    dotclear.dbCommentsCount_Timer = setInterval(dotclear.dbCommentsCount, 60 * 1000);
  }
  // Posts
  var icon_com = $('#dashboard-main #icons p a[href="posts.php"]');
  if (icon_com.length) {
    // Icon exists on dashboard
    // First pass
    dotclear.dbPostsCount();
    // Then fired every 600 seconds (10 minutes)
    dotclear.dbPostsCount_Timer = setInterval(dotclear.dbCommentsPost, 600 * 1000);
  }
});
