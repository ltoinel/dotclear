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

$new_version = $core->plugins->moduleInfo('rateIt','version');
$old_version = $core->getVersion('rateIt');
if (version_compare($old_version,$new_version,'>=')) return;

try {
	# Database
	$ts = new dbStruct($core->con,$core->prefix);
	$ts->rateit
		->blog_id ('varchar',32,false)
		->rateit_id ('varchar',192,false)
		->rateit_type('varchar',16,false)
		->rateit_note ('float',0,false)
		->rateit_quotient ('float',0,false)
		->rateit_ip ('varchar',48,false)
		->rateit_time ('timestamp',0,false,'now()')
		->primary('pk_rateit','blog_id','rateit_type','rateit_id','rateit_ip') //mysql error 1071 limit key to 768.
		->index('idx_rateit_blog_id','btree','blog_id')
		->index('idx_rateit_rateit_type','btree','rateit_type')
		->index('idx_rateit_rateit_id','btree','rateit_id')
		->index('idx_rateit_rateit_ip','btree','rateit_ip');

	$si = new dbStruct($core->con,$core->prefix);
	$changes = $si->synchronize($ts);

	# Settings
	$core->blog->settings->addNamespace('rateit');
	$s = $core->blog->settings->rateit;

	# Settings main
	$s->put('rateit_active',false,'boolean','rateit plugin enabled',false,true);
	$s->put('rateit_importexport_active',true,'boolean','rateit import/export enabled',false,true);
	$s->put('rateit_rating_style','classic','string','Style of rating',false,true);
	$s->put('rateit_quotient',5,'integer','rateit maximum note',false,true);
	$s->put('rateit_digit',1,'integer','rateit note digits number',false,true);
	$s->put('rateit_msglike','I like','string','rateit message for the the Like button',false,true);
	$s->put('rateit_msgnotlike',"I don't like",'string',"rateit message for the Don't like button",false,true);
	$s->put('rateit_msgthanks','Thank you for having voted','string','rateit message when voted',false,true);
	$s->put('rateit_userident',0,'integer','rateit use cookie and/or ip',false,true);
	$s->put('rateit_dispubjs',false,'boolean','disable rateit public javascript',false,true);
	$s->put('rateit_dispubcss',false,'boolean','disable rateit public css',false,true);
	$s->put('rateit_firstimage_size','t','string','Size of entryfirstimage on widget',false,true);
	# Settings for posts
	$s->put('rateit_post_active',true,'boolean','Enabled post rating',false,true);
	$s->put('rateit_poststpl',false,'boolean','rateit template on post on post page',false,true);
	$s->put('rateit_homepoststpl',false,'boolean','rateit template on post on home page',false,true);
	$s->put('rateit_tagpoststpl',false,'boolean','rateit template on post on tag page',false,true);
	$s->put('rateit_categorypoststpl',false,'boolean','rateit template on post on category page',false,true);
	$s->put('rateit_categorylimitposts',false,'integer','rateit limit post vote only to one category',false,true);
	$s->put('rateit_categorylimitinvert',false,'boolean','rateit limit post vote only to other category',false,true);
	# Settings for comments
	$s->put('rateit_comment_active',false,'boolean','Enable comments rating',false,true);
	$s->put('rateit_commentstpl',true,'boolean','Use comments behavior',false,true);
	# Settings for categories
	$s->put('rateit_category_active',false,'boolean','rateit category addon enabled',false,true);
	# Settings for tags
	$s->put('rateit_tag_active',false,'boolean','rateit tag addon enabled',false,true);
	# Settings for galleries
	$s->put('rateit_gal_active',false,'boolean','rateit addon gallery enabled',false,true);
	$s->put('rateit_galitem_active',false,'boolean','rateit addon gallery item enabled',false,true);
	$s->put('rateit_galtpl',true,'boolean','rateit template galleries page',false,true);
	$s->put('rateit_galitemtpl',true,'boolean','rateit template gallery items page',false,true);
	# Settings for cinecturlink2
	$s->put('rateit_cinecturlink2_active',false,'boolean','Enabled cinecturlink2 rating',false,true);
	$s->put('rateit_cinecturlink2_widget',false,'boolean','Enabled rating on cinecturlink2 widget',false,true);
	$s->put('rateit_cinecturlink2_page',false,'boolean','Enabled rating on cinecturlink2 page',false,true);
	# Settings for eventHandler
	$s->put('rateit_eventhandler_active',true,'boolean','Enabled events rating',false,true);
	$s->put('rateit_eventsinglestpl',false,'boolean','rateit template on single event page',false,true);
	$s->put('rateit_eventslisttpl',false,'boolean','rateit template on events list page',false,true);

	# Version
	$core->setVersion('rateIt',$new_version);

	return true;
} catch (Exception $e) {
	$core->error->add($e->getMessage());
}
return false;