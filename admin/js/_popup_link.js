/*global $, dotclear */
'use strict';

$(function() {
  // Enable submet button only if mandotory field is not empty
  $('#link-insert-ok').prop('disabled', true);
  $('#link-insert-ok').addClass('disabled');
  $('#href').keyup(function() {
    $('#link-insert-ok').prop('disabled', (this.value == '' ? true : false));
    $('#link-insert-ok').toggleClass('disabled', (this.value == '' ? true : false));
  });

  // Set focus on #href input
  $('#href').focus();

  // Deal with enter key on link insert popup form : every form element will be filtered but Cancel button
  dotclear.enterKeyInForm('#link-insert-form', '#link-insert-ok', '#link-insert-cancel');
});
