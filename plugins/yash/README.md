Yash plugin for Dotclear 2
==========================

Packaging [Yash script](http://alexgorbatchev.com/SyntaxHighlighter) from Alex Gorbatchev

Available syntaxes
------------------

| Language code | Syntaxe             |
| ------------- | ------------------- |
| plain         | Plain Text          |
| applescript   | AppleScript         |
| as3           | ActionScript3       |
| bash          | Bash/shell          |
| cf            | ColdFusion          |
| csharp        | C#                  |
| cpp           | C/C++               |
| css           | CSS                 |
| delphi        | Delphi              |
| diff          | Diff/Patch          |
| erl           | Erlang              |
| groovy        | Groovy              |
| haxe          | Haxe                |
| js            | Javascript/JSON     |
| java          | Java                |
| jfx           | JavaFX              |
| pl            | Perl                |
| php           | PHP                 |
| ps            | PowerShell          |
| python        | Python              |
| ruby          | Ruby                |
| sass          | SASS                |
| scala         | Scala               |
| sql           | SQL                 |
| tap           | Tap                 |
| ts            | TypeScript          |
| vb            | Visual Basic        |
| xml           | XML/XSLT/XHTML/HTML |
| yaml          | Yaml                |

Usage
-----

Activate the plugin for the blog (see main setting page of this plugin)

Syntax
------

Wiki:

    ///yash language_code
    … code …
	///

HTML/Markdown:

	<pre class="brush: language_code">
	… code …
	</pre>

A toolbar button is available for dcLegacyEditor (wiki/markown and wysiwyg in source mode) to select syntax

Options
-------

SyntaxeHL: the ///[language_code] wiki macro is supported by Yash (if enabled in settings) for the following languages:

| SyntaxeHL     | Yash langage code |
| ------------- | ----------------- |
| 4cs           |                   |
| abap          |                   |
| actionscript  | as3               |
| ada           |                   |
| apache        |                   |
| applescript   | applescript       |
| apt_sources   |                   |
| asm           |                   |
| asp           |                   |
| autoconf      |                   |
| autohotkey    |                   |
| autoit        |                   |
| avisynth      | 	                |
| awk           |                   |
| bash          | bash              |
| basic4gl      |                   |
| bf            |                   |
| bibtex        |                   |
| blitzbasic    |                   |
| bnf           |                   |
| boo           |                   |
| c             | cpp               |
| c_mac         | cpp               |
| caddcl        |                   |
| cadlisp       |                   |
| cfdg          |                   |
| cfm           | cf                |
| chaiscript    |                   |
| cil           |                   |
| clojure       |                   |
| cmake         |                   |
| cobol         |                   |
| cpp-qt        | cpp               |
| cpp           | cpp               |
| csharp        | csharp            |
| css           | css               |
| cuesheet      |                   |
| d             |                   |
| dcs           |                   |
| delphi        | delphi            |
| diff          | diff              |
| div           |                   |
| dos           |                   |
| dot           |                   |
| ecmascript    | js                |
| eiffel        |                   |
| email         |                   |
| erlang        | erlang            |
| fo            |                   |
| fortran       |                   |
| freebasic     |                   |
| fsharp        |                   |
| gambas        |                   |
| gdb           |                   |
| genero        |                   |
| genie         |                   |
| gettext       |                   |
| glsl          |                   |
| gml           |                   |
| gnuplot       |                   |
| groovy        | groovy            |
| gwbasic       |                   |
| haskell       |                   |
| hicest        |                   |
| hq9plus       |                   |
| html4strict   | xml               |
| icon          |                   |
| idl           |                   |
| ini           |                   |
| inno          | delphi            |
| intercal      |                   |
| io            |                   |
| j             |                   |
| java          | java              |
| java5         | java              |
| javascript    | js                |
| jquery        | js                |
| kixtart       |                   |
| klonec        | cpp               |
| klonecpp      | cpp               |
| latex         |                   |
| lisp          |                   |
| locobasic     |                   |
| logtalk       |                   |
| lolcode       |                   |
| lotusformulas |                   |
| lotusscript   |                   |
| lscript       |                   |
| lsl2          |                   |
| lua           |                   |
| m68k          |                   |
| magiksf       |                   |
| make          |                   |
| mapbasic      |                   |
| matlab        |                   |
| mirc          |                   |
| mmix          |                   |
| modula2       |                   |
| modula3       |                   |
| mpasm         |                   |
| mxml          | xml               |
| mysql         | sql               |
| newlisp       |                   |
| nsis          |                   |
| oberon2       |                   |
| objc          |                   |
| ocaml-brief   |                   |
| ocaml         |                   |
| oobas         |                   |
| oracle11      | sql               |
| oracle8       | sql               |
| oxygene       | delphi            |
| oz            |                   |
| pascal        |                   |
| pcre          |                   |
| per           |                   |
| perl          | pl                |
| perl6         | pl                |
| pf            |                   |
| php-brief     | php               |
| php           | php               |
| pic16         |                   |
| pike          |                   |
| pixelbender   |                   |
| plsql         | sql               |
| postgresql    | sql               |
| povray        |                   |
| powerbuilder  |                   |
| powershell    | ps                |
| progress      |                   |
| prolog        |                   |
| properties    |                   |
| providex      |                   |
| purebasic     |                   |
| python        | python            |
| q             |                   |
| qbasic        |                   |
| rails         | ruby              |
| rebol         |                   |
| reg           |                   |
| robots        |                   |
| rpmspec       |                   |
| rsplus        |                   |
| ruby          | ruby              |
| sas           |                   |
| scala         | scala             |
| scheme        |                   |
| scilab        |                   |
| sdlbasic      |                   |
| smalltalk     |                   |
| smarty        |                   |
| sql           | sql               |
| systemverilog |                   |
| tcl           |                   |
| teraterm      |                   |
| text          |                   |
| thinbasic     |                   |
| tsql          | sql               |
| typoscript    |                   |
| unicon        |                   |
| vala          |                   |
| vb            | vb                |
| vbnet         | vb                |
| verilog       |                   |
| vhdl          |                   |
| vim           |                   |
| visualfoxpro  |                   |
| visualprolog  |                   |
| whitespace    |                   |
| whois         |                   |
| winbatch      |                   |
| xbasic        |                   |
| xml           | xml               |
| xorg_conf     |                   |
| xpp           |                   |
| z80           |                   |

