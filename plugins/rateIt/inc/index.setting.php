<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of rateIt, a plugin for Dotclear 2.
#
# Copyright(c) 2014-2015 Nicolas Roudaire <nikrou77@gmail.com> http://www.nikrou.net
#
# Copyright (c) 2009-2010 JC Denis and contributors
# jcdenis@gdwd.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_RATEIT') || DC_CONTEXT_RATEIT != 'setting'){return;}

# Fixed display of stars in admin
function rateItPictureshow($star)
{
	$s = getimagesize($star['dir']);
	return
	'<td><div style="'.
	'	display:block;'.
	'	overflow:hidden;'.
	'	text-indent:-999em;'.
	'	margin: 0px;'.
	'	padding: 1px;'.
	'	background: transparent url('.$star['url'].') no-repeat 0 0;'.
	'	width:'.$s[0].'px;'.
	'	height:'.(floor($s[1] /3)-1).'px;'.
	'">&nbsp;</div></td>'.
	'<td><div style="'.
	'	display:block;'.
	'	overflow:hidden;'.
	'	text-indent:-999em;'.
	'	margin: 0px;'.
	'	padding: 1px;'.
	'	background: transparent url('.$star['url'].') no-repeat 0 -'.floor($s[1] /3).'px;'.
	'	width:'.$s[0].'px;'.
	'	height:'.(floor($s[1] /3)-1).'px;'.
	'">&nbsp;</div></td>'.
	'<td><div style="'.
	'	display:block;'.
	'	overflow:hidden;'.
	'	text-indent:-999em;'.
	'	margin: 0px;'.
	'	padding: 1px;'.
	'	background: transparent url('.$star['url'].') no-repeat 0 -'.(floor($s[1] /3) *2).'px;'.
	'	width:'.$s[0].'px;'.
	'	height:'.(floor($s[1] /3)-1).'px;'.
	'">&nbsp;</div></td><td>'.$s[0].'x'.floor($s[1] /3).'</td>';
}

# Update settings
if ($action == 'save_setting')
{
	try
	{
		$s->put('rateit_active',!empty($_POST['rateit_active']));
		$s->put('rateit_userident',$_POST['rateit_userident']);
		$s->put('rateit_dispubjs',!empty($_POST['rateit_dispubjs']));
		$s->put('rateit_dispubcss',!empty($_POST['rateit_dispubcss']));
		$s->put('rateit_quotient',$_POST['rateit_quotient']);
		$s->put('rateit_rating_style',$_POST['rateit_rating_style']);
		$s->put('rateit_digit',$_POST['rateit_digit']);
		$s->put('rateit_msglike',$_POST['rateit_msglike']);
		$s->put('rateit_msgnotlike',$_POST['rateit_msgnotlike']);
		$s->put('rateit_msgthanks',$_POST['rateit_msgthanks']);
		$s->put('rateit_firstimage_size',$_POST['rateit_firstimage_size']);

		# Destination image according to rateItLibImagePath()
		$dest_file = $core->blog->settings->system->public_path.'/rateIt-default-image.png';

		# Change rate image
		if (isset($_POST['starsimage']) && preg_match('/^star-[0-9]+.png$/',$_POST['starsimage']))
		{
			$source = dirname(__FILE__).'/../default-templates/img/stars/'.$_POST['starsimage'];

			if (file_exists($source))
			{
				file_put_contents($dest_file,file_get_contents($source));
			}
		}
		# Upload rate image
		if (!empty($_FILES['starsuserfile']['tmp_name']))
		{
			if (2 == $_FILES['starsuserfile']['error'])
			{
				throw new Exception(__('Maximum file size exceeded'));
			}
			if (0 != $_FILES['starsuserfile']['error'])
			{
				throw new Exception(__('Something went wrong while download file'));
			}
			if (!in_array($_FILES['starsuserfile']['type'],array('image/png','image/x-png')))
			{
				throw new Exception(__('Image must be in png format'));
			}
			move_uploaded_file($_FILES['starsuserfile']['tmp_name'],$dest_file);
		}
		$core->blog->triggerBlog();

		http::redirect($p_url.'&part=setting&msg='.$action.'&section='.$section);
	}
	catch (Exception $e)
	{
		$core->error->add(sprintf($errors[$action],$e->getMessage()));
	}
}

# Init some values
$combo_quotient = array();
for($i=2;$i<21;$i++)
{
	$combo_quotient[$i] = $i;
}
$combo_digit = array();
for($i=0;$i<5;$i++)
{
	$combo_digit[$i] = $i;
}
$combo_userident = array(
	__('Ip') => 0,
	__('Cookie') => 2,
	__('Both ip and cookie') => 1
);
$combo_firstimage_size = array(
	__('square') => 'sq',
	__('thumbnail') => 't',
	__('small') => 's',
	__('medium') => 'm',
	__('original') => 'o'
);

# Display
echo '
<html>
<head><title>'.__('Rate it').' - '.__('Settings').'</title>'.$header;

# --BEHAVIOR-- adminRateItHeader
$core->callBehavior('adminRateItHeader',$core);

echo
'</head>
<body>'.$menu.
'<h3>'.__('Settings').'</h3>'.
$msg.'
<form id="setting-form" method="post" action="'.$p_url.'" enctype="multipart/form-data">

