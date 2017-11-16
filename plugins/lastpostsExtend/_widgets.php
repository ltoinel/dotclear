<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of lastpostsExtend, a plugin for Dotclear 2.
# 
# Copyright (c) 2009-2016 Jean-Christian Denis and contributors
# contact@jcdenis.fr http://jcdenis.net
# 
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) {

	return null;
}

$core->addBehavior(
	'initWidgets',
	array('lastpostsextendWidget', 'initWidget')
);

class lastpostsextendWidget
{
	public static function initWidget($w)
	{
		global $core;

		# Create widget
		$w->create(
			'lastpostsextend',
			__('Last entries (Extended)'),
			array('lastpostsextendWidget', 'parseWidget'),
			null,
			__('Extended list of entries')
		);
		# Title
		$w->lastpostsextend->setting(
			'title',
			__('Title:'),
			__('Last entries'),
			'text'
		);
		# type
		$posttypes = array(
			__('Post') => 'post',
			__('Page') => 'page',
			__('Gallery') => 'galitem'
		);
		# plugin muppet types
		if ($core->plugins->moduleExists('muppet')) {
			$muppet_types = muppet::getPostTypes();
			if(is_array($muppet_types) && !empty($muppet_types)) {
			
				foreach($muppet_types as $k => $v) {
					$posttypes[$v['name']] = $k;
				}
			}
		}
		$w->lastpostsextend->setting(
			'posttype',
			__('Type:'),
			'post',
			'combo',
			$posttypes
		);
		# Category (post and page have same category)
		$rs = $core->blog->getCategories(array(
			'post_type' => 'post'
		));
		$categories = array(
			'' => '',
			__('Uncategorized') => 'null'
		);
		while ($rs->fetch()) {
			$categories[str_repeat(
				'&nbsp;&nbsp;',
				$rs->level-1
			).'&bull; '.html::escapeHTML($rs->cat_title)] = 
				$rs->cat_id;
		}
		$w->lastpostsextend->setting(
			'category',
			__('Category:'),
			'',
			'combo',
			$categories
		);
		unset($rs,$categories);
		# Pasworded
		$w->lastpostsextend->setting(
			'passworded',
			__('Protection:'),
			'no',
			'combo',
			array(
				__('all') => 'all',
				__('only without password') => 'no',
				__('only with password') => 'yes'
			)
		);
		# Status
		$w->lastpostsextend->setting(
			'status',
			__('Status:'),
			'1',
			'combo',
			array(
				__('all') => 'all',
				__('pending') => '-2',
				__('scheduled') => '-1',
				__('unpublished') => '0',
				__('published') => '1'
			)
		);
		# Selected entries only
		$w->lastpostsextend->setting(
			'selectedonly',
			__('Selected entries only'),
			0,
			'check'
		);
		# Updated entries only
		$w->lastpostsextend->setting(
			'updatedonly',
			__('Updated entries only'),
			0,
			'check'
		);
		# Tag
		if ($core->plugins->moduleExists('tags'))
		{
			$w->lastpostsextend->setting(
				'tag',
				__('Limit to tags:'),
				'',
				'text'
			);
		}
		# Search
		$w->lastpostsextend->setting(
			'search',
			__('Limit to words:'),
			'',
			'text'
		);
		# Entries limit
		$w->lastpostsextend->setting(
			'limit',
			__('Entries limit:'),
			10,
			'text'
		);
		# Sort type
		$w->lastpostsextend->setting(
			'sortby',
			__('Order by:'),
			'date',
			'combo',
			array(
				__('Date') => 'date',
				__('Title') => 'post_title',
				__('Comments') => 'nb_comment'
			)
		);
		# Sort order
		$w->lastpostsextend->setting(
			'sort',
			__('Sort:'),
			'desc',
			'combo',
			array(
				__('Ascending') => 'asc',
				__('Descending') => 'desc'
			)
		);
		# First image
		$w->lastpostsextend->setting(
			'firstimage',
			__('Show entries first image:'),
			'',
			'combo',
			array(
				__('no') => '',
				__('square') => 'sq',
				__('thumbnail') => 't',
				__('small') => 's',
				__('medium') => 'm',
				__('original') => 'o'
			)
		);
		# With excerpt
		$w->lastpostsextend->setting(
			'excerpt',
			__('Show entries excerpt'),
			0,
			'check'
		);
		# Excerpt length
		$w->lastpostsextend->setting(
			'excerptlen',
			__('Excerpt length:'),
			100,
			'text'
		);
		# Comment count
		$w->lastpostsextend->setting(
			'commentscount',
			__('Show comments count'),
			0,
			'check'
		);
		# Home only
		$w->lastpostsextend->setting(
			'homeonly',
			__('Display on:'),
			0,
			'combo',
			array(
				__('All pages') => 0,
				__('Home page only') => 1,
				__('Except on home page') => 2
			)
		);
		# widget option - content only
		$w->lastpostsextend->setting(
			'content_only',
			__('Content only'),
			0,
			'check'
		);
		# widget option - additionnal CSS
		$w->lastpostsextend->setting(
			'class',
			__('CSS class:'),
			''
		);
		# widget option - put offline
		$w->lastpostsextend->setting(
			'offline',
			__('Offline'),
			0,
			'check'
		);
	}
	
