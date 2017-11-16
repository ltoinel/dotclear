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

class log404ErrorsAdmin
{
	public static function show($params=array())
	{
		global $core;

		if (!empty($params['group']))
		{
			$query = 'SELECT url, COUNT(id) AS count, MAX(dt) AS max_dt '.
				'FROM '.$core->prefix.'errors_log '.
				'WHERE (blog_id = \''.$core->con->escape(
					$core->blog->id).'\') '.
				'GROUP BY url '.
				'ORDER BY count DESC ';
		}
		else
		{
			$query = 'SELECT id, url, dt, ip, host, referrer, user_agent '.
				'FROM '.$core->prefix.'errors_log '.
				'WHERE (blog_id = \''.$core->con->escape(
					$core->blog->id).'\') '.
				'ORDER BY dt DESC ';
		}
		
		if (!empty($params['limit'])) {
			$query .= $core->con->limit($params['limit']);
		}
		
		$rs = $core->con->select($query);
		
		while ($rs->fetch())
		{
			$url = $rs->url;
			
			# html::escapeHTML() is used to avoid malicious code injection
			
			if (strlen($url) > 60)
			{
				$url = '<a href="'.html::escapeHTML($url).'" '.
					'title="'.html::escapeHTML($url).'">'.
					html::escapeHTML(text::cutString($url,60)).'…</a>';
			}
			else
			{
				$url = '<a href="'.html::escapeHTML($url).'" '.
					'title="'.html::escapeHTML($url).'">'.
					html::escapeHTML($url).'</a>';
			}
			
			if (!empty($params['group']))
			{
				echo('<tr>'.
					'<td>'.$rs->count.'</td>'.
					'<td>'.$url.'</td>'.
					'<td>'.dt::dt2str('%Y-%m-%d %H:%M:%S',$rs->max_dt,
						$core->blog->settings->system->blog_timezone).'</td>'.
					'</tr>'."\n");
			}
			else
			{
				$referrer = $rs->referrer;
				
				if (empty($referrer))
				{
					$referrer = '&nbsp;';
				}
				else
				{
					if (strlen($referrer) > 60)
					{
						$referrer = '<a href="'.html::escapeHTML($referrer).'" '.
							'title="'.html::escapeHTML($referrer).'">'.
							html::escapeHTML(text::cutString($referrer,60)).'…</a>';
					}
					else
					{
						$referrer = '<a href="'.
							html::escapeHTML($referrer).'">'.
								html::escapeHTML($referrer).'</a>';
					}
				}
				
				$ip = $rs->ip;
				
				$ip = ((empty($ip)) ? '&nbsp;' : html::escapeHTML($ip));
				
				$host = $rs->host;
				
				$host = ((empty($host)) ? '&nbsp;' : html::escapeHTML($host));
				
				$user_agent = $rs->user_agent;
				
				$user_agent = ((empty($user_agent))
					? '&nbsp;' : html::escapeHTML($user_agent));
				
				echo('<tr>'.
					'<td>'.$rs->id.'</td>'.
					'<td>'.$url.'</td>'.
					'<td>'.dt::dt2str('%Y-%m-%d %H:%M:%S ',$rs->dt,
						$core->blog->settings->system->blog_timezone).'</td>'.
					'<td>'.$ip.'</td>'.
					'<td>'.$host.'</td>'.
					'<td>'.$referrer.'</td>'.
					'<td>'.$user_agent.'</td>'.
					'</tr>'."\n");
			}
		}
	}
}

?>
