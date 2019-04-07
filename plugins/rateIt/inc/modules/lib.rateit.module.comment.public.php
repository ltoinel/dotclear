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
class commentRateItModulePublic
{
	public static function publicRateItPageAfterVote($core,$type,$id,$note,$voted)
	{
		if ($type != 'comment') return;

		$comment = $core->blog->getComments($id);
		if ($comment->comment_id) {
			http::redirect($core->blog->url.$core->url->getBase('post').'/'.$post->post_url.($voted ? '#rateit' : ''));
			return;
		}
	}

	public static function publicRateItTplBlockRateIt($type,$attr,$content)
	{
		if ($type != '' && $type != 'comment') return;

		return
		"if (\$_ctx->exists('comments')".
		" && \$core->blog->settings->rateit->rateit_comment_active) { \n".
		" \$rateit_params['type'] = 'comment'; \n".
		" \$rateit_params['id'] = \$_ctx->comments->comment_id; \n".
		"} \n";
	}

	public static function publicRateItTplValueRateItTitle($type,$attr)
	{
		return "if (\$_ctx->rateIt->type == 'comment') { \$title = __('Rate this comment'); } \n";
	}

	public static function publicRateItWidgetRank($w,$p,$_ctx)
	{
		if ($w->type != 'comment') return;

		global $core;

		if (!$core->blog->settings->rateit->rateit_comment_active) return;

		$p['columns'][] = $core->con->concat("'".$core->blog->url.$core->getPostPublicUrl('post','')."'",'P.post_url').' AS url';
		$p['columns'][] = 'C.comment_author AS title';
		$p['columns'][] = 'C.comment_id AS id';

		$p['groups'][] = 'C.comment_id';
		$p['groups'][] = 'C.comment_author';
		$p['groups'][] = 'P.post_url';

		if ($core->con->driver() == 'mysqli')
		{
			$p['from'] .= ' INNER JOIN '.$core->prefix.'comment C ON CAST(C.comment_id as char)=RI.rateit_id ';
		}
		else
		{
			$p['from'] .= ' INNER JOIN '.$core->prefix.'comment C ON CAST(C.comment_id as int)=CAST(RI.rateit_id as int) ';
		}

		$p['from'] .= ' INNER JOIN '.$core->prefix.'post P ON C.comment_id = P.post_id ';

		if ($w->catlimit) {
			$p['sql'] .= " AND P.cat_id='".$w->catlimit."' ";
		}
	}
}

# DC public behaviors
class commentRateItPublic extends dcUrlHandlers
{
	public static function publicCommentAfterContent($core,$_ctx)
	{
		if (!$core->blog->settings->rateit->rateit_active
		 || !$core->blog->settings->rateit->rateit_comment_active
		 || !$core->blog->settings->rateit->rateit_commentstpl
		 || !$_ctx->exists('comments')) return;

		$GLOBALS['rateit_params']['type'] = 'comment';
		$GLOBALS['rateit_params']['id'] = $_ctx->comments->comment_id;

		echo $core->tpl->getData('rateit.html');
	}
}