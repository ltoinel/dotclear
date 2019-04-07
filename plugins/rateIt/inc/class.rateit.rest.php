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

if (!defined('DC_RC_PATH')){return;}

class rateItRest
{
	public static function vote($core,$get,$post)
	{
		$type = isset($post['voteType']) ? $post['voteType'] : null;
		$id = isset($post['voteId']) ? $post['voteId'] : null;
		$note = isset($post['voteNote']) ? $post['voteNote'] : null;

		$rsp = new xmlTag();

		if (!$core->blog->settings->rateit->rateit_active)
		{
			throw new Exception(__('Rating is disabled on this blog'));
		}
		if ($type === null || $id === null || $note === null)
		{
			throw new Exception(__('Rating failed because of missing informations'));
		}

		$core->rateIt->loadModules();
		$rateit_types = $core->rateIt->getModules();

		if (!isset($rateit_types[$type]))
		{
			throw new Exception(__('Rating failed because of a wrong type of entry'));
		}

		$voted = $core->rateIt->voted($type,$id);
		if ($voted)
		{
			throw new Exception(__('You have already voted'));
		}
		else
		{
			$core->rateIt->set($type,$id,$note);
		}

		$rs = $core->rateIt->get($type,$id);
		$xv = new xmlTag('item');
		$xv->type = $type;
		$xv->id = $id;
		$xv->ip = $core->rateIt->ip;
		$xv->sum = $rs->sum;
		$xv->max = $rs->max;
		$xv->min = $rs->min;
		$xv->maxcount = $rs->maxcount;
		$xv->mincount = $rs->mincount;
		$xv->total = $rs->total;
		$xv->note = $rs->note;
		$xv->quotient = $rs->quotient;
		$rsp->insertNode($xv);

		return $rsp;
	}
}
