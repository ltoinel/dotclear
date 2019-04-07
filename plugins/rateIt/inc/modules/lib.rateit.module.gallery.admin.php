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
class galleryRateItModuleAdmin
{
	public static function adminRateItModuleUpdate($core,$type,$action,$page_url,$hidden_fields)
	{
		if ($type != 'gal' || $type != 'galitem') return;

		if ($action == 'save_module_gal')
		{
			$core->blog->settings->rateit->put('rateit_gal_active',!empty($_POST['rateit_gal_active']),'boolean','rateit addon gallery enabled',true,false);
			$core->blog->settings->rateit->put('rateit_galtpl',!empty($_POST['rateit_galtpl']),'boolean','rateit template galleries page',true,false);

			$core->blog->triggerBlog();
			return 'save_setting';
		}

		if ($action == 'save_moule_galitem')
		{
			$core->blog->settings->rateit->put('rateit_galitem_active',!empty($_POST['rateit_galitem_active']),'boolean','rateit addon gallery item enabled',true,false);
			$core->blog->settings->rateit->put('rateit_galitemtpl',!empty($_POST['rateit_galitemtpl']),'boolean','rateit template gallery items page',true,false);

			$core->blog->triggerBlog();
			return 'save_setting';
		}

		if ($action == 'rateit_gal_empty' && isset($_POST['entries']))
		{
			foreach($_POST['entries'] as $gal_id)
			{
				$core->rateIt->del('gal',$gal_id);
			}

			$core->blog->triggerBlog();
			return 'del_records';
		}

		if ($action == 'rateit_galitem_empty' && isset($_POST['entries']))
		{
			foreach($_POST['entries'] as $galitem_id)
			{
				$core->rateIt->del('galitem',$galitem_id);
			}

			$core->blog->triggerBlog();
			return 'del_records';
		}

		return ;
	}