	public static function parseWidget($w)
	{
		global $core;
		
		$params = array(
			'sql' => '',
			'columns' => array(),
			'from' => ''
		);

		# Widget is offline
		if ($w->offline) 
			return; 

		# Home page only
		if ($w->homeonly == 1 && $core->url->type != 'default' 
		||  $w->homeonly == 2 && $core->url->type == 'default'
		) {
			return null;
		}

		# Need posts excerpt
		if ($w->excerpt) {
			$params['columns'][] = 'post_excerpt';
		}

		# Passworded
		if ($w->passworded == 'yes') {
			$params['sql'] .= 'AND post_password IS NOT NULL ';
		}
		elseif ($w->passworded == 'no') {
			$params['sql'] .= 'AND post_password IS NULL ';
		}

		# Status
		if ($w->status != 'all') {
			$params['post_status'] = $w->status;
		}

		# Search words
		if ('' != $w->search) {
			$params['search'] = $w->search;
		}

		# Updated posts only
		if ($w->updatedonly) {
			$params['sql'] .= 
				"AND post_creadt < post_upddt ".
				"AND post_dt < post_upddt ";
/*
			$params['sql'] .= 
			"AND TIMESTAMP(post_creadt ,'DD-MM-YYYY HH24:MI:SS') < TIMESTAMP(post_upddt ,'DD-MM-YYYY HH24:MI:SS') ".
			"AND TIMESTAMP(post_dt ,'DD-MM-YYYY HH24:MI:SS') < TIMESTAMP(post_upddt ,'DD-MM-YYYY HH24:MI:SS') ";
//*/
			$params['order'] = $w->sortby == 'date' ? 
				'post_upddt ' : $w->sortby.' ';
		}
		else {
			$params['order'] = $w->sortby == 'date' ? 
				'post_dt ' : $w->sortby.' ';
		}
		$params['order'] .= $w->sort == 'asc' ? 'asc' : 'desc';
		$params['limit'] = abs((integer) $w->limit);
		$params['no_content'] = true;

		# Selected posts only
		if ($w->selectedonly) {
			$params['post_selected'] = 1;
		}

		# Type
		$params['post_type'] = $w->posttype;

		# Category
		if ($w->category) {
			if ($w->category == 'null') {
				$params['sql'] .= ' AND P.cat_id IS NULL ';
			}
			elseif (is_numeric($w->category)) {
				$params['cat_id'] = (integer) $w->category;
			}
			else {
				$params['cat_url'] = $w->category;
			}
		}

		# Tags
		if ($core->plugins->moduleExists('tags') && $w->tag) {
			$tags = explode(',', $w->tag);
			foreach($tags as $i => $tag) { 
				$tags[$i] = trim($tag);
			}
			$params['from'] .= ', '.$core->prefix.'meta META ';
			$params['sql'] .= 'AND META.post_id = P.post_id ';
			$params['sql'] .= "AND META.meta_id ".$core->con->in($tags)." ";
			$params['sql'] .= "AND META.meta_type = 'tag' ";
		}

		$rs = $core->auth->sudo(
			array($core->blog, 'getPosts'),
			$params,
			false
		);

		# No result
		if ($rs->isEmpty()) {

			return null;
		}

		# Return
		$res = $w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '';

		while ($rs->fetch()) {
			$res .= '<li>'.
			'<'.($rs->post_status == 1 ? 'a href="'.$rs->getURL().'"' : 'span').
			' title="'.
			dt::dt2str(
				$core->blog->settings->system->date_format,
				$rs->post_upddt
			).', '.
			dt::dt2str(
				$core->blog->settings->system->time_format,
				$rs->post_upddt
			).'">'.
			html::escapeHTML($rs->post_title).
			'</'.($rs->post_status == 1 ? 'a' : 'span').'>';

			# Nb comments
			if ($w->commentscount && $rs->post_status == 1) {
				$res .= ' ('.$rs->nb_comment.')';
			}

			# First image
			if ($w->firstimage != '') {
				$res .= self::entryFirstImage(
					$core,
					$rs->post_type,
					$rs->post_id,
					$w->firstimage
				);
			}

			# Excerpt
			if ($w->excerpt) {
				$excerpt = $rs->post_excerpt;
				if ($rs->post_format == 'wiki') {
					$core->initWikiComment();
					$excerpt = $core->wikiTransform($excerpt);
					$excerpt = $core->HTMLfilter($excerpt);
				}
				if (strlen($excerpt) > 0) {
					$cut = text::cutString(
						$excerpt,
						abs((integer) $w->excerptlen)
					);
					$res .= ' : '.$cut.(strlen($cut) < strlen($excerpt) ? 
						'...' : '');

					unset($cut);
				}
			}
			$res .= '</li>';
		}

		return $w->renderDiv(
			$w->content_only, 
			'lastpostsextend '.$w->class, 
			'', 
			'<ul>'.$res.'</ul>'
		);
	}
	
