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
class categoryRateItModulePublic
{
	public static function publicRateItPageAfterVote($core,$type,$id,$voted,$note)
	{
		if ($type != 'category') return;

		$cat = $core->blog->getCategory($id);
		if ($cat->cat_id)
		{
			http::redirect($core->blog->url.$core->url->getBase('category').'/'.$cat->cat_url.($voted ? '#rateit' : ''));
			return;
		}
	}

	public static function publicRateItTplBlockRateIt($type,$attr,$content)
	{
		if ($type != '' && $type != 'category') return;

		return
		"if (\$_ctx->exists('categories')".
		" && \$core->blog->settings->rateit->rateit_category_active) { \n".
		" \$rateit_params['type'] = 'category'; \n".
		" \$rateit_params['id'] = \$_ctx->categories->cat_id; \n".
		"} \n";
	}

	public static function publicRateItTplValueRateItTitle($type,$attr)
	{
		return "if (\$_ctx->rateIt->type == 'category') { \$title = __('Rate this category'); } \n";
	}

	public static function publicRateItWidgetVote($w,$_ctx)
	{
		global $core;

		if ($w->enable_cat && 'category.html' == $_ctx->current_tpl
		 && $core->blog->settings->rateit->rateit_category_active) {
			$w->type = 'category';
			$w->id = $_ctx->categories->cat_id;
			$w->title = $w->title_cat;
		}
	}

	public static function publicRateItWidgetRank($w,$p,$_ctx)
	{
		if ($w->type != 'category') return;

		global $core;

		if (!$core->blog->settings->rateit->rateit_category_active) return;

		$p['columns'][] = $core->con->concat("'".$core->blog->url.$core->url->getBase('category')."/'",'C.cat_url').' AS url';
		$p['columns'][] = 'C.cat_title AS title';
		$p['columns'][] = 'C.cat_id AS id';

		$p['groups'][] = 'C.cat_url';
		$p['groups'][] = 'C.cat_title';
		$p['groups'][] = 'C.cat_id';

		if ($core->con->driver() == 'mysqli')
		{
			$p['from'] .= ' INNER JOIN '.$core->prefix.'category C ON CAST(C.cat_id as char)=RI.rateit_id ';
		}
		else
		{
			$p['from'] .= ' INNER JOIN '.$core->prefix.'category C ON CAST(C.cat_id as int)=CAST(RI.rateit_id as int) ';
		}
/*
		if ($w->catlimit)
		{
			$p['sql'] .= " AND C.cat_id='".$w->catlimit."' ";
		}
//*/
	}
}
