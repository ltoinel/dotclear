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

if (!defined('DC_CONTEXT_ADMIN')) {return;}

$m_version = $core->plugins->moduleInfo('log404Errors','version');

$i_version = $core->getVersion('log404Errors');
 
if (version_compare($i_version,$m_version,'>=')) {
	return;
}

$set =& $core->blog->settings;

$set->addNameSpace('log404errors');
$set->log404errors->put('errors_ttl',7,
	'integer','Delete 404 errors older than x days',
	# don't replace old value, global setting
	false,true
);
$set->log404errors->put('date_last_purge',0,
	'integer','log404Errors Date Last Purge (unix timestamp)',
	# don't replace old value, global setting
	false,true
);
$set->log404errors->put('log404errors_nb_per_page',30,
	'integer','Errors per page',
	# don't replace old value, global setting
	false,true
);


# table
$s = new dbStruct($core->con,$core->prefix);

$s->errors_log
	->id('bigint',0,false)
	->blog_id('varchar',32,false)
	->url('text',0,false)
	->dt('timestamp',0,false,'now()')
	->ip('varchar',39,true,null)
	->host('text',0,true,null)
	->user_agent('varchar',255,true,null)
	->referrer('text',0,true,null)
;

$si = new dbStruct($core->con,$core->prefix);
$changes = $si->synchronize($s);

$core->setVersion('log404Errors',$m_version);

return true;
?>
