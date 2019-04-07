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
class tagRateItModuleAdmin
{
	public static function adminRateItModuleUpdate($core,$type,$action,$page_url,$hidden_fields)
	{
		if ($type != 'tag') return;

		if ($action == 'save_module_tag')
		{
			$core->blog->settings->rateit->put('rateit_tag_active',!empty($_POST['rateit_tag_active']),'boolean','Enable tags rating',true,false);

			$core->blog->triggerBlog();
			return 'save_setting';
		}

		if ($action == 'rateit_tag_empty' && isset($_POST['entries']))
		{
			foreach($_POST['entries'] as $comment_id)
			{
				$core->rateIt->del('tag',$comment_id);
			}

			$core->blog->triggerBlog();
			return 'del_records';
		}
		return;
	}

	public static function adminRateItModuleSettingsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'tag') return;

		echo
		'<form method="post" action="'.$page_url.'">'.
		'<p><label class="classic">'.
		form::checkbox(array('rateit_tag_active'),1,$core->blog->settings->rateit->rateit_tag_active).
		__('Enable tags rating').'</label></p>'.

		'<p><input type="submit" name="save" value="'.__('Save').'" />'.
		$hidden_fields.
		form::hidden(array('action'),'save_module_tag').
		'</p></form>';

		return 1;
	}

	public static function adminRateItModuleRecordsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'tag') return;

		try
		{
			$metas = $core->meta->getMetadata(array('meta_type'=>'tag'));
		}
		catch (Exception $e)
		{
			$core->error->add($e->getMessage());
		}

		$table = '';
		while ($metas->fetch())
		{
			$rs = $core->rateIt->get('tag',$metas->meta_id);
			if (!$rs->total) continue;
			$table .=
			'<tr class="line">'.
			'<td class="nowrap">'.form::checkbox(array('entries[]'),$metas->meta_id,'','','',false).'</td>'.
			'<td class="maximal"><a href="plugin.php?p=metadata&amp;m=tag_posts&amp;tag='.$metas->meta_id.'">
				'.html::escapeHTML($metas->meta_id).'</a></td>'.
			'<td class="nowrap"><a title="'.__('Show rating details').'" href="plugin.php?p=rateIt&amp;part=detail&amp;type=tag&amp;id='.$metas->meta_id.'">'.$rs->total.'</a></td>'.
			'<td class="nowrap">'.$rs->note.'</td>'.
			'<td class="nowrap">'.$rs->max.'</td>'.
			'<td class="nowrap">'.$rs->min.'</td>'.
			'</tr>';
		}

		if ($table=='')
		{
			echo '<p class="message">'.__('There is no tag rating at this time').'</p>';
		}
		else
		{
			echo
			'<h3>'.__('List of all the tags having rating').'</h3>'.
			'<form method="post" action="'.$page_url.'">'.
			'<table class="clear"><tr>'.
			'<th colspan="2">'.__('Title').'</th>'.
			'<th>'.__('Votes').'</th>'.
			'<th>'.__('Note').'</th>'.
			'<th>'.__('Higher').'</th>'.
			'<th>'.__('Lower').'</th>'.
			'</tr>'.
			$table.
			'</table>'.

			'<div class="two-cols">'.
			'<p class="col checkboxes-helpers"></p>'.
			'<p class="col right">'.__('Selected tags action:').' '.
			form::combo(array('action'),array(__('delete rating') => 'rateit_tag_empty')).
			'<input type="submit" name="save" value="'.__('ok').'" />'.
			$hidden_fields.
			'</p>'.
			'</div>'.
			'</form>';
		}

		return 1;
	}

	public static function adminRateItWidgetVote($w)
	{
		$w->rateit->setting('enable_tag',__('Enable vote for tags'),
			0,'check');
		$w->rateit->setting('title_tag',__('Title for tags:'),
		__('Rate this tag'),'text');
	}

	public static function adminRateItWidgetRank($types)
	{
		$types[] = array(__('tags') => 'tag');
	}
}

# DC admin behaviors
//to do
