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

$this->registerModule(
	/* Name */		"Sitemaps",
	/* Description*/	"Add XML Sitemaps",
	/* Author */		"Pep and contributors",
	/* Version */		'1.3.3',
	/* Properties */
	array(
		'requires' => array(array('core','2.11')),
		'permissions' => 'contentadmin',
		'type' => 'plugin',
		'support' => 'http://forum.dotclear.org/viewtopic.php?id=48307',
		'details' => 'http://plugins.dotaddict.org/dc2/details/sitemaps'
	)
);
