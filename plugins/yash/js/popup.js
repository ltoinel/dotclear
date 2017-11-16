$(function() {
	$('#yash-cancel').click(function() {
		window.close();
		return false;
	});

	$('#yash-ok').click(function() {
		sendClose();
		window.close();
		return false;
	});

	function sendClose() {
		var insert_form = $('#yash-form').get(0);
		if (insert_form == undefined) { return; }
		var tb = window.opener.the_toolbar;
		var data = tb.elements.yash.data;
		data.syntax = insert_form.syntax.value;
		tb.elements.yash.fncall[tb.mode].call(tb);
	};
});
