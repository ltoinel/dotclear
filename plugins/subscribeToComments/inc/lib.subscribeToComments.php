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

if (!defined('DC_RC_PATH')) {return;}

/**
@ingroup Subscribe to comments
@brief Generic functions
*/
class subscribeToComments
{
	/** check if the string is a valid email address
	@param	email	<b>string</b>	Email address
	@return	<b>boolean</b> Is an email address ?
	*/
	public static function checkEmail(&$email)
	{
		$email = urldecode($email);
		if (!text::isEmail($email))
		{
			throw new Exception(__('Invalid email address.'));
		}	
	}

	/** check if the string is a valid key
	@param	key	<b>string</b>	Key
	@return	<b>boolean</b> Is a key ?
	\see http://www.php.net/manual/fr/function.md5.php#40251
	*/
	public static function checkKey($key)
	{
		if (!(preg_match('/^[a-f0-9]{40}$/',$key)))
		{
			throw new Exception(__('Invalid key.'));
		}
	}

	/**
	remove old temporary keys
	*/
	public static function cleanKeys()
	{
		global $core;

		if ($_SERVER['REQUEST_TIME'] <= 
			$core->blog->settings->subscribetocomments->subscribetocomments_clean_keys) {return;}

		$core->blog->settings->addNameSpace('subscribetocomments');
		$core->blog->settings->subscribetocomments->put('subscribetocomments_clean_keys',strtotime('+1 hour'),
		'integer','Clean temporary keys');

		# delete old temporary keys
		$cur = $core->con->openCursor($core->prefix.'comment_subscriber');
		$cur->temp_key = null;
		$cur->temp_expire = null;
		$cur->update('WHERE ((temp_expire IS NOT NULL)'.
			' AND (temp_expire < \''.date('Y-m-d H:i:s').'\'))');
	}

	/**
	get the URL of the subscriptions page
	@return	<b>string</b> URL
	*/
	public static function url()
	{
		global $core;
		
		return($core->blog->url.$core->url->getBase('subscribetocomments'));
	}
	
	/**
	get a plugin's setting
	@param	setting	<b>string</b>	Setting
	@return	<b>string</b> setting
	*/
	public static function getSetting($setting)
	{
		global $core;
		
		$setting = $core->blog->settings->subscribetocomments->{'subscribetocomments_'.$setting};
		
		if (strlen($setting) == 0) {return '';}
		# else		
		return(base64_decode($setting));
	}
	
	/**
	format settings
	@param	tags	<b>array</b>	Tags array
	@param	str	<b>string</b>	String
	@param	flip	<b>boolean</b>	Flip the tags array
	@param	encode	<b>boolean</b>	Serialize and encode returned string
	@return	<b>string</b> string
	*/
	public static function format($tags,$str,$flip=false,$encode=false)
	{
		global $tags_global;
		$array = array();
		foreach ($tags_global as $k => $v)
		{
			$array[$k] = $v['tag'];
		}
		if (empty($tags)) {$tags = array();}
		foreach ($tags as $k => $v)
		{
			$array[$k] = $v['tag'];
		}
		if ($flip) {
			$array = array_flip($array);
		}
		$str = str_replace(array_keys($array),array_values($array),$str);
		
		if ($encode)
		{
			$str = base64_encode($str);
		}
		
		return($str);
	}
	
