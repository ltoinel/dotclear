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
class categoryRateItModuleAdmin
{
	public static function adminRateItModuleUpdate($core,$type,$action,$page_url,$hidden_fields)
	{
		if ($type != 'category') return;

		if ($action == 'save_module_category')
		{
			$core->blog->settings->rateit->put('rateit_category_active',!empty($_POST['rateit_category_active']),'boolean','Enabled post rating',true,false);

			$core->blog->triggerBlog();
			return 'save_setting';
		}

		if ($action == 'rateit_cat_empty' && isset($_POST['entries']))
		{
			foreach($_POST['entries'] as $cat_id)
			{
				$core->rateIt->del('category',$cat_id);
			}
			$core->blog->triggerBlog();
			return 'del_records';
		}
		return;
	}

	public static function adminRateItModuleSettingsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'category') return;

		echo
		'<form method="post" action="'.$page_url.'">'.
		'<p><label class="classic">'.
		form::checkbox(array('rateit_category_active'),1,$core->blog->settings->rateit->rateit_category_active).
		__('Enable categories rating').'</label></p>'.

		'<p><input type="submit" name="save" value="'.__('Save').'" />'.
		$hidden_fields.
		form::hidden(array('action'),'save_module_category').
		'</p></form>';

		return 1;
	}

	public static function adminRateItModuleRecordsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'category') return;

		try
		{
			$categories = $core->blog->getCategories(array('post_type'=>'post'));
		}
		catch (Exception $e)
		{
			$core->error->add($e->getMessage());
		}

		$table = '';
		while ($categories->fetch())
		{
			$rs = $core->rateIt->get('category',$categories->cat_id);
			if (!$rs->total) continue;
			$table .=
			'<tr class="line">'.
			'<td class="nowrap">'.form::checkbox(array('entries[]'),$categories->cat_id,'','','',false).'</td>'.
			'<td class="maximal"><a href="plugin.php?p=rateIt&amp;t=post&amp;cat_id='.$categories->cat_id.'">
				'.html::escapeHTML($categories->cat_title).'</a></td>'.
			'<td class="nowrap">'.$rs->note.'</td>'.
			'<td class="nowrap"><a title="'.__('Show rating details').'" href="plugin.php?p=rateIt&amp;part=detail&amp;type=category&amp;id='.$categories->cat_id.'">'.$rs->total.'</a></td>'.
			'<td class="nowrap">'.$rs->max.'</td>'.
			'<td class="nowrap">'.$rs->min.'</td>'.
			'<td class="nowrap">'.$categories->cat_id.'</td>'.
			'<td class="nowrap">'.$categories->level.'</td>'.
			'<td class="nowrap">'.$categories->nb_post.'</td>'.
			'</tr>';
		}

		if ($table=='')
		{
			echo '<p class="message">'.__('There is no category rating at this time').'</p>';
		}
		else
		{
			echo
			'<h3>'.__('List of all the categories having rating').'</h3>'.
			'<form action="plugin.php" method="post" id="form-categories">'.
			'<table class="clear"><tr>'.
			'<th colspan="2">'.__('Title').'</th>'.
			'<th>'.__('Note').'</th>'.
			'<th>'.__('Votes').'</th>'.
			'<th>'.__('Higher').'</th>'.
			'<th>'.__('Lower').'</th>'.
			'<th>'.__('Id').'</th>'.
			'<th>'.__('Level').'</th>'.
			'<th>'.__('Entries').'</th>'.
			'</tr>'.
			$table.
			'</table>'.

			'<div class="two-cols">'.
			'<p class="col checkboxes-helpers"></p>'.
			'<p class="col right">'.__('Selected categories action:').' '.
			form::combo(array('action'),array(__('delete rating') => 'rateit_cat_empty')).
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
		$w->rateit->setting('enable_cat',__('Enable vote for categories'),
			0,'check');
		$w->rateit->setting('title_cat',__('Title for categories:'),
			__('Rate this category'),'text');
	}

	public static function adminRateItWidgetRank($types)
	{
		$types[] = array(__('categories') => 'category');
	}
}