<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of YASH, a plugin for DotClear2.
# Copyright (c) 2008 Pep and contributors. All rights
# reserved.
#
# This plugin is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This plugin is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this plugin; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
# ***** END LICENSE BLOCK *****

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

// Getting current parameters if any (get global parameters if not)
$core->blog->settings->addNamespace('yash');
$active = (boolean)$core->blog->settings->yash->yash_active;
$theme = (string)$core->blog->settings->yash->yash_theme;
$custom_css = (string)$core->blog->settings->yash->yash_custom_css;
$hide_gutter = (boolean)$core->blog->settings->yash->yash_hide_gutter;
$syntaxehl = (boolean)$core->blog->settings->yash->yash_syntaxehl;

if (!empty($_REQUEST['popup'])) {
	$yash_brushes = array(
		'plain' 		=> __('Plain Text'),
		'applescript'	=> __('AppleScript'),
		'as3'			=> __('ActionScript3'),
		'bash'			=> __('Bash/shell'),
		'cf'			=> __('ColdFusion'),
		'csharp'		=> __('C#'),
		'cpp'			=> __('C/C++'),
		'css'			=> __('CSS'),
		'delphi'		=> __('Delphi'),
		'diff'			=> __('Diff/Patch'),
		'erl'			=> __('Erlang'),
		'groovy'		=> __('Groovy'),
		'haxe'			=> __('Haxe'),
		'js'			=> __('Javascript/JSON'),
		'java'			=> __('Java'),
		'jfx'			=> __('JavaFX'),
		'pl'			=> __('Perl'),
		'php'			=> __('PHP'),
		'ps'			=> __('PowerShell'),
		'python'		=> __('Python'),
		'ruby'			=> __('Ruby'),
		'sass'			=> __('SASS'),
		'scala'			=> __('Scala'),
		'sql'			=> __('SQL'),
		'tap'			=> __('Tap'),
		'ts'			=> __('TypeScript'),
		'vb'			=> __('Visual Basic'),
		'xml' 			=> __('XML/XSLT/XHTML/HTML'),
		'yaml'			=> __('Yaml')
	);

	echo
		'<html>'.
		'<head>'.
	  	'<title>'.__('YASH - Syntax Selector').'</title>'.
	  	dcPage::jsLoad(urldecode(dcPage::getPF('yash/js/popup.js')),$core->getVersion('yash')).
		'</head>'.
		'<body>'.
		'<h2>'.__('YASH - Syntax Selector').'</h2>'.
		'<form id="yash-form" action="'.$p_url.'&amp;popup=1" method="get">'.
		'<p><label>'.__('Select the primary syntax of your code snippet.').
		form::combo('syntax',array_flip($yash_brushes)).'</label></p>'.
		'<p><button id="yash-cancel">'.__('Cancel').'</button> - '.
		'<button id="yash-ok"><strong>'.__('Ok').'</strong></button></p>'.
		'</form>'.
		'</body>'.
		'</html>';
	return;
}

// Saving new configuration
if (!empty($_POST['saveconfig'])) {
	try
	{
		$core->blog->settings->addNameSpace('yash');
		$active = (empty($_POST['active'])) ? false : true;
		$theme = (empty($_POST['theme'])) ? 'Default' : $_POST['theme'];
		$custom_css = (empty($_POST['custom_css'])) ? '' : html::sanitizeURL($_POST['custom_css']);
		$hide_gutter = (empty($_POST['hide_gutter'])) ? false : true;
		$syntaxehl = (empty($_POST['syntaxehl'])) ? false : true;
		$core->blog->settings->yash->put('yash_active',$active,'boolean');
		$core->blog->settings->yash->put('yash_theme',$theme,'string');
		$core->blog->settings->yash->put('yash_custom_css',$custom_css,'string');
		$core->blog->settings->yash->put('yash_hide_gutter',$hide_gutter,'boolean');
		$core->blog->settings->yash->put('yash_syntaxehl',$syntaxehl,'boolean');
		$core->blog->triggerBlog();
		dcPage::addSuccessNotice(__('Configuration successfully updated.'));
		http::redirect($p_url);
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}
?>
<html>
<head>
	<title><?php echo __('YASH'); ?></title>
</head>

<body>
<?php
	echo dcPage::breadcrumb(
		array(
			html::escapeHTML($core->blog->name) => '',
			__('YASH') => ''
		));
echo dcPage::notices();

$combo_theme = array(
	__('Default')         => 'Default',
	__('Django')          => 'Django',
	__('Eclipse')         => 'Eclipse',
	__('Emacs')           => 'Emacs',
	__('Fade to gray')    => 'FadeToGrey',
	__('Material')        => 'Material',
	__('MD Ultra')        => 'MDUltra',
	__('Midnight')        => 'Midnight',
	__('RDark')           => 'RDark',
	__('Solarized Dark')  => 'SolarizedDark',
	__('Solarized Light') => 'SolarizedLight',
	__('Tomorrow Night')  => 'TomorrowNight'
	);
?>

<div id="yash_options">
	<form method="post" action="<?php http::getSelfURI(); ?>">
		<p>
			<?php echo form::checkbox('active',1,$active); ?>
			<label class="classic" for="active">&nbsp;<?php echo __('Enable YASH');?></label>
		</p>

		<h3><?php echo __('Presentation'); ?></h3>
		<p class="field"><label for="theme" class="classic"><?php echo __('Theme:'); ?> </label>
			<?php echo form::combo('theme',$combo_theme,$theme); ?>
		</p>
		<p class="field">
			<label for="custom_css" class="classic"><?php echo __('Use custom CSS:') ; ?> </label>
			<?php echo form::field('custom_css',40,128,$custom_css); ?>
		</p>
		<p class="info">
			<?php echo __('You can use a custom CSS by providing its location.'); ?><br />
			<?php echo __('A location beginning with a / is treated as absolute, else it is treated as relative to the blog\'s current theme URL'); ?>
		</p>
		<p>
			<?php echo form::checkbox('hide_gutter',1,$hide_gutter); ?>
			<label class="classic" for="hide_gutter">&nbsp;<?php echo __('Hide gutter with line numbers'); ?></label>
		</p>
		<h3><?php echo __('Options'); ?></h3>
		<p>
			<?php echo form::checkbox('syntaxehl',1,$syntaxehl); ?>
			<label class="classic" for="syntaxehl">&nbsp;<?php echo __('SyntaxeHL compatibility mode'); ?></label>
		</p>
		<p class="info">
			<?php echo __('Will be applied on future edition of posts containing SyntaxeHL macros (///[language]…///).'); ?><br /><?php echo __('All SyntaxeHL languages is not supported by Yash (see documentation).'); ?>
		</p>
		<p><input type="hidden" name="p" value="yash" />
			<?php echo $core->formNonce(); ?>
			<input type="submit" name="saveconfig" value="<?php echo __('Save configuration'); ?>" />
		</p>
	</form>
</div>

</body>
</html>
