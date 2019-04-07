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

class rateItLibImagePath
{
	public static $version = '1.1';

	public static function getArray($core,$m='')
	{
		if (!$core->plugins->moduleExists($m)
		 || !$core->url->getBase($m.'module')) {
			return array(
				'theme'=>array('dir'=>null,'url'=>null),
				'public'=>array('dir'=>null,'url'=>null),
				'module'=>array('dir'=>null,'url'=>null),
			);
		}

		return array(
			'public' => array(
				'dir' => $core->blog->public_path.'/'.$m.'-default-image.png',
				'url' => $core->blog->settings->system->public_url.'/'.$m.'-default-image.png'
			),
			'theme' => array(
				'dir' => $core->blog->themes_path.'/'.$core->blog->settings->system->theme.'/img/'.$m.'-default-image.png',
				'url' => $core->blog->settings->system->themes_url.'/'.$core->blog->settings->system->theme.'/img/'.$m.'-default-image.png'
			),
			'module' => array(
				'dir' => $core->plugins->moduleRoot($m).'/default-templates/img/'.$m.'-default-image.png',
				'url' => $core->blog->url.$core->url->getBase($m.'module').'/img/'.$m.'-default-image.png'
			)
		);
	}

	public static function getUrl($core,$m='')
	{
		$files = self::getArray($core,$m);
		foreach($files as $k => $file) {
			if (file_exists($files[$k]['dir']))
				return $files[$k]['url'];
		}
		return null;
	}

	public static function getPath($core,$m='')
	{
		$files = self::getArray($core,$m);
		foreach($files as $k => $file) {
			if (file_exists($files[$k]['dir']))
				return $files[$k]['dir'];
		}
		return null;
	}

	public static function getSize($core,$m='')
	{
		if (!($img = self::getPath($core,$m)))
			return array('w'=>16,'h'=>16);
		else {
			$info = getimagesize($img);
			return array('w'=>$info[0],'h'=>floor($info[1] /3));
		}
	}
}
