// External JS
$(function() {$('a[data-rel*=external]').click( function() { window.open(this.href); return false;});});

// Gravatar
(function(a){a.fn.async_gravatars=function(b){return this.each(function(){var d="https://static.geeek.org/gravatar/cache/geeek/";var e=a(this).attr("alt");var f=d+encodeURIComponent(e);a(this).attr("src",f)})}})(jQuery)


// jquery Cookie
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?a(require("jquery")):a(jQuery)}(function(a){function b(a){return h.raw?a:encodeURIComponent(a)}function c(a){return h.raw?a:decodeURIComponent(a)}function d(a){return b(h.json?JSON.stringify(a):String(a))}function e(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return a=decodeURIComponent(a.replace(g," ")),h.json?JSON.parse(a):a}catch(b){}}function f(b,c){var d=h.raw?b:e(b);return a.isFunction(c)?c(d):d}var g=/\+/g,h=a.cookie=function(e,g,i){if(void 0!==g&&!a.isFunction(g)){if(i=a.extend({},h.defaults,i),"number"==typeof i.expires){var j=i.expires,k=i.expires=new Date;k.setTime(+k+864e5*j)}return document.cookie=[b(e),"=",d(g),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}for(var l=e?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;o>n;n++){var p=m[n].split("="),q=c(p.shift()),r=p.join("=");if(e&&e===q){l=f(r,g);break}e||void 0===(r=f(r))||(l[q]=r)}return l};h.defaults={},a.removeCookie=function(b,c){return void 0===a.cookie(b)?!1:(a.cookie(b,"",a.extend({},c,{expires:-1})),!a.cookie(b))}});

// modal.js
!function(t){if(/^1\.(0|1)\./.test(t.fn.jquery)||/^1\.2\.(0|1|2|3|4|5)/.test(t.fn.jquery))throw"Modal requieres jQuery v1.2.6 or later. You are using v"+t.fn.jquery;t.modal=function(e,i){return this.params=t.extend(this.params,i),this.build(e)},t.modal.version="1.0",t.modal.prototype={params:{width:null,height:null,speed:300,opacity:.9,loader_img:"loader.gif",loader_txt:"loading...",close_img:"close.png",close_txt:"close",on_close:function(){}},ctrl:{box:t(),loader:t(),overlay:t('<div id="jq-modal-overlay"></div>'),hidden:t()},build:function(e){this.ctrl.loader=t('<div class="jq-modal-load"><img src="'+this.params.loader_img+'" alt="'+this.params.loader_txt+'" /></div>'),this.addOverlay();var i=this.getBoxSize(this.ctrl.loading);return this.ctrl.box=this.getBox(this.ctrl.loading,{top:Math.round(t(window).height()/2+t(window).scrollTop()-i.h/2),left:Math.round(t(window).width()/2+t(window).scrollLeft()-i.w/2),visibility:"hidden"}),this.ctrl.overlay.after(this.ctrl.box),void 0!=e&&(this.updateBox(e),this.data=e),this},updateBox:function(e,i){var o=this;this.hideCloser(),i=t.isFunction(i)?i:function(){};var a=t("div.jq-modal-content",this.ctrl.box);a.empty().append(this.ctrl.loader);var n=this.getBoxSize(e,this.params.width,this.params.height),r=Math.round(t(window).height()/2+t(window).scrollTop()-n.h/2),s=Math.round(t(window).width()/2+t(window).scrollLeft()-n.w/2);this.ctrl.box.css("visibility","visible").animate({top:0>r?0:r,left:0>s?0:s,width:n.w,height:n.h},this.params.speed,function(){o.setContentSize(a,o.params.width,o.params.height),a.empty().append(e).css("opacity",0).fadeTo(o.params.speed,1,function(){i.call(o,a)}),o.showCloser()})},getBox:function(e,i,o,a){var n=t('<div class="jq-modal"><div class="jq-modal-container"><div class="jq-modal-content"></div></div></div>').css(t.extend({position:"absolute",top:0,left:0,zIndex:100},i));return void 0!=e&&t("div.jq-modal-content",n).append(e),this.setContentSize(t("div.jq-modal-content",n),o,a),n},getBoxSize:function(t,e,i){var o=this.getBox(t,{visibility:"hidden"},e,i);this.ctrl.overlay.after(o);var a={w:o.width(),h:o.height()};return o.remove(),o=null,a},setContentSize:function(t,e,i){t.css({width:e>0?e:"auto",height:i>0?i:"auto"})},showCloser:function(){if(t("div.jq-modal-closer",this.ctrl.box).length>0)return void t("div.jq-modal-closer",this.ctrl.box).show();t("div.jq-modal-container",this.ctrl.box).append('<div class="jq-modal-closer"><a href="#">'+this.params.close_txt+"</a></div>");var e=this,i=t("div.jq-modal-closer a",this.ctrl.box);i.css({background:"transparent url("+this.params.close_img+") no-repeat"}).click(function(){return e.removeOverlay(),!1}),document.all&&(i[0].runtimeStyle.filter='progid:DXImageTransform.Microsoft.AlphaImageLoader(src="'+this.params.close_img+'", sizingMethod="crop")',i[0].runtimeStyle.backgroundImage="none")},hideCloser:function(){t("div.jq-modal-closer",this.ctrl.box).hide()},addOverlay:function(){var e=this;document.all&&(this.ctrl.hidden=t("select:visible, object:visible, embed:visible").css("visibility","hidden")),this.ctrl.overlay.css({backgroundColor:"#000",position:"absolute",top:0,left:0,zIndex:90,opacity:this.params.opacity}).appendTo("body").dblclick(function(){e.removeOverlay()}),this.resizeOverlay({data:this.ctrl}),t(window).bind("resize.modal",this.ctrl,this.resizeOverlay),t(document).bind("keypress.modal",this,this.keyRemove)},resizeOverlay:function(e){if(e.data.overlay.css({width:t(window).width(),height:t(document).height()}),e.data.box.parents("body").length>0){var i=Math.round(t(window).height()/2+t(window).scrollTop()-e.data.box.height()/2),o=Math.round(t(window).width()/2+t(window).scrollLeft()-e.data.box.width()/2);e.data.box.css({top:0>i?0:i,left:0>o?0:o})}},keyRemove:function(t){return 27==t.keyCode&&t.data.removeOverlay(),!0},removeOverlay:function(){t(window).unbind("resize.modal"),t(document).unbind("keypress"),this.params.on_close.apply(this),this.ctrl.overlay.remove(),this.ctrl.hidden.css("visibility","visible"),this.ctrl.box.remove(),this.ctrl.box=t()}}}(jQuery),function(t){t.fn.modalImages=function(e){e=t.extend(this.params,e);var i=new Array;return this.each(function(){if(""==t(this).attr("href")||void 0==t(this).attr("href")||"#"==t(this).attr("href"))return!1;var o=i.length;return i.push(t(this)),t(this).click(function(){return new t.modalImages(o,i,e),!1}),!0}),this},t.modalImages=function(e,i,o){this.links=i,this.modal=new t.modal(null,o),this.showImage(e)},t.modalImages.prototype={params:{prev_txt:"previous",next_txt:"next",prev_img:"prev.png",next_img:"next.png",blank_img:"blank.gif"},showImage:function(e){var i=this;t(document).unbind("keypress.modalImage"),void 0==this.links[e]&&this.modal.removeOverlay();var o=this.links[e],a=this.modal,n=t("<div></div>");n.append('<img src="'+o.attr("href")+'" alt="" />');var r=t("img:first",o);r.length>0&&r.attr("title")?n.append('<span class="jq-modal-legend">'+r.attr("title")+"</span>"):o.attr("title")&&n.append('<span class="jq-modal-legend">'+o.attr("title")+"</span>"),0!=e&&t('<a class="jq-modal-prev" href="#">prev</a>').appendTo(n),e+1<this.links.length&&t('<a class="jq-modal-next" href="#">next</a>').appendTo(n);var s=new Image;"visible"==this.modal.ctrl.box.css("visibility")?t("div.jq-modal-content",this.modal.ctrl.box).empty().append(this.modal.ctrl.loader):this.modal.updateBox(this.modal.ctrl.loader),s.onload=function(){a.updateBox(n,function(){var o=t("div.jq-modal-content img",this.ctrl.box);i.navBtnStyle(t("a.jq-modal-next",this.ctrl.box),!0).css("height",o.height()).bind("click",e+1,l),i.navBtnStyle(t("a.jq-modal-prev",this.ctrl.box),!1).css("height",o.height()).bind("click",e-1,l),o.click(function(){i.modal.removeOverlay()}),t(document).bind("keypress.modalImage",d)}),this.onload=function(){}},s.src=o.attr("href");var l=function(t){return i.showImage(t.data),!1},d=function(t){var o=String.fromCharCode(t.which).toLowerCase();("n"==o||39==t.keyCode)&&e+1<i.links.length&&i.showImage(e+1),"p"!=o&&37!=t.keyCode||0==e||i.showImage(e-1)}},navBtnStyle:function(e,i){var o="transparent url("+this.modal.params.blank_img+") repeat",a=i?this.modal.params.next_img:this.modal.params.prev_img,n=i?"right":"left";return e.css("background",o).bind("mouseenter",function(){t(this).css("background","transparent url("+a+") no-repeat center "+n).css("z-index",110)}).bind("mouseleave",function(){t(this).css("background",o)}),e}}}(jQuery),function(t){t.modalWeb=function(e,i,o){return iframe=t('<iframe src="'+e+'" frameborder="0">').css({border:"none",width:i,height:o}),new t.modal(iframe)},t.fn.modalWeb=function(e,i){this.click(function(){return void 0!=this.href&&t.modalWeb(this.href,e,i),!1})}}(jQuery);

//post.js
var post_remember_str = 'Se souvenir de moi';
$(function(){function d(a){if(!a){return false}var b=a.split("\n");if(b.length!=3){c();return false}return b}function c(){$.cookie("comment_info","",{expires:-30,path:"/"})}function b(){var a=$("#c_name").val();var b=$("#c_mail").val();var c=$("#c_site").val();var d=$("link[rel=top]").attr("href");if(!d){d="/"}else{d=d.replace(/.*:\/\/[^\/]*([^?]*).*/g,"$1")}$.cookie("comment_info",a+"\n"+b+"\n"+c,{expires:60,path:d})}$("#comment-form div:has(button[type=submit][name=preview])").before('<div class="checkbox"><label for="c_remember" class="control-label"><input type="checkbox" id="c_remember" name="c_remember" /> '+post_remember_str+'</label></div>');var a=d($.cookie("comment_info"));if(a!=false){$("#c_name").val(a[0]);$("#c_mail").val(a[1]);$("#c_site").val(a[2]);$("#c_remember").attr("checked","checked")}$("#c_remember").click(function(){if(this.checked){b()}else{c()}});$("#c_name").change(function(){if($("#c_remember").get(0).checked){b()}});$("#c_mail").change(function(){if($("#c_remember").get(0).checked){b()}});$("#c_site").change(function(){if($("#c_remember").get(0).checked){b()}})})


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
/*!
 * XRegExp v2.0.0
 * (c) 2007-2012 Steven Levithan <http://xregexp.com/>
 * MIT License
 */

/**
 * XRegExp provides augmented, extensible JavaScript regular expressions. You get new syntax,
 * flags, and methods beyond what browsers support natively. XRegExp is also a regex utility belt
 * with tools to make your client-side grepping simpler and more powerful, while freeing you from
 * worrying about pesky cross-browser inconsistencies and the dubious `lastIndex` property. See
 * XRegExp's documentation (http://xregexp.com/) for more details.
 * @module xregexp
 * @requires N/A
 */
var XRegExp;

