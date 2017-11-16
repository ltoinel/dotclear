<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of yash, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

class yashBehaviors
{
	private static $syntaxehl_brushes = array(
		'4cs' => 			'',
		'abap' => 			'',
		'actionscript' => 	'as3',
		'ada' => 			'',
		'apache' => 		'',
		'applescript' => 	'applescript',
		'apt_sources' => 	'',
		'asm' => 			'',
		'asp' => 			'',
		'autoconf' => 		'',
		'autohotkey' => 	'',
		'autoit' => 		'',
		'avisynth' => 		'',
		'awk' => 			'',
		'bash' => 			'bash',
		'basic4gl' => 		'',
		'bf' => 			'',
		'bibtex' => 		'',
		'blitzbasic' => 	'',
		'bnf' => 			'',
		'boo' => 			'',
		'c' => 				'cpp',
		'c_mac' => 			'cpp',
		'caddcl' => 		'',
		'cadlisp' => 		'',
		'cfdg' => 			'',
		'cfm' => 			'cf',
		'chaiscript' => 	'',
		'cil' => 			'',
		'clojure' => 		'',
		'cmake' => 			'',
		'cobol' => 			'',
		'cpp-qt' => 		'cpp',
		'cpp' => 			'cpp',
		'csharp' => 		'csharp',
		'css' => 			'css',
		'cuesheet' => 		'',
		'd' => 				'',
		'dcs' => 			'',
		'delphi' => 		'delphi',
		'diff' => 			'diff',
		'div' => 			'',
		'dos' => 			'',
		'dot' => 			'',
		'ecmascript' => 	'js',
		'eiffel' => 		'',
		'email' => 			'',
		'erlang' => 		'erlang',
		'fo' => 			'',
		'fortran' => 		'',
		'freebasic' => 		'',
		'fsharp' => 		'',
		'gambas' => 		'',
		'gdb' => 			'',
		'genero' => 		'',
		'genie' => 			'',
		'gettext' => 		'',
		'glsl' => 			'',
		'gml' => 			'',
		'gnuplot' => 		'',
		'groovy' => 		'groovy',
		'gwbasic' => 		'',
		'haskell' => 		'',
		'hicest' => 		'',
		'hq9plus' => 		'',
		'html4strict' => 	'xml',
		'icon' => 			'',
		'idl' => 			'',
		'ini' => 			'',
		'inno' => 			'delphi',
		'intercal' => 		'',
		'io' => 			'',
		'j' => 				'',
		'java' => 			'java',
		'java5' => 			'java',
		'javascript' => 	'js',
		'jquery' => 		'js',
		'kixtart' => 		'',
		'klonec' => 		'cpp',
		'klonecpp' => 		'cpp',
		'latex' => 			'',
		'lisp' => 			'',
		'locobasic' => 		'',
		'logtalk' => 		'',
		'lolcode' => 		'',
		'lotusformulas' => 	'',
		'lotusscript' => 	'',
		'lscript' => 		'',
		'lsl2' => 			'',
		'lua' => 			'',
		'm68k' => 			'',
		'magiksf' => 		'',
		'make' => 			'',
		'mapbasic' => 		'',
		'matlab' => 		'',
		'mirc' => 			'',
		'mmix' => 			'',
		'modula2' => 		'',
		'modula3' => 		'',
		'mpasm' => 			'',
		'mxml' => 			'xml',
		'mysql' => 			'sql',
		'newlisp' => 		'',
		'nsis' => 			'',
		'oberon2' => 		'',
		'objc' => 			'',
		'ocaml-brief' => 	'',
		'ocaml' => 			'',
		'oobas' => 			'',
		'oracle11' => 		'sql',
		'oracle8' => 		'sql',
		'oxygene' => 		'delphi',
		'oz' => 			'',
		'pascal' => 		'',
		'pcre' => 			'',
		'per' => 			'',
		'perl' => 			'pl',
		'perl6' => 			'pl',
		'pf' => 			'',
		'php-brief' => 		'php',
		'php' => 			'php',
		'pic16' => 			'',
		'pike' => 			'',
		'pixelbender' => 	'',
		'plsql' => 			'sql',
		'postgresql' => 	'sql',
		'povray' => 		'',
		'powerbuilder' => 	'',
		'powershell' => 	'ps',
		'progress' => 		'',
		'prolog' => 		'',
		'properties' => 	'',
		'providex' => 		'',
		'purebasic' => 		'',
		'python' => 		'python',
		'q' => 				'',
		'qbasic' => 		'',
		'rails' => 			'ruby',
		'rebol' => 			'',
		'reg' => 			'',
		'robots' => 		'',
		'rpmspec' => 		'',
		'rsplus' => 		'',
		'ruby' => 			'ruby',
		'sas' => 			'',
		'scala' => 			'scala',
		'scheme' => 		'',
		'scilab' => 		'',
		'sdlbasic' => 		'',
		'smalltalk' => 		'',
		'smarty' => 		'',
		'sql' => 			'sql',
		'systemverilog' => 	'',
		'tcl' => 			'',
		'teraterm' => 		'',
		'text' => 			'',
		'thinbasic' => 		'',
		'tsql' => 			'sql',
		'typoscript' => 	'',
		'unicon' => 		'',
		'vala' => 			'',
		'vb' => 			'vb',
		'vbnet' => 			'vb',
		'verilog' => 		'',
		'vhdl' => 			'',
		'vim' => 			'',
		'visualfoxpro' => 	'',
		'visualprolog' => 	'',
		'whitespace' => 	'',
		'whois' => 			'',
		'winbatch' => 		'',
		'xbasic' => 		'',
		'xml' => 			'xml',
		'xorg_conf' => 		'',
		'xpp' => 			'',
		'z80' => 			''
	);

	public static function adminPostEditor($editor='',$context='',array $tags=array(),$syntax='')
	{
		global $core;

		if ($editor != 'dcLegacyEditor') return;

		return
		dcPage::jsLoad(urldecode(dcPage::getPF('yash/js/post.js')),$core->getVersion('yash')).
		'<script type="text/javascript">'."\n".
		dcPage::jsVar('jsToolBar.prototype.elements.yash.title',__('Highlighted Code')).
		"</script>\n";
	}

	public static function coreInitWikiPost($wiki2xhtml)
	{
		global $core;

		$wiki2xhtml->registerFunction('macro:yash',array('yashBehaviors','transform'));

		$core->blog->settings->addNameSpace('yash');
		if ((boolean)$core->blog->settings->yash->yash_syntaxehl) {
			// Add syntaxehl compatibility macros
			foreach (self::$syntaxehl_brushes as $brush => $alias) {
				$wiki2xhtml->registerFunction('macro:['.$brush.']',array('yashBehaviors','transformSyntaxehl'));
			}
		}
	}

	public static function transform($text,$args)
	{
		$text = trim($text);
		$real_args = explode(' ',$args);
		$class = empty($real_args[1]) ? 'plain' : $real_args[1];
		return '<pre class="brush: '.$class.'">'.htmlspecialchars($text).'</pre>';
	}

	public static function transformSyntaxehl($text,$args)
	{
		$text = trim($text);
		$real_args = preg_replace('/^(\[(.*)\]$)/','$2',$args);
		$class = array_key_exists($real_args,self::$syntaxehl_brushes) && self::$syntaxehl_brushes[$real_args] != ''
			? self::$syntaxehl_brushes[$real_args]
			: 'plain';
		return '<pre class="brush: '.$class.'">'.htmlspecialchars($text).'</pre>';
	}
}
