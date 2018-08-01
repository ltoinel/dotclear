<?php
# -- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of Dotclear 2.
#
# Copyright (c) 2003-2013 Olivier Meunier & Association Dotclear
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK -----------------------------------------

//@HACK : On appelle en premier ce fichier de cache qui évitera dans 99% des requêtes d'aller plus loin ....
require dirname(__FILE__).'/cache.php';
//END @HACK

if (isset($_SERVER['DC_BLOG_ID'])) {
	define('DC_BLOG_ID',$_SERVER['DC_BLOG_ID']);
} elseif (isset($_SERVER['REDIRECT_DC_BLOG_ID'])) {
	define('DC_BLOG_ID',$_SERVER['REDIRECT_DC_BLOG_ID']);
}else {
	# Define your blog here
	define('DC_BLOG_ID','default');
}

require dirname(__FILE__).'/inc/public/prepend.php';
