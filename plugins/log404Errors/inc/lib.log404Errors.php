<?php
# ***** BEGIN LICENSE BLOCK *****
#
# This file is part of Log 404 Errors, a plugin for Dotclear 2
# Copyright (C) 2009,2010 Moe (http://gniark.net/)
#
# Log 404 Errors is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License v2.0
# as published by the Free Software Foundation.
#
# Log 404 Errors is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software Foundation,
# Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
#
# Icon (icon.png) is from Silk Icons :
# <http://www.famfamfam.com/lab/icons/silk/>
#
# ***** END LICENSE BLOCK *****

if (!defined('DC_RC_PATH')) {return;}

class log404Errors
{
	public static function drop($dt = null)
	{
		global $core;
		
		$date = '';
		
		if ($dt !== null)
		{
			$date = ' AND (dt < \''.$core->con->escape($dt).'\')';
		}

		$query = 'DELETE FROM '.$core->prefix.'errors_log'.
		' WHERE (blog_id = \''.$core->con->escape($core->blog->id).'\')'.
		$date.';';

		$core->con->execute($query);
	}
	
	# inspired by the Antispam plugin
	public static function dropOldErrors()
	{
		global $core;
		
		$s =& $core->blog->settings;
		
		$now = $_SERVER['REQUEST_TIME'];
		
		// we call the purge every day
		if (($now - $s->log404errors->date_last_purge) <= (86400))
		{
			return;
		}
		
		$days = (int) $s->log404errors->errors_ttl;
		
		if ($days < 0)
		{
			return;
		}
		
		self::drop(date('Y-m-d H:i:s',($now - $days*86400)));
		
		$s->log404errors->put('date_last_purge',$now,
			'integer','log404Errors Date Last Purge (unix timestamp)'
		);
	}
}

?>