	public static function setDefaultSettings($replace=false,$lang='en')
	{
		global $core;
		
		global $tags_global, $tags_account, $tags_subscribe, $tags_comment,
			$tags_email;
		
		$settings =& $core->blog->settings->subscribetocomments;
		
		# load locales for the blog language
		l10n::set(dirname(__FILE__).'/../locales/'.$lang.'/admin');
		
		# Change From: header of outbound emails
		$settings->put('subscribetocomments_email_from',
		'dotclear@'.$_SERVER['HTTP_HOST'],
		'string','Change From: header of outbound emails',$replace);
		
		# Allowed post types
		$settings->put('subscribetocomments_post_types',
		serialize(subscribeToComments::getPostTypes()),
		'string','Allowed post types',$replace);
		
		$nl = "\n";
		$nls = $nl.$nl;
		$separator = '----------';
		$foot_separator = '--';
		$hello = __('Hello [email],');
		$account = 
			__('To manage your subscriptions, change your email address or block emails, click here: [manageurl]');
		
		# Account subject
		$settings->put('subscribetocomments_account_subject',
			subscribeToComments::format($tags_account,__('Your account on [blogname]'),
				false,true),'text',
			'Email subject',$replace);
		# Account content
		$settings->put('subscribetocomments_account_content',
			subscribeToComments::format($tags_account,
				$hello.$nl.
				__('here are some informations about your account on [blogname]:').
				$nls.
				__('Email address: [email]').$nls.
				$account.$nls.
				$foot_separator.$nl.'[blogurl]',false,true)
			,'text','Email content',$replace);
		
		# Send an email for each subscription
		$settings->put('subscribetocomments_subscribe_active',
			false,'boolean','Send an email for each subscription');
		# Subscription subject
		$settings->put('subscribetocomments_subscribe_subject',
			subscribeToComments::format($tags_subscribe,
				__('Subscribed to [posttitle] - [blogname]'),false,true),'text',
				'Subscription subject',$replace);
		# Subscription content
		$settings->put('subscribetocomments_subscribe_content',
			subscribeToComments::format($tags_subscribe,
				$hello.$nl.
				__('you subscribed to [posttitle]: [posturl]').$nls.
				$separator.$nls.
				$account.$nls.
				$foot_separator.$nl.'[blogurl]',false,true)
			,'text','Subscription content',$replace);
		
		# Comment subject
		$settings->put('subscribetocomments_comment_subject',
			subscribeToComments::format($tags_comment,
			__('New comment on [posttitle] - [blogname]'),false,true),'text',
			'Comment subject',$replace);
		# Comment content
		$settings->put('subscribetocomments_comment_content',
			subscribeToComments::format($tags_comment,
				$hello.$nl.
				__('a new comment has been posted by [commentauthor] on [posttitle]:').
				$nls. 
				$separator.$nls.
				'[commentcontent]'.$nls.
				$separator.$nls.
				__('View the comment: [commenturl]').$nls.
				__('View the post: [posturl]').$nls.
				$separator.$nls.
				$account.$nls.
				$foot_separator.$nl.'[blogurl]',false,true)
			,'text','Comment content',$replace);
		
		# Email subject
		$settings->put('subscribetocomments_email_subject',
			subscribeToComments::format($tags_email,
				__('Change email address on [blogname]'),false,true),'text','Email subject',
				$replace);
		# Email content
		$settings->put('subscribetocomments_email_content',
			subscribeToComments::format($tags_email,
				$hello.$nl.
				__('you have requested to change the email address of your subscriptions to [newemail], click on this link: [emailurl]').
				$nls.
				__('This link is valid for 24 hours.').$nls.
				$separator.$nls.
				$account.$nls.
				$foot_separator.$nl.'[blogurl]',false,true)
			,'text','Email content',$replace);
		
		# display
		$settings->put('subscribetocomments_tpl_checkbox',true,
			'boolean','Checkbox in comment form',$replace);
		
		$subscribetocomments_tpl_css = false;
		$theme = $settings->theme;
		if (($theme == 'default') OR ($theme == 'blueSilence'))
		{
			$subscribetocomments_tpl_css = true;
		}
		$settings->put('subscribetocomments_tpl_css',
			$subscribetocomments_tpl_css,'boolean','Add CSS rule',$replace);
		
		$settings->put('subscribetocomments_tpl_link',true,
			'boolean','Link to Subscribe to comments page',$replace);
	}
	
	/**
	get informations about a post
	@param	post_id <b>integer</b> Post ID
	@return	<b>array</b>	Array with informations
	*/
	public static function getPost($post_id)
	{
		global $core;

		$rs = $core->blog->getPosts(array('no_content' => true,
			'post_id' => $post_id, 'post_open_comment' => 1,
			'post_type' => self::getAllowedPostTypes())
		);

		if ($rs->isEmpty()) {return(false);}

		$array['title'] = $rs->post_title;
		# from getURL()
		$array['url'] = $rs->getURL();
		# /from getURL()
		return($array);
	}

	/**
	get available post types
	@return	<b>array</b>	Array with post types
	*/
	public static function getPostTypes($blog=false)
	{
		global $core;

		$rs = $core->con->select('SELECT post_type AS type '.
		'FROM '.$core->prefix.'post '.
		(($blog)
			?	'WHERE blog_id = \''.$core->con->escape($core->blog->id).'\' '
			: '').
		'GROUP BY type ORDER BY type ASC;');

		if ($rs->isEmpty()) {return(array());}

		$types = array();

		while ($rs->fetch())
		{
			$types[] = $rs->type;
		}

		return($types);
	}

	/**
	get allowed post types
	@return	<b>array</b>	Array with post types
	*/
	public static function getAllowedPostTypes()
	{
		global $core;

		$post_types = @unserialize(
			$core->blog->settings->subscribetocomments->subscribetocomments_post_types);

		if (empty($post_types))
		{
			return(array());
		}

		return($post_types);
	}

