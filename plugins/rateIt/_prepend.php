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

if (!defined('DC_RC_PATH')){return;}

global $__autoload, $core;

# Namespace for settings
$core->blog->settings->addNamespace('rateit');

# Class
$__autoload['rateIt'] = dirname(__FILE__).'/inc/class.rateit.php';
$__autoload['rateItRest'] = dirname(__FILE__).'/inc/class.rateit.rest.php';
$__autoload['rateItExtList'] = dirname(__FILE__).'/inc/lib.rateit.list.php';
$__autoload['rateItModule'] = dirname(__FILE__).'/inc/class.rateit.modules.php';
$__autoload['rateItContext'] = dirname(__FILE__).'/inc/lib.rateit.context.php';
$__autoload['rateItLibImagePath'] = dirname(__FILE__).'/inc/lib.image.path.php';

# Put rateIt in core (against multiple instance of rateIt)
try {
	$core->rateIt = new rateIt($core);
} catch (Exception $e) {
	return null;
}

# Public urls
$core->url->register('rateItmodule','rateit','^rateit/(.+)$',array('urlRateIt','files'));
$core->url->register('rateItpostform','rateitpost','^rateitpost/(.+)$',array('urlRateIt','postform'));
$core->url->register('rateItservice','rateitservice','^rateitservice/$',array('urlRateIt','service'));
//todo: public API

# Add rateIt report on plugin activityReport
if (defined('ACTIVITY_REPORT')) {
	require_once dirname(__FILE__).'/inc/lib.rateit.activityreport.php';
}

