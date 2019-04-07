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
class commentRateItModuleAdmin
{
	public static function adminRateItModuleUpdate($core,$type,$action,$page_url,$hidden_fields)
	{
		if ($type != 'comment') return;

		if ($action == 'save_module_comment')
		{
			$core->blog->settings->rateit->put('rateit_comment_active',!empty($_POST['rateit_comment_active']),'boolean','Enable comments rating',true,false);
			$core->blog->settings->rateit->put('rateit_commentstpl',!empty($_POST['rateit_commentstpl']),'boolean','Use comments behavior',true,false);

			$core->blog->triggerBlog();
			return 'save_setting';
		}

		if ($action == 'rateit_comment_empty' && isset($_POST['entries']))
		{
			foreach($_POST['entries'] as $comment_id)
			{
				$core->rateIt->del('comment',$comment_id);
			}

			$core->blog->triggerBlog();
			return 'del_records';
		}
		return;
	}

	public static function adminRateItModuleSettingsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'comment') return;

		echo
		'<form method="post" action="'.$page_url.'">'.
		'<p><label class="classic">'.
		form::checkbox(array('rateit_comment_active'),1,$core->blog->settings->rateit->rateit_comment_active).
		__('Enable comments rating').'</label></p>'.
		'<p><label class="classic">'.
		form::checkbox(array('rateit_commentstpl'),1,$core->blog->settings->rateit->rateit_commentstpl).
		__('Include on comments').'</label></p>'.
		'<p class="form-note">'.__('To use this option you must have behavior "publicCommentAfterContent" in your theme').'</p>'.

		'<p><input type="submit" name="save" value="'.__('Save').'" />'.
		$hidden_fields.
		form::hidden(array('action'),'save_module_comment').
		'</p></form>';

		return 1;
	}

	public static function adminRateItModuleRecordsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'comment') return;

		try
		{
			$comments = $core->blog->getComments(array('post_type'=>'post'));
		}
		catch (Exception $e)
		{
			$core->error->add($e->getMessage());
		}

		$table = '';
		while ($comments->fetch())
		{
			$rs = $core->rateIt->get('comment',$comments->comment_id);
			if (!$rs->total) continue;
			$table .=
			'<tr class="line">'.
			'<td class="nowrap">'.form::checkbox(array('entries[]'),$comments->comment_id,'','','',false).'</td>'.
			'<td class="maximal"><a href="post.php?id='.$comments->post_id.'">
				'.html::escapeHTML($comments->post_title).'</a></td>'.
			'<td class="nowrap">'.$rs->note.'</td>'.
			'<td class="nowrap"><a title="'.__('Show rating details').'" href="plugin.php?p=rateIt&amp;part=detail&amp;type=comment&amp;id='.$comments->comment_id.'">'.$rs->total.'</a></td>'.
			'<td class="nowrap">'.$rs->max.'</td>'.
			'<td class="nowrap">'.$rs->min.'</td>'.
			'<td class="nowrap">'.$comments->comment_id.'</td>'.
			'<td class="nowrap">'.$comments->comment_author.'</td>'.
			'<td class="nowrap">'.dt::dt2str(__('%Y-%m-%d %H:%M'),$comments->comment_dt,$core->auth->getInfo('user_tz')).'</td>'.
			'</tr>';
		}

		if ($table=='')
		{
			echo '<p class="message">'.__('There is no comment rating at this time').'</p>';
		}
		else
		{
			echo
			'<h3>'.__('List of all the comments having rating').'</h3>'.
			'<form method="post" action="'.$page_url.'">'.
			'<table class="clear"><tr>'.
			'<th colspan="2">'.__('Title').'</th>'.
			'<th>'.__('Note').'</th>'.
			'<th>'.__('Votes').'</th>'.
			'<th>'.__('Higher').'</th>'.
			'<th>'.__('Lower').'</th>'.
			'<th>'.__('Id').'</th>'.
			'<th>'.__('Author').'</th>'.
			'<th>'.__('Date').'</th>'.
			'</tr>'.
			$table.
			'</table>'.

			'<div class="two-cols">'.
			'<p class="col checkboxes-helpers"></p>'.
			'<p class="col right">'.__('Selected comments action:').' '.
			form::combo(array('action'),array(__('delete rating') => 'rateit_comment_empty')).
			'<input type="submit" name="save" value="'.__('ok').'" />'.
			$hidden_fields.
			'</p></div>'.
			'</form>';
		}

		return 1;
	}

	public static function adminRateItWidgetRank($types)
	{
		$types[] = array(__('comments') => 'comment');
	}
}

# DC admin behaviors
//to do
