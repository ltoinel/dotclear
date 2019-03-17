<?php
# ***** BEGIN LICENSE BLOCK *****
#
# This file is part of Subscribe to comments.
# Copyright (C) 2008-2018 Moe (http://gniark.net/)
#
# Subscribe to comments is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License, or
# (at your option) any later version.
#
# Subscribe to comments is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# Icon (icon.png) is from Silk Icons : http://www.famfamfam.com/lab/icons/silk/
#
# Inspired by http://txfx.net/code/wordpress/subscribe-to-comments/
#
# ***** END LICENSE BLOCK *****

if (!defined('DC_CONTEXT_ADMIN')) {return;}

# On lit la version du plugin
$m_version = $core->plugins->moduleInfo('subscribeToComments','version');
 
# On lit la version du plugin dans la table des versions
$i_version = $core->getVersion('subscribeToComments');
 
# La version dans la table est supérieure ou égale à
# celle du module, on ne fait rien puisque celui-ci
# est installé
if (version_compare($i_version,$m_version,'>=')) {
	return;
}

$core->blog->settings->addNamespace('subscribetocomments');

# only update if the plugin has already been installed
if ($i_version !== null)
{
	# replace old tag with new tag
	if (version_compare($i_version,'1.0-RC4','<'))
	{
		$core->blog->settings->subscribetocomments->put('subscribetocomments_email_subject',
		str_replace('%5$s','%6$s',$core->blog->settings->subscribetocomments->subscribetocomments_email_subject),
		'text','Email subject',true);
		$core->blog->settings->subscribetocomments->put('subscribetocomments_email_content',
		str_replace('%5$s','%6$s',$core->blog->settings->subscribetocomments->subscribetocomments_email_content),
		'text','Email subject',true);
	}
	
	# move the notifications to (dc_)comment and
	# delete the table (dc_)comment_notification 
	if (version_compare($i_version,'1.0.4','<'))
	{
		# add notification_sent column to (dc_)comment 
		$s = new dbStruct($core->con,$core->prefix);
		$s->comment->notification_sent('smallint',0,false,0)
		;
		$si = new dbStruct($core->con,$core->prefix);
		$changes = $si->synchronize($s);
	
		$comment_ids = '';
		$rs = $core->con->select('SELECT comment_id FROM '.
			$core->prefix.'comment_notification WHERE (sent = 1);');
		if (!$rs->isEmpty())
		{
			while ($rs->fetch())
			{
				if ($comment_ids == '') {$comment_ids = $rs->comment_id;}
				else {$comment_ids .= ','.$rs->comment_id;}
			}
			$cur = $core->con->openCursor($core->prefix.'comment');
			$cur->notification_sent = 1;
			$cur->update('WHERE comment_id IN ('.$comment_ids.');');		
		}
		$core->con->execute('DROP TABLE '.$core->prefix.'comment_notification;');
	}
	
	# encode settings to preserve new lines when editing about:config
	if (version_compare($i_version,'1.2.5','<'))
	{	
		$rs = $core->con->select('SELECT setting_value, setting_id, blog_id '.
		'FROM '.$core->prefix.'setting '.
		'WHERE setting_ns = \'subscribetocomments\' '.
		'AND ((setting_id LIKE \'%_subject\') '.
		'OR (setting_id LIKE \'%_content\'));');
		
		while($rs->fetch())
		{
			$cur = $core->con->openCursor($core->prefix.'setting');
			$cur->setting_value = base64_encode($rs->setting_value);
			$cur->update('WHERE setting_ns = \'subscribetocomments\' '.
				'AND setting_id = \''.$rs->setting_id.'\''.
				'AND blog_id = \''.$rs->blog_id.'\';');
		}
	}
}

# add post types
# Allowed post types
$core->blog->settings->subscribetocomments->put('subscribetocomments_post_types',
	serialize(subscribeToComments::getPostTypes()),
	'string','Allowed post types',false,true);

# Define From: header of outbound emails
$core->blog->settings->subscribetocomments->put('subscribetocomments_email_from',
	'dotclear@'.$_SERVER['HTTP_HOST'],
	'string','Define From: header of outbound emails',false,true);

# table
$s = new dbStruct($core->con,$core->prefix);
 
$s->comment_subscriber
	->id('bigint',0,false)
	->email('varchar',255,false)
	# key sent by email
	->user_key('varchar',40,false)
	# temporary key when changing email address
	->temp_key('varchar',40,true,null)
	# timestamp when the temporary key expire
	->temp_expire('timestamp',0,true,null)
	# status
	->status('smallint',0,false,0)

	->primary('pk_comment_subscriber','id','email','user_key')
;

# add notification_sent column to (dc_)comment 
$s->comment
	->notification_sent('smallint',0,false,0)
;

# indexes
$s->comment_subscriber->index('idx_id', 'btree', 'id');
$s->comment_subscriber->index('idx_email', 'btree', 'email');

$si = new dbStruct($core->con,$core->prefix);
$changes = $si->synchronize($s);

# La procédure d'installation commence vraiment là
$core->setVersion('subscribeToComments',$m_version);

return true;