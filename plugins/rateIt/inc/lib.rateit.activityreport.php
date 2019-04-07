<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of rateIt, a plugin for Dotclear 2.
#
# Copyright(c) 2014 Nicolas Roudaire <nikrou77@gmail.com> http://www.nikrou.net
#
# Copyright (c) 2009-2010 JC Denis and contributors
# jcdenis@gdwd.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')){return;}

# This file is used with plugin activityReport
$core->activityReport->addGroup('rateit',__('Plugin rateIt'));

# from BEHAVIOR rateItAfterSet in rateit/inc/class.rateit.php
$core->activityReport->addAction(
	'rateit',
	'set',
	__('new vote'),
	__('New vote of type "%s" was set with note of %s/%s'),
	'rateItAfterSet',
	array('rateItActivityReportBehaviors','rateItSet')
);

class rateItActivityReportBehaviors
{
	public static function rateItSet($cur)
	{
		$logs = array(
			$cur->rateit_type,
			$cur->rateit_note,
			$cur->rateit_quotient
		);

		$GLOBALS['core']->activityReport->addLog('rateit','set',$logs);
	}
}
