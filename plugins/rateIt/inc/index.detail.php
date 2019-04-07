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

if (!defined('DC_CONTEXT_RATEIT') || DC_CONTEXT_RATEIT != 'detail'){return;}

if ($action == 'rateit_del_entry' && !empty($_POST['entries']))
{
	try
	{
		foreach($_POST['entries'] AS $entry)
		{
			$val = explode('|',$entry);
			$core->rateIt->del($val[0],$val[1],$val[2]);
		}

		$core->blog->triggerBlog();
		http::redirect($p_url.'&part=detail&type='.$type.'&id='.$id.'&done=1');
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}

try
{
	$rs = $core->rateIt->getDetails($type,$id);
}
catch (Exception $e)
{
	$core->error->add($e->getMessage());
}

$lines = '';
while ($rs->fetch())
{
	$lines .=
	'<tr class="line">'.
	'<td class="nowrap">'.form::checkbox(array('entries[]'),$rs->rateit_type.'|'.$rs->rateit_id.'|'.$rs->rateit_ip,'','','',false).'</td>'.
	'<td class="nowrap">'.dt::dt2str(__('%Y-%m-%d %H:%M'),$rs->rateit_time,$core->auth->getInfo('user_tz')).'</td>'.
	'<td class="nowrap">'.$rs->rateit_note.'</td>'.
	'<td class="nowrap">'.$rs->rateit_quotient.'</td>'.
	'<td class="nowrap maximal">'.$rs->rateit_ip.'</td>'.
	'<td class="nowrap">'.$rs->rateit_type.'</td>'.
	'<td class="nowrap">'.$rs->rateit_id.'</td>'.
	'</tr>';
}

# Display
echo '
<html>
<head><title>'.__('Rate it').' - '.__('Detail').'</title>'.$header.
dcPage::jsLoad('js/_posts_list.js');

# --BEHAVIOR-- adminRateItHeader
$core->callBehavior('adminRateItHeader',$core);

echo
'</head>
<body>'.$menu.'
<div id="detail"><h3>'.__('Detail').'</h3>
<p>'.sprintf(__('This is detailed list for rating of type "%s" and id "%s"'),$type,$id).'</p>
<form action="plugin.php" method="post" id="form-details">';

if ($lines == '')
{
	echo '<p class="message">'.__('There is no rating for this request at this time').'</p>';
}
else
{
	echo
	'<table class="clear"><tr>'.
	'<th colspan="2">'.__('Date').'</th>'.
	'<th>'.__('Note').'</th>'.
	'<th>'.__('Quotient').'</th>'.
	'<th>'.__('Ip').'</th>'.
	'<th>'.__('Type').'</th>'.
	'<th>'.__('Id').'</th>'.
	'</tr>'.
	$lines.
	'</table>'.

	'<div class="two-cols">'.
	'<p class="col checkboxes-helpers"></p>'.
	'<p class="col right">'.__('Selected lines action:').' '.
	form::combo(array('action'),array(__('delete entry') => 'rateit_del_entry')).
	'<input type="submit" name="save" value="'.__('ok').'" />'.
	form::hidden(array('p'),'rateIt').
	form::hidden(array('part'),'detail').
	form::hidden(array('type'),$type).
	form::hidden(array('id'),$id).
	$core->formNonce().
	'</p>'.
	'</div>';
}
echo '</form></div>';

echo $footer.'</body></html>';
