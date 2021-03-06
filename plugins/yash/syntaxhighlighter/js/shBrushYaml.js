/**
 * SyntaxHighlighter
 * http://alexgorbatchev.com/SyntaxHighlighter
 *
 * SyntaxHighlighter is donationware. If you are using it, please donate.
 * http://alexgorbatchev.com/SyntaxHighlighter/donate.html
 *
 * @version
 * 3.0.9 (Thu, 04 Dec 2014 12:32:21 GMT)
 *
 * @copyright
 * Copyright (C) 2004-2013 Alex Gorbatchev.
 *
 * @license
 * Dual licensed under the MIT and GPL licenses.
 */
;(function()
{
	// CommonJS
	SyntaxHighlighter = SyntaxHighlighter || (typeof require !== 'undefined'? require('shCore').SyntaxHighlighter : null);

	function Brush()
	{
		var constants	= '~ true false on off';

		this.regexList = [
			{ regex: SyntaxHighlighter.regexLib.singleLinePerlComments, css: 'comments' },		// comment
			{ regex: SyntaxHighlighter.regexLib.doubleQuotedString,		css: 'string' },		// double quoted string
			{ regex: SyntaxHighlighter.regexLib.singleQuotedString,		css: 'string' },		// single quoted string
			{ regex: /^\s*([a-z0-9\._-])+\s*:/gmi,						css: 'variable' },		// key
			{ regex: /\s?(\.)([a-z0-9\._-])+\s?:/gmi,					css: 'comments' },		// section
			{ regex: /\s(@|:)([a-z0-9\._-])+\s*$/gmi,					css: 'variable bold' },	// variable, reference
			{ regex: /\s+\d+\s?$/gm,									css: 'color2 bold' },	// integers
			{ regex: /(\{|\}|\[|\]|,|~|:)/gm,							css: 'constants' },		// inline hash and array, comma, null
			{ regex: /^\s+(-)+/gm,										css: 'string bold' },	// array list entry
			{ regex: /^---/gm,											css: 'string bold' },	// category
			{ regex: new RegExp(this.getKeywords(constants), 'gmi'),	css: 'constants' }		// constants
		];

	};

	Brush.prototype	= new SyntaxHighlighter.Highlighter();
	Brush.aliases	= ['yaml', 'yml'];

	SyntaxHighlighter.brushes.Yaml = Brush;

	// CommonJS
	typeof(exports) != 'undefined' ? exports.Brush = Brush : null;
})();
