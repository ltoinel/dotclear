<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of rateIt, a plugin for Dotclear 2.
#
# Copyright(c) 2014 Nicolas Roudaire <nikrou77@gmail.com> http://www.nikrou.net
#
# Copyright (c) 2009-2010 JC Denis and contributors
# jcdenis@gdwd.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

# rateIt public behaviors
class tagRateItModulePublic
{
	public static function publicRateItPageAfterVote($core,$type,$id,$note,$voted)
	{
		if ($type != 'comment') return;

		$metas = $core->meta->getMetadata(array('meta_type'=>'tag','meta_id'=>$id));
		if ($metas->meta_id) {
			http::redirect($core->blog->url.$core->url->getBase('tag').'/'.$metas->meta_id.($voted ? '#rateit' : ''));
			return;
		}
	}

	public static function publicRateItTplBlockRateIt($type,$attr,$content)
	{
		if ($type != '' && $type != 'comment') return;

		return
		"if (\$_ctx->exists('meta')".
		" && \$_ctx->meta->meta_type = 'tag'".
		" && \$core->blog->settings->rateit->rateit_tag_active) { \n".
		" \$rateit_params['type'] = 'tag'; \n".
		" \$rateit_params['id'] = \$_ctx->meta->meta_id; \n".
		"} \n";
	}

	public static function publicRateItTplValueRateItTitle($type,$attr)
	{
		return "if (\$_ctx->rateIt->type == 'tag') { \$title = __('Rate this tag'); } \n";
	}

	public static function publicRateItWidgetVote($w,$_ctx)
	{
		global $core;

		if ($w->enable_tag && 'tag.html' == $_ctx->current_tpl
		 && $core->blog->settings->rateit->rateit_tag_active)
		{
			$w->type = 'tag';
			$w->id = $_ctx->meta->meta_id;
			$w->title = $w->title_tag;
		}
	}

	public static function publicRateItWidgetRank($w,$p,$_ctx)
	{
		if ($w->type != 'tag') return;

		global $core;

		if (!$core->blog->settings->rateit->rateit_tag_active) return;

		$p['columns'][] = $core->con->concat("'".$core->blog->url.$core->url->getBase('tag')."/'",'M.meta_id').' AS url';
		$p['columns'][] = 'M.meta_id AS title';
		$p['columns'][] = 'M.meta_id AS id';

		$p['groups'][] = 'M.meta_id';

		$p['from'] .= ' INNER JOIN '.$core->prefix.'meta M ON M.meta_id=RI.rateit_id ';
		$p['sql'] .= "AND M.meta_type='tag' ";

		if ($w->catlimit)
		{
			$p['from'] .= ' INNER JOIN '.$core->prefix.'post P ON M.post_id = P.post_id ';
			$p['sql'] .= " AND P.cat_id='".$w->catlimit."' ";
		}
	}
}