	private static function entryFirstImage($core, $type, $id, $size='s')
	{
		if (!in_array($type, array('post', 'page', 'galitem'))) {

			return '';
		}

		$rs = $core->auth->sudo(
			array($core->blog, 'getPosts'),
			array('post_id' => $id, 'post_type' => $type),
			false
		);

		if ($rs->isEmpty()) {

			return '';
		}

		if (!preg_match('/^sq|t|s|m|o$/',$size)) {
			$size = 's';
		}

		$p_url = $core->blog->settings->system->public_url;
		$p_site = preg_replace(
			'#^(.+?//.+?)/(.*)$#',
			'$1',
			$core->blog->url
		);
		$p_root = $core->blog->public_path;

		$pattern = '(?:'.preg_quote($p_site, '/').')?'.preg_quote($p_url, '/');
		$pattern = sprintf('/<img.+?src="%s(.*?\.(?:jpg|gif|png))"[^>]+/msu', $pattern);

		$src = '';
		$alt = '';

		$subject = $rs->post_excerpt_xhtml.$rs->post_content_xhtml.$rs->cat_desc;
		if (preg_match_all($pattern, $subject, $m) > 0) {

			foreach ($m[1] as $i => $img) {
				if (($src = self::ContentFirstImageLookup($p_root, $img, $size)) !== false) {

					$src = $p_url.(dirname($img) != '/' ? dirname($img) : '').'/'.$src;
					if (preg_match('/alt="([^"]+)"/', $m[0][$i], $malt)) {
						$alt = $malt[1];
					}
					break;
				}
			}
		}

		if (!$src) {

			return '';
		}

		return 
		'<div class="img-box">'.				
		'<div class="img-thumbnail">'.
		'<a title="'.html::escapeHTML($rs->post_title).'" href="'.$rs->getURL().'">'.
		'<img alt="'.$alt.'" src="'.stripslashes($src).'" />'.
		'</a></div>'.
		"</div>\n";
	}

	private static function ContentFirstImageLookup($root,$img,$size)
	{
		# Get base name and extension
		$info = path::info($img);
		$base = $info['base'];
		
		if (preg_match('/^\.(.+)_(sq|t|s|m)$/',$base,$m)) {
			$base = $m[1];
		}
		
		$res = false;
		if ($size != 'o' && file_exists($root.'/'.$info['dirname'].'/.'.$base.'_'.$size.'.jpg')) {
			$res = '.'.$base.'_'.$size.'.jpg';
		}
		else {
			$f = $root.'/'.$info['dirname'].'/'.$base;
			if (file_exists($f.'.'.$info['extension'])) {
				$res = $base.'.'.$info['extension'];
			}
			elseif (file_exists($f.'.jpg')) {
				$res = $base.'.jpg';
			}
			elseif (file_exists($f.'.png')) {
				$res = $base.'.png';
			}
			elseif (file_exists($f.'.gif')) {
				$res = $base.'.gif';
			}
		}

		return $res ? $res : false;
	}
}