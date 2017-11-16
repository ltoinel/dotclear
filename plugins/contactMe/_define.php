<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of contactMe, a plugin for Dotclear 2.
#
# Copyright (c) Olivier Meunier and contributors
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) { return; }

$this->registerModule(
	/* Name */		"ContactMe",
	/* Description*/	"Add a simple contact form on your blog",
	/* Author */		"Olivier Meunier and contributors",
	/* Version */		'1.8.1',
	array(
		/* Permissions */	'permissions' =>	'admin',
		/* Type */			'type' =>			'plugin'
	)
);