	/**
	behavior coreAfterCommentCreate
	@param	core <b></b> dcCore object
	@param	cur <b>cursor</b> Cursor
	\see	http://dev.dotclear.net/2.0/changeset/2181
	*/
	public static function coreAfterCommentCreate($core,$cur)
	{
		# ignore comments marked as spam or pending comments
		if ($cur->comment_status != 1)  {
			return;
		}
		
		if (isset($_POST['subscribeToComments']))
		{
			$subscriber = new subscriber($cur->comment_email);
			$subscriber->subscribe($cur->post_id);
		}
		self::send($cur,$cur->comment_id);
	}
	
	/**
	behavior coreAfterCommentUpdate
	@param	this_ <b></b> null
	@param	cur <b>cursor</b> Cursor
	@param	rs <b>recordset</b> Recordset
	*/
	public static function coreAfterCommentUpdate($this_,$cur,$rs)
	{
		$cur->post_id = $rs->post_id;
		$cur->comment_trackback = $rs->comment_trackback;
		self::send($cur,$rs->comment_id);
	}
	
	/**
	send emails
	@param	cur <b>cursor</b> Cursor
	@param	comment_id <b>integer</b> Comment ID
	*/
	public static function send($cur,$comment_id)
	{
		# We don't want notification for spam and trackbacks
		# from emailNotification (modified)
		# and pending comments
		if ($cur->comment_status != 1)  {
			return;
		}
		
		# /from emailNotification

		global $core;

		# we send only one mail to notify the subscribers
		# won't send multiple emails when updating an email from the backend
		$rs = $core->con->select(
			'SELECT notification_sent FROM '.$core->prefix.'comment '.
			'WHERE (comment_id = '.$core->con->escape($comment_id).') '.
			'AND (notification_sent = 1);'
		);

		if ($rs->isEmpty())
		{
			# get the subscribers' email addresses
			$rs = $core->con->select(
				'SELECT S.id, S.email, S.user_key FROM '.
				$core->prefix.'comment_subscriber S '.
				'INNER JOIN '.$core->prefix.'meta M ON '.
				(($core->con->driver() == 'pgsql') ?
				# CAST = PostgreSQL compatibility :
				# PGSQL need datas of the same type to compare
				'(S.id = CAST(M.meta_id AS integer))': 
				'(S.id = M.meta_id)').
				' AND (M.meta_type = \'subscriber\') AND (M.post_id = '.$cur->post_id.')'.
				' AND (S.email != \''.$cur->comment_email.'\')'.
				' AND (S.status = \'1\');'
			);

			# remember that the comment's notification was sent
			$cur_sent = $core->con->openCursor($core->prefix.'comment');
			$cur_sent->notification_sent = 1;
			$cur_sent->update('WHERE comment_id = '.
				$core->con->escape($comment_id).';');

			if (!$rs->isEmpty())
			{
				$post = self::getPost($cur->post_id);
				if ($post == false) {return;}
			
				# from emailNotification/behaviors.php
				$comment = preg_replace('%</p>\s*<p>%msu',"\n\n",$cur->comment_content);
				$comment = str_replace('<br />',"\n",$comment);
				$comment = html::clean($comment);

				while ($rs->fetch())
				{
					# email
					$subject = sprintf(
						self::getSetting('comment_subject'),
						$core->blog->name,$core->blog->url,$rs->email,
						subscriber::pageLink($rs->email,$rs->user_key),
						$post['title'],$post['url'],$post['url'].'#c'.$comment_id,
						$cur->comment_author,$comment);
					$content = sprintf(
						self::getSetting('comment_content'),
						$core->blog->name,$core->blog->url,$rs->email,
						subscriber::pageLink($rs->email,$rs->user_key),
						$post['title'],$post['url'],$post['url'].'#c'.$comment_id,
						$cur->comment_author,$comment);
					self::mail($rs->email,$subject,$content);
				}
			}
		}
	}

	/**
	send an email
	@param	to <b>string</b> Email recipient
	@param	subject <b>string</b> Email subject
	@param	content <b>string</b> Email content
	*/
	public static function mail($to,$subject,$content)
	{
		global $core;

		$headers = array(
			'From: '.$core->blog->settings->subscribetocomments->subscribetocomments_email_from,
			'MIME-Version: 1.0',
			'Content-Type: text/plain; charset=UTF-8;',
			'X-Mailer: Dotclear'
		);

		# from /dotclear/admin/auth.php : mail::B64Header($subject)
		mail::sendMail($to,mail::B64Header($subject),
			wordwrap($content,70),$headers);
	}

	/**
	redirect to an URL with a message and exit
	@param	get <b>string</b> GET URL
	*/
	public static function redirect($get='')
	{
		http::redirect(subscribeToComments::url().'/'.$get);
		exit();
	}
}