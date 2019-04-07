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
class galleryRateItModulePublic
{
	public static function publicRateItPageAfterVote($core,$type,$id,$note,$voted)
	{
		if ($type == 'gal')
		{
			$gal = $core->blog->getPost(array('post_id'=>$id,'no_content'=>true));
			if ($gal->cat_id)
			{
				http::redirect($core->blog->url.$core->url->getBase('galleries').'/'.$gal->post_url.($voted ? '#rateit' : ''));
				return;
			}
		}

		if ($type == 'galitem')
		{
			$gal = $core->blog->getPost(array('post_id'=>$id,'no_content'=>true));
			if ($gal->cat_id)
			{
				http::redirect($core->blog->url.$core->url->getBase('gal').'/'.$gal->post_url.($voted ? '#rateit' : ''));
				return;
			}
		}
	}

	public static function publicRateItTplBlockRateIt($type,$attr,$content)
	{
		if ($type != '' && !in_array($type,array('gal','galitem'))) return;

		return
		"if (\$_ctx->exists('posts') ".
		" && \$_ctx->posts->post_type == '".$type."' ".
		" && \$core->blog->settings->rateit->rateit_".$type."_active ".
		" && \$core->blog->settings->rateit->rateit_".$type."tpl) { \n".
		" \$rateit_params['type'] = '".$type."'; \n".
		" \$rateit_params['id'] = \$_ctx->posts->post_id; \n".
		"} \n".
		"elseif (\$_ctx->exists('posts') ".
		" && \$_ctx->posts->post_type == 'gal' ".
		" && \$core->blog->settings->rateit->rateit_gal_active ".
		" && \$core->blog->settings->rateit->rateit_galtpl) { \n".
		" \$rateit_params['type'] = 'gal'; \n".
		" \$rateit_params['id'] = \$_ctx->posts->post_id; \n".
		"} \n".
		"elseif (\$_ctx->exists('posts') ".
		" && \$_ctx->posts->post_type == 'galitem' ".
		" && \$core->blog->settings->rateit->rateit_galitem_active ".
		" && \$core->blog->settings->rateit->rateit_galitemtpl) { \n".
		" \$rateit_params['type'] = 'galitem'; \n".
		" \$rateit_params['id'] = \$_ctx->posts->post_id; \n".
		"} \n";
	}

	public static function publicRateItTplValueRateItTitle($type,$attr)
	{
		return
		"if (\$_ctx->rateIt->type == 'gal') { \$title = __('Rate this gallery'); } ".
		"elseif (\$_ctx->rateIt->type == 'galitem') { \$title = __('Rate this gallery item'); } \n";
	}

	public static function publicRateItWidgetVote($w,$_ctx)
	{
		global $core;

		if ($w->enable_gal && strstr($_ctx->current_tpl,'gallery.html')
		 && $core->blog->settings->rateit->rateit_gal_active)
		{
			$w->type = 'gal';
			$w->id = $_ctx->posts->post_id;
			$w->title = $w->title_gal;
		}

		if ($w->enable_galitem && strstr($_ctx->current_tpl,'image.html')
		 && $core->blog->settings->rateit->rateit_galitem_active)
		{
			$w->type = 'galitem';
			$w->id = $_ctx->posts->post_id;
			$w->title = $w->title_galitem;
		}
	}

	public static function publicRateItWidgetRank($w,$p,$_ctx)
	{
		global $core;

		if ($w->type == 'gal')
		{
			if (!$core->blog->settings->rateit->rateit_gal_active) return;

			$p['columns'][] = $core->con->concat("'".$core->blog->url.$core->url->getBase('gal')."/'",'P.post_url').' AS url';
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

			$p['sql'] .= "AND post_type='gal' ";

			if ($w->catlimit)
			{
				$p['sql'] .= " AND P.cat_id='".$w->catlimit."' ";
			}
		}

		if ($w->type == 'galitem')
		{
			if (!$core->blog->settings->rateit->rateit_galitem_active) return;

			$p['columns'][] = $core->con->concat("'".$core->blog->url.$core->url->getBase('galitem')."/'",'P.post_url').' AS url';
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

			$p['sql'] .= "AND post_type='galitem' ";

			if ($w->catlimit)
			{
				$p['sql'] .= " AND P.cat_id='".$w->catlimit."' ";
			}
		}
	}
}

# DC public behaviors
class galleryRateItPublic extends dcUrlHandlers
{
	public static function publicEntryAfterContent($core,$_ctx)
	{
		if (!$core->plugins->moduleExists('gallery')
		 || !$_ctx->exists('posts')) return;

		if ($_ctx->posts->post_type == 'gal'
		 && $core->blog->settings->rateit->rateit_gal_active
		 && $core->blog->settings->rateit->rateit_galtpl
		 || $_ctx->posts->post_type == 'galitem'
		 && $core->blog->settings->rateit->rateit_galitem_active
		 && $core->blog->settings->rateit->rateit_galitemtpl) {

			$GLOBALS['rateit_params']['type'] = $_ctx->posts->post_type;
			$GLOBALS['rateit_params']['id'] = $_ctx->posts->post_id;

			echo $core->tpl->getData('rateit.html');
		}
		return;
	}
}
