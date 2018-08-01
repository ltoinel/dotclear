<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Sitemaps, a plugin for DotClear2.
# Copyright (c) 2006-2015 Pep and contributors.
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------
if (!defined('DC_RC_PATH')) return;

global $core, $__autoload;

$__autoload['dcSitemaps'] = dirname(__FILE__).'/inc/class.dc.sitemaps.php';

// Behavior(s)
class sitemapsBehaviors
{
	public static function addTemplatePath($core)
	{
		$core->tpl->setPath($core->tpl->getPath(),dirname(__FILE__).'/default-templates');
	}

}

$core->addBehavior('publicBeforeDocument', array('sitemapsBehaviors','addTemplatePath'));


// URL Handler(s)
class sitemapsUrlHandlers extends dcUrlHandlers
{
	public static function sitemap($args)
	{
		global $core,$_ctx;

		if (!$core->blog->settings->sitemaps->sitemaps_active) {
			self::p404();
			return;
		}

		$sitemap = new dcSitemaps($core);
		$_ctx->sitemap_urls = staticRecord::newFromArray($sitemap->getURLs());
		if ($_ctx->sitemap_urls->isEmpty()) {
			self::p404();
		}
		else {
			self::serveDocument('sitemap.xml','text/xml');
		}
	}
}

$core->url->register('gsitemap','sitemap.xml','^sitemap[_\.]xml$',array('sitemapsUrlHandlers','sitemap'));
