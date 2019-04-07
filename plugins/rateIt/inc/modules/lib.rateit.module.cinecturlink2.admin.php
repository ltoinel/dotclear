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

# rateIt admin behaviors
class cinecturlink2RateItModuleAdmin
{
	public static function adminRateItModuleUpdate($core,$type,$action,$page_url,$hidden_fields)
	{
		if ($type != 'cinecturlink2') return;

		if ($action == 'save_module_cinecturlink2')
		{
			$core->blog->settings->rateit->put('rateit_cinecturlink2_active',!empty($_POST['rateit_cinecturlink2_active']),'boolean','Enabled cinecturlink2 rating',true,false);
			$core->blog->settings->rateit->put('rateit_cinecturlink2_widget',!empty($_POST['rateit_cinecturlink2_widget']),'boolean','Enabled rating on cinecturlink2 widget',true,false);
			$core->blog->settings->rateit->put('rateit_cinecturlink2_page',!empty($_POST['rateit_cinecturlink2_page']),'boolean','Enabled rating on cinecturlink2 page',true,false);

			$core->blog->triggerBlog();
			return 'save_setting';
		}

		return;
	}

	public static function adminRateItModuleSettingsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'cinecturlink2') return;

		echo
		'<form method="post" action="'.$page_url.'">'.
		'<p><label class="classic">'.
		form::checkbox(array('rateit_cinecturlink2_active'),1,$core->blog->settings->rateit->rateit_cinecturlink2_active).
		__('Enable cinecturlinks rating').'</label></p>'.
		'<p><label class="classic">'.
		form::checkbox(array('rateit_cinecturlink2_widget'),1,$core->blog->settings->rateit->rateit_cinecturlink2_widget).
		__('Include on cinecturlinks widget').'</label></p>'.
		'<p><label class="classic">'.
		form::checkbox(array('rateit_cinecturlink2_page'),1,$core->blog->settings->rateit->rateit_cinecturlink2_page).
		__('Include on cinecturlinks page').'</label></p>'.

		'<p><input type="submit" name="save" value="'.__('Save').'" />'.
		$hidden_fields.
		form::hidden(array('action'),'save_module_cinecturlink2').
		'</p></form>';

		return 1;
	}

	public static function adminRateItModuleRecordsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'cinecturlink2') return;

		try
		{
			$C2 = new cinecturlink2($core);
			$links = $C2->getLinks();
		}
		catch (Exception $e)
		{
			$core->error->add($e->getMessage());
		}

		$table = '';
		while ($links->fetch())
		{
			$rs = $core->rateIt->get('cinecturlink2',$links->link_id);
			if (!$rs->total) continue;
			$table .=
			'<tr class="line">'.
			'<td class="nowrap">'.form::checkbox(array('entries[]'),$links->link_id,'','','',false).'</td>'.
			'<td class="maximal"><a href="plugin.php?p=cinecturlink2&amp;part=main&amp;tab=newlink&amp;link_id='.$links->link_id.'">'.html::escapeHTML($links->link_title).'</a></td>'.
			'<td class="nowrap">'.$rs->note.'</td>'.
			'<td class="nowrap"><a title="'.__('Show rating details').'" href="plugin.php?p=rateIt&amp;part=detail&amp;type=cinecturlink2&amp;id='.$links->link_id.'">'.$rs->total.'</a></td>'.
			'<td class="nowrap">'.$rs->max.'</td>'.
			'<td class="nowrap">'.$rs->min.'</td>'.
			'<td class="nowrap">'.$links->link_id.'</td>'.
			'<td class="nowrap">'.$links->link_creadt.'</td>'.
			'<td class="nowrap">'.$links->link_note.'/10</td>'.
			'</tr>';
		}

		if ($table=='')
		{
			echo '<p class="message">'.__('There is no cinecturlink rating at this time').'</p>';
		}
		else
		{
			echo
			'<h3>'.__('List of all the cinecturlink having rating').'</h3>'.
			'<form method="post" action="'.$page_url.'">'.
			'<table class="clear"><tr>'.
			'<th colspan="2">'.__('Title').'</th>'.
			'<th>'.__('Note').'</th>'.
			'<th>'.__('Votes').'</th>'.
			'<th>'.__('Higher').'</th>'.
			'<th>'.__('Lower').'</th>'.
			'<th>'.__('Id').'</th>'.
			'<th>'.__('Date').'</th>'.
			'<th>'.__('My note').'</th>'.
			'</tr>'.
			$table.
			'</table>'.

			'<div class="two-cols">'.
			'<p class="col checkboxes-helpers"></p>'.
			'<p class="col right">'.__('Selected cinecturlink action:').' '.
			form::combo(array('action'),array(__('delete rating') => 'rateit_cinecturlink2_empty')).
			'<input type="submit" name="save" value="'.__('ok').'" />'.
			$hidden_fields.
			'</p></div>'.
			'</form>';
		}

		return 1;
	}

	public static function adminRateItWidgetRank($types)
	{
		$types[] = array(__('Cinecturlink') => 'cinecturlink2');
	}
}

# DC admin behaviors
//to do
