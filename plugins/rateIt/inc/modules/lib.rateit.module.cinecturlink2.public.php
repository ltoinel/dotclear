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
class cinecturlink2RateItModulePublic
{
	public static function publicRateItPageAfterVote($core,$type,$id,$note,$voted)
	{
		if ($type == 'cinecturlink2')
		{
			http::redirect($core->blog->url.$core->url->getBase('cinecturlink2').'/detail/'.$id.($voted ? '#rateit' : ''));
		}
		return;
	}

	public static function publicRateItTplBlockRateIt($type,$attr,$content)
	{
		if ($type != '' && $type != 'cinecturlink2') return;

		return
		"if (\$_ctx->exists('cinecturlink') ".
		" && \$core->blog->settings->rateit->rateit_active ".
		" && \$core->blog->settings->rateit->rateit_cinecturlink2_active ".
		" && \$core->blog->settings->rateit->rateit_cinecturlink2_page) { \n".
		" \$rateit_params['type'] = 'cinecturlink2'; \n".
		" \$rateit_params['id'] = \$_ctx->c2_entries->link_id; \n".
		"} \n";
	}

	public static function publicRateItTplValueRateItTitle($type,$attr)
	{
		return "if (\$_ctx->rateIt->type == 'cinecturlink2') { \$title = __('Rate this'); } \n";
	}

	public static function publicRateItWidgetRank($w,$p,$_ctx)
	{
		if ($w->type != 'cinecturlink2') return;

		global $core;

		if (!$core->blog->settings->rateit->rateit_cinecturlink2_active) return;

		$p['columns'][] = $core->con->concat("'".$core->blog->url.$core->url->getBase('cinecturlink2')."/'",'C.link_id').' AS url';
		$p['columns'][] = 'C.link_title AS title';
		$p['columns'][] = 'C.link_id AS id';

		$p['groups'][] = 'C.link_id';
		$p['groups'][] = 'C.link_title';

		if ($core->con->driver() == 'mysqli')
		{
			$p['from'] .= ' INNER JOIN '.$core->prefix.'cinecturlink2 C ON CAST(C.link_id as char)=RI.rateit_id ';
		}
		else
		{
			$p['from'] .= ' INNER JOIN '.$core->prefix.'cinecturlink2 C ON CAST(C.link_id as int)=CAST(RI.rateit_id as int) ';
		}
	}
}

# DC public behaviors
class cinecturlink2RateItPublic extends dcUrlHandlers
{
	public static function publicC2EntryAfterContent($core,$_ctx)
	{
		if ($core->blog->settings->rateit->rateit_active
		 && $core->blog->settings->rateit->rateit_cinecturlink2_active
		 && $core->blog->settings->rateit->rateit_cinecturlink2_page
		 && $_ctx->exists('cinecturlink')
		) {
			$GLOBALS['rateit_params']['type'] = 'cinecturlink2';
			$GLOBALS['rateit_params']['id'] = $_ctx->c2_entries->link_id;

			echo $core->tpl->getData('rateit.html');
		}
		else
		{
			return;
		}
	}

	# vote on cinecturlink2 widget
	public static function cinecturlink2WidgetLinks($id)
	{
		global $core;

		if (!$core->blog->settings->rateit->rateit_active
		 || !$core->blog->settings->rateit->rateit_cinecturlink2_active
		 || !$core->blog->settings->rateit->rateit_cinecturlink2_widget) return;

		$style = $core->blog->settings->rateit->rateit_rating_style;
		$type = 'cinecturlink2';
		$rateit_voted = $core->rateIt->voted($type,$id);
		$rs = $core->rateIt->get($type,$id);
		$voted = $core->rateIt->voted($type,$id);

		$res = '<div class="rateit '.$style.'">';

		if ($style == 'classic')
		{
			$res .= '<p>'.rateItContext::value('fullnote',$type,$id,$rs->note.'/'.$rs->quotient).'</p>';
		}
		elseif ($style == 'twin')
		{
			$res .= '<p>'.rateItContext::value('mincount',$type,$id,$rs->mincount).'</p>';
		}

		$res .= rateItContext::linker($voted,$type,$id,$rs->note,$rs->quotient);

		if (in_array($style,array('twin','simple')))
		{
			$res .= '<p>'.rateItContext::value('mincount',$type,$id,$rs->mincount).'</p>';
		}

		$res .= '</div><p>&nbsp;</p>';

		return $res;
	}
}