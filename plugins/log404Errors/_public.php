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

if ($core->blog->settings->log404errors->active)
{
	$core->addBehavior('publicAfterDocument',
		array('log404ErrorsBehaviors','publicAfterDocument'));
}

class log404ErrorsBehaviors
{
	public static function publicAfterDocument($core)
	{
		if ($core->url->type != '404') {return;}
		
		$core->con->writeLock($core->prefix.'errors_log');
		
		try
		{
			# Get ID
			$id = $core->con->select(
				'SELECT MAX(id) '.
				'FROM '.$core->prefix.'errors_log ')->f(0);
			
			if (empty($id)) {$id = 0;}
			
			$cur = $core->con->openCursor($core->prefix.'errors_log');
			$cur->id = (integer) $id + 1;
			$cur->blog_id = $core->blog->id;
			$cur->url = http::getHost().$_SERVER['REQUEST_URI'];
			$cur->dt = date("Y-m-d H:i:s");
			$cur->ip = http::realIP();
			$cur->host = @gethostbyaddr(http::realIP());
			$cur->user_agent = $_SERVER['HTTP_USER_AGENT'];
			$cur->referrer = (isset($_SERVER['HTTP_REFERER'])
				? $_SERVER['HTTP_REFERER'] : '');
			$cur->insert();
			
			$core->con->unlock();
		}
		catch (Exception $e)
		{
			$core->con->unlock();
			throw $e;
		}
		
		log404Errors::dropOldErrors();
	}
}

?>
