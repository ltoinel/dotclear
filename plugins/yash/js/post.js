jsToolBar.prototype.elements.yashSpace = {type: 'space',
	format: {
		wysiwyg: true,
		wiki: true,
		xhtml: true,
		markdown: true
	}
};

jsToolBar.prototype.elements.yash = {
	type: 'button',
	title: 'Highlighted Code',
	context: 'post',
	icon: 'index.php?pf=yash/icon.png',
	fn:{},
	fncall:{},
	open_url:'plugin.php?p=yash&popup=1',
	data:{},
	popup: function() {
		window.the_toolbar = this;
		this.elements.yash.data = {};

		var p_win = window.open(this.elements.yash.open_url,'dc_popup',
		'alwaysRaised=yes,dependent=yes,toolbar=yes,height=240,width=480,'+
		'menubar=no,resizable=yes,scrollbars=yes,status=no');
	}
};

jsToolBar.prototype.elements.yash.fn.wiki = function() {
	this.elements.yash.popup.call(this);
};
jsToolBar.prototype.elements.yash.fn.xhtml = function() {
	this.elements.yash.popup.call(this);
};
jsToolBar.prototype.elements.yash.fn.markdown = function() {
	this.elements.yash.popup.call(this);
};

jsToolBar.prototype.elements.yash.fncall.wiki = function() {
	var stag = '\n///yash ' + this.elements.yash.data.syntax + '\n';
	var etag = '\n///\n';
	this.encloseSelection(stag,etag);
};
jsToolBar.prototype.elements.yash.fncall.xhtml = function() {
	var stag = '<pre class="brush: ' + this.elements.yash.data.syntax + '">\n';
	var etag = '\n</pre>\n';
	this.encloseSelection(stag,etag);
};
jsToolBar.prototype.elements.yash.fncall.markdown = function() {
	var stag = '<pre class="brush: ' + this.elements.yash.data.syntax + '">\n';
	var etag = '\n</pre>\n';
	this.encloseSelection(stag,etag);
};
