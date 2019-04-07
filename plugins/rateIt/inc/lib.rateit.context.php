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

class rateItContext
{
	# Form
	public static function linker($enable,$type,$id,$note,$quotient,$style='')
	{
		global $core;

		if (!in_array($style,array('classic','simple','twin')))
		{
			$style = $core->blog->settings->rateit->rateit_rating_style;
		}
		$like = $core->blog->settings->rateit->rateit_msglike;
		if (empty($like)) { $like = __('I like'); }
		$notlike = $core->blog->settings->rateit->rateit_msgnotlike;
		if (empty($notlike)) { $notlike = __("I don't like"); }
		$uid = uniqid();
		$dis = $enable ? ' disabled="disabled"' : '';

		$res =
		'<form class="rateit-linker" method="post" action="'.
		$core->blog->url.$core->url->getBase('rateItpostform').'/'.$type.'/'.$id.'">'.
		'<p>'.
		'<input type="hidden" name="linkertype" value="'.$type.'" />'.
		'<input type="hidden" name="linkerid" value="'.$id.'" />'.
		'<input type="hidden" name="linkeruid" value="'.$uid.'" />';


		if ($style == 'simple')
		{
			$chk = $enable ? ' checked="checked"' : '';
			$res .= '<input title="'.$like.'" name="'.$uid.'" class="rateit-'.$type.'-'.$id.'" type="radio" value="'.$quotient.'" '.$chk.$dis.' />';
		}
		elseif ($style == 'twin')
		{
			$chk = $enable ? ' checked="checked"' : '';
			$res .= '<input title="'.$notlike.'" name="'.$uid.'" class="rateit-'.$type.'-'.$id.'" type="radio" value="1" checked="checked"'.$dis.' />';
			$res .= '<input title="'.$like.'" name="'.$uid.'" class="rateit-'.$type.'-'.$id.'" type="radio" value="'.$quotient.'" '.$dis.' />';
		}
		else
		{
			for($i = 0; $i < $quotient; $i++)
			{
				$chk = $note > $i && $note <= $i+1 ? ' checked="checked"' : '';

				$res .= '<input name="'.$uid.'" class="rateit-'.$type.'-'.$id.'" type="radio" value="'.($i+1).'"'.$chk.$dis.' />';
			}
		}

		if (!$enable)
		{
			$res .= '<input class="rateit-submit" name="rateit_submit_'.$uid.'" type="submit" value="'.__('Vote').'" />';
		}
		$res .= '</p></form>';

		return $res;
	}

	# Info
	public static function value($name,$type,$id,$value)
	{
		$itemprop = "";

		if ($name == "total"){
			$itemprop="ratingCount";
		} else if ($name == "note"){
			$itemprop="ratingValue";
		}

		return '<span itemprop="'.$itemprop.'" class="rateit-'.$name.' rateit-'.$name.'-'.$type.'-'.$id.'">'.$value.'</span>';
	}
}
