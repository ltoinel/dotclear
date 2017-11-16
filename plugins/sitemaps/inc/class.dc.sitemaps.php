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
if (!defined('DC_RC_PATH')) {return;}

class dcSitemaps
{
	protected $core;
	protected $blog;
	protected $urls;
	protected $freqs;
	protected $post_types;

	public function __construct($core)
	{
		$this->core = $core;
		$this->blog = $core->blog;

		$this->urls  = array();
		$this->freqs = array('','always','hourly','daily','weekly','monthly','never');
		$post_types = array();

		// Default post types
		$this->addPostType(
			'post',
			$this->blog->url,
			$this->blog->settings->sitemaps->sitemaps_posts_fq,
			$this->blog->settings->sitemaps->sitemaps_posts_pr
		);
		$this->addPostType(
			'page',
			$this->blog->url.$this->core->url->getBase('pages').'/',
			$this->blog->settings->sitemaps->sitemaps_pages_fq,
			$this->blog->settings->sitemaps->sitemaps_pages_pr
		);
	}


	public function getURLs()
	{
		if ($this->blog->settings->sitemaps->sitemaps_active && empty($this->urls)) {
			$this->collectURLs();
		}
		return $this->urls;
	}

	public function addPostType($type,$base_url,$freq = 0, $priority = 0.3)
	{
		if (preg_match('!^([a-z_-]+)$!',$type)) {
			$this->post_types[$type]['base_url']	= $base_url;
			$this->post_types[$type]['frequency']	= $this->getFrequency($freq);
			$this->post_types[$type]['priority']	= $this->getPriority($priority);
			return true;
		}
		return false;
	}

	public function addEntry($loc,$priority,$frequency,$lastmod = '')
	{
		$this->urls[] = array(
			'loc'	  => $loc,
			'priority'  => $priority,
			'frequency' => ($frequency == '')?null:$frequency,
			'lastmod'	  => ($lastmod == '')?null:$lastmod
			);
	}

	public function getPriority($value)
	{
		return(sprintf('%.1f',min(abs((float)$value),1)));
	}

	public function getFrequency($value)
	{
		return $this->freqs[min(abs(intval($value)),6)];
	}

	public function collectEntriesURLs($type = 'post')
	{
		if (!array_key_exists($type,$this->post_types)) {
			return;
		}

		$freq 	= $this->post_types[$type]['frequency'];
		$prio 	= $this->post_types[$type]['priority'];
		$base_url = $this->post_types[$type]['base_url'];

		// Let's have fun !
		$query =
			"SELECT p.post_id, p.post_url, p.post_tz, ".
			"p.post_upddt, MAX(c.comment_upddt) AS comments_dt ".
			"FROM ".$this->blog->prefix."post AS p ".
			"LEFT OUTER JOIN ".$this->blog->prefix."comment AS c ON c.post_id = p.post_id ".
			"WHERE p.blog_id = '".$this->blog->con->escape($this->blog->id)."' ".
			"AND p.post_type = '".$type."' AND p.post_status = 1 AND p.post_password IS NULL ".
			'GROUP BY p.post_id, p.post_url, p.post_tz, p.post_upddt, p.post_dt '.
			'ORDER BY p.post_dt ASC';

		$rs = $this->blog->con->select($query);
		while ($rs->fetch()) {
			if ($rs->comments_dt !== null) {
				$last_ts = max(strtotime($rs->post_upddt),strtotime($rs->comments_dt));
			} else {
				$last_ts = strtotime($rs->post_upddt);
			}
			$last_dt = dt::iso8601($last_ts,$rs->post_tz);
			$url = $base_url.html::sanitizeURL($rs->post_url);
			$this->addEntry($url,$prio,$freq,$last_dt);
		}
	}

	protected function collectURLs()
	{
		// Homepage URL
		if ($this->blog->settings->sitemaps->sitemaps_home_url)
		{
			$freq = $this->getFrequency($this->blog->settings->sitemaps->sitemaps_home_fq);
			$prio = $this->getPriority($this->blog->settings->sitemaps->sitemaps_home_pr);

			$this->addEntry($this->blog->url,$prio,$freq);
		}

		// Main syndication feeds URLs
		if ($this->core->blog->settings->sitemaps->sitemaps_feeds_url)
		{
			$freq = $this->getFrequency($this->blog->settings->sitemaps->sitemaps_feeds_fq);
			$prio = $this->getPriority($this->blog->settings->sitemaps->sitemaps_feeds_pr);

			$this->addEntry(
					$this->blog->url.$this->core->url->getBase('feed').'/rss2',
					$prio,$freq);
			$this->addEntry(
					$this->blog->url.$this->core->url->getBase('feed').'/atom',
					$prio,$freq);
		}

		// Posts entries URLs
		if ($this->core->blog->settings->sitemaps->sitemaps_posts_url) {
			$this->collectEntriesURLs('post');
		}

		// Pages entries URLs
		if ($this->core->plugins->moduleExists('pages') && $this->core->blog->settings->sitemaps->sitemaps_pages_url) {
			$this->collectEntriesURLs('page');
		}

		// Categories URLs
		if ($this->core->blog->settings->sitemaps->sitemaps_cats_url)
		{
			$freq = $this->getFrequency($this->blog->settings->sitemaps->sitemaps_cats_fq);
			$prio = $this->getPriority($this->blog->settings->sitemaps->sitemaps_cats_pr);

			$cats = $this->blog->getCategories(array('post_type'=>'post'));
			while ($cats->fetch()) {
				$this->addEntry(
						$this->blog->url.$this->core->url->getBase("category")."/".$cats->cat_url,
						$prio,$freq);
			}
		}

		if ($this->core->plugins->moduleExists('tags') && $this->core->blog->settings->sitemaps->sitemaps_tags_url)
		{
			$freq = $this->getFrequency($this->blog->settings->sitemaps->sitemaps_tags_fq);
			$prio = $this->getPriority($this->blog->settings->sitemaps->sitemaps_tags_pr);

			$meta = new dcMeta($this->core);
			$tags = $meta->getMeta('tag');
			while ($tags->fetch()) {
				$this->addEntry(
					$this->blog->url.$this->core->url->getBase("tag")."/".rawurlencode($tags->meta_id),
					$prio,$freq);
			}
		}

		// External parts ?
		# --BEHAVIOR-- sitemapsURLsCollect
		$this->core->callBehavior('sitemapsURLsCollect', $this);
	}
}
