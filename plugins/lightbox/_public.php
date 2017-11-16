<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of lightbox, a plugin for Dotclear 2.
#
# Copyright (c) Olivier Meunier and contributors
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) { return; }

$core->addBehavior('publicHeadContent',array('lightBoxPublic','publicHeadContent'));

class lightBoxPublic
{
	public static function publicHeadContent($core)
	{
		return;

		$core->blog->settings->addNameSpace('lightbox');
		if (!$core->blog->settings->lightbox->lightbox_enabled) {
			return;
		}

		$url = $core->blog->getQmarkURL().'pf='.basename(dirname(__FILE__));
		echo
		'<style type="text/css">'."\n".
		'@import url('.$url.'/css/modal.css);'."\n".
		"</style>\n".
		'<script type="text/javascript" src="'.$url.'/js/modal.js"></script>'."\n".
		'<script type="text/javascript">'."\n".
		"//<![CDATA[\n".
		'$(function() {'."\n".
			'var lb_settings = {'."\n".
				"loader_img : '".html::escapeJS($url)."/img/loader.gif',\n".
				"prev_img   : '".html::escapeJS($url)."/img/prev.png',\n".
				"next_img   : '".html::escapeJS($url)."/img/next.png',\n".
				"close_img  : '".html::escapeJS($url)."/img/close.png',\n".
				"blank_img  : '".html::escapeJS($url)."/img/blank.gif'\n".
			"};".
			'$("div.post").each(function() {'."\n".
					'$(this).find(\'a[href$=".jpg"],a[href$=".jpeg"],a[href$=".png"],a[href$=".gif"],'.
					'a[href$=".JPG"],a[href$=".JPEG"],a[href$=".PNG"],a[href$=".GIF"]\').modalImages(lb_settings);'."\n".

			"})\n".
		"});\n".
		"\n//]]>\n".
		"</script>\n";
	}
}