	public static function adminRateItModuleSettingsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'gal' || $type != 'galitem') return;

		if ($type == 'gal')
		{
			echo
			'<form method="post" action="'.$page_url.'">'.
			'<p><label class="classic">'.
			form::checkbox(array('rateit_gal_active'),1,$core->blog->settings->rateit->rateit_gal_active).
			__('Enable galleries rating').'</label></p>'.
			'<p><label class="classic">'.
			form::checkbox(array('rateit_galtpl'),1,$core->blog->settings->rateit->rateit_galtpl).
			__('Include on galleries pages').' *</label></p>'.

			'<p><input type="submit" name="save" value="'.__('save').'" />'.
			$hidden_fields.
			form::hidden(array('action'),'save_module_gal').
			'</p>'.
			'</form>'.
			'<p class="form-note">* '.__('To use this option you must have behavior "publicEntryAfterContent" in your theme').'</p>';

			return 1;
		}

		if ($type == 'galitem')
		{
			echo
			'<form method="post" action="'.$page_url.'">'.
			'<p><label class="classic">'.
			form::checkbox(array('rateit_galitem_active'),1,$core->blog->settings->rateit->rateit_galitem_active).
			__('Enable galleries rating').'</label></p>'.
			'<p><label class="classic">'.
			form::checkbox(array('rateit_galitemtpl'),1,$core->blog->settings->rateit->rateit_galitemtpl).
			__('Include on galleries pages').' *</label></p>'.

			'<p><input type="submit" name="save" value="'.__('save').'" />'.
			$hidden_fields.
			form::hidden(array('action'),'save_module_galitem').
			'</p>'.
			'</form>'.
			'<p class="form-note">* '.__('To use this option you must have behavior "publicEntryAfterContent" in your theme').'</p>';

			return 1;
		}
	}

	public static function adminRateItModuleRecordsTab($core,$type,$page_url,$hidden_fields)
	{
		if ($type != 'gal' || $type != 'galitem') return;

		if ($type == 'gal')
		{
			try
			{
				$galObject = new dcGallery($core);
				$galleries = $galObject->getGalleries();
			}
			catch (Exception $e)
			{
				$core->error->add($e->getMessage());
			}

			$table = '';
			while ($galleries->fetch())
			{
				$rs = $core->rateIt->get('gal',$galleries->post_id);
				if (!$rs->total) continue;
				$table .=
				'<tr class="line">'.
				'<td class="nowrap">'.form::checkbox(array('entries[]'),$galleries->post_id,'','','',false).'</td>'.
				'<td class="maximal"><a href="plugin.php?p=gallery&amp;m=gal&amp;id='.$galleries->post_id.'">
					'.html::escapeHTML($galleries->post_title).'</a></td>'.
				'<td class="nowrap"><a title="'.__('Show rating details').'" href="plugin.php?p=rateIt&amp;part=detail&amp;type=gal&amp;id='.$galleries->post_id.'">'.$rs->total.'</a></td>'.
				'<td class="nowrap">'.$rs->note.'</td>'.
				'<td class="nowrap">'.$rs->max.'</td>'.
				'<td class="nowrap">'.$rs->min.'</td>'.
				'</tr>';
			}

			if ($table=='')
			{
				echo '<p class="message">'.__('There is no gallery rating at this time').'</p>';
			}
			else
			{
				echo
				'<h3>'.__('List of all the galleries having rating').'</h3>'.
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
				'<p class="col right">'.__('Selected galeries action:').' '.
				form::combo(array('action'),array(__('delete rating') => 'rateit_gal_empty')).
				'<input type="submit" name="save" value="'.__('ok').'" />'.
				$hidden_fields.
				'</p></div>'.
				'</form>';
			}

			return 1;
		}

		if ($type == 'galitem')
		{
			try
			{
				$galObject = new dcGallery($core);
				$galleries_items = $galObject->getGalItems();
			}
			catch (Exception $e)
			{
				$core->error->add($e->getMessage());
			}

			$table = '';
			while ($galleries_items->fetch())
			{
				$rs = $rateIt->get('galitem',$galleries_items->post_id);
				if (!$rs->total) continue;
				$table .=
				'<tr class="line">'.
				'<td class="nowrap">'.form::checkbox(array('entries[]'),$galleries_items->post_id,'','','',false).'</td>'.
				'<td class="maximal"><a href="plugin.php?p=gallery&amp;m=item&amp;id='.$galleries_items->post_id.'">
					'.html::escapeHTML($galleries_items->post_title).'</a></td>'.
				'<td class="nowrap"><a title="'.__('Show rating details').'" href="plugin.php?p=rateIt&amp;prat=detail&amp;type=galitem&amp;id='.$galleries_items->post_id.'">'.$rs->total.'</a></td>'.
				'<td class="nowrap">'.$rs->note.'</td>'.
				'<td class="nowrap">'.$rs->max.'</td>'.
				'<td class="nowrap">'.$rs->min.'</td>'.
				'</tr>';
			}

			if ($table=='')
			{
				echo '<p class="message">'.__('There is no gallery item rating at this time').'</p>';
			}
			else
			{
				echo
				'<p>'.__('This is a list of all the galleries items having rating').'</p>'.
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
				'<p class="col right">'.__('Selected galeries items action:').' '.
				form::combo(array('action'),array(__('delete rating') => 'rateit_galitem_empty')).
				'<input type="submit" name="save" value="'.__('ok').'" />'.
				$hidden_fields.
				'</p></div>'.
				'</form>';
			}
		}
	}

	public static function adminRateItWidgetVote($w)
	{
		$w->rateit->setting('enable_gal',__('Enable vote for galleries'),
			0,'check');
		$w->rateit->setting('title_gal',__('Title for galleries:'),
			__('Rate this gallery'),'text');
		$w->rateit->setting('enable_galitem',__('Enable vote for gallery items'),
			0,'check');
		$w->rateit->setting('title_galitem',__('Title for gallery items:'),
			__('Rate this gallery item'),'text');
	}

	public static function adminRateItWidgetRank($types)
	{
		$types[] = array(__('galleries') => 'gal');
		$types[] = array(__('galleries items') => 'galitem');
	}
}

# DC admin behaviors
//todo
