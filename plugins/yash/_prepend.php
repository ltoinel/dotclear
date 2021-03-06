<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of yash, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) { return; }

$__autoload['yashBehaviors'] = dirname(__FILE__).'/inc/yash.behaviors.php';

$core->addBehavior('coreInitWikiPost',array('yashBehaviors','coreInitWikiPost'));
