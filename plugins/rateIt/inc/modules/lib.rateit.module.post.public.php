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
class postRateItModulePublic
{
	public static function publicRateItPageAfterVote($core,$type,$id,$note,$voted)
	{
		if ($type != 'post') return;

		$post = $core->blog->getPosts(array('post_id'=>$id,'no_content'=>1));
		if ($post->post_id)
		{
			http::redirect($core->blog->url.$core->url->getBase('post').'/'.$post->post_url.($voted ? '#rateit' : ''));
			return;
		}
	}

	public static function publicRateItTplBlockRateIt($type,$attr,$content) {
		if ($type != '' && $type != 'post') return;
        //utiliser $core->getPostType
		return
		"if (\$_ctx->exists('posts')".
		" && \$_ctx->posts->post_type == 'post'".
		" && \$core->blog->settings->rateit->rateit_post_active) { \n".
		" \$rateit_params['type'] = 'post'; \n".
		" \$rateit_params['id'] = \$_ctx->posts->post_id; \n".
		"} \n";
	}

	public static function publicRateItTplValueRateItTitle($type,$attr)
	{
		return "if (\$_ctx->rateIt->type == 'post') { \$title = __('Rate this entry'); } \n";
	}

	public static function publicRateItWidgetVote($w,$_ctx)
	{
		global $core;

		if ($w->enable_post && 'post.html' == $_ctx->current_tpl
		&& $core->blog->settings->rateit->rateit_post_active
		&& (!$core->blog->settings->rateit->rateit_categorylimitposts
		 || ($core->blog->settings->rateit->rateit_categorylimitposts == $_ctx->posts->cat_id && !$core->blog->settings->rateit->rateit_categorylimitinvert
			 || $core->blog->settings->rateit->rateit_categorylimitposts != $_ctx->posts->cat_id && $core->blog->settings->rateit->rateit_categorylimitinvert )
			)
		) {
			$w->type = 'post';
			$w->id = $_ctx->posts->post_id;
			$w->title = $w->title_post;
		}
	}

	public static function publicRateItWidgetRank($w,$p,$_ctx)
	{
		if ($w->type != 'post') return;

		global $core;

		if (!$core->blog->settings->rateit->rateit_post_active) return;

		$p['columns'][] = $core->con->concat("'".$core->blog->url.$core->getPostPublicUrl('post','')."'",'P.post_url').' AS url';
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

		$p['sql'] .= " AND P.post_type='post' AND P.post_status = 1 AND P.post_password IS NULL ";

		if ($w->catlimit)
		{
			$p['sql'] .= " AND P.cat_id='".$w->catlimit."' ";
		}
	}
}

# DC public behaviors
class postRateItPublic extends dcUrlHandlers
{
	public static function publicEntryAfterContent($core,$_ctx)
	{
		if ($core->blog->settings->rateit->rateit_active
		 && $core->blog->settings->rateit->rateit_post_active
		 && $_ctx->exists('posts')
		 && $_ctx->posts->post_type == 'post'
		 && (
			 $core->blog->settings->rateit->rateit_poststpl && 'post.html' == $_ctx->current_tpl
		  || $core->blog->settings->rateit->rateit_homepoststpl && 'home.html' == $_ctx->current_tpl
		  || $core->blog->settings->rateit->rateit_tagpoststpl && 'tag.html' == $_ctx->current_tpl
		  || $core->blog->settings->rateit->rateit_categorypoststpl && 'category.html' == $_ctx->current_tpl
		 )
		 && (
			!$core->blog->settings->rateit->rateit_categorylimitposts
		  || (
				$core->blog->settings->rateit->rateit_categorylimitposts == $_ctx->posts->cat_id && !$core->blog->settings->rateit->rateit_categorylimitinvert
			 || $core->blog->settings->rateit->rateit_categorylimitposts != $_ctx->posts->cat_id && $core->blog->settings->rateit->rateit_categorylimitinvert
			)
		 )
		) {

			$GLOBALS['rateit_params']['type'] = 'post';
			$GLOBALS['rateit_params']['id'] = $_ctx->posts->post_id;

			self::serveDocumentOld('rateit.html');
		}
		else
		{
			return;
		}
	}
}
