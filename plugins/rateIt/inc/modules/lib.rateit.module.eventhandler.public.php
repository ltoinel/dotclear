<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of rateIt, a plugin for Dotclear 2.
#
# Copyright(c) 2014-2016 Nicolas Roudaire <nikrou77@gmail.com> http://www.nikrou.net
#
# Copyright (c) 2009-2010 JC Denis and contributors
# jcdenis@gdwd.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

# rateIt public behaviors
class eventhandlerRateItModulePublic
{
	public static function publicRateItPageAfterVote($core,$type,$id,$note,$voted)
	{
		if ($type != 'eventhandler') return;

		$post = $core->blog->getPosts(array('post_type'=>'eventhandler','post_id'=>$id,'no_content'=>1));
		if ($post->post_id)
		{
			http::redirect($core->blog->url.$core->url->getBase('eventhandler_single').'/'.$post->post_url.($voted ? '#rateit' : ''));
			return;
		}
	}

	public static function publicRateItTplBlockRateIt($type,$attr,$content)
	{
		if ($type != '' && $type != 'eventhandler') return;
//utiliser $core->getPostType
		return
		"if (\$_ctx->exists('posts')".
		" && \$_ctx->posts->post_type == 'eventhandler'".
		" && \$core->blog->settings->rateit->rateit_eventhandler_active) { \n".
		" \$rateit_params['type'] = 'eventhandler'; \n".
		" \$rateit_params['id'] = \$_ctx->posts->post_id; \n".
		"} \n";
	}

	public static function publicRateItTplValueRateItTitle($type,$attr)
	{
		return "if (\$_ctx->rateIt->type == 'eventhandler') { \$title = __('Rate this event'); } \n";
	}

	public static function publicRateItWidgetVote($w,$_ctx)
	{
		global $core;

		if ($w->enable_eventhandler
		&& $core->blog->settings->rateit->rateit_eventhandler_active
		&& 'eventhandler-single.html' == $_ctx->current_tpl) {
			$w->type = 'eventhandler';
			$w->id = $_ctx->posts->post_id;
			$w->title = $w->title_eventhandler;
		}
	}

	public static function publicRateItWidgetRank($w,$p,$_ctx)
	{
		if ($w->type != 'eventhandler') return;

		global $core;

		if (!$core->blog->settings->rateit->rateit_eventhandler_active) return;

		$p['columns'][] = $core->con->concat("'".$core->blog->url.$core->getPostPublicUrl('eventhandler','')."'",'P.post_url').' AS url';
		$p['columns'][] = 'P.post_title AS title';
		$p['columns'][] = 'P.post_id AS id';

		$p['groups'][] = 'P.post_url';
		$p['groups'][] = 'P.post_title';
		$p['groups'][] = 'P.post_id';

		if ($core->con->driver() == 'mysqli')
		{
			$p['from'] .= ' INNER JOIN '.$core->prefix.'post P ON CAST(P.post_id as char)=RI.rateit_id ';
		}
		else
		{
			$p['from'] .= ' INNER JOIN '.$core->prefix.'post P ON CAST(P.post_id as int)=CAST(RI.rateit_id as int) ';
		}

		$p['sql'] .= " AND P.post_type='eventhandler' AND P.post_status = 1 AND P.post_password IS NULL ";
	}
}

# DC public behaviors
class eventhandlerRateItPublic extends dcUrlHandlers
{
	public static function publicEntryAfterContent($core,$_ctx)
	{
		if ($core->blog->settings->rateit->rateit_active
		 && $core->blog->settings->rateit->rateit_eventhandler_active
		 && $_ctx->exists('posts')
		 && $_ctx->posts->post_type == 'eventhandler'
		 && (
			 $core->blog->settings->rateit->rateit_eventsingletpl && 'eventhandler-single.html' == $_ctx->current_tpl
		  || $core->blog->settings->rateit->rateit_eventslisttpl && 'eventhandler-list.html' == $_ctx->current_tpl
		 )
		) {
			$GLOBALS['rateit_params']['type'] = 'eventhandler';
			$GLOBALS['rateit_params']['id'] = $_ctx->posts->post_id;

			echo $core->tpl->getData('rateit.html');
		}
		else
		{
			return;
		}
	}
}