<div class="fieldset" id="setting-plugin"><h3>'.__('Extension').'</h3>
<p><label class="classic">'.
form::checkbox(array('rateit_active'),1,$s->rateit_active).
__('Enable plugin').'</label></p>
<p><label class="classic">'.
form::checkbox(array('rateit_dispubjs'),1,$s->rateit_dispubjs).
__('Disable public javascript').'</label></p>
<p class="form-note">'.__('This disables all image effects, shows standard form and reloads page on vote.').'</p>
<p><label class="classic">'.
form::checkbox(array('rateit_dispubcss'),1,$s->rateit_dispubcss).
__('Disable public css').'</label></p>
<p class="form-note">'.__('This disables the file "rateit.css" if you want to include your styles directly in the CSS file of the theme.').'</p>
<p><label>'.__('Identify users by:').' '.
form::combo(array('rateit_userident'),$combo_userident,$s->rateit_userident).'</label></p>
<p class="info">'.__('In order to change url of public page you can use plugin myUrlHandlers.').'</p>
</div>

<div class="fieldset" id="setting-note"><h3>'.__('Note').'</h3>
<p><label>'.__('Note out of:').' '.
form::combo(array('rateit_quotient'),$combo_quotient,$s->rateit_quotient).'</label></p>
<p><label>'.__('Number of digits:').' '.
form::combo(array('rateit_digit'),$combo_digit,$s->rateit_digit).'</label></p>
<p><label>'.__("Text for Like button:").' '.
form::field(array('rateit_msglike'),40,255,html::escapeHTML($s->rateit_msglike),'',2).'</label></p>
<p><label>'.__("Text for Don't like button:").' '.
form::field(array('rateit_msgnotlike'),40,255,html::escapeHTML($s->rateit_msgnotlike),'',2).'</label></p>
<p><label>'.__('Message of thanks:').' '.
form::field(array('rateit_msgthanks'),40,255,html::escapeHTML($s->rateit_msgthanks),'',2).'</label></p>
<p class="form-note">'.__('This message replaces stars, leave it empty to not replace stars').'</p>
<p><label class="classic">'.
form::radio(array('rateit_rating_style'),'classic',$s->rateit_rating_style=='classic').
__('Classic rating mode').' </label></p>
<p class="form-note">'.__('User attributes a note to an item.').'</p>
<p><label class="classic">'.
form::radio(array('rateit_rating_style'),'twin',$s->rateit_rating_style=='twin').
__('Twin rating mode').' </label></p>
<p class="form-note">'.__('User says if he likes or not an item.').'</p>
<p><label class="classic">'.
form::radio(array('rateit_rating_style'),'simple',$s->rateit_rating_style=='simple').
__('Simple rating mode').' </label></p>
<p class="form-note">'.__('User just says if he like an item.').'</p>
</div>

<div class="fieldset" id="settings-widget"><h3>'.__('Widget').'</h3>
<p class="field"><label>'.__('Widget entry image size').' '.
form::combo(array('rateit_firstimage_size'),$combo_firstimage_size,$s->rateit_firstimage_size).'</label></p>
</div>'.

'<div class="fieldset" id="setting-image"><h3>'.__('Image').'</h3>';

$stars_rateit_files = files::scandir(dirname(__FILE__).'/../default-templates/img/stars');
$stars = rateItLibImagePath::getArray($core,'rateIt');

# Get stars images from multiples folders
if (file_exists($stars['theme']['dir']))
{
	# Theme dir
	echo
	'<p>'.__('Rating image exists on theme it will be used:').'</p>'.
	form::hidden(array('starsimage'),'theme').
	'<table><tr><th>'.__('negative').'</th><th>'.__('positive').'</th><th>'.__('hover').'</th><th>'.__('size').'</th></tr>'.
	'<tr>'.rateItPictureshow($stars['theme']).'</tr></table>';
}
else
{
	echo
	'<p>'.__('Rating image not exists on theme choose one to use:').'</p>'.
	'<table><tr><th>&nbsp;</th><th>'.__('negative').'</th><th>'.__('positive').'</th><th>'.__('hover').'</th><th>'.__('size').'</th></tr>';

	# Public dir
	if (file_exists($stars['public']['dir']))
	{
		echo
		'<tr><td><label class="classic">'.form::radio(array('starsimage'),'default',1).' '.__('current').'</label></td>'.
		rateItPictureshow($stars['public']).'</tr>';
	}
	# rateIt plugin dir
	elseif (file_exists($stars['module']['dir']))
	{
		echo
		'<tr><td><label class="classic">'.form::radio(array('starsimage'),'default',1).' '.__('current').'</label></td>'.
		rateItPictureshow($stars['module']).'</tr>';
	}

	sort($stars_rateit_files);
	foreach($stars_rateit_files AS $f)
	{
		if (!preg_match('/star-[0-9]+.png/',$f)) continue;

		echo
		'<tr class="line"><td><label class="classic">'.form::radio(array('starsimage'),$f).' '.$f.'</label></td>'.
		rateItPictureshow(array(
			'dir'=>dirname(__FILE__).'/../default-templates/img/stars/'.$f,
			'url'=>'index.php?pf=rateIt/default-templates/img/stars/'.$f)
		).'</tr>';
	}
	echo
	'<tr class="line"><td>'.form::radio(array('starsimage'),'user').'</td>'.
	'<td colspan="4">'.form::hidden(array('MAX_FILE_SIZE'),30000).'<input type="file" name="starsuserfile" /></td></tr>'.
	'</table>'.
	'<p class="form-note">'.__('Image must be in png format and having three equal height.').'</p>';
}
echo
'</div>

<div class="clear">
<p><input type="submit" name="save" value="'.__('Save').'" />'.
form::hidden(array('p'),'rateIt').
form::hidden(array('part'),'setting').
form::hidden(array('section'),$section).
form::hidden(array('action'),'save_setting').
$core->formNonce().'</p></div>'.
'</form>';

dcPage::helpBlock('rateIt_settings');
echo $footer.'</body></html>';