# Module "post"
$__autoload['postRateItModuleAdmin'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.post.admin.php';
$__autoload['postRateItModulePublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.post.public.php';
$__autoload['postRateItAdmin'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.post.admin.php';
$__autoload['postRateItPublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.post.public.php';

$core->addBehavior('addRateItModule','postRateItModule');
function postRateItModule($core,$modules) {
	$modules['post'] = __('Entries');

	$core->addBehavior('adminRateItModuleUpdate',array('postRateItModuleAdmin','adminRateItModuleUpdate'));
	$core->addBehavior('adminRateItModuleSettingsTab',array('postRateItModuleAdmin','adminRateItModuleSettingsTab'));
	$core->addBehavior('adminRateItModuleRecordsTab',array('postRateItModuleAdmin','adminRateItModuleRecordsTab'));
	$core->addBehavior('publicRateItPageAfterVote',array('postRateItModulePublic','publicRateItPageAfterVote'));
	$core->addBehavior('publicRateItTplBlockRateIt',array('postRateItModulePublic','publicRateItTplBlockRateIt'));
	$core->addBehavior('publicRateItTplValueRateItTitle',array('postRateItModulePublic','publicRateItTplValueRateItTitle'));
	$core->addBehavior('adminRateItWidgetVote',array('postRateItModuleAdmin','adminRateItWidgetVote'));
	$core->addBehavior('adminRateItWidgetRank',array('postRateItModuleAdmin','adminRateItWidgetRank'));
	$core->addBehavior('publicRateItWidgetVote',array('postRateItModulePublic','publicRateItWidgetVote'));
	$core->addBehavior('publicRateItWidgetRank',array('postRateItModulePublic','publicRateItWidgetRank'));
	if ($core->blog->settings->rateit->rateit_active) {
		$core->addBehavior('adminBeforePostDelete',array('postRateItAdmin','adminBeforePostDelete'));
		$core->addBehavior('adminPostsActionsCombo',array('postRateItAdmin','adminPostsActionsCombo'));
		$core->addBehavior('adminPostsActions',array('postRateItAdmin','adminPostsActions'));
		$core->addBehavior('adminPostsActionsContent',array('postRateItAdmin','adminPostsActionsContent'));
		$core->addBehavior('publicEntryAfterContent',array('postRateItPublic','publicEntryAfterContent'));
	}
}

# Module "comment"
//todo: admin behaviors (delete/edit item...)
$__autoload['commentRateItModuleAdmin'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.comment.admin.php';
$__autoload['commentRateItModulePublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.comment.public.php';
$__autoload['commentRateItPublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.comment.public.php';

$core->addBehavior('addRateItModule','commentRateItModule');
function commentRateItModule($core,$modules) {
	$modules['comment'] = __('Comments');

	$core->addBehavior('adminRateItModuleUpdate',array('commentRateItModuleAdmin','adminRateItModuleUpdate'));
	$core->addBehavior('adminRateItModuleSettingsTab',array('commentRateItModuleAdmin','adminRateItModuleSettingsTab'));
	$core->addBehavior('adminRateItModuleRecordsTab',array('commentRateItModuleAdmin','adminRateItModuleRecordsTab'));
	$core->addBehavior('publicRateItPageAfterVote',array('commentRateItModulePublic','publicRateItPageAfterVote'));
	$core->addBehavior('publicRateItTplBlockRateIt',array('commentRateItModulePublic','publicRateItTplBlockRateIt'));
	$core->addBehavior('publicRateItTplValueRateItTitle',array('commentRateItModulePublic','publicRateItTplValueRateItTitle'));
	$core->addBehavior('adminRateItWidgetRank',array('commentRateItModuleAdmin','adminRateItWidgetRank'));
	$core->addBehavior('publicRateItWidgetRank',array('commentRateItModulePublic','publicRateItWidgetRank'));
	if ($core->blog->settings->rateit->rateit_active) {
		$core->addBehavior('publicCommentAfterContent',array('commentRateItPublic','publicCommentAfterContent'));
	}
}

# Module "category"
//todo: admin behaviors (delete/edit item...)
$__autoload['categoryRateItModuleAdmin'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.category.admin.php';
$__autoload['categoryRateItModulePublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.category.public.php';

$core->addBehavior('addRateItModule','categoryRateItModule');
function categoryRateItModule($core,$modules) {
	$modules['category'] = __('Categories');

	$core->addBehavior('adminRateItModuleUpdate',array('categoryRateItModuleAdmin','adminRateItModuleUpdate'));
	$core->addBehavior('adminRateItModuleSettingsTab',array('categoryRateItModuleAdmin','adminRateItModuleSettingsTab'));
	$core->addBehavior('adminRateItModuleRecordsTab',array('categoryRateItModuleAdmin','adminRateItModuleRecordsTab'));
	$core->addBehavior('publicRateItPageAfterVote',array('categoryRateItModulePublic','publicRateItPageAfterVote'));
	$core->addBehavior('publicRateItTplBlockRateIt',array('categoryRateItModulePublic','publicRateItTplBlockRateIt'));
	$core->addBehavior('publicRateItTplValueRateItTitle',array('categoryRateItModulePublic','publicRateItTplValueRateItTitle'));
	$core->addBehavior('adminRateItWidgetVote',array('categoryRateItModuleAdmin','adminRateItWidgetVote'));
	$core->addBehavior('adminRateItWidgetRank',array('categoryRateItModuleAdmin','adminRateItWidgetRank'));
	$core->addBehavior('publicRateItWidgetVote',array('categoryRateItModulePublic','publicRateItWidgetVote'));
	$core->addBehavior('publicRateItWidgetRank',array('categoryRateItModulePublic','publicRateItWidgetRank'));
}

# Module "tag"
//todo: admin behaviors (delete/edit item...)
$__autoload['tagRateItModuleAdmin'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.tag.admin.php';
$__autoload['tagRateItModulePublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.tag.public.php';

$core->addBehavior('addRateItModule','tagRateItModule');
function tagRateItModule($core,$modules) {
	if (!$core->plugins->moduleExists('tags')) return;

	$modules['tag'] = __('Tags');

	$core->addBehavior('adminRateItModuleUpdate',array('tagRateItModuleAdmin','adminRateItModuleUpdate'));
	$core->addBehavior('adminRateItModuleSettingsTab',array('tagRateItModuleAdmin','adminRateItModuleSettingsTab'));
	$core->addBehavior('adminRateItModuleRecordsTab',array('tagRateItModuleAdmin','adminRateItModuleRecordsTab'));
	$core->addBehavior('publicRateItPageAfterVote',array('tagRateItModulePublic','publicRateItPageAfterVote'));
	$core->addBehavior('publicRateItTplBlockRateIt',array('tagRateItModulePublic','publicRateItTplBlockRateIt'));
	$core->addBehavior('publicRateItTplValueRateItTitle',array('tagRateItModulePublic','publicRateItTplValueRateItTitle'));
	$core->addBehavior('adminRateItWidgetVote',array('tagRateItModuleAdmin','adminRateItWidgetVote'));
	$core->addBehavior('adminRateItWidgetRank',array('tagRateItModuleAdmin','adminRateItWidgetRank'));
	$core->addBehavior('publicRateItWidgetVote',array('tagRateItModulePublic','publicRateItWidgetVote'));
	$core->addBehavior('publicRateItWidgetRank',array('tagRateItModulePublic','publicRateItWidgetRank'));
}

# Module "gallery"
//todo: admin behaviors (delete/edit item...)
$__autoload['galleryRateItModuleAdmin'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.gallery.admin.php';
$__autoload['galleryRateItModulePublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.gallery.public.php';
$__autoload['galleryRateItPublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.gallery.public.php';

$core->addBehavior('addRateItModule','galleryRateItModule');
function galleryRateItModule($core,$modules) {
	if (!$core->plugins->moduleExists('gallery')) return;

	$modules['gal'] = __('Galleries');
	$modules['galitem'] = __('Gallery items');

	$core->addBehavior('adminRateItModuleUpdate',array('galleryRateItModuleAdmin','adminRateItModuleUpdate'));
	$core->addBehavior('adminRateItModuleSettingsTab',array('galleryRateItModuleAdmin','adminRateItModuleSettingsTab'));
	$core->addBehavior('adminRateItModuleRecordsTab',array('galleryRateItModuleAdmin','adminRateItModuleRecordsTab'));
	$core->addBehavior('publicRateItPageAfterVote',array('galleryRateItModulePublic','publicRateItPageAfterVote'));
	$core->addBehavior('publicRateItTplBlockRateIt',array('galleryRateItModulePublic','publicRateItTplBlockRateIt'));
	$core->addBehavior('publicRateItTplValueRateItTitle',array('galleryRateItModulePublic','publicRateItTplValueRateItTitle'));
	$core->addBehavior('adminRateItWidgetVote',array('galleryRateItModuleAdmin','adminRateItWidgetVote'));
	$core->addBehavior('adminRateItWidgetRank',array('galleryRateItModuleAdmin','adminRateItWidgetRank'));
	$core->addBehavior('publicRateItWidgetVote',array('galleryRateItModulePublic','publicRateItWidgetVote'));
	$core->addBehavior('publicRateItWidgetRank',array('galleryRateItModulePublic','publicRateItWidgetRank'));
	if ($core->blog->settings->rateit->rateit_active) {
		$core->addBehavior('publicEntryAfterContent',array('galleryRateItPublic','publicEntryAfterContent'));
	}
}

# Module "cinecturlink2"
//todo: admin behaviors (delete/edit item...)
$__autoload['cinecturlink2RateItModuleAdmin'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.cinecturlink2.admin.php';
$__autoload['cinecturlink2RateItModulePublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.cinecturlink2.public.php';
$__autoload['cinecturlink2RateItPublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.cinecturlink2.public.php';
$__autoload['cinecturlink2RateItBackup'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.cinecturlink2.backup.php';

$core->addBehavior('addRateItModule','cinecturlink2RateItModule');
function cinecturlink2RateItModule($core,$modules) {
	if (!$core->plugins->moduleExists('cinecturlink2')) return;

	$modules['cinecturlink2'] = __('Cinecturlink');

	$core->addBehavior('adminRateItModuleUpdate',array('cinecturlink2RateItModuleAdmin','adminRateItModuleUpdate'));
	$core->addBehavior('adminRateItModuleSettingsTab',array('cinecturlink2RateItModuleAdmin','adminRateItModuleSettingsTab'));
	$core->addBehavior('adminRateItModuleRecordsTab',array('cinecturlink2RateItModuleAdmin','adminRateItModuleRecordsTab'));
	$core->addBehavior('publicRateItPageAfterVote',array('cinecturlink2RateItModulePublic','publicRateItPageAfterVote'));
	$core->addBehavior('publicRateItTplBlockRateIt',array('cinecturlink2RateItModulePublic','publicRateItTplBlockRateIt'));
	$core->addBehavior('publicRateItTplValueRateItTitle',array('cinecturlink2RateItModulePublic','publicRateItTplValueRateItTitle'));
	$core->addBehavior('adminRateItWidgetRank',array('cinecturlink2RateItModuleAdmin','adminRateItWidgetRank'));
	$core->addBehavior('publicRateItWidgetRank',array('cinecturlink2RateItModulePublic','publicRateItWidgetRank'));
	if ($core->blog->settings->rateit->rateit_active) {
		$core->addBehavior('publicC2EntryAfterContent',array('cinecturlink2RateItPublic','publicC2EntryAfterContent'));
		$core->addBehavior('cinecturlink2WidgetLinks',array('cinecturlink2RateItPublic','cinecturlink2WidgetLinks'));
	}
	$core->addBehavior('exportSingle',array('backupRateItCinecturlink2','exportSingle'));
	$core->addBehavior('importInit',array('backupRateItCinecturlink2','importInit'));
	$core->addBehavior('importSingle',array('backupRateItCinecturlink2','importSingle'));
}

# Module "eventhandler"
$__autoload['eventhandlerRateItModuleAdmin'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.eventhandler.admin.php';
$__autoload['eventhandlerRateItModulePublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.eventhandler.public.php';
$__autoload['eventhandlerRateItAdmin'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.eventhandler.admin.php';
$__autoload['eventhandlerRateItPublic'] = dirname(__FILE__).'/inc/modules/lib.rateit.module.eventhandler.public.php';

$core->addBehavior('addRateItModule','eventhandlerRateItModule');
function eventhandlerRateItModule($core,$modules) {
	$modules['eventhandler'] = __('Events');

	$core->addBehavior('adminRateItModuleUpdate',array('eventhandlerRateItModuleAdmin','adminRateItModuleUpdate'));
	$core->addBehavior('adminRateItModuleSettingsTab',array('eventhandlerRateItModuleAdmin','adminRateItModuleSettingsTab'));
	$core->addBehavior('adminRateItModuleRecordsTab',array('eventhandlerRateItModuleAdmin','adminRateItModuleRecordsTab'));
	$core->addBehavior('publicRateItPageAfterVote',array('eventhandlerRateItModulePublic','publicRateItPageAfterVote'));
	$core->addBehavior('publicRateItTplBlockRateIt',array('eventhandlerRateItModulePublic','publicRateItTplBlockRateIt'));
	$core->addBehavior('publicRateItTplValueRateItTitle',array('eventhandlerRateItModulePublic','publicRateItTplValueRateItTitle'));
	$core->addBehavior('adminRateItWidgetVote',array('eventhandlerRateItModuleAdmin','adminRateItWidgetVote'));
	$core->addBehavior('adminRateItWidgetRank',array('eventhandlerRateItModuleAdmin','adminRateItWidgetRank'));
	$core->addBehavior('publicRateItWidgetVote',array('eventhandlerRateItModulePublic','publicRateItWidgetVote'));
	$core->addBehavior('publicRateItWidgetRank',array('eventhandlerRateItModulePublic','publicRateItWidgetRank'));
	if ($core->blog->settings->rateit->rateit_active) {
		$core->addBehavior('adminBeforePostDelete',array('eventhandlerRateItAdmin','adminBeforePostDelete'));
		$core->addBehavior('adminPostsActionsCombo',array('eventhandlerRateItAdmin','adminPostsActionsCombo'));
		$core->addBehavior('adminPostsActions',array('eventhandlerRateItAdmin','adminPostsActions'));
		$core->addBehavior('adminPostsActionsContent',array('eventhandlerRateItAdmin','adminPostsActionsContent'));
		$core->addBehavior('publicEntryAfterContent',array('eventhandlerRateItPublic','publicEntryAfterContent'));
	}
}