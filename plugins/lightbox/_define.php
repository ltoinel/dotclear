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

$this->registerModule(
	/* Name */			"lightBox",
	/* Description*/		"lightBox like effect on images using jquery modal",
	/* Author */			"Olivier Meunier and contributors",
	/* Version */			'1.3.1',
	array(
		/* Permissions */	'permissions' =>	'admin',
		/* Type */			'type' =>			'plugin'
	)
);