// Avoid running twice; that would reset tokens and could break references to native globals
XRegExp = XRegExp || (function (undef) {
    "use strict";

/*--------------------------------------
 *  Private variables
 *------------------------------------*/

    var self,
        addToken,
        add,

// Optional features; can be installed and uninstalled
        features = {
            natives: false,
            extensibility: false
        },

// Store native methods to use and restore ("native" is an ES3 reserved keyword)
        nativ = {
            exec: RegExp.prototype.exec,
            test: RegExp.prototype.test,
            match: String.prototype.match,
            replace: String.prototype.replace,
            split: String.prototype.split
        },

// Storage for fixed/extended native methods
        fixed = {},

// Storage for cached regexes
        cache = {},

// Storage for addon tokens
        tokens = [],

// Token scopes
        defaultScope = "default",
        classScope = "class",

// Regexes that match native regex syntax
        nativeTokens = {
            // Any native multicharacter token in default scope (includes octals, excludes character classes)
            "default": /^(?:\\(?:0(?:[0-3][0-7]{0,2}|[4-7][0-7]?)?|[1-9]\d*|x[\dA-Fa-f]{2}|u[\dA-Fa-f]{4}|c[A-Za-z]|[\s\S])|\(\?[:=!]|[?*+]\?|{\d+(?:,\d*)?}\??)/,
            // Any native multicharacter token in character class scope (includes octals)
            "class": /^(?:\\(?:[0-3][0-7]{0,2}|[4-7][0-7]?|x[\dA-Fa-f]{2}|u[\dA-Fa-f]{4}|c[A-Za-z]|[\s\S]))/
        },

// Any backreference in replacement strings
        replacementToken = /\$(?:{([\w$]+)}|(\d\d?|[\s\S]))/g,

// Any character with a later instance in the string
        duplicateFlags = /([\s\S])(?=[\s\S]*\1)/g,

// Any greedy/lazy quantifier
        quantifier = /^(?:[?*+]|{\d+(?:,\d*)?})\??/,

// Check for correct `exec` handling of nonparticipating capturing groups
        compliantExecNpcg = nativ.exec.call(/()??/, "")[1] === undef,

// Check for flag y support (Firefox 3+)
        hasNativeY = RegExp.prototype.sticky !== undef,

// Used to kill infinite recursion during XRegExp construction
        isInsideConstructor = false,

// Storage for known flags, including addon flags
        registeredFlags = "gim" + (hasNativeY ? "y" : "");

/*--------------------------------------
 *  Private helper functions
 *------------------------------------*/

/**
 * Attaches XRegExp.prototype properties and named capture supporting data to a regex object.
 * @private
 * @param {RegExp} regex Regex to augment.
 * @param {Array} captureNames Array with capture names, or null.
 * @param {Boolean} [isNative] Whether the regex was created by `RegExp` rather than `XRegExp`.
 * @returns {RegExp} Augmented regex.
 */
    function augment(regex, captureNames, isNative) {
        var p;
        // Can't auto-inherit these since the XRegExp constructor returns a nonprimitive value
        for (p in self.prototype) {
            if (self.prototype.hasOwnProperty(p)) {
                regex[p] = self.prototype[p];
            }
        }
        regex.xregexp = {captureNames: captureNames, isNative: !!isNative};
        return regex;
    }

/**
 * Returns native `RegExp` flags used by a regex object.
 * @private
 * @param {RegExp} regex Regex to check.
 * @returns {String} Native flags in use.
 */
    function getNativeFlags(regex) {
        //return nativ.exec.call(/\/([a-z]*)$/i, String(regex))[1];
        return (regex.global     ? "g" : "") +
               (regex.ignoreCase ? "i" : "") +
               (regex.multiline  ? "m" : "") +
               (regex.extended   ? "x" : "") + // Proposed for ES6, included in AS3
               (regex.sticky     ? "y" : ""); // Proposed for ES6, included in Firefox 3+
    }

/**
 * Copies a regex object while preserving special properties for named capture and augmenting with
 * `XRegExp.prototype` methods. The copy has a fresh `lastIndex` property (set to zero). Allows
 * adding and removing flags while copying the regex.
 * @private
 * @param {RegExp} regex Regex to copy.
 * @param {String} [addFlags] Flags to be added while copying the regex.
 * @param {String} [removeFlags] Flags to be removed while copying the regex.
 * @returns {RegExp} Copy of the provided regex, possibly with modified flags.
 */
    function copy(regex, addFlags, removeFlags) {
        if (!self.isRegExp(regex)) {
            throw new TypeError("type RegExp expected");
        }
        var flags = nativ.replace.call(getNativeFlags(regex) + (addFlags || ""), duplicateFlags, "");
        if (removeFlags) {
            // Would need to escape `removeFlags` if this was public
            flags = nativ.replace.call(flags, new RegExp("[" + removeFlags + "]+", "g"), "");
        }
        if (regex.xregexp && !regex.xregexp.isNative) {
            // Compiling the current (rather than precompilation) source preserves the effects of nonnative source flags
            regex = augment(self(regex.source, flags),
                            regex.xregexp.captureNames ? regex.xregexp.captureNames.slice(0) : null);
        } else {
            // Augment with `XRegExp.prototype` methods, but use native `RegExp` (avoid searching for special tokens)
            regex = augment(new RegExp(regex.source, flags), null, true);
        }
        return regex;
    }

/*
 * Returns the last index at which a given value can be found in an array, or `-1` if it's not
 * present. The array is searched backwards.
 * @private
 * @param {Array} array Array to search.
 * @param {*} value Value to locate in the array.
 * @returns {Number} Last zero-based index at which the item is found, or -1.
 */
    function lastIndexOf(array, value) {
        var i = array.length;
        if (Array.prototype.lastIndexOf) {
            return array.lastIndexOf(value); // Use the native method if available
        }
        while (i--) {
            if (array[i] === value) {
                return i;
            }
        }
        return -1;
    }

/**
 * Determines whether an object is of the specified type.
 * @private
 * @param {*} value Object to check.
 * @param {String} type Type to check for, in lowercase.
 * @returns {Boolean} Whether the object matches the type.
 */
    function isType(value, type) {
        return Object.prototype.toString.call(value).toLowerCase() === "[object " + type + "]";
    }

/**
 * Prepares an options object from the given value.
 * @private
 * @param {String|Object} value Value to convert to an options object.
 * @returns {Object} Options object.
 */
    function prepareOptions(value) {
        value = value || {};
        if (value === "all" || value.all) {
            value = {natives: true, extensibility: true};
        } else if (isType(value, "string")) {
            value = self.forEach(value, /[^\s,]+/, function (m) {
                this[m] = true;
            }, {});
        }
        return value;
    }

/**
 * Runs built-in/custom tokens in reverse insertion order, until a match is found.
 * @private
 * @param {String} pattern Original pattern from which an XRegExp object is being built.
 * @param {Number} pos Position to search for tokens within `pattern`.
 * @param {Number} scope Current regex scope.
 * @param {Object} context Context object assigned to token handler functions.
 * @returns {Object} Object with properties `output` (the substitution string returned by the
 *   successful token handler) and `match` (the token's match array), or null.
 */
    function runTokens(pattern, pos, scope, context) {
        var i = tokens.length,
            result = null,
            match,
            t;
        // Protect against constructing XRegExps within token handler and trigger functions
        isInsideConstructor = true;
        // Must reset `isInsideConstructor`, even if a `trigger` or `handler` throws
        try {
            while (i--) { // Run in reverse order
                t = tokens[i];
                if ((t.scope === "all" || t.scope === scope) && (!t.trigger || t.trigger.call(context))) {
                    t.pattern.lastIndex = pos;
                    match = fixed.exec.call(t.pattern, pattern); // Fixed `exec` here allows use of named backreferences, etc.
                    if (match && match.index === pos) {
                        result = {
                            output: t.handler.call(context, match, scope),
                            match: match
                        };
                        break;
                    }
                }
            }
        } catch (err) {
            throw err;
        } finally {
            isInsideConstructor = false;
        }
        return result;
    }

/**
 * Enables or disables XRegExp syntax and flag extensibility.
 * @private
 * @param {Boolean} on `true` to enable; `false` to disable.
 */
    function setExtensibility(on) {
        self.addToken = addToken[on ? "on" : "off"];
        features.extensibility = on;
    }

/**
 * Enables or disables native method overrides.
 * @private
 * @param {Boolean} on `true` to enable; `false` to disable.
 */
    function setNatives(on) {
        RegExp.prototype.exec = (on ? fixed : nativ).exec;
        RegExp.prototype.test = (on ? fixed : nativ).test;
        String.prototype.match = (on ? fixed : nativ).match;
        String.prototype.replace = (on ? fixed : nativ).replace;
        String.prototype.split = (on ? fixed : nativ).split;
        features.natives = on;
    }

/*--------------------------------------
 *  Constructor
 *------------------------------------*/

/**
 * Creates an extended regular expression object for matching text with a pattern. Differs from a
 * native regular expression in that additional syntax and flags are supported. The returned object
 * is in fact a native `RegExp` and works with all native methods.
 * @class XRegExp
 * @constructor
 * @param {String|RegExp} pattern Regex pattern string, or an existing `RegExp` object to copy.
 * @param {String} [flags] Any combination of flags:
 *   <li>`g` - global
 *   <li>`i` - ignore case
 *   <li>`m` - multiline anchors
 *   <li>`n` - explicit capture
 *   <li>`s` - dot matches all (aka singleline)
 *   <li>`x` - free-spacing and line comments (aka extended)
 *   <li>`y` - sticky (Firefox 3+ only)
 *   Flags cannot be provided when constructing one `RegExp` from another.
 * @returns {RegExp} Extended regular expression object.
 * @example
 *
 * // With named capture and flag x
 * date = XRegExp('(?<year>  [0-9]{4}) -?  # year  \n\
 *                 (?<month> [0-9]{2}) -?  # month \n\
 *                 (?<day>   [0-9]{2})     # day   ', 'x');
 *
 * // Passing a regex object to copy it. The copy maintains special properties for named capture,
 * // is augmented with `XRegExp.prototype` methods, and has a fresh `lastIndex` property (set to
 * // zero). Native regexes are not recompiled using XRegExp syntax.
 * XRegExp(/regex/);
 */
    self = function (pattern, flags) {
        if (self.isRegExp(pattern)) {
            if (flags !== undef) {
                throw new TypeError("can't supply flags when constructing one RegExp from another");
            }
            return copy(pattern);
        }
        // Tokens become part of the regex construction process, so protect against infinite recursion
        // when an XRegExp is constructed within a token handler function
        if (isInsideConstructor) {
            throw new Error("can't call the XRegExp constructor within token definition functions");
        }

        var output = [],
            scope = defaultScope,
            tokenContext = {
                hasNamedCapture: false,
                captureNames: [],
                hasFlag: function (flag) {
                    return flags.indexOf(flag) > -1;
                }
            },
            pos = 0,
            tokenResult,
            match,
            chr;
        pattern = pattern === undef ? "" : String(pattern);
        flags = flags === undef ? "" : String(flags);

        if (nativ.match.call(flags, duplicateFlags)) { // Don't use test/exec because they would update lastIndex
            throw new SyntaxError("invalid duplicate regular expression flag");
        }
        // Strip/apply leading mode modifier with any combination of flags except g or y: (?imnsx)
        pattern = nativ.replace.call(pattern, /^\(\?([\w$]+)\)/, function ($0, $1) {
            if (nativ.test.call(/[gy]/, $1)) {
                throw new SyntaxError("can't use flag g or y in mode modifier");
            }
            flags = nativ.replace.call(flags + $1, duplicateFlags, "");
            return "";
        });
        self.forEach(flags, /[\s\S]/, function (m) {
            if (registeredFlags.indexOf(m[0]) < 0) {
                throw new SyntaxError("invalid regular expression flag " + m[0]);
            }
        });

        while (pos < pattern.length) {
            // Check for custom tokens at the current position
            tokenResult = runTokens(pattern, pos, scope, tokenContext);
            if (tokenResult) {
                output.push(tokenResult.output);
                pos += (tokenResult.match[0].length || 1);
            } else {
                // Check for native tokens (except character classes) at the current position
                match = nativ.exec.call(nativeTokens[scope], pattern.slice(pos));
                if (match) {
                    output.push(match[0]);
                    pos += match[0].length;
                } else {
                    chr = pattern.charAt(pos);
                    if (chr === "[") {
                        scope = classScope;
                    } else if (chr === "]") {
                        scope = defaultScope;
                    }
                    // Advance position by one character
                    output.push(chr);
                    ++pos;
                }
            }
        }

        return augment(new RegExp(output.join(""), nativ.replace.call(flags, /[^gimy]+/g, "")),
                       tokenContext.hasNamedCapture ? tokenContext.captureNames : null);
    };

/*--------------------------------------
 *  Public methods/properties
 *------------------------------------*/

// Installed and uninstalled states for `XRegExp.addToken`
    addToken = {
        on: function (regex, handler, options) {
            options = options || {};
            if (regex) {
                tokens.push({
                    pattern: copy(regex, "g" + (hasNativeY ? "y" : "")),
                    handler: handler,
                    scope: options.scope || defaultScope,
                    trigger: options.trigger || null
                });
            }
            // Providing `customFlags` with null `regex` and `handler` allows adding flags that do
            // nothing, but don't throw an error
            if (options.customFlags) {
                registeredFlags = nativ.replace.call(registeredFlags + options.customFlags, duplicateFlags, "");
            }
        },
        off: function () {
            throw new Error("extensibility must be installed before using addToken");
        }
    };

/**
 * Extends or changes XRegExp syntax and allows custom flags. This is used internally and can be
 * used to create XRegExp addons. `XRegExp.install('extensibility')` must be run before calling
 * this function, or an error is thrown. If more than one token can match the same string, the last
 * added wins.
 * @memberOf XRegExp
 * @param {RegExp} regex Regex object that matches the new token.
 * @param {Function} handler Function that returns a new pattern string (using native regex syntax)
 *   to replace the matched token within all future XRegExp regexes. Has access to persistent
 *   properties of the regex being built, through `this`. Invoked with two arguments:
 *   <li>The match array, with named backreference properties.
 *   <li>The regex scope where the match was found.
 * @param {Object} [options] Options object with optional properties:
 *   <li>`scope` {String} Scopes where the token applies: 'default', 'class', or 'all'.
 *   <li>`trigger` {Function} Function that returns `true` when the token should be applied; e.g.,
 *     if a flag is set. If `false` is returned, the matched string can be matched by other tokens.
 *     Has access to persistent properties of the regex being built, through `this` (including
 *     function `this.hasFlag`).
 *   <li>`customFlags` {String} Nonnative flags used by the token's handler or trigger functions.
 *     Prevents XRegExp from throwing an invalid flag error when the specified flags are used.
 * @example
 *
 * // Basic usage: Adds \a for ALERT character
 * XRegExp.addToken(
 *   /\\a/,
 *   function () {return '\\x07';},
 *   {scope: 'all'}
 * );
 * XRegExp('\\a[\\a-\\n]+').test('\x07\n\x07'); // -> true
 */
    self.addToken = addToken.off;

/**
 * Caches and returns the result of calling `XRegExp(pattern, flags)`. On any subsequent call with
 * the same pattern and flag combination, the cached copy is returned.
 * @memberOf XRegExp
 * @param {String} pattern Regex pattern string.
 * @param {String} [flags] Any combination of XRegExp flags.
 * @returns {RegExp} Cached XRegExp object.
 * @example
 *
 * while (match = XRegExp.cache('.', 'gs').exec(str)) {
 *   // The regex is compiled once only
 * }
 */
    self.cache = function (pattern, flags) {
        var key = pattern + "/" + (flags || "");
        return cache[key] || (cache[key] = self(pattern, flags));
    };

/**
 * Escapes any regular expression metacharacters, for use when matching literal strings. The result
 * can safely be used at any point within a regex that uses any flags.
 * @memberOf XRegExp
 * @param {String} str String to escape.
 * @returns {String} String with regex metacharacters escaped.
 * @example
 *
 * XRegExp.escape('Escaped? <.>');
 * // -> 'Escaped\?\ <\.>'
 */
    self.escape = function (str) {
        return nativ.replace.call(str, /[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
    };

/**
 * Executes a regex search in a specified string. Returns a match array or `null`. If the provided
 * regex uses named capture, named backreference properties are included on the match array.
 * Optional `pos` and `sticky` arguments specify the search start position, and whether the match
 * must start at the specified position only. The `lastIndex` property of the provided regex is not
 * used, but is updated for compatibility. Also fixes browser bugs compared to the native
 * `RegExp.prototype.exec` and can be used reliably cross-browser.
 * @memberOf XRegExp
 * @param {String} str String to search.
 * @param {RegExp} regex Regex to search with.
 * @param {Number} [pos=0] Zero-based index at which to start the search.
 * @param {Boolean|String} [sticky=false] Whether the match must start at the specified position
 *   only. The string `'sticky'` is accepted as an alternative to `true`.
 * @returns {Array} Match array with named backreference properties, or null.
 * @example
 *
 * // Basic use, with named backreference
 * var match = XRegExp.exec('U+2620', XRegExp('U\\+(?<hex>[0-9A-F]{4})'));
 * match.hex; // -> '2620'
 *
 * // With pos and sticky, in a loop
 * var pos = 2, result = [], match;
 * while (match = XRegExp.exec('<1><2><3><4>5<6>', /<(\d)>/, pos, 'sticky')) {
 *   result.push(match[1]);
 *   pos = match.index + match[0].length;
 * }
 * // result -> ['2', '3', '4']
 */
    self.exec = function (str, regex, pos, sticky) {
        var r2 = copy(regex, "g" + (sticky && hasNativeY ? "y" : ""), (sticky === false ? "y" : "")),
            match;
        r2.lastIndex = pos = pos || 0;
        match = fixed.exec.call(r2, str); // Fixed `exec` required for `lastIndex` fix, etc.
        if (sticky && match && match.index !== pos) {
            match = null;
        }
        if (regex.global) {
            regex.lastIndex = match ? r2.lastIndex : 0;
        }
        return match;
    };

/**
 * Executes a provided function once per regex match.
 * @memberOf XRegExp
 * @param {String} str String to search.
 * @param {RegExp} regex Regex to search with.
 * @param {Function} callback Function to execute for each match. Invoked with four arguments:
 *   <li>The match array, with named backreference properties.
 *   <li>The zero-based match index.
 *   <li>The string being traversed.
 *   <li>The regex object being used to traverse the string.
 * @param {*} [context] Object to use as `this` when executing `callback`.
 * @returns {*} Provided `context` object.
 * @example
 *
 * // Extracts every other digit from a string
 * XRegExp.forEach('1a2345', /\d/, function (match, i) {
 *   if (i % 2) this.push(+match[0]);
 * }, []);
 * // -> [2, 4]
 */
    self.forEach = function (str, regex, callback, context) {
        var pos = 0,
            i = -1,
            match;
        while ((match = self.exec(str, regex, pos))) {
            callback.call(context, match, ++i, str, regex);
            pos = match.index + (match[0].length || 1);
        }
        return context;
    };

/**
 * Copies a regex object and adds flag `g`. The copy maintains special properties for named
 * capture, is augmented with `XRegExp.prototype` methods, and has a fresh `lastIndex` property
 * (set to zero). Native regexes are not recompiled using XRegExp syntax.
 * @memberOf XRegExp
 * @param {RegExp} regex Regex to globalize.
 * @returns {RegExp} Copy of the provided regex with flag `g` added.
 * @example
 *
 * var globalCopy = XRegExp.globalize(/regex/);
 * globalCopy.global; // -> true
 */
    self.globalize = function (regex) {
        return copy(regex, "g");
    };

/**
 * Installs optional features according to the specified options.
 * @memberOf XRegExp
 * @param {Object|String} options Options object or string.
 * @example
 *
 * // With an options object
 * XRegExp.install({
 *   // Overrides native regex methods with fixed/extended versions that support named
 *   // backreferences and fix numerous cross-browser bugs
 *   natives: true,
 *
 *   // Enables extensibility of XRegExp syntax and flags
 *   extensibility: true
 * });
 *
 * // With an options string
 * XRegExp.install('natives extensibility');
 *
 * // Using a shortcut to install all optional features
 * XRegExp.install('all');
 */
    self.install = function (options) {
        options = prepareOptions(options);
        if (!features.natives && options.natives) {
            setNatives(true);
        }
        if (!features.extensibility && options.extensibility) {
            setExtensibility(true);
        }
    };

/**
 * Checks whether an individual optional feature is installed.
 * @memberOf XRegExp
 * @param {String} feature Name of the feature to check. One of:
 *   <li>`natives`
 *   <li>`extensibility`
 * @returns {Boolean} Whether the feature is installed.
 * @example
 *
 * XRegExp.isInstalled('natives');
 */
    self.isInstalled = function (feature) {
        return !!(features[feature]);
    };

/**
 * Returns `true` if an object is a regex; `false` if it isn't. This works correctly for regexes
 * created in another frame, when `instanceof` and `constructor` checks would fail.
 * @memberOf XRegExp
 * @param {*} value Object to check.
 * @returns {Boolean} Whether the object is a `RegExp` object.
 * @example
 *
 * XRegExp.isRegExp('string'); // -> false
 * XRegExp.isRegExp(/regex/i); // -> true
 * XRegExp.isRegExp(RegExp('^', 'm')); // -> true
 * XRegExp.isRegExp(XRegExp('(?s).')); // -> true
 */
    self.isRegExp = function (value) {
        return isType(value, "regexp");
    };

/**
 * Retrieves the matches from searching a string using a chain of regexes that successively search
 * within previous matches. The provided `chain` array can contain regexes and objects with `regex`
 * and `backref` properties. When a backreference is specified, the named or numbered backreference
 * is passed forward to the next regex or returned.
 * @memberOf XRegExp
 * @param {String} str String to search.
 * @param {Array} chain Regexes that each search for matches within preceding results.
 * @returns {Array} Matches by the last regex in the chain, or an empty array.
 * @example
 *
 * // Basic usage; matches numbers within <b> tags
 * XRegExp.matchChain('1 <b>2</b> 3 <b>4 a 56</b>', [
 *   XRegExp('(?is)<b>.*?</b>'),
 *   /\d+/
 * ]);
 * // -> ['2', '4', '56']
 *
 * // Passing forward and returning specific backreferences
 * html = '<a href="http://xregexp.com/api/">XRegExp</a>\
 *         <a href="http://www.google.com/">Google</a>';
 * XRegExp.matchChain(html, [
 *   {regex: /<a href="([^"]+)">/i, backref: 1},
 *   {regex: XRegExp('(?i)^https?://(?<domain>[^/?#]+)'), backref: 'domain'}
 * ]);
 * // -> ['xregexp.com', 'www.google.com']
 */
    self.matchChain = function (str, chain) {
        return (function recurseChain(values, level) {
            var item = chain[level].regex ? chain[level] : {regex: chain[level]},
                matches = [],
                addMatch = function (match) {
                    matches.push(item.backref ? (match[item.backref] || "") : match[0]);
                },
                i;
            for (i = 0; i < values.length; ++i) {
                self.forEach(values[i], item.regex, addMatch);
            }
            return ((level === chain.length - 1) || !matches.length) ?
                    matches :
                    recurseChain(matches, level + 1);
        }([str], 0));
    };

/**
 * Returns a new string with one or all matches of a pattern replaced. The pattern can be a string
 * or regex, and the replacement can be a string or a function to be called for each match. To
 * perform a global search and replace, use the optional `scope` argument or include flag `g` if
 * using a regex. Replacement strings can use `${n}` for named and numbered backreferences.
 * Replacement functions can use named backreferences via `arguments[0].name`. Also fixes browser
 * bugs compared to the native `String.prototype.replace` and can be used reliably cross-browser.
 * @memberOf XRegExp
 * @param {String} str String to search.
 * @param {RegExp|String} search Search pattern to be replaced.
 * @param {String|Function} replacement Replacement string or a function invoked to create it.
 *   Replacement strings can include special replacement syntax:
 *     <li>$$ - Inserts a literal '$'.
 *     <li>$&, $0 - Inserts the matched substring.
 *     <li>$` - Inserts the string that precedes the matched substring (left context).
 *     <li>$' - Inserts the string that follows the matched substring (right context).
 *     <li>$n, $nn - Where n/nn are digits referencing an existent capturing group, inserts
 *       backreference n/nn.
 *     <li>${n} - Where n is a name or any number of digits that reference an existent capturing
 *       group, inserts backreference n.
 *   Replacement functions are invoked with three or more arguments:
 *     <li>The matched substring (corresponds to $& above). Named backreferences are accessible as
 *       properties of this first argument.
 *     <li>0..n arguments, one for each backreference (corresponding to $1, $2, etc. above).
 *     <li>The zero-based index of the match within the total search string.
 *     <li>The total string being searched.
 * @param {String} [scope='one'] Use 'one' to replace the first match only, or 'all'. If not
 *   explicitly specified and using a regex with flag `g`, `scope` is 'all'.
 * @returns {String} New string with one or all matches replaced.
 * @example
 *
 * // Regex search, using named backreferences in replacement string
 * var name = XRegExp('(?<first>\\w+) (?<last>\\w+)');
 * XRegExp.replace('John Smith', name, '${last}, ${first}');
 * // -> 'Smith, John'
 *
 * // Regex search, using named backreferences in replacement function
 * XRegExp.replace('John Smith', name, function (match) {
 *   return match.last + ', ' + match.first;
 * });
 * // -> 'Smith, John'
 *
 * // Global string search/replacement
 * XRegExp.replace('RegExp builds RegExps', 'RegExp', 'XRegExp', 'all');
 * // -> 'XRegExp builds XRegExps'
 */
    self.replace = function (str, search, replacement, scope) {
        var isRegex = self.isRegExp(search),
            search2 = search,
            result;
        if (isRegex) {
            if (scope === undef && search.global) {
                scope = "all"; // Follow flag g when `scope` isn't explicit
            }
            // Note that since a copy is used, `search`'s `lastIndex` isn't updated *during* replacement iterations
            search2 = copy(search, scope === "all" ? "g" : "", scope === "all" ? "" : "g");
        } else if (scope === "all") {
            search2 = new RegExp(self.escape(String(search)), "g");
        }
        result = fixed.replace.call(String(str), search2, replacement); // Fixed `replace` required for named backreferences, etc.
        if (isRegex && search.global) {
            search.lastIndex = 0; // Fixes IE, Safari bug (last tested IE 9, Safari 5.1)
        }
        return result;
    };

/**
 * Splits a string into an array of strings using a regex or string separator. Matches of the
 * separator are not included in the result array. However, if `separator` is a regex that contains
 * capturing groups, backreferences are spliced into the result each time `separator` is matched.
 * Fixes browser bugs compared to the native `String.prototype.split` and can be used reliably
 * cross-browser.
 * @memberOf XRegExp
 * @param {String} str String to split.
 * @param {RegExp|String} separator Regex or string to use for separating the string.
 * @param {Number} [limit] Maximum number of items to include in the result array.
 * @returns {Array} Array of substrings.
 * @example
 *
 * // Basic use
 * XRegExp.split('a b c', ' ');
 * // -> ['a', 'b', 'c']
 *
 * // With limit
 * XRegExp.split('a b c', ' ', 2);
 * // -> ['a', 'b']
 *
 * // Backreferences in result array
 * XRegExp.split('..word1..', /([a-z]+)(\d+)/i);
 * // -> ['..', 'word', '1', '..']
 */
    self.split = function (str, separator, limit) {
        return fixed.split.call(str, separator, limit);
    };

/**
 * Executes a regex search in a specified string. Returns `true` or `false`. Optional `pos` and
 * `sticky` arguments specify the search start position, and whether the match must start at the
 * specified position only. The `lastIndex` property of the provided regex is not used, but is
 * updated for compatibility. Also fixes browser bugs compared to the native
 * `RegExp.prototype.test` and can be used reliably cross-browser.
 * @memberOf XRegExp
 * @param {String} str String to search.
 * @param {RegExp} regex Regex to search with.
 * @param {Number} [pos=0] Zero-based index at which to start the search.
 * @param {Boolean|String} [sticky=false] Whether the match must start at the specified position
 *   only. The string `'sticky'` is accepted as an alternative to `true`.
 * @returns {Boolean} Whether the regex matched the provided value.
 * @example
 *
 * // Basic use
 * XRegExp.test('abc', /c/); // -> true
 *
 * // With pos and sticky
 * XRegExp.test('abc', /c/, 0, 'sticky'); // -> false
 */
    self.test = function (str, regex, pos, sticky) {
        // Do this the easy way :-)
        return !!self.exec(str, regex, pos, sticky);
    };

/**
 * Uninstalls optional features according to the specified options.
 * @memberOf XRegExp
 * @param {Object|String} options Options object or string.
 * @example
 *
 * // With an options object
 * XRegExp.uninstall({
 *   // Restores native regex methods
 *   natives: true,
 *
 *   // Disables additional syntax and flag extensions
 *   extensibility: true
 * });
 *
 * // With an options string
 * XRegExp.uninstall('natives extensibility');
 *
 * // Using a shortcut to uninstall all optional features
 * XRegExp.uninstall('all');
 */
    self.uninstall = function (options) {
        options = prepareOptions(options);
        if (features.natives && options.natives) {
            setNatives(false);
        }
        if (features.extensibility && options.extensibility) {
            setExtensibility(false);
        }
    };

/**
 * Returns an XRegExp object that is the union of the given patterns. Patterns can be provided as
 * regex objects or strings. Metacharacters are escaped in patterns provided as strings.
 * Backreferences in provided regex objects are automatically renumbered to work correctly. Native
 * flags used by provided regexes are ignored in favor of the `flags` argument.
 * @memberOf XRegExp
 * @param {Array} patterns Regexes and strings to combine.
 * @param {String} [flags] Any combination of XRegExp flags.
 * @returns {RegExp} Union of the provided regexes and strings.
 * @example
 *
 * XRegExp.union(['a+b*c', /(dogs)\1/, /(cats)\1/], 'i');
 * // -> /a\+b\*c|(dogs)\1|(cats)\2/i
 *
 * XRegExp.union([XRegExp('(?<pet>dogs)\\k<pet>'), XRegExp('(?<pet>cats)\\k<pet>')]);
 * // -> XRegExp('(?<pet>dogs)\\k<pet>|(?<pet>cats)\\k<pet>')
 */
    self.union = function (patterns, flags) {
        var parts = /(\()(?!\?)|\\([1-9]\d*)|\\[\s\S]|\[(?:[^\\\]]|\\[\s\S])*]/g,
            numCaptures = 0,
            numPriorCaptures,
            captureNames,
            rewrite = function (match, paren, backref) {
                var name = captureNames[numCaptures - numPriorCaptures];
                if (paren) { // Capturing group
                    ++numCaptures;
                    if (name) { // If the current capture has a name
                        return "(?<" + name + ">";
                    }
                } else if (backref) { // Backreference
                    return "\\" + (+backref + numPriorCaptures);
                }
                return match;
            },
            output = [],
            pattern,
            i;
        if (!(isType(patterns, "array") && patterns.length)) {
            throw new TypeError("patterns must be a nonempty array");
        }
        for (i = 0; i < patterns.length; ++i) {
            pattern = patterns[i];
            if (self.isRegExp(pattern)) {
                numPriorCaptures = numCaptures;
                captureNames = (pattern.xregexp && pattern.xregexp.captureNames) || [];
                // Rewrite backreferences. Passing to XRegExp dies on octals and ensures patterns
                // are independently valid; helps keep this simple. Named captures are put back
                output.push(self(pattern.source).source.replace(parts, rewrite));
            } else {
                output.push(self.escape(pattern));
            }
        }
        return self(output.join("|"), flags);
    };

/**
 * The XRegExp version number.
 * @static
 * @memberOf XRegExp
 * @type String
 */
    self.version = "2.0.0";

/*--------------------------------------
 *  Fixed/extended native methods
 *------------------------------------*/

/**
 * Adds named capture support (with backreferences returned as `result.name`), and fixes browser
 * bugs in the native `RegExp.prototype.exec`. Calling `XRegExp.install('natives')` uses this to
 * override the native method. Use via `XRegExp.exec` without overriding natives.
 * @private
 * @param {String} str String to search.
 * @returns {Array} Match array with named backreference properties, or null.
 */
    fixed.exec = function (str) {
        var match, name, r2, origLastIndex, i;
        if (!this.global) {
            origLastIndex = this.lastIndex;
        }
        match = nativ.exec.apply(this, arguments);
        if (match) {
            // Fix browsers whose `exec` methods don't consistently return `undefined` for
            // nonparticipating capturing groups
            if (!compliantExecNpcg && match.length > 1 && lastIndexOf(match, "") > -1) {
                r2 = new RegExp(this.source, nativ.replace.call(getNativeFlags(this), "g", ""));
                // Using `str.slice(match.index)` rather than `match[0]` in case lookahead allowed
                // matching due to characters outside the match
                nativ.replace.call(String(str).slice(match.index), r2, function () {
                    var i;
                    for (i = 1; i < arguments.length - 2; ++i) {
                        if (arguments[i] === undef) {
                            match[i] = undef;
                        }
                    }
                });
            }
            // Attach named capture properties
            if (this.xregexp && this.xregexp.captureNames) {
                for (i = 1; i < match.length; ++i) {
                    name = this.xregexp.captureNames[i - 1];
                    if (name) {
                        match[name] = match[i];
                    }
                }
            }
            // Fix browsers that increment `lastIndex` after zero-length matches
            if (this.global && !match[0].length && (this.lastIndex > match.index)) {
                this.lastIndex = match.index;
            }
        }
        if (!this.global) {
            this.lastIndex = origLastIndex; // Fixes IE, Opera bug (last tested IE 9, Opera 11.6)
        }
        return match;
    };

/**
 * Fixes browser bugs in the native `RegExp.prototype.test`. Calling `XRegExp.install('natives')`
 * uses this to override the native method.
 * @private
 * @param {String} str String to search.
 * @returns {Boolean} Whether the regex matched the provided value.
 */
    fixed.test = function (str) {
        // Do this the easy way :-)
        return !!fixed.exec.call(this, str);
    };

/**
 * Adds named capture support (with backreferences returned as `result.name`), and fixes browser
 * bugs in the native `String.prototype.match`. Calling `XRegExp.install('natives')` uses this to
 * override the native method.
 * @private
 * @param {RegExp} regex Regex to search with.
 * @returns {Array} If `regex` uses flag g, an array of match strings or null. Without flag g, the
 *   result of calling `regex.exec(this)`.
 */
    fixed.match = function (regex) {
        if (!self.isRegExp(regex)) {
            regex = new RegExp(regex); // Use native `RegExp`
        } else if (regex.global) {
            var result = nativ.match.apply(this, arguments);
            regex.lastIndex = 0; // Fixes IE bug
            return result;
        }
        return fixed.exec.call(regex, this);
    };

/**
 * Adds support for `${n}` tokens for named and numbered backreferences in replacement text, and
 * provides named backreferences to replacement functions as `arguments[0].name`. Also fixes
 * browser bugs in replacement text syntax when performing a replacement using a nonregex search
 * value, and the value of a replacement regex's `lastIndex` property during replacement iterations
 * and upon completion. Note that this doesn't support SpiderMonkey's proprietary third (`flags`)
 * argument. Calling `XRegExp.install('natives')` uses this to override the native method. Use via
 * `XRegExp.replace` without overriding natives.
 * @private
 * @param {RegExp|String} search Search pattern to be replaced.
 * @param {String|Function} replacement Replacement string or a function invoked to create it.
 * @returns {String} New string with one or all matches replaced.
 */
    fixed.replace = function (search, replacement) {
        var isRegex = self.isRegExp(search), captureNames, result, str, origLastIndex;
        if (isRegex) {
            if (search.xregexp) {
                captureNames = search.xregexp.captureNames;
            }
            if (!search.global) {
                origLastIndex = search.lastIndex;
            }
        } else {
            search += "";
        }
        if (isType(replacement, "function")) {
            result = nativ.replace.call(String(this), search, function () {
                var args = arguments, i;
                if (captureNames) {
                    // Change the `arguments[0]` string primitive to a `String` object that can store properties
                    args[0] = new String(args[0]);
                    // Store named backreferences on the first argument
                    for (i = 0; i < captureNames.length; ++i) {
                        if (captureNames[i]) {
                            args[0][captureNames[i]] = args[i + 1];
                        }
                    }
                }
                // Update `lastIndex` before calling `replacement`.
                // Fixes IE, Chrome, Firefox, Safari bug (last tested IE 9, Chrome 17, Firefox 11, Safari 5.1)
                if (isRegex && search.global) {
                    search.lastIndex = args[args.length - 2] + args[0].length;
                }
                return replacement.apply(null, args);
            });
        } else {
            str = String(this); // Ensure `args[args.length - 1]` will be a string when given nonstring `this`
            result = nativ.replace.call(str, search, function () {
                var args = arguments; // Keep this function's `arguments` available through closure
                return nativ.replace.call(String(replacement), replacementToken, function ($0, $1, $2) {
                    var n;
                    // Named or numbered backreference with curly brackets
                    if ($1) {
                        /* XRegExp behavior for `${n}`:
                         * 1. Backreference to numbered capture, where `n` is 1+ digits. `0`, `00`, etc. is the entire match.
                         * 2. Backreference to named capture `n`, if it exists and is not a number overridden by numbered capture.
                         * 3. Otherwise, it's an error.
                         */
                        n = +$1; // Type-convert; drop leading zeros
                        if (n <= args.length - 3) {
                            return args[n] || "";
                        }
                        n = captureNames ? lastIndexOf(captureNames, $1) : -1;
                        if (n < 0) {
                            throw new SyntaxError("backreference to undefined group " + $0);
                        }
                        return args[n + 1] || "";
                    }
                    // Else, special variable or numbered backreference (without curly brackets)
                    if ($2 === "$") return "$";
                    if ($2 === "&" || +$2 === 0) return args[0]; // $&, $0 (not followed by 1-9), $00
                    if ($2 === "`") return args[args.length - 1].slice(0, args[args.length - 2]);
                    if ($2 === "'") return args[args.length - 1].slice(args[args.length - 2] + args[0].length);
                    // Else, numbered backreference (without curly brackets)
                    $2 = +$2; // Type-convert; drop leading zero
                    /* XRegExp behavior:
                     * - Backreferences without curly brackets end after 1 or 2 digits. Use `${..}` for more digits.
                     * - `$1` is an error if there are no capturing groups.
                     * - `$10` is an error if there are less than 10 capturing groups. Use `${1}0` instead.
                     * - `$01` is equivalent to `$1` if a capturing group exists, otherwise it's an error.
                     * - `$0` (not followed by 1-9), `$00`, and `$&` are the entire match.
                     * Native behavior, for comparison:
                     * - Backreferences end after 1 or 2 digits. Cannot use backreference to capturing group 100+.
                     * - `$1` is a literal `$1` if there are no capturing groups.
                     * - `$10` is `$1` followed by a literal `0` if there are less than 10 capturing groups.
                     * - `$01` is equivalent to `$1` if a capturing group exists, otherwise it's a literal `$01`.
                     * - `$0` is a literal `$0`. `$&` is the entire match.
                     */
                    if (!isNaN($2)) {
                        if ($2 > args.length - 3) {
                            throw new SyntaxError("backreference to undefined group " + $0);
                        }
                        return args[$2] || "";
                    }
                    throw new SyntaxError("invalid token " + $0);
                });
            });
        }
        if (isRegex) {
            if (search.global) {
                search.lastIndex = 0; // Fixes IE, Safari bug (last tested IE 9, Safari 5.1)
            } else {
                search.lastIndex = origLastIndex; // Fixes IE, Opera bug (last tested IE 9, Opera 11.6)
            }
        }
        return result;
    };

/**
 * Fixes browser bugs in the native `String.prototype.split`. Calling `XRegExp.install('natives')`
 * uses this to override the native method. Use via `XRegExp.split` without overriding natives.
 * @private
 * @param {RegExp|String} separator Regex or string to use for separating the string.
 * @param {Number} [limit] Maximum number of items to include in the result array.
 * @returns {Array} Array of substrings.
 */
    fixed.split = function (separator, limit) {
        if (!self.isRegExp(separator)) {
            return nativ.split.apply(this, arguments); // use faster native method
        }
        var str = String(this),
            origLastIndex = separator.lastIndex,
            output = [],
            lastLastIndex = 0,
            lastLength;
        /* Values for `limit`, per the spec:
         * If undefined: pow(2,32) - 1
         * If 0, Infinity, or NaN: 0
         * If positive number: limit = floor(limit); if (limit >= pow(2,32)) limit -= pow(2,32);
         * If negative number: pow(2,32) - floor(abs(limit))
         * If other: Type-convert, then use the above rules
         */
        limit = (limit === undef ? -1 : limit) >>> 0;
        self.forEach(str, separator, function (match) {
            if ((match.index + match[0].length) > lastLastIndex) { // != `if (match[0].length)`
                output.push(str.slice(lastLastIndex, match.index));
                if (match.length > 1 && match.index < str.length) {
                    Array.prototype.push.apply(output, match.slice(1));
                }
                lastLength = match[0].length;
                lastLastIndex = match.index + lastLength;
            }
        });
        if (lastLastIndex === str.length) {
            if (!nativ.test.call(separator, "") || lastLength) {
                output.push("");
            }
        } else {
            output.push(str.slice(lastLastIndex));
        }
        separator.lastIndex = origLastIndex;
        return output.length > limit ? output.slice(0, limit) : output;
    };

/*--------------------------------------
 *  Built-in tokens
 *------------------------------------*/

// Shortcut
    add = addToken.on;

/* Letter identity escapes that natively match literal characters: \p, \P, etc.
 * Should be SyntaxErrors but are allowed in web reality. XRegExp makes them errors for cross-
 * browser consistency and to reserve their syntax, but lets them be superseded by XRegExp addons.
 */
    add(/\\([ABCE-RTUVXYZaeg-mopqyz]|c(?![A-Za-z])|u(?![\dA-Fa-f]{4})|x(?![\dA-Fa-f]{2}))/,
        function (match, scope) {
            // \B is allowed in default scope only
            if (match[1] === "B" && scope === defaultScope) {
                return match[0];
            }
            throw new SyntaxError("invalid escape " + match[0]);
        },
        {scope: "all"});

/* Empty character class: [] or [^]
 * Fixes a critical cross-browser syntax inconsistency. Unless this is standardized (per the spec),
 * regex syntax can't be accurately parsed because character class endings can't be determined.
 */
    add(/\[(\^?)]/,
        function (match) {
            // For cross-browser compatibility with ES3, convert [] to \b\B and [^] to [\s\S].
            // (?!) should work like \b\B, but is unreliable in Firefox
            return match[1] ? "[\\s\\S]" : "\\b\\B";
        });

/* Comment pattern: (?# )
 * Inline comments are an alternative to the line comments allowed in free-spacing mode (flag x).
 */
    add(/(?:\(\?#[^)]*\))+/,
        function (match) {
            // Keep tokens separated unless the following token is a quantifier
            return nativ.test.call(quantifier, match.input.slice(match.index + match[0].length)) ? "" : "(?:)";
        });

/* Named backreference: \k<name>
 * Backreference names can use the characters A-Z, a-z, 0-9, _, and $ only.
 */
    add(/\\k<([\w$]+)>/,
        function (match) {
            var index = isNaN(match[1]) ? (lastIndexOf(this.captureNames, match[1]) + 1) : +match[1],
                endIndex = match.index + match[0].length;
            if (!index || index > this.captureNames.length) {
                throw new SyntaxError("backreference to undefined group " + match[0]);
            }
            // Keep backreferences separate from subsequent literal numbers
            return "\\" + index + (
                endIndex === match.input.length || isNaN(match.input.charAt(endIndex)) ? "" : "(?:)"
            );
        });

/* Whitespace and line comments, in free-spacing mode (aka extended mode, flag x) only.
 */
    add(/(?:\s+|#.*)+/,
        function (match) {
            // Keep tokens separated unless the following token is a quantifier
            return nativ.test.call(quantifier, match.input.slice(match.index + match[0].length)) ? "" : "(?:)";
        },
        {
            trigger: function () {
                return this.hasFlag("x");
            },
            customFlags: "x"
        });

/* Dot, in dotall mode (aka singleline mode, flag s) only.
 */
    add(/\./,
        function () {
            return "[\\s\\S]";
        },
        {
            trigger: function () {
                return this.hasFlag("s");
            },
            customFlags: "s"
        });

/* Named capturing group; match the opening delimiter only: (?<name>
 * Capture names can use the characters A-Z, a-z, 0-9, _, and $ only. Names can't be integers.
 * Supports Python-style (?P<name> as an alternate syntax to avoid issues in recent Opera (which
 * natively supports the Python-style syntax). Otherwise, XRegExp might treat numbered
 * backreferences to Python-style named capture as octals.
 */
    add(/\(\?P?<([\w$]+)>/,
        function (match) {
            if (!isNaN(match[1])) {
                // Avoid incorrect lookups, since named backreferences are added to match arrays
                throw new SyntaxError("can't use integer as capture name " + match[0]);
            }
            this.captureNames.push(match[1]);
            this.hasNamedCapture = true;
            return "(";
        });

/* Numbered backreference or octal, plus any following digits: \0, \11, etc.
 * Octals except \0 not followed by 0-9 and backreferences to unopened capture groups throw an
 * error. Other matches are returned unaltered. IE <= 8 doesn't support backreferences greater than
 * \99 in regex syntax.
 */
    add(/\\(\d+)/,
        function (match, scope) {
            if (!(scope === defaultScope && /^[1-9]/.test(match[1]) && +match[1] <= this.captureNames.length) &&
                    match[1] !== "0") {
                throw new SyntaxError("can't use octal escape or backreference to undefined group " + match[0]);
            }
            return match[0];
        },
        {scope: "all"});

/* Capturing group; match the opening parenthesis only.
 * Required for support of named capturing groups. Also adds explicit capture mode (flag n).
 */
    add(/\((?!\?)/,
        function () {
            if (this.hasFlag("n")) {
                return "(?:";
            }
            this.captureNames.push(null);
            return "(";
        },
        {customFlags: "n"});

/*--------------------------------------
 *  Expose XRegExp
 *------------------------------------*/

// For CommonJS enviroments
    if (typeof exports !== "undefined") {
        exports.XRegExp = self;
    }

    return self;

}());

//
// Begin anonymous function. This is used to contain local scope variables without polutting global scope.
//
if (typeof(SyntaxHighlighter) == 'undefined') var SyntaxHighlighter = function() {

// CommonJS
if (typeof(require) != 'undefined' && typeof(XRegExp) == 'undefined')
{
	XRegExp = require('xregexp').XRegExp;
}

// Shortcut object which will be assigned to the SyntaxHighlighter variable.
// This is a shorthand for local reference in order to avoid long namespace
// references to SyntaxHighlighter.whatever...
var sh = {
	defaults : {
		/** Additional CSS class names to be added to highlighter elements. */
		'class-name' : '',

		/** First line number. */
		'first-line' : 1,

		/**
		 * Pads line numbers. Possible values are:
		 *
		 *   false - don't pad line numbers.
		 *   true  - automaticaly pad numbers with minimum required number of leading zeroes.
		 *   [int] - length up to which pad line numbers.
		 */
		'pad-line-numbers' : false,

		/** Lines to highlight. */
		'highlight' : null,

		/** Title to be displayed above the code block. */
		'title' : null,

		/** Enables or disables smart tabs. */
		'smart-tabs' : true,

		/** Gets or sets tab size. */
		'tab-size' : 4,

		/** Enables or disables gutter. */
		'gutter' : true,

		/** Enables or disables toolbar. */
		'toolbar' : true,

		/** Enables quick code copy and paste from double click. */
		'quick-code' : true,

		/** Forces code view to be collapsed. */
		'collapse' : false,

		/** Enables or disables automatic links. */
		'auto-links' : true,

		/** Gets or sets light mode. Equavalent to turning off gutter and toolbar. */
		'light' : false,

		'unindent' : true,

		'html-script' : false
	},

	config : {
		space : '&nbsp;',

		/** Enables use of <SCRIPT type="syntaxhighlighter" /> tags. */
		useScriptTags : true,

		/** Blogger mode flag. */
		bloggerMode : false,

		stripBrs : false,

		/** Name of the tag that SyntaxHighlighter will automatically look for. */
		tagName : 'pre',

		strings : {
			expandSource : 'expand source',
			help : '?',
			alert: 'SyntaxHighlighter\n\n',
			noBrush : 'Can\'t find brush for: ',
			brushNotHtmlScript : 'Brush wasn\'t configured for html-script option: ',

			// this is populated by the build script
			aboutDialog : '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>About SyntaxHighlighter</title></head><body style=\"font-family:Geneva,Arial,Helvetica,sans-serif;background-color:#fff;color:#000;font-size:1em;text-align:center;\"><div style=\"text-align:center;margin-top:1.5em;\"><div style=\"font-size:xx-large;\">SyntaxHighlighter</div><div style=\"font-size:.75em;margin-bottom:3em;\"><div>version 3.0.9 (Thu, 04 Dec 2014 12:32:21 GMT)</div><div><a href=\"http://alexgorbatchev.com/SyntaxHighlighter\" target=\"_blank\" style=\"color:#005896\">http://alexgorbatchev.com/SyntaxHighlighter</a></div><div>JavaScript code syntax highlighter.</div><div>Copyright 2004-2013 Alex Gorbatchev.</div></div><div>If you like this script, please <a href=\"https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2930402\" style=\"color:#005896\">donate</a> to <br/>keep development active!</div></div></body></html>'
		}
	},

	/** Internal 'global' variables. */
	vars : {
		highlighters : {}
	},

	/** This object is populated by user included external brush files. */
	brushes : {},

	/** Common regular expressions. */
	regexLib : {
		multiLineCComments			: XRegExp('/\\*.*?\\*/', 'gs'),
		singleLineCComments			: /\/\/.*$/gm,
		singleLinePerlComments		: /#.*$/gm,
		doubleQuotedString			: /"([^\\"\n]|\\.)*"/g,
		singleQuotedString			: /'([^\\'\n]|\\.)*'/g,
		multiLineDoubleQuotedString	: XRegExp('"([^\\\\"]|\\\\.)*"', 'gs'),
		multiLineSingleQuotedString	: XRegExp("'([^\\\\']|\\\\.)*'", 'gs'),
		xmlComments					: XRegExp('(&lt;|<)!--.*?--(&gt;|>)', 'gs'),
		url							: /\w+:\/\/[\w-.\/?%&=:@;#]*/g,
		phpScriptTags 				: { left: /(&lt;|<)\?(?:=|php)?/g, right: /\?(&gt;|>)/g, 'eof' : true },
		aspScriptTags				: { left: /(&lt;|<)%=?/g, right: /%(&gt;|>)/g },
		scriptScriptTags			: { left: /(&lt;|<)\s*script.*?(&gt;|>)/gi, right: /(&lt;|<)\/\s*script\s*(&gt;|>)/gi }
	},

	toolbar: {
		/**
		 * Generates HTML markup for the toolbar.
		 * @param {Highlighter} highlighter Highlighter instance.
		 * @return {String} Returns HTML markup.
		 */
		getHtml: function(highlighter)
		{
			var html = '<div class="toolbar">',
				items = sh.toolbar.items,
				list = items.list
				;

			function defaultGetHtml(highlighter, name)
			{
				return sh.toolbar.getButtonHtml(highlighter, name, sh.config.strings[name]);
			}

			for (var i = 0, l = list.length; i < l; i++)
			{
				html += (items[list[i]].getHtml || defaultGetHtml)(highlighter, list[i]);
			}

			html += '</div>';

			return html;
		},

		/**
		 * Generates HTML markup for a regular button in the toolbar.
		 * @param {Highlighter} highlighter Highlighter instance.
		 * @param {String} commandName		Command name that would be executed.
		 * @param {String} label			Label text to display.
		 * @return {String}					Returns HTML markup.
		 */
		getButtonHtml: function(highlighter, commandName, label)
		{
			commandName = escapeHtml(commandName);

			return '<span><a href="#" class="toolbar_item'
				+ ' command_' + commandName
				+ ' ' + commandName
				+ '">' + escapeHtml(label) + '</a></span>'
				;
		},

		/**
		 * Event handler for a toolbar anchor.
		 */
		handler: function(e)
		{
			var target = e.target,
				className = target.className || ''
				;

			function getValue(name)
			{
				var r = new RegExp(name + '_(\\w+)'),
					match = r.exec(className)
					;

				return match ? match[1] : null;
			}

			var highlighter = getHighlighterById(findParentElement(target, '.syntaxhighlighter').id),
				commandName = getValue('command')
				;

			// execute the toolbar command
			if (highlighter && commandName)
				sh.toolbar.items[commandName].execute(highlighter);

			// disable default A click behaviour
			e.preventDefault();
		},

		/** Collection of toolbar items. */
		items : {
			// Ordered lis of items in the toolbar. Can't expect `for (var n in items)` to be consistent.
			list: ['expandSource', 'help'],

			expandSource: {
				getHtml: function(highlighter)
				{
					if (highlighter.getParam('collapse') != true)
						return '';

					var title = highlighter.getParam('title');
					return sh.toolbar.getButtonHtml(highlighter, 'expandSource', title ? title : sh.config.strings.expandSource);
				},

				execute: function(highlighter)
				{
					var div = getHighlighterDivById(highlighter.id);
					removeClass(div, 'collapsed');
				}
			},

			/** Command to display the about dialog window. */
			help: {
				execute: function(highlighter)
				{
					var wnd = popup('', '_blank', 500, 250, 'scrollbars=0'),
						doc = wnd.document
						;

					doc.write(sh.config.strings.aboutDialog);
					doc.close();
					wnd.focus();
				}
			}
		}
	},

	/**
	 * Finds all elements on the page which should be processes by SyntaxHighlighter.
	 *
	 * @param {Object} globalParams		Optional parameters which override element's
	 * 									parameters. Only used if element is specified.
	 *
	 * @param {Object} element	Optional element to highlight. If none is
	 * 							provided, all elements in the current document
	 * 							are returned which qualify.
	 *
	 * @return {Array}	Returns list of <code>{ target: DOMElement, params: Object }</code> objects.
	 */
	findElements: function(globalParams, element)
	{
		var elements = element ? [element] : toArray(document.getElementsByTagName(sh.config.tagName)),
			conf = sh.config,
			result = []
			;

		// support for <SCRIPT TYPE="syntaxhighlighter" /> feature
		if (conf.useScriptTags)
			elements = elements.concat(getSyntaxHighlighterScriptTags());

		if (elements.length === 0)
			return result;

		for (var i = 0, l = elements.length; i < l; i++)
		{
			var item = {
				target: elements[i],
				// local params take precedence over globals
				params: merge(globalParams, parseParams(elements[i].className))
			};

			if (item.params['brush'] == null)
				continue;

			result.push(item);
		}

		return result;
	},

	/**
	 * Shorthand to highlight all elements on the page that are marked as
	 * SyntaxHighlighter source code.
	 *
	 * @param {Object} globalParams		Optional parameters which override element's
	 * 									parameters. Only used if element is specified.
	 *
	 * @param {Object} element	Optional element to highlight. If none is
	 * 							provided, all elements in the current document
	 * 							are highlighted.
	 */
	highlight: function(globalParams, element)
	{
		var elements = this.findElements(globalParams, element),
			propertyName = 'innerHTML',
			highlighter = null,
			conf = sh.config
			;

		if (elements.length === 0)
			return;

		for (var i = 0, l = elements.length; i < l; i++)
		{
			var element = elements[i],
				target = element.target,
				params = element.params,
				brushName = params.brush,
				code
				;

			if (brushName == null)
				continue;

			// Instantiate a brush
			if (params['html-script'] == 'true' || sh.defaults['html-script'] == true)
			{
				highlighter = new sh.HtmlScript(brushName);
				brushName = 'htmlscript';
			}
			else
			{
				var brush = findBrush(brushName);

				if (brush)
					highlighter = new brush();
				else
					continue;
			}

			code = target[propertyName];

			// remove CDATA from <SCRIPT/> tags if it's present
			if (conf.useScriptTags)
				code = stripCData(code);

			// Inject title if the attribute is present
			if ((target.title || '') != '')
				params.title = target.title;

			params['brush'] = brushName;
			highlighter.init(params);
			element = highlighter.getDiv(code);

			// carry over ID
			if ((target.id || '') != '')
				element.id = target.id;

			target.parentNode.replaceChild(element, target);
		}
	},

	/**
	 * Main entry point for the SyntaxHighlighter.
	 * @param {Object} params Optional params to apply to all highlighted elements.
	 */
	all: function(params)
	{
		attachEvent(
			window,
			'load',
			function() { sh.highlight(params); }
		);
	}
}; // end of sh

function escapeHtml(html)
{
	return document.createElement('div').appendChild(document.createTextNode(html)).parentNode.innerHTML.replace(/"/g, '&quot;');
};

/**
 * Checks if target DOM elements has specified CSS class.
 * @param {DOMElement} target Target DOM element to check.
 * @param {String} className Name of the CSS class to check for.
 * @return {Boolean} Returns true if class name is present, false otherwise.
 */
function hasClass(target, className)
{
	return target.className.indexOf(className) != -1;
};

/**
 * Adds CSS class name to the target DOM element.
 * @param {DOMElement} target Target DOM element.
 * @param {String} className New CSS class to add.
 */
function addClass(target, className)
{
	if (!hasClass(target, className))
		target.className += ' ' + className;
};

/**
 * Removes CSS class name from the target DOM element.
 * @param {DOMElement} target Target DOM element.
 * @param {String} className CSS class to remove.
 */
function removeClass(target, className)
{
	target.className = target.className.replace(className, '');
};

/**
 * Converts the source to array object. Mostly used for function arguments and
 * lists returned by getElementsByTagName() which aren't Array objects.
 * @param {List} source Source list.
 * @return {Array} Returns array.
 */
function toArray(source)
{
	var result = [];

	for (var i = 0, l = source.length; i < l; i++)
		result.push(source[i]);

	return result;
};

/**
 * Splits block of text into lines.
 * @param {String} block Block of text.
 * @return {Array} Returns array of lines.
 */
function splitLines(block)
{
	return block.split(/\r?\n/);
}

/**
 * Generates HTML ID for the highlighter.
 * @param {String} highlighterId Highlighter ID.
 * @return {String} Returns HTML ID.
 */
function getHighlighterId(id)
{
	var prefix = 'highlighter_';
	return id.indexOf(prefix) == 0 ? id : prefix + id;
};

/**
 * Finds Highlighter instance by ID.
 * @param {String} highlighterId Highlighter ID.
 * @return {Highlighter} Returns instance of the highlighter.
 */
function getHighlighterById(id)
{
	return sh.vars.highlighters[getHighlighterId(id)];
};

/**
 * Finds highlighter's DIV container.
 * @param {String} highlighterId Highlighter ID.
 * @return {Element} Returns highlighter's DIV element.
 */
function getHighlighterDivById(id)
{
	return document.getElementById(getHighlighterId(id));
};

/**
 * Stores highlighter so that getHighlighterById() can do its thing. Each
 * highlighter must call this method to preserve itself.
 * @param {Highilghter} highlighter Highlighter instance.
 */
function storeHighlighter(highlighter)
{
	sh.vars.highlighters[getHighlighterId(highlighter.id)] = highlighter;
};

/**
 * Looks for a child or parent node which has specified classname.
 * Equivalent to jQuery's $(container).find(".className")
 * @param {Element} target Target element.
 * @param {String} search Class name or node name to look for.
 * @param {Boolean} reverse If set to true, will go up the node tree instead of down.
 * @return {Element} Returns found child or parent element on null.
 */
function findElement(target, search, reverse /* optional */)
{
	if (target == null)
		return null;

	var nodes			= reverse != true ? target.childNodes : [ target.parentNode ],
		propertyToFind	= { '#' : 'id', '.' : 'className' }[search.substr(0, 1)] || 'nodeName',
		expectedValue,
		found
		;

	expectedValue = propertyToFind != 'nodeName'
		? search.substr(1)
		: search.toUpperCase()
		;

	// main return of the found node
	if ((target[propertyToFind] || '').indexOf(expectedValue) != -1)
		return target;

	for (var i = 0, l = nodes.length; nodes && i < l && found == null; i++)
		found = findElement(nodes[i], search, reverse);

	return found;
};

/**
 * Looks for a parent node which has specified classname.
 * This is an alias to <code>findElement(container, className, true)</code>.
 * @param {Element} target Target element.
 * @param {String} className Class name to look for.
 * @return {Element} Returns found parent element on null.
 */
function findParentElement(target, className)
{
	return findElement(target, className, true);
};

/**
 * Finds an index of element in the array.
 * @ignore
 * @param {Object} searchElement
 * @param {Number} fromIndex
 * @return {Number} Returns index of element if found; -1 otherwise.
 */
function indexOf(array, searchElement, fromIndex)
{
	fromIndex = Math.max(fromIndex || 0, 0);

	for (var i = fromIndex, l = array.length; i < l; i++)
		if(array[i] == searchElement)
			return i;

	return -1;
};

/**
 * Generates a unique element ID.
 */
function guid(prefix)
{
	return (prefix || '') + Math.round(Math.random() * 1000000).toString();
};

/**
 * Merges two objects. Values from obj2 override values in obj1.
 * Function is NOT recursive and works only for one dimensional objects.
 * @param {Object} obj1 First object.
 * @param {Object} obj2 Second object.
 * @return {Object} Returns combination of both objects.
 */
function merge(obj1, obj2)
{
	var result = {}, name;

	for (name in obj1)
		result[name] = obj1[name];

	for (name in obj2)
		result[name] = obj2[name];

	return result;
};

/**
 * Attempts to convert string to boolean.
 * @param {String} value Input string.
 * @return {Boolean} Returns true if input was "true", false if input was "false" and value otherwise.
 */
function toBoolean(value)
{
	var result = { "true" : true, "false" : false }[value];
	return result == null ? value : result;
};

/**
 * Opens up a centered popup window.
 * @param {String} url		URL to open in the window.
 * @param {String} name		Popup name.
 * @param {int} width		Popup width.
 * @param {int} height		Popup height.
 * @param {String} options	window.open() options.
 * @return {Window}			Returns window instance.
 */
function popup(url, name, width, height, options)
{
	var x = (screen.width - width) / 2,
		y = (screen.height - height) / 2
		;

	options +=	', left=' + x +
				', top=' + y +
				', width=' + width +
				', height=' + height
		;
	options = options.replace(/^,/, '');

	var win = window.open(url, name, options);
	win.focus();
	return win;
};

/**
 * Adds event handler to the target object.
 * @param {Object} obj		Target object.
 * @param {String} type		Name of the event.
 * @param {Function} func	Handling function.
 */
function attachEvent(obj, type, func, scope)
{
	function handler(e)
	{
		e = e || window.event;

		if (!e.target)
		{
			e.target = e.srcElement;
			e.preventDefault = function()
			{
				this.returnValue = false;
			};
		}

		func.call(scope || window, e);
	};

	if (obj.attachEvent)
	{
		obj.attachEvent('on' + type, handler);
	}
	else
	{
		obj.addEventListener(type, handler, false);
	}
};

/**
 * Displays an alert.
 * @param {String} str String to display.
 */
function alert(str)
{
	window.alert(sh.config.strings.alert + str);
};

/**
 * Finds a brush by its alias.
 *
 * @param {String} alias		Brush alias.
 * @param {Boolean} showAlert	Suppresses the alert if false.
 * @return {Brush}				Returns bursh constructor if found, null otherwise.
 */
function findBrush(alias, showAlert)
{
	var brush = sh.brushes[alias];

  if (brush !== undefined) {
    return brush;
  }

  // Find brush
  for (brush in sh.brushes)
  {
    var info = sh.brushes[brush],
      aliases = info.aliases
      ;

    if (aliases == null)
      continue;

    // keep the brush name
    info.brushName = brush.toLowerCase();

    for (var i = 0, l = aliases.length; i < l; i++)
      sh.brushes[aliases[i]] = info;
  }

	brush = sh.brushes[alias];

	if (brush === undefined) {
    sh.brushes[alias] = null;
    if (showAlert) {
      alert(sh.config.strings.noBrush + alias);
    }
  }

	return brush;
};

/**
 * Executes a callback on each line and replaces each line with result from the callback.
 * @param {Object} str			Input string.
 * @param {Object} callback		Callback function taking one string argument and returning a string.
 */
function eachLine(str, callback)
{
	var lines = splitLines(str);

	for (var i = 0, l = lines.length; i < l; i++)
		lines[i] = callback(lines[i], i);

	// include \r to enable copy-paste on windows (ie8) without getting everything on one line
	return lines.join('\r\n');
};

/**
 * This is a special trim which only removes first and last empty lines
 * and doesn't affect valid leading space on the first line.
 *
 * @param {String} str   Input string
 * @return {String}      Returns string without empty first and last lines.
 */
function trimFirstAndLastLines(str)
{
	return str.replace(/^[ ]*[\n]+|[\n]*[ ]*$/g, '');
};

/**
 * Parses key/value pairs into hash object.
 *
 * Understands the following formats:
 * - name: word;
 * - name: [word, word];
 * - name: "string";
 * - name: 'string';
 *
 * For example:
 *   name1: value; name2: [value, value]; name3: 'value'
 *
 * @param {String} str    Input string.
 * @return {Object}       Returns deserialized object.
 */
function parseParams(str)
{
	var match,
		result = {},
		arrayRegex = XRegExp("^\\[(?<values>(.*?))\\]$"),
		pos = 0,
		regex = XRegExp(
			"(?<name>[\\w-]+)" +
			"\\s*:\\s*" +
			"(?<value>" +
				"[\\w%#-]+|" +		// word
				"\\[.*?\\]|" +		// [] array
				'".*?"|' +			// "" string
				"'.*?'" +			// '' string
			")\\s*;?",
			"g"
		)
		;

	while ((match = XRegExp.exec(str, regex, pos)) != null)
	{
		var value = match.value
			.replace(/^['"]|['"]$/g, '') // strip quotes from end of strings
			;

		// try to parse array value
		if (value != null && arrayRegex.test(value))
		{
			var m = XRegExp.exec(value, arrayRegex);
			value = m.values.length > 0 ? m.values.split(/\s*,\s*/) : [];
		}

		result[match.name] = value;
		pos = match.index + match[0].length;
	}

	return result;
};

/**
 * Wraps each line of the string into <code/> tag with given style applied to it.
 *
 * @param {String} str   Input string.
 * @param {String} css   Style name to apply to the string.
 * @return {String}      Returns input string with each line surrounded by <span/> tag.
 */
function wrapLinesWithCode(str, css)
{
	if (str == null || str.length == 0 || str == '\n')
		return str;

	str = str.replace(/</g, '&lt;');

	// Replace two or more sequential spaces with &nbsp; leaving last space untouched.
	str = str.replace(/ {2,}/g, function(m)
	{
		var spaces = '';

		for (var i = 0, l = m.length; i < l - 1; i++)
			spaces += sh.config.space;

		return spaces + ' ';
	});

	// Split each line and apply <span class="...">...</span> to them so that
	// leading spaces aren't included.
	if (css != null)
		str = eachLine(str, function(line)
		{
			if (line.length == 0)
				return '';

			var spaces = '';

			line = line.replace(/^(&nbsp;| )+/, function(s)
			{
				spaces = s;
				return '';
			});

			if (line.length == 0)
				return spaces;

			return spaces + '<code class="' + css + '">' + line + '</code>';
		});

	return str;
};

/**
 * Pads number with zeros until it's length is the same as given length.
 *
 * @param {Number} number	Number to pad.
 * @param {Number} length	Max string length with.
 * @return {String}			Returns a string padded with proper amount of '0'.
 */
function padNumber(number, length)
{
	var result = number.toString();

	while (result.length < length)
		result = '0' + result;

	return result;
};

/**
 * Replaces tabs with spaces.
 *
 * @param {String} code		Source code.
 * @param {Number} tabSize	Size of the tab.
 * @return {String}			Returns code with all tabs replaces by spaces.
 */
function processTabs(code, tabSize)
{
	var tab = '';

	for (var i = 0; i < tabSize; i++)
		tab += ' ';

	return code.replace(/\t/g, tab);
};

/**
 * Replaces tabs with smart spaces.
 *
 * @param {String} code    Code to fix the tabs in.
 * @param {Number} tabSize Number of spaces in a column.
 * @return {String}        Returns code with all tabs replaces with roper amount of spaces.
 */
function processSmartTabs(code, tabSize)
{
	var lines = splitLines(code),
		tab = '\t',
		spaces = ''
		;

	// Create a string with 1000 spaces to copy spaces from...
	// It's assumed that there would be no indentation longer than that.
	for (var i = 0; i < 50; i++)
		spaces += '                    '; // 20 spaces * 50

	// This function inserts specified amount of spaces in the string
	// where a tab is while removing that given tab.
	function insertSpaces(line, pos, count)
	{
		return line.substr(0, pos)
			+ spaces.substr(0, count)
			+ line.substr(pos + 1, line.length) // pos + 1 will get rid of the tab
			;
	};

	// Go through all the lines and do the 'smart tabs' magic.
	code = eachLine(code, function(line)
	{
		if (line.indexOf(tab) == -1)
			return line;

		var pos = 0;

		while ((pos = line.indexOf(tab)) != -1)
		{
			// This is pretty much all there is to the 'smart tabs' logic.
			// Based on the position within the line and size of a tab,
			// calculate the amount of spaces we need to insert.
			var spaces = tabSize - pos % tabSize;
			line = insertSpaces(line, pos, spaces);
		}

		return line;
	});

	return code;
};

/**
 * Performs various string fixes based on configuration.
 */
function fixInputString(str)
{
	var br = /<br\s*\/?>|&lt;br\s*\/?&gt;/gi;

	if (sh.config.bloggerMode == true)
		str = str.replace(br, '\n');

	if (sh.config.stripBrs == true)
		str = str.replace(br, '');

	return str;
};

/**
 * Removes all white space at the begining and end of a string.
 *
 * @param {String} str   String to trim.
 * @return {String}      Returns string without leading and following white space characters.
 */
function trim(str)
{
	return str.replace(/^\s+|\s+$/g, '');
};

/**
 * Unindents a block of text by the lowest common indent amount.
 * @param {String} str   Text to unindent.
 * @return {String}      Returns unindented text block.
 */
function unindent(str)
{
	var lines = splitLines(fixInputString(str)),
		indents = new Array(),
		regex = /^\s*/,
		min = 1000
		;

	// go through every line and check for common number of indents
	for (var i = 0, l = lines.length; i < l && min > 0; i++)
	{
		var line = lines[i];

		if (trim(line).length == 0)
			continue;

		var matches = regex.exec(line);

		// In the event that just one line doesn't have leading white space
		// we can't unindent anything, so bail completely.
		if (matches == null)
			return str;

		min = Math.min(matches[0].length, min);
	}

	// trim minimum common number of white space from the begining of every line
	if (min > 0)
		for (var i = 0, l = lines.length; i < l; i++)
			lines[i] = lines[i].substr(min);

	return lines.join('\n');
};

/**
 * Callback method for Array.sort() which sorts matches by
 * index position and then by length.
 *
 * @param {Match} m1	Left object.
 * @param {Match} m2    Right object.
 * @return {Number}     Returns -1, 0 or -1 as a comparison result.
 */
function matchesSortCallback(m1, m2)
{
	// sort matches by index first
	if(m1.index < m2.index)
		return -1;
	else if(m1.index > m2.index)
		return 1;
	else
	{
		// if index is the same, sort by length
		if(m1.length < m2.length)
			return -1;
		else if(m1.length > m2.length)
			return 1;
	}

	return 0;
};

/**
 * Executes given regular expression on provided code and returns all
 * matches that are found.
 *
 * @param {String} code    Code to execute regular expression on.
 * @param {Object} regex   Regular expression item info from <code>regexList</code> collection.
 * @return {Array}         Returns a list of Match objects.
 */
function getMatches(code, regexInfo)
{
	function defaultAdd(match, regexInfo)
	{
		return match[0];
	};

	var index = 0,
		match = null,
		matches = [],
		func = regexInfo.func ? regexInfo.func : defaultAdd
		pos = 0
		;

	while((match = XRegExp.exec(code, regexInfo.regex, pos)) != null)
	{
		var resultMatch = func(match, regexInfo);

		if (typeof(resultMatch) == 'string')
			resultMatch = [new sh.Match(resultMatch, match.index, regexInfo.css)];

		matches = matches.concat(resultMatch);
		pos = match.index + match[0].length;
	}

	return matches;
};

/**
 * Turns all URLs in the code into <a/> tags.
 * @param {String} code Input code.
 * @return {String} Returns code with </a> tags.
 */
function processUrls(code)
{
	var gt = /(.*)((&gt;|&lt;).*)/;

	return code.replace(sh.regexLib.url, function(m)
	{
		var suffix = '',
			match = null
			;

		// We include &lt; and &gt; in the URL for the common cases like <http://google.com>
		// The problem is that they get transformed into &lt;http://google.com&gt;
		// Where as &gt; easily looks like part of the URL string.

		if (match = gt.exec(m))
		{
			m = match[1];
			suffix = match[2];
		}

		return '<a href="' + m + '">' + m + '</a>' + suffix;
	});
};

/**
 * Finds all <SCRIPT TYPE="syntaxhighlighter" /> elementss.
 * @return {Array} Returns array of all found SyntaxHighlighter tags.
 */
function getSyntaxHighlighterScriptTags()
{
	var tags = document.getElementsByTagName('script'),
		result = []
		;

	for (var i = 0, l = tags.length; i < l; i++)
		if (tags[i].type == 'syntaxhighlighter')
			result.push(tags[i]);

	return result;
};

/**
 * Strips <![CDATA[]]> from <SCRIPT /> content because it should be used
 * there in most cases for XHTML compliance.
 * @param {String} original	Input code.
 * @return {String} Returns code without leading <![CDATA[]]> tags.
 */
function stripCData(original)
{
	var left = '<![CDATA[',
		right = ']]>',
		// for some reason IE inserts some leading blanks here
		copy = trim(original),
		changed = false,
		leftLength = left.length,
		rightLength = right.length
		;

	if (copy.indexOf(left) == 0)
	{
		copy = copy.substring(leftLength);
		changed = true;
	}

	var copyLength = copy.length;

	if (copy.indexOf(right) == copyLength - rightLength)
	{
		copy = copy.substring(0, copyLength - rightLength);
		changed = true;
	}

	return changed ? copy : original;
};


/**
 * Quick code mouse double click handler.
 */
function quickCodeHandler(e)
{
	var target = e.target,
		highlighterDiv = findParentElement(target, '.syntaxhighlighter'),
		container = findParentElement(target, '.container'),
		textarea = document.createElement('textarea'),
		highlighter
		;

	if (!container || !highlighterDiv || findElement(container, 'textarea'))
		return;

	highlighter = getHighlighterById(highlighterDiv.id);

	// add source class name
	addClass(highlighterDiv, 'source');

	// Have to go over each line and grab it's text, can't just do it on the
	// container because Firefox loses all \n where as Webkit doesn't.
	var lines = container.childNodes,
		code = []
		;

	for (var i = 0, l = lines.length; i < l; i++)
		code.push(lines[i].innerText || lines[i].textContent);

	// using \r instead of \r or \r\n makes this work equally well on IE, FF and Webkit
	code = code.join('\r');

    // For Webkit browsers, replace nbsp with a breaking space
    code = code.replace(/\u00a0/g, " ");

	// inject <textarea/> tag
	textarea.appendChild(document.createTextNode(code));
	container.appendChild(textarea);

	// preselect all text
	textarea.focus();
	textarea.select();

	// set up handler for lost focus
	attachEvent(textarea, 'blur', function(e)
	{
		textarea.parentNode.removeChild(textarea);
		removeClass(highlighterDiv, 'source');
	});
};

/**
 * Match object.
 */
sh.Match = function(value, index, css)
{
	this.value = value;
	this.index = index;
	this.length = value.length;
	this.css = css;
	this.brushName = null;
};

sh.Match.prototype.toString = function()
{
	return this.value;
};

/**
 * Simulates HTML code with a scripting language embedded.
 *
 * @param {String} scriptBrushName Brush name of the scripting language.
 */
sh.HtmlScript = function(scriptBrushName)
{
	var brushClass = findBrush(scriptBrushName),
		scriptBrush,
		xmlBrush = new sh.brushes.Xml(),
		bracketsRegex = null,
		ref = this,
		methodsToExpose = 'getDiv getHtml init'.split(' ')
		;

	if (brushClass == null)
		return;

	scriptBrush = new brushClass();

	for(var i = 0, l = methodsToExpose.length; i < l; i++)
		// make a closure so we don't lose the name after i changes
		(function() {
			var name = methodsToExpose[i];

			ref[name] = function()
			{
				return xmlBrush[name].apply(xmlBrush, arguments);
			};
		})();

	if (scriptBrush.htmlScript == null)
	{
		alert(sh.config.strings.brushNotHtmlScript + scriptBrushName);
		return;
	}

	xmlBrush.regexList.push(
		{ regex: scriptBrush.htmlScript.code, func: process }
	);

	function offsetMatches(matches, offset)
	{
		for (var j = 0, l = matches.length; j < l; j++)
			matches[j].index += offset;
	}

	function process(match, info)
	{
		var code = match.code,
			matches = [],
			regexList = scriptBrush.regexList,
			offset = match.index + match.left.length,
			htmlScript = scriptBrush.htmlScript,
			result
			;

		// add all matches from the code
		for (var i = 0, l = regexList.length; i < l; i++)
		{
			result = getMatches(code, regexList[i]);
			offsetMatches(result, offset);
			matches = matches.concat(result);
		}

		// add left script bracket
		if (htmlScript.left != null && match.left != null)
		{
			result = getMatches(match.left, htmlScript.left);
			offsetMatches(result, match.index);
			matches = matches.concat(result);
		}

		// add right script bracket
		if (htmlScript.right != null && match.right != null)
		{
			result = getMatches(match.right, htmlScript.right);
			offsetMatches(result, match.index + match[0].lastIndexOf(match.right));
			matches = matches.concat(result);
		}

		for (var j = 0, l = matches.length; j < l; j++)
			matches[j].brushName = brushClass.brushName;

		return matches;
	}
};

/**
 * Main Highlither class.
 * @constructor
 */
sh.Highlighter = function()
{
	// not putting any code in here because of the prototype inheritance
};

sh.Highlighter.prototype = {
	/**
	 * Returns value of the parameter passed to the highlighter.
	 * @param {String} name				Name of the parameter.
	 * @param {Object} defaultValue		Default value.
	 * @return {Object}					Returns found value or default value otherwise.
	 */
	getParam: function(name, defaultValue)
	{
		var result = this.params[name];
		return toBoolean(result == null ? defaultValue : result);
	},

	/**
	 * Shortcut to document.createElement().
	 * @param {String} name		Name of the element to create (DIV, A, etc).
	 * @return {HTMLElement}	Returns new HTML element.
	 */
	create: function(name)
	{
		return document.createElement(name);
	},

	/**
	 * Applies all regular expression to the code and stores all found
	 * matches in the `this.matches` array.
	 * @param {Array} regexList		List of regular expressions.
	 * @param {String} code			Source code.
	 * @return {Array}				Returns list of matches.
	 */
	findMatches: function(regexList, code)
	{
		var result = [];

		if (regexList != null)
			for (var i = 0, l = regexList.length; i < l; i++)
				// BUG: length returns len+1 for array if methods added to prototype chain (oising@gmail.com)
				if (typeof (regexList[i]) == "object")
					result = result.concat(getMatches(code, regexList[i]));

		// sort and remove nested the matches
		return this.removeNestedMatches(result.sort(matchesSortCallback));
	},

	/**
	 * Checks to see if any of the matches are inside of other matches.
	 * This process would get rid of highligted strings inside comments,
	 * keywords inside strings and so on.
	 */
	removeNestedMatches: function(matches)
	{
		// Optimized by Jose Prado (http://joseprado.com)
		for (var i = 0, l = matches.length; i < l; i++)
		{
			if (matches[i] === null)
				continue;

			var itemI = matches[i],
				itemIEndPos = itemI.index + itemI.length
				;

			for (var j = i + 1, l = matches.length; j < l && matches[i] !== null; j++)
			{
				var itemJ = matches[j];

				if (itemJ === null)
					continue;
				else if (itemJ.index > itemIEndPos)
					break;
				else if (itemJ.index == itemI.index && itemJ.length > itemI.length)
					matches[i] = null;
				else if (itemJ.index >= itemI.index && itemJ.index < itemIEndPos)
					matches[j] = null;
			}
		}

		return matches;
	},

	/**
	 * Creates an array containing integer line numbers starting from the 'first-line' param.
	 * @return {Array} Returns array of integers.
	 */
	figureOutLineNumbers: function(code)
	{
		var lines = [],
			firstLine = parseInt(this.getParam('first-line'))
			;

		eachLine(code, function(line, index)
		{
			lines.push(index + firstLine);
		});

		return lines;
	},

	/**
	 * Determines if specified line number is in the highlighted list.
	 */
	isLineHighlighted: function(lineNumber)
	{
		var list = this.getParam('highlight', []);

		if (typeof(list) != 'object' && list.push == null)
			list = [ list ];

		return indexOf(list, lineNumber.toString()) != -1;
	},

	/**
	 * Generates HTML markup for a single line of code while determining alternating line style.
	 * @param {Integer} lineNumber	Line number.
	 * @param {String} code Line	HTML markup.
	 * @return {String}				Returns HTML markup.
	 */
	getLineHtml: function(lineIndex, lineNumber, code)
	{
		var classes = [
			'line',
			'number' + lineNumber,
			'index' + lineIndex,
			'alt' + (lineNumber % 2 == 0 ? 1 : 2).toString()
		];

		if (this.isLineHighlighted(lineNumber))
		 	classes.push('highlighted');

		if (lineNumber == 0)
			classes.push('break');

		return '<div class="' + classes.join(' ') + '">' + code + '</div>';
	},

	/**
	 * Generates HTML markup for line number column.
	 * @param {String} code			Complete code HTML markup.
	 * @param {Array} lineNumbers	Calculated line numbers.
	 * @return {String}				Returns HTML markup.
	 */
	getLineNumbersHtml: function(code, lineNumbers)
	{
		var html = '',
			count = splitLines(code).length,
			firstLine = parseInt(this.getParam('first-line')),
			pad = this.getParam('pad-line-numbers')
			;

		if (pad == true)
			pad = (firstLine + count - 1).toString().length;
		else if (isNaN(pad) == true)
			pad = 0;

		for (var i = 0; i < count; i++)
		{
			var lineNumber = lineNumbers ? lineNumbers[i] : firstLine + i,
				code = lineNumber == 0 ? sh.config.space : padNumber(lineNumber, pad)
				;

			html += this.getLineHtml(i, lineNumber, code);
		}

		return html;
	},

	/**
	 * Splits block of text into individual DIV lines.
	 * @param {String} code			Code to highlight.
	 * @param {Array} lineNumbers	Calculated line numbers.
	 * @return {String}				Returns highlighted code in HTML form.
	 */
	getCodeLinesHtml: function(html, lineNumbers)
	{
		html = trim(html);

		var lines = splitLines(html),
			padLength = this.getParam('pad-line-numbers'),
			firstLine = parseInt(this.getParam('first-line')),
			html = '',
			brushName = this.getParam('brush')
			;

		for (var i = 0, l = lines.length; i < l; i++)
		{
			var line = lines[i],
				indent = /^(&nbsp;|\s)+/.exec(line),
				spaces = null,
				lineNumber = lineNumbers ? lineNumbers[i] : firstLine + i;
				;

			if (indent != null)
			{
				spaces = indent[0].toString();
				line = line.substr(spaces.length);
				spaces = spaces.replace(' ', sh.config.space);
			}

			line = trim(line);

			if (line.length == 0)
				line = sh.config.space;

			html += this.getLineHtml(
				i,
				lineNumber,
				(spaces != null ? '<code class="' + brushName + ' spaces">' + spaces + '</code>' : '') + line
			);
		}

		return html;
	},

	/**
	 * Returns HTML for the table title or empty string if title is null.
	 */
	getTitleHtml: function(title)
	{
		return title ? '<caption>' + escapeHtml(title) + '</caption>' : '';
	},

	/**
	 * Finds all matches in the source code.
	 * @param {String} code		Source code to process matches in.
	 * @param {Array} matches	Discovered regex matches.
	 * @return {String} Returns formatted HTML with processed mathes.
	 */
	getMatchesHtml: function(code, matches)
	{
		var pos = 0,
			result = '',
			brushName = this.getParam('brush', '')
			;

		function getBrushNameCss(match)
		{
			var result = match ? (match.brushName || brushName) : brushName;
			return result ? result + ' ' : '';
		};

		// Finally, go through the final list of matches and pull the all
		// together adding everything in between that isn't a match.
		for (var i = 0, l = matches.length; i < l; i++)
		{
			var match = matches[i],
				matchBrushName
				;

			if (match === null || match.length === 0)
				continue;

			matchBrushName = getBrushNameCss(match);

			result += wrapLinesWithCode(code.substr(pos, match.index - pos), matchBrushName + 'plain')
					+ wrapLinesWithCode(match.value, matchBrushName + match.css)
					;

			pos = match.index + match.length + (match.offset || 0);
		}

		// don't forget to add whatever's remaining in the string
		result += wrapLinesWithCode(code.substr(pos), getBrushNameCss() + 'plain');

		return result;
	},

	/**
	 * Generates HTML markup for the whole syntax highlighter.
	 * @param {String} code Source code.
	 * @return {String} Returns HTML markup.
	 */
	getHtml: function(code)
	{
		var html = '',
			classes = [ 'syntaxhighlighter' ],
			tabSize,
			matches,
			lineNumbers
			;

		// process light mode
		if (this.getParam('light') == true)
			this.params.toolbar = this.params.gutter = false;

		className = 'syntaxhighlighter';

		if (this.getParam('collapse') == true)
			classes.push('collapsed');

		if ((gutter = this.getParam('gutter')) == false)
			classes.push('nogutter');

		// add custom user style name
		classes.push(this.getParam('class-name'));

		// add brush alias to the class name for custom CSS
		classes.push(this.getParam('brush'));

		code = trimFirstAndLastLines(code)
			.replace(/\r/g, ' ') // IE lets these buggers through
			;

		tabSize = this.getParam('tab-size');

		// replace tabs with spaces
		code = this.getParam('smart-tabs') == true
			? processSmartTabs(code, tabSize)
			: processTabs(code, tabSize)
			;

		// unindent code by the common indentation
		if (this.getParam('unindent'))
			code = unindent(code);

		if (gutter)
			lineNumbers = this.figureOutLineNumbers(code);

		// find matches in the code using brushes regex list
		matches = this.findMatches(this.regexList, code);
		// processes found matches into the html
		html = this.getMatchesHtml(code, matches);
		// finally, split all lines so that they wrap well
		html = this.getCodeLinesHtml(html, lineNumbers);

		// finally, process the links
		if (this.getParam('auto-links'))
			html = processUrls(html);

		if (typeof(navigator) != 'undefined' && navigator.userAgent && navigator.userAgent.match(/MSIE/))
			classes.push('ie');

		html =
			'<div id="' + getHighlighterId(this.id) + '" class="' + escapeHtml(classes.join(' ')) + '">'
				+ (this.getParam('toolbar') ? sh.toolbar.getHtml(this) : '')
				+ '<table border="0" cellpadding="0" cellspacing="0">'
					+ this.getTitleHtml(this.getParam('title'))
					+ '<tbody>'
						+ '<tr>'
							+ (gutter ? '<td class="gutter">' + this.getLineNumbersHtml(code) + '</td>' : '')
							+ '<td class="code">'
								+ '<div class="container">'
									+ html
								+ '</div>'
							+ '</td>'
						+ '</tr>'
					+ '</tbody>'
				+ '</table>'
			+ '</div>'
			;

		return html;
	},

	/**
	 * Highlights the code and returns complete HTML.
	 * @param {String} code     Code to highlight.
	 * @return {Element}        Returns container DIV element with all markup.
	 */
	getDiv: function(code)
	{
		if (code === null)
			code = '';

		this.code = code;

		var div = this.create('div');

		// create main HTML
		div.innerHTML = this.getHtml(code);

		// set up click handlers
		if (this.getParam('toolbar'))
			attachEvent(findElement(div, '.toolbar'), 'click', sh.toolbar.handler);

		if (this.getParam('quick-code'))
			attachEvent(findElement(div, '.code'), 'dblclick', quickCodeHandler);

		return div;
	},

	/**
	 * Initializes the highlighter/brush.
	 *
	 * Constructor isn't used for initialization so that nothing executes during necessary
	 * `new SyntaxHighlighter.Highlighter()` call when setting up brush inheritence.
	 *
	 * @param {Hash} params Highlighter parameters.
	 */
	init: function(params)
	{
		this.id = guid();

		// register this instance in the highlighters list
		storeHighlighter(this);

		// local params take precedence over defaults
		this.params = merge(sh.defaults, params || {})

		// process light mode
		if (this.getParam('light') == true)
			this.params.toolbar = this.params.gutter = false;
	},

	/**
	 * Converts space separated list of keywords into a regular expression string.
	 * @param {String} str    Space separated keywords.
	 * @return {String}       Returns regular expression string.
	 */
	getKeywords: function(str)
	{
		str = str
			.replace(/^\s+|\s+$/g, '')
			.replace(/\s+/g, '|')
			;

		return '\\b(?:' + str + ')\\b';
	},

	/**
	 * Makes a brush compatible with the `html-script` functionality.
	 * @param {Object} regexGroup Object containing `left` and `right` regular expressions.
	 */
	forHtmlScript: function(regexGroup)
	{
		var regex = { 'end' : regexGroup.right.source };

		if(regexGroup.eof)
			regex.end = "(?:(?:" + regex.end + ")|$)";

		this.htmlScript = {
			left : { regex: regexGroup.left, css: 'script' },
			right : { regex: regexGroup.right, css: 'script' },
			code : XRegExp(
				"(?<left>" + regexGroup.left.source + ")" +
				"(?<code>.*?)" +
				"(?<right>" + regex.end + ")",
				"sgi"
				)
		};
	}
}; // end of Highlighter

return sh;
}(); // end of anonymous function

// CommonJS
typeof(exports) != 'undefined' ? exports.SyntaxHighlighter = SyntaxHighlighter : null;
var XRegExp;if(XRegExp=XRegExp||function(t){"use strict";function e(t,e,n){var r;for(r in c.prototype)c.prototype.hasOwnProperty(r)&&(t[r]=c.prototype[r]);return t.xregexp={captureNames:e,isNative:!!n},t}function n(t){return(t.global?"g":"")+(t.ignoreCase?"i":"")+(t.multiline?"m":"")+(t.extended?"x":"")+(t.sticky?"y":"")}function r(t,r,i){if(!c.isRegExp(t))throw new TypeError("type RegExp expected");var a=p.replace.call(n(t)+(r||""),y,"");return i&&(a=p.replace.call(a,new RegExp("["+i+"]+","g"),"")),t=t.xregexp&&!t.xregexp.isNative?e(c(t.source,a),t.xregexp.captureNames?t.xregexp.captureNames.slice(0):null):e(new RegExp(t.source,a),null,!0)}function i(t,e){var n=t.length;if(Array.prototype.lastIndexOf)return t.lastIndexOf(e);for(;n--;)if(t[n]===e)return n;return-1}function a(t,e){return Object.prototype.toString.call(t).toLowerCase()==="[object "+e+"]"}function l(t){return t=t||{},"all"===t||t.all?t={natives:!0,extensibility:!0}:a(t,"string")&&(t=c.forEach(t,/[^\s,]+/,function(t){this[t]=!0},{})),t}function s(t,e,n,r){var i,a,l=m.length,s=null;N=!0;try{for(;l--;)if(("all"===(a=m[l]).scope||a.scope===n)&&(!a.trigger||a.trigger.call(r))&&(a.pattern.lastIndex=e,(i=d.exec.call(a.pattern,t))&&i.index===e)){s={output:a.handler.call(r,i,n),match:i};break}}catch(t){throw t}finally{N=!1}return s}function o(t){c.addToken=g[t?"on":"off"],f.extensibility=t}function u(t){RegExp.prototype.exec=(t?d:p).exec,RegExp.prototype.test=(t?d:p).test,String.prototype.match=(t?d:p).match,String.prototype.replace=(t?d:p).replace,String.prototype.split=(t?d:p).split,f.natives=t}var c,g,h,f={natives:!1,extensibility:!1},p={exec:RegExp.prototype.exec,test:RegExp.prototype.test,match:String.prototype.match,replace:String.prototype.replace,split:String.prototype.split},d={},x={},m=[],v={default:/^(?:\\(?:0(?:[0-3][0-7]{0,2}|[4-7][0-7]?)?|[1-9]\d*|x[\dA-Fa-f]{2}|u[\dA-Fa-f]{4}|c[A-Za-z]|[\s\S])|\(\?[:=!]|[?*+]\?|{\d+(?:,\d*)?}\??)/,class:/^(?:\\(?:[0-3][0-7]{0,2}|[4-7][0-7]?|x[\dA-Fa-f]{2}|u[\dA-Fa-f]{4}|c[A-Za-z]|[\s\S]))/},b=/\$(?:{([\w$]+)}|(\d\d?|[\s\S]))/g,y=/([\s\S])(?=[\s\S]*\1)/g,w=/^(?:[?*+]|{\d+(?:,\d*)?})\??/,S=void 0===p.exec.call(/()??/,"")[1],E=void 0!==RegExp.prototype.sticky,N=!1,H="gim"+(E?"y":"");return c=function(t,n){if(c.isRegExp(t)){if(void 0!==n)throw new TypeError("can't supply flags when constructing one RegExp from another");return r(t)}if(N)throw new Error("can't call the XRegExp constructor within token definition functions");var i,a,l,o=[],u="default",g={hasNamedCapture:!1,captureNames:[],hasFlag:function(t){return n.indexOf(t)>-1}},h=0;if(t=void 0===t?"":String(t),n=void 0===n?"":String(n),p.match.call(n,y))throw new SyntaxError("invalid duplicate regular expression flag");for(t=p.replace.call(t,/^\(\?([\w$]+)\)/,function(t,e){if(p.test.call(/[gy]/,e))throw new SyntaxError("can't use flag g or y in mode modifier");return n=p.replace.call(n+e,y,""),""}),c.forEach(n,/[\s\S]/,function(t){if(H.indexOf(t[0])<0)throw new SyntaxError("invalid regular expression flag "+t[0])});h<t.length;)(i=s(t,h,u,g))?(o.push(i.output),h+=i.match[0].length||1):(a=p.exec.call(v[u],t.slice(h)))?(o.push(a[0]),h+=a[0].length):("["===(l=t.charAt(h))?u="class":"]"===l&&(u="default"),o.push(l),++h);return e(new RegExp(o.join(""),p.replace.call(n,/[^gimy]+/g,"")),g.hasNamedCapture?g.captureNames:null)},g={on:function(t,e,n){n=n||{},t&&m.push({pattern:r(t,"g"+(E?"y":"")),handler:e,scope:n.scope||"default",trigger:n.trigger||null}),n.customFlags&&(H=p.replace.call(H+n.customFlags,y,""))},off:function(){throw new Error("extensibility must be installed before using addToken")}},c.addToken=g.off,c.cache=function(t,e){var n=t+"/"+(e||"");return x[n]||(x[n]=c(t,e))},c.escape=function(t){return p.replace.call(t,/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&")},c.exec=function(t,e,n,i){var a,l=r(e,"g"+(i&&E?"y":""),!1===i?"y":"");return l.lastIndex=n=n||0,a=d.exec.call(l,t),i&&a&&a.index!==n&&(a=null),e.global&&(e.lastIndex=a?l.lastIndex:0),a},c.forEach=function(t,e,n,r){for(var i,a=0,l=-1;i=c.exec(t,e,a);)n.call(r,i,++l,t,e),a=i.index+(i[0].length||1);return r},c.globalize=function(t){return r(t,"g")},c.install=function(t){t=l(t),!f.natives&&t.natives&&u(!0),!f.extensibility&&t.extensibility&&o(!0)},c.isInstalled=function(t){return!!f[t]},c.isRegExp=function(t){return a(t,"regexp")},c.matchChain=function(t,e){return function t(n,r){var i,a=e[r].regex?e[r]:{regex:e[r]},l=[];for(i=0;i<n.length;++i)c.forEach(n[i],a.regex,function(t){l.push(a.backref?t[a.backref]||"":t[0])});return r!==e.length-1&&l.length?t(l,r+1):l}([t],0)},c.replace=function(t,e,n,i){var a,l=c.isRegExp(e),s=e;return l?(void 0===i&&e.global&&(i="all"),s=r(e,"all"===i?"g":"","all"===i?"":"g")):"all"===i&&(s=new RegExp(c.escape(String(e)),"g")),a=d.replace.call(String(t),s,n),l&&e.global&&(e.lastIndex=0),a},c.split=function(t,e,n){return d.split.call(t,e,n)},c.test=function(t,e,n,r){return!!c.exec(t,e,n,r)},c.uninstall=function(t){t=l(t),f.natives&&t.natives&&u(!1),f.extensibility&&t.extensibility&&o(!1)},c.union=function(t,e){var n,r,i,l,s=/(\()(?!\?)|\\([1-9]\d*)|\\[\s\S]|\[(?:[^\\\]]|\\[\s\S])*]/g,o=0,u=[];if(!a(t,"array")||!t.length)throw new TypeError("patterns must be a nonempty array");for(l=0;l<t.length;++l)i=t[l],c.isRegExp(i)?(n=o,r=i.xregexp&&i.xregexp.captureNames||[],u.push(c(i.source).source.replace(s,function(t,e,i){var a=r[o-n];if(e){if(++o,a)return"(?<"+a+">"}else if(i)return"\\"+(+i+n);return t}))):u.push(c.escape(i));return c(u.join("|"),e)},c.version="2.0.0",d.exec=function(t){var e,r,a,l,s;if(this.global||(l=this.lastIndex),e=p.exec.apply(this,arguments)){if(!S&&e.length>1&&i(e,"")>-1&&(a=new RegExp(this.source,p.replace.call(n(this),"g","")),p.replace.call(String(t).slice(e.index),a,function(){var t;for(t=1;t<arguments.length-2;++t)void 0===arguments[t]&&(e[t]=void 0)})),this.xregexp&&this.xregexp.captureNames)for(s=1;s<e.length;++s)(r=this.xregexp.captureNames[s-1])&&(e[r]=e[s]);this.global&&!e[0].length&&this.lastIndex>e.index&&(this.lastIndex=e.index)}return this.global||(this.lastIndex=l),e},d.test=function(t){return!!d.exec.call(this,t)},d.match=function(t){if(c.isRegExp(t)){if(t.global){var e=p.match.apply(this,arguments);return t.lastIndex=0,e}}else t=new RegExp(t);return d.exec.call(t,this)},d.replace=function(t,e){var n,r,l,s,o=c.isRegExp(t);return o?(t.xregexp&&(n=t.xregexp.captureNames),t.global||(s=t.lastIndex)):t+="",a(e,"function")?r=p.replace.call(String(this),t,function(){var r,i=arguments;if(n)for(i[0]=new String(i[0]),r=0;r<n.length;++r)n[r]&&(i[0][n[r]]=i[r+1]);return o&&t.global&&(t.lastIndex=i[i.length-2]+i[0].length),e.apply(null,i)}):(l=String(this),r=p.replace.call(l,t,function(){var t=arguments;return p.replace.call(String(e),b,function(e,r,a){var l;if(r){if((l=+r)<=t.length-3)return t[l]||"";if((l=n?i(n,r):-1)<0)throw new SyntaxError("backreference to undefined group "+e);return t[l+1]||""}if("$"===a)return"$";if("&"===a||0==+a)return t[0];if("`"===a)return t[t.length-1].slice(0,t[t.length-2]);if("'"===a)return t[t.length-1].slice(t[t.length-2]+t[0].length);if(a=+a,!isNaN(a)){if(a>t.length-3)throw new SyntaxError("backreference to undefined group "+e);return t[a]||""}throw new SyntaxError("invalid token "+e)})})),o&&(t.global?t.lastIndex=0:t.lastIndex=s),r},d.split=function(t,e){if(!c.isRegExp(t))return p.split.apply(this,arguments);var n,r=String(this),i=t.lastIndex,a=[],l=0;return e=(void 0===e?-1:e)>>>0,c.forEach(r,t,function(t){t.index+t[0].length>l&&(a.push(r.slice(l,t.index)),t.length>1&&t.index<r.length&&Array.prototype.push.apply(a,t.slice(1)),n=t[0].length,l=t.index+n)}),l===r.length?p.test.call(t,"")&&!n||a.push(""):a.push(r.slice(l)),t.lastIndex=i,a.length>e?a.slice(0,e):a},(h=g.on)(/\\([ABCE-RTUVXYZaeg-mopqyz]|c(?![A-Za-z])|u(?![\dA-Fa-f]{4})|x(?![\dA-Fa-f]{2}))/,function(t,e){if("B"===t[1]&&"default"===e)return t[0];throw new SyntaxError("invalid escape "+t[0])},{scope:"all"}),h(/\[(\^?)]/,function(t){return t[1]?"[\\s\\S]":"\\b\\B"}),h(/(?:\(\?#[^)]*\))+/,function(t){return p.test.call(w,t.input.slice(t.index+t[0].length))?"":"(?:)"}),h(/\\k<([\w$]+)>/,function(t){var e=isNaN(t[1])?i(this.captureNames,t[1])+1:+t[1],n=t.index+t[0].length;if(!e||e>this.captureNames.length)throw new SyntaxError("backreference to undefined group "+t[0]);return"\\"+e+(n===t.input.length||isNaN(t.input.charAt(n))?"":"(?:)")}),h(/(?:\s+|#.*)+/,function(t){return p.test.call(w,t.input.slice(t.index+t[0].length))?"":"(?:)"},{trigger:function(){return this.hasFlag("x")},customFlags:"x"}),h(/\./,function(){return"[\\s\\S]"},{trigger:function(){return this.hasFlag("s")},customFlags:"s"}),h(/\(\?P?<([\w$]+)>/,function(t){if(!isNaN(t[1]))throw new SyntaxError("can't use integer as capture name "+t[0]);return this.captureNames.push(t[1]),this.hasNamedCapture=!0,"("}),h(/\\(\d+)/,function(t,e){if(!("default"===e&&/^[1-9]/.test(t[1])&&+t[1]<=this.captureNames.length)&&"0"!==t[1])throw new SyntaxError("can't use octal escape or backreference to undefined group "+t[0]);return t[0]},{scope:"all"}),h(/\((?!\?)/,function(){return this.hasFlag("n")?"(?:":(this.captureNames.push(null),"(")},{customFlags:"n"}),"undefined"!=typeof exports&&(exports.XRegExp=c),c}(),void 0===SyntaxHighlighter)var SyntaxHighlighter=function(){function t(t){return document.createElement("div").appendChild(document.createTextNode(t)).parentNode.innerHTML.replace(/"/g,"&quot;")}function e(t,e){return-1!=t.className.indexOf(e)}function n(t,n){e(t,n)||(t.className+=" "+n)}function r(t,e){t.className=t.className.replace(e,"")}function i(t){for(var e=[],n=0,r=t.length;n<r;n++)e.push(t[n]);return e}function a(t){return t.split(/\r?\n/)}function l(t){return 0==t.indexOf("highlighter_")?t:"highlighter_"+t}function s(t){return O.vars.highlighters[l(t)]}function o(t){return document.getElementById(l(t))}function u(t){O.vars.highlighters[l(t.id)]=t}function c(t,e,n){if(null==t)return null;var r,i,a=1!=n?t.childNodes:[t.parentNode],l={"#":"id",".":"className"}[e.substr(0,1)]||"nodeName";if(r="nodeName"!=l?e.substr(1):e.toUpperCase(),-1!=(t[l]||"").indexOf(r))return t;for(var s=0,o=a.length;a&&s<o&&null==i;s++)i=c(a[s],e,n);return i}function g(t,e){return c(t,e,!0)}function h(t,e,n){for(var r=n=Math.max(n||0,0),i=t.length;r<i;r++)if(t[r]==e)return r;return-1}function f(t){return(t||"")+Math.round(1e6*Math.random()).toString()}function p(t,e){var n,r={};for(n in t)r[n]=t[n];for(n in e)r[n]=e[n];return r}function d(t){var e={true:!0,false:!1}[t];return null==e?t:e}function x(t,e,n,r,i){i=(i+=", left="+(screen.width-n)/2+", top="+(screen.height-r)/2+", width="+n+", height="+r).replace(/^,/,"");var a=window.open(t,e,i);return a.focus(),a}function m(t,e,n,r){function i(t){(t=t||window.event).target||(t.target=t.srcElement,t.preventDefault=function(){this.returnValue=!1}),n.call(r||window,t)}t.attachEvent?t.attachEvent("on"+e,i):t.addEventListener(e,i,!1)}function v(t){window.alert(O.config.strings.alert+t)}function b(t,e){var n=O.brushes[t];if(void 0!==n)return n;for(n in O.brushes){var r=O.brushes[n],i=r.aliases;if(null!=i){r.brushName=n.toLowerCase();for(var a=0,l=i.length;a<l;a++)O.brushes[i[a]]=r}}return void 0===(n=O.brushes[t])&&(O.brushes[t]=null,e&&v(O.config.strings.noBrush+t)),n}function y(t,e){for(var n=a(t),r=0,i=n.length;r<i;r++)n[r]=e(n[r],r);return n.join("\r\n")}function w(t){return t.replace(/^[ ]*[\n]+|[\n]*[ ]*$/g,"")}function S(t){for(var e,n={},r=XRegExp("^\\[(?<values>(.*?))\\]$"),i=0,a=XRegExp("(?<name>[\\w-]+)\\s*:\\s*(?<value>[\\w%#-]+|\\[.*?\\]|\".*?\"|'.*?')\\s*;?","g");null!=(e=XRegExp.exec(t,a,i));){var l=e.value.replace(/^['"]|['"]$/g,"");if(null!=l&&r.test(l)){var s=XRegExp.exec(l,r);l=s.values.length>0?s.values.split(/\s*,\s*/):[]}n[e.name]=l,i=e.index+e[0].length}return n}function E(t,e){return null==t||0==t.length||"\n"==t?t:(t=t.replace(/</g,"&lt;"),t=t.replace(/ {2,}/g,function(t){for(var e="",n=0,r=t.length;n<r-1;n++)e+=O.config.space;return e+" "}),null!=e&&(t=y(t,function(t){if(0==t.length)return"";var n="";return t=t.replace(/^(&nbsp;| )+/,function(t){return n=t,""}),0==t.length?n:n+'<code class="'+e+'">'+t+"</code>"})),t)}function N(t,e){for(var n=t.toString();n.length<e;)n="0"+n;return n}function H(t,e){for(var n="",r=0;r<e;r++)n+=" ";return t.replace(/\t/g,n)}function R(t,e){function n(t,e,n){return t.substr(0,e)+r.substr(0,n)+t.substr(e+1,t.length)}a(t);for(var r="",i=0;i<50;i++)r+="                    ";return t=y(t,function(t){if(-1==t.indexOf("\t"))return t;for(var r=0;-1!=(r=t.indexOf("\t"));)t=n(t,r,e-r%e);return t})}function T(t){var e=/<br\s*\/?>|&lt;br\s*\/?&gt;/gi;return 1==O.config.bloggerMode&&(t=t.replace(e,"\n")),1==O.config.stripBrs&&(t=t.replace(e,"")),t}function C(t){return t.replace(/^\s+|\s+$/g,"")}function P(t){for(var e=a(T(t)),n=(new Array,/^\s*/),r=1e3,i=0,l=e.length;i<l&&r>0;i++){var s=e[i];if(0!=C(s).length){var o=n.exec(s);if(null==o)return t;r=Math.min(o[0].length,r)}}if(r>0)for(var i=0,l=e.length;i<l;i++)e[i]=e[i].substr(r);return e.join("\n")}function k(t,e){return t.index<e.index?-1:t.index>e.index?1:t.length<e.length?-1:t.length>e.length?1:0}function L(t,e){var n=null,r=[],i=e.func?e.func:function(t,e){return t[0]};for(pos=0;null!=(n=XRegExp.exec(t,e.regex,pos));){var a=i(n,e);"string"==typeof a&&(a=[new O.Match(a,n.index,e.css)]),r=r.concat(a),pos=n.index+n[0].length}return r}function I(t){var e=/(.*)((&gt;|&lt;).*)/;return t.replace(O.regexLib.url,function(t){var n="",r=null;return(r=e.exec(t))&&(t=r[1],n=r[2]),'<a href="'+t+'">'+t+"</a>"+n})}function A(){for(var t=document.getElementsByTagName("script"),e=[],n=0,r=t.length;n<r;n++)"syntaxhighlighter"==t[n].type&&e.push(t[n]);return e}function M(t){var e=C(t),n=!1,r="<![CDATA[".length,i="]]>".length;0==e.indexOf("<![CDATA[")&&(e=e.substring(r),n=!0);var a=e.length;return e.indexOf("]]>")==a-i&&(e=e.substring(0,a-i),n=!0),n?e:t}function X(t){var e=t.target,i=g(e,".syntaxhighlighter"),a=g(e,".container"),l=document.createElement("textarea");if(a&&i&&!c(a,"textarea")){s(i.id),n(i,"source");for(var o=a.childNodes,u=[],h=0,f=o.length;h<f;h++)u.push(o[h].innerText||o[h].textContent);u=(u=u.join("\r")).replace(/\u00a0/g," "),l.appendChild(document.createTextNode(u)),a.appendChild(l),l.focus(),l.select(),m(l,"blur",function(t){l.parentNode.removeChild(l),r(i,"source")})}}"undefined"!=typeof require&&void 0===XRegExp&&(XRegExp=require("xregexp").XRegExp);var O={defaults:{"class-name":"","first-line":1,"pad-line-numbers":!1,highlight:null,title:null,"smart-tabs":!0,"tab-size":4,gutter:!0,toolbar:!0,"quick-code":!0,collapse:!1,"auto-links":!0,light:!1,unindent:!0,"html-script":!1},config:{space:"&nbsp;",useScriptTags:!0,bloggerMode:!1,stripBrs:!1,tagName:"pre",strings:{expandSource:"expand source",help:"?",alert:"SyntaxHighlighter\n\n",noBrush:"Can't find brush for: ",brushNotHtmlScript:"Brush wasn't configured for html-script option: ",aboutDialog:'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>About SyntaxHighlighter</title></head><body style="font-family:Geneva,Arial,Helvetica,sans-serif;background-color:#fff;color:#000;font-size:1em;text-align:center;"><div style="text-align:center;margin-top:1.5em;"><div style="font-size:xx-large;">SyntaxHighlighter</div><div style="font-size:.75em;margin-bottom:3em;"><div>version 3.0.9 (Thu, 04 Dec 2014 12:32:21 GMT)</div><div><a href="http://alexgorbatchev.com/SyntaxHighlighter" target="_blank" style="color:#005896">http://alexgorbatchev.com/SyntaxHighlighter</a></div><div>JavaScript code syntax highlighter.</div><div>Copyright 2004-2013 Alex Gorbatchev.</div></div><div>If you like this script, please <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2930402" style="color:#005896">donate</a> to <br/>keep development active!</div></div></body></html>'}},vars:{highlighters:{}},brushes:{},regexLib:{multiLineCComments:XRegExp("/\\*.*?\\*/","gs"),singleLineCComments:/\/\/.*$/gm,singleLinePerlComments:/#.*$/gm,doubleQuotedString:/"([^\\"\n]|\\.)*"/g,singleQuotedString:/'([^\\'\n]|\\.)*'/g,multiLineDoubleQuotedString:XRegExp('"([^\\\\"]|\\\\.)*"',"gs"),multiLineSingleQuotedString:XRegExp("'([^\\\\']|\\\\.)*'","gs"),xmlComments:XRegExp("(&lt;|<)!--.*?--(&gt;|>)","gs"),url:/\w+:\/\/[\w-.\/?%&=:@;#]*/g,phpScriptTags:{left:/(&lt;|<)\?(?:=|php)?/g,right:/\?(&gt;|>)/g,eof:!0},aspScriptTags:{left:/(&lt;|<)%=?/g,right:/%(&gt;|>)/g},scriptScriptTags:{left:/(&lt;|<)\s*script.*?(&gt;|>)/gi,right:/(&lt;|<)\/\s*script\s*(&gt;|>)/gi}},toolbar:{getHtml:function(t){for(var e='<div class="toolbar">',n=O.toolbar.items,r=n.list,i=0,a=r.length;i<a;i++)e+=(n[r[i]].getHtml||function(t,e){return O.toolbar.getButtonHtml(t,e,O.config.strings[e])})(t,r[i]);return e+="</div>"},getButtonHtml:function(e,n,r){return'<span><a href="#" class="toolbar_item command_'+(n=t(n))+" "+n+'">'+t(r)+"</a></span>"},handler:function(t){var e=t.target,n=e.className||"",r=s(g(e,".syntaxhighlighter").id),i=function(t){var e=new RegExp(t+"_(\\w+)").exec(n);return e?e[1]:null}("command");r&&i&&O.toolbar.items[i].execute(r),t.preventDefault()},items:{list:["expandSource","help"],expandSource:{getHtml:function(t){if(1!=t.getParam("collapse"))return"";var e=t.getParam("title");return O.toolbar.getButtonHtml(t,"expandSource",e||O.config.strings.expandSource)},execute:function(t){r(o(t.id),"collapsed")}},help:{execute:function(t){var e=x("","_blank",500,250,"scrollbars=0"),n=e.document;n.write(O.config.strings.aboutDialog),n.close(),e.focus()}}}},findElements:function(t,e){var n=e?[e]:i(document.getElementsByTagName(O.config.tagName)),r=[];if(O.config.useScriptTags&&(n=n.concat(A())),0===n.length)return r;for(var a=0,l=n.length;a<l;a++){var s={target:n[a],params:p(t,S(n[a].className))};null!=s.params.brush&&r.push(s)}return r},highlight:function(t,e){var n=this.findElements(t,e),r=null,i=O.config;if(0!==n.length)for(var a=0,l=n.length;a<l;a++){var s,o=(e=n[a]).target,u=e.params,c=u.brush;if(null!=c){if("true"==u["html-script"]||1==O.defaults["html-script"])r=new O.HtmlScript(c),c="htmlscript";else{var g=b(c);if(!g)continue;r=new g}s=o.innerHTML,i.useScriptTags&&(s=M(s)),""!=(o.title||"")&&(u.title=o.title),u.brush=c,r.init(u),e=r.getDiv(s),""!=(o.id||"")&&(e.id=o.id),o.parentNode.replaceChild(e,o)}}},all:function(t){m(window,"load",function(){O.highlight(t)})}};return O.Match=function(t,e,n){this.value=t,this.index=e,this.length=t.length,this.css=n,this.brushName=null},O.Match.prototype.toString=function(){return this.value},O.HtmlScript=function(t){function e(t,e){for(var n=0,r=t.length;n<r;n++)t[n].index+=e}var n,r=b(t),i=new O.brushes.Xml,a=this,l="getDiv getHtml init".split(" ");if(null!=r){n=new r;for(var s=0,o=l.length;s<o;s++)!function(){var t=l[s];a[t]=function(){return i[t].apply(i,arguments)}}();null!=n.htmlScript?i.regexList.push({regex:n.htmlScript.code,func:function(t,i){for(var a,l=t.code,s=[],o=n.regexList,u=t.index+t.left.length,c=n.htmlScript,g=0,h=o.length;g<h;g++)e(a=L(l,o[g]),u),s=s.concat(a);null!=c.left&&null!=t.left&&(e(a=L(t.left,c.left),t.index),s=s.concat(a)),null!=c.right&&null!=t.right&&(e(a=L(t.right,c.right),t.index+t[0].lastIndexOf(t.right)),s=s.concat(a));for(var f=0,h=s.length;f<h;f++)s[f].brushName=r.brushName;return s}}):v(O.config.strings.brushNotHtmlScript+t)}},O.Highlighter=function(){},O.Highlighter.prototype={getParam:function(t,e){var n=this.params[t];return d(null==n?e:n)},create:function(t){return document.createElement(t)},findMatches:function(t,e){var n=[];if(null!=t)for(var r=0,i=t.length;r<i;r++)"object"==typeof t[r]&&(n=n.concat(L(e,t[r])));return this.removeNestedMatches(n.sort(k))},removeNestedMatches:function(t){for(var e=0,n=t.length;e<n;e++)if(null!==t[e])for(var r=t[e],i=r.index+r.length,a=e+1,n=t.length;a<n&&null!==t[e];a++){var l=t[a];if(null!==l){if(l.index>i)break;l.index==r.index&&l.length>r.length?t[e]=null:l.index>=r.index&&l.index<i&&(t[a]=null)}}return t},figureOutLineNumbers:function(t){var e=[],n=parseInt(this.getParam("first-line"));return y(t,function(t,r){e.push(r+n)}),e},isLineHighlighted:function(t){var e=this.getParam("highlight",[]);return"object"!=typeof e&&null==e.push&&(e=[e]),-1!=h(e,t.toString())},getLineHtml:function(t,e,n){var r=["line","number"+e,"index"+t,"alt"+(e%2==0?1:2).toString()];return this.isLineHighlighted(e)&&r.push("highlighted"),0==e&&r.push("break"),'<div class="'+r.join(" ")+'">'+n+"</div>"},getLineNumbersHtml:function(t,e){var n="",r=a(t).length,i=parseInt(this.getParam("first-line")),l=this.getParam("pad-line-numbers");1==l?l=(i+r-1).toString().length:1==isNaN(l)&&(l=0);for(var s=0;s<r;s++){var o=e?e[s]:i+s,t=0==o?O.config.space:N(o,l);n+=this.getLineHtml(s,o,t)}return n},getCodeLinesHtml:function(t,e){for(var n=a(t=C(t)),r=(this.getParam("pad-line-numbers"),parseInt(this.getParam("first-line"))),t="",i=this.getParam("brush"),l=0,s=n.length;l<s;l++){var o=n[l],u=/^(&nbsp;|\s)+/.exec(o),c=null,g=e?e[l]:r+l;null!=u&&(c=u[0].toString(),o=o.substr(c.length),c=c.replace(" ",O.config.space)),0==(o=C(o)).length&&(o=O.config.space),t+=this.getLineHtml(l,g,(null!=c?'<code class="'+i+' spaces">'+c+"</code>":"")+o)}return t},getTitleHtml:function(e){return e?"<caption>"+t(e)+"</caption>":""},getMatchesHtml:function(t,e){function n(t){var e=t?t.brushName||a:a;return e?e+" ":""}for(var r=0,i="",a=this.getParam("brush",""),l=0,s=e.length;l<s;l++){var o,u=e[l];null!==u&&0!==u.length&&(o=n(u),i+=E(t.substr(r,u.index-r),o+"plain")+E(u.value,o+u.css),r=u.index+u.length+(u.offset||0))}return i+=E(t.substr(r),n()+"plain")},getHtml:function(e){var n,r,i,a="",s=["syntaxhighlighter"];return 1==this.getParam("light")&&(this.params.toolbar=this.params.gutter=!1),className="syntaxhighlighter",1==this.getParam("collapse")&&s.push("collapsed"),0==(gutter=this.getParam("gutter"))&&s.push("nogutter"),s.push(this.getParam("class-name")),s.push(this.getParam("brush")),e=w(e).replace(/\r/g," "),n=this.getParam("tab-size"),e=1==this.getParam("smart-tabs")?R(e,n):H(e,n),this.getParam("unindent")&&(e=P(e)),gutter&&(i=this.figureOutLineNumbers(e)),r=this.findMatches(this.regexList,e),a=this.getMatchesHtml(e,r),a=this.getCodeLinesHtml(a,i),this.getParam("auto-links")&&(a=I(a)),"undefined"!=typeof navigator&&navigator.userAgent&&navigator.userAgent.match(/MSIE/)&&s.push("ie"),a='<div id="'+l(this.id)+'" class="'+t(s.join(" "))+'">'+(this.getParam("toolbar")?O.toolbar.getHtml(this):"")+'<table border="0" cellpadding="0" cellspacing="0">'+this.getTitleHtml(this.getParam("title"))+"<tbody><tr>"+(gutter?'<td class="gutter">'+this.getLineNumbersHtml(e)+"</td>":"")+'<td class="code"><div class="container">'+a+"</div></td></tr></tbody></table></div>"},getDiv:function(t){null===t&&(t=""),this.code=t;var e=this.create("div");return e.innerHTML=this.getHtml(t),this.getParam("toolbar")&&m(c(e,".toolbar"),"click",O.toolbar.handler),this.getParam("quick-code")&&m(c(e,".code"),"dblclick",X),e},init:function(t){this.id=f(),u(this),this.params=p(O.defaults,t||{}),1==this.getParam("light")&&(this.params.toolbar=this.params.gutter=!1)},getKeywords:function(t){return"\\b(?:"+(t=t.replace(/^\s+|\s+$/g,"").replace(/\s+/g,"|"))+")\\b"},forHtmlScript:function(t){var e={end:t.right.source};t.eof&&(e.end="(?:(?:"+e.end+")|$)"),this.htmlScript={left:{regex:t.left,css:"script"},right:{regex:t.right,css:"script"},code:XRegExp("(?<left>"+t.left.source+")(?<code>.*?)(?<right>"+e.end+")","sgi")}}},O}();"undefined"!=typeof exports&&(exports.SyntaxHighlighter=SyntaxHighlighter);

!function(){var t=SyntaxHighlighter;t.autoloader=function(){function a(t){var a=document.createElement("script"),n=!1;a.src=t,a.type="text/javascript",a.language="javascript",a.onload=a.onreadystatechange=function(){n||this.readyState&&"loaded"!=this.readyState&&"complete"!=this.readyState||(n=!0,l[t]=!0,e(),a.onload=a.onreadystatechange=null,a.parentNode.removeChild(a))},document.body.appendChild(a)}function e(){for(var t in l)if(0==l[t])return;h&&SyntaxHighlighter.highlight(d)}var n,r=arguments,i=t.findElements(),o={},l={},h=(SyntaxHighlighter.all,!1),d=null;for(SyntaxHighlighter.all=function(t){d=t,h=!0},n=0;n<r.length;n++){var c=function(t){return t.pop?t:t.split(/\s+/)}(r[n]);!function(t,a){for(var e=0;e<t.length;e++)o[t[e]]=a}(c,u=c.pop())}for(n=0;n<i.length;n++){var u=o[i[n].params.brush];u&&void 0===l[u]&&("true"===i[n].params["html-script"]&&void 0===l[o.xml]&&(a(o.xml),l[u]=!1),l[u]=!1,a(u))}}}();



// On document ready ...
$( document ).ready( function() {

    $.ajax({
        url: 'https://www.geeek.org/counter/index.php',
        type: "GET",
        dataType: "json",
        success: function (data) {
          $("#social .list-group-item.rss .count").html(data.rss + " Lecteurs");
          $("#social .list-group-item.facebook .count").html(data.facebook + " Abonnés");
          $("#social .list-group-item.twitter .count").html(data.twitter + " Followers");
        }
      });
  
	// Load the gravatars
    $( '.gravatar' ).async_gravatars();
	
    // Load the modal images
    var lb_settings = {
      loader_img : 'https://www.geeek.org/?pf=lightbox/img/loader.gif',
      prev_img   : 'https://www.geeek.org/?pf=lightbox/img/prev.png',
      next_img   : 'https://www.geeek.org/?pf=lightbox/img/next.png',
      close_img  : 'https://www.geeek.org/?pf=lightbox/img/close.png',
      blank_img  : 'https://www.geeek.org/?pf=lightbox/img/blank.gif'
    };

    $( '.entry-content' ).each(function() {
      $(this).find("a[href$=\".jpg\"],a[href$=\".jpeg\"],a[href$=\".png\"],a[href$=\".gif\"]").modalImages(lb_settings);
    })

    //yash
    var yash_path = "/?pf=yash/syntaxhighlighter/js/";

    function shGetPath()
    {
        var args = arguments, result = [];
        for(var i = 0; i < args.length; i++)
                result.push(args[i].replace('@', yash_path));
        return result;
    };
    SyntaxHighlighter.autoloader.apply(null, shGetPath(
      'applescript            @shBrushAppleScript.js',
      'actionscript3 as3      @shBrushAS3.js',
      'bash shell             @shBrushBash.js',
      'coldfusion cf          @shBrushColdFusion.js',
      'cpp c                  @shBrushCpp.js',
      'c# c-sharp csharp      @shBrushCSharp.js',
      'css                    @shBrushCss.js',
      'delphi pascal          @shBrushDelphi.js',
      'diff patch pas         @shBrushDiff.js',
      'erl erlang             @shBrushErlang.js',
      'groovy                 @shBrushGroovy.js',
      'java                   @shBrushJava.js',
      'jfx javafx             @shBrushJavaFX.js',
      'js jscript javascript  @shBrushJScript.js',
      'perl pl                @shBrushPerl.js',
      'php                    @shBrushPhp.js',
      'text plain             @shBrushPlain.js',
      'ps powershell              @shBrushPowerShell.js',
      'py python              @shBrushPython.js',
      'ruby rails ror rb      @shBrushRuby.js',
      'sass scss              @shBrushSass.js',
      'scala                  @shBrushScala.js',
      'sql                    @shBrushSql.js',
      'vb vbnet               @shBrushVb.js',
      'xml xhtml xslt html    @shBrushXml.js'
   ));
   SyntaxHighlighter.all();


    // Initialize tooltips
    $("[data-rel~='tooltip']").tooltip();
  
    // Initialize load buttons
    function initShareButton(element,url){
	
        //Twitter
        if (typeof (twttr) != 'undefined') {
                twttr.widgets.load();
        } else {
		$.getScript("https://platform.twitter.com/widgets.js")
        }

        //Facebook
        if (typeof (FB) != 'undefined') {
         	FB.XFBML.parse(element.get(0));	
        } else {

		$.getScript( "https://connect.facebook.net/en_US/all.js#xfbml=1" )
  			.done(function( script, textStatus ) {
    				FB.init({ appId: '111114779937', status: true, cookie: true, xfbml: true });	
  			});
		
        }
    }
   
    // Popover 
    $(".share-button").popover({
	html:true,
	placement:"bottom",
	trigger:"click",
	delay: { show: 100, hide: 100 },
	content:    function(){
        var url = $(this).attr("data-url");
        var text = $(this).attr("data-text");
        return '<div class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-url="'+url+'" data-text="'+text+'" data-via="ltoinel" data-lang="fr" data-dnt="true">Tweeter</a></div><div class="fb-like" data-href="'+url+'" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false"></div>';
    }
    });

    $(".share-button").on('shown.bs.popover', function () {
       initShareButton($(this).parent(),$(this).attr("data-url"));
    });

    // Social event tracking	
    $("[data-rel~='social']").click(function(){
	if (ga !== undefined){
		ga('send', 'event', 'social', $(this).attr("id"));
	};
    });
   
    // Tab initialize
    $('#annexe').tab();

    // Rateit
    $.fn.rateit.defaults.service_url = 'https://www.geeek.org/rateitservice/';
    $.fn.rateit.defaults.service_func = 'rateItVote';
    $.fn.rateit.defaults.image_size = '48';
    $.fn.rateit.defaults.blog_uid = '174dd04649aae74eaf9b394b8a511698';
    $.fn.rateit.defaults.enable_cookie = '1';
    $.fn.rateit.defaults.msg_thanks = '';
    $('.rateit').rateit();

});

