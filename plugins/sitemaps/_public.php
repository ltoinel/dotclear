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

$core->tpl->addBlock('SitemapEntries'		,array('sitemapsTemplates','SitemapEntries'));
$core->tpl->addBlock('SitemapEntryIf'		,array('sitemapsTemplates','SitemapEntryIf'));
$core->tpl->addValue('SitemapEntryLoc'		,array('sitemapsTemplates','SitemapEntryLoc'));
$core->tpl->addValue('SitemapEntryFrequency'	,array('sitemapsTemplates','SitemapEntryFrequency'));
$core->tpl->addValue('SitemapEntryPriority'	,array('sitemapsTemplates','SitemapEntryPriority'));
$core->tpl->addValue('SitemapEntryLastmod'	,array('sitemapsTemplates','SitemapEntryLastmod'));

class sitemapsTemplates
{
	public static function SitemapEntries($attr,$content)
	{
		return
			'<?php if ($_ctx->exists("sitemap_urls")) : ?>'."\n".
			'<?php while ($_ctx->sitemap_urls->fetch()) : ?>'.$content.'<?php endwhile; ?>'.
			'<?php endif; ?>'."\n";
	}

	public static function SitemapEntryIf($attr,$content)
	{
		$if = '';
		if (isset($attr['has_attr'])) {
			switch ($attr['has_attr']) {
				case 'frequency'	: $if = '!is_null($_ctx->sitemap_urls->frequency)'; break;
				case 'priority'	: $if = '!is_null($_ctx->sitemap_urls->priority)'; break;
				case 'lastmod'		: $if = '!is_null($_ctx->sitemap_urls->lastmod)'; break;
			}
		}
		if (!empty($if)) {
			return '<?php if ('.$if.') : ?>'.$content.'<?php endif; ?>';
		}
		else {
			return $content;
		}
	}

	public static function SitemapEntryLoc($attr)
	{
		$f = $GLOBALS['core']->tpl->getFilters($attr);
		return '<?php echo '.sprintf($f,'$_ctx->sitemap_urls->loc').'; ?>';
	}

	public static function SitemapEntryFrequency($attr)
	{
		$f = $GLOBALS['core']->tpl->getFilters($attr);
		return '<?php echo '.sprintf($f,'$_ctx->sitemap_urls->frequency').'; ?>';
	}

	public static function SitemapEntryPriority($attr)
	{
		$f = $GLOBALS['core']->tpl->getFilters($attr);
		return '<?php echo '.sprintf($f,'$_ctx->sitemap_urls->priority').'; ?>';
	}

	public static function SitemapEntryLastmod($attr)
	{
		$f = $GLOBALS['core']->tpl->getFilters($attr);
		return '<?php echo '.sprintf($f,'$_ctx->sitemap_urls->lastmod').'; ?>';
	}
}
