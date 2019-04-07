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

if (!defined('DC_CONTEXT_ADMIN')){return;}

$_menu['Blog']->addItem(
	__('Rate it'),
	'plugin.php?p=rateIt','index.php?pf=rateIt/icon.png',
	preg_match('/plugin.php\?p=rateIt(&.*)?$/',$_SERVER['REQUEST_URI']),
	$core->auth->check('usage,contentadmin',$core->blog->id)
);

$core->rateIt->loadModules();

require dirname(__FILE__).'/_widgets.php';

if ($core->blog->settings->rateit->rateit_importexport_active) {
	$core->addBehavior('exportFull',array('rateitBackup','exportFull'));
	$core->addBehavior('exportSingle',array('rateitBackup','exportSingle'));
	$core->addBehavior('importInit',array('rateitBackup','importInit'));
	$core->addBehavior('importSingle',array('rateitBackup','importSingle'));
	$core->addBehavior('importFull',array('rateitBackup','importFull'));
}

// backup is not complete (missing cinecturlink, comment, ...)
class rateItBackup
{
	public static function exportSingle($core,$exp,$blog_id) {
		$exp->export('rateit',
                     'SELECT blog_id, rateit_id, rateit_type, rateit_note, rateit_quotient, rateit_ip, rateit_time '.
                     'FROM '.$core->prefix.'rateit '.
                     "WHERE blog_id = '".$blog_id."'"
		);
	}

	public static function exportFull($core,$exp) {
		$exp->exportTable('rateit');
	}

	public static function importInit($bk,$core) {
		$bk->cur_rateit = $core->con->openCursor($core->prefix.'rateit');
	}

	public static function importSingle($line,$bk,$core) {
		if ($line->__name != 'rateit') {
            return;
        }

		if ($line->rateit_type == 'post' && isset($bk->old_ids['post'][(integer) $line->rateit_id])) {
			$line->rateit_id = $bk->old_ids['post'][(integer) $line->rateit_id];
		} elseif ($line->rateit_type == 'comment') {
			# Can't retreive old/new comment_id
			# See: http://dev.dotclear.org/2.0/ticket/789#comment:1
			return;
		} elseif ($line->rateit_type == 'category' && isset($bk->old_ids['category'][(integer) $line->rateit_id])) {
			$line->rateit_id = $bk->old_ids['category'][(integer) $line->rateit_id];
		} elseif ($line->rateit_type == 'gal' && isset($bk->old_ids['post'][(integer) $line->rateit_id])) {
			$line->rateit_id = $bk->old_ids['post'][(integer) $line->rateit_id];
		} elseif ($line->rateit_type == 'galitem' && isset($bk->old_ids['post'][(integer) $line->rateit_id])) {
			$line->rateit_id = $bk->old_ids['post'][(integer) $line->rateit_id];
		} elseif ($line->rateit_type == 'tag') {
			$line->rateit_id = (string) $line->rateit_id;
		} else {
			return;
		}
		$bk->cur_rateit->clean();
		$bk->cur_rateit->blog_id   = (string) $core->blog_id;
		$bk->cur_rateit->rateit_id   = (string) $line->rateit_id;
		$bk->cur_rateit->rateit_type   = (string) $line->rateit_type;
		$bk->cur_rateit->rateit_note   = (integer) $line->rateit_note;
		$bk->cur_rateit->rateit_quotient   = (integer) $line->rateit_quotient;
		$bk->cur_rateit->rateit_ip   = (string) $line->rateit_ip;
		$bk->cur_rateit->rateit_time   = (string) $line->rateit_time;
		$bk->cur_rateit->insert();
	}

	public static function importFull($line,$bk,$core) {
		if ($line->__name == 'rateit') {
			$bk->cur_rateit->clean();
			$bk->cur_rateit->blog_id   = (string) $line->blog_id;
			$bk->cur_rateit->rateit_id   = (string) $line->rateit_id;
			$bk->cur_rateit->rateit_type   = (string) $line->rateit_type;
			$bk->cur_rateit->rateit_note   = (integer) $line->rateit_note;
			$bk->cur_rateit->rateit_quotient   = (integer) $line->rateit_quotient;
			$bk->cur_rateit->rateit_ip   = (string) $line->rateit_ip;
			$bk->cur_rateit->rateit_time   = (string) $line->rateit_time;
			$bk->cur_rateit->insert();
		}
	}
}

$core->addBehavior('adminDashboardFavorites','rateItDashboardFavorites');

function rateItDashboardFavorites($core,$favs)
{
	$favs->register('rateIt', array(
		'title' => __('Rate it'),
		'url' => 'plugin.php?p=rateIt',
		'small-icon' => 'index.php?pf=rateIt/icon.png',
		'large-icon' => 'index.php?pf=rateIt/icon-b.png',
		'permissions' => 'admin'
	));
}