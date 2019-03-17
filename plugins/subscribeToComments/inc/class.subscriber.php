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
@brief Subscriber
*/
class subscriber
{
	public $email;
	public $id;

	private $key;

	private $link;

	private $blog_name;
	private $blog_url;

	/**
	construct
	@param	email	<b>string</b>	Email address
	*/
	public function __construct($email)
	{
		global $core;
		subscribeToComments::checkEmail($email);

		$this->email = $email;

		# create subscriber if it doesn't exist yet
		$this->id = self::getID($email);
		
		$rs = $core->con->select('SELECT user_key FROM '.$core->prefix.
			'comment_subscriber WHERE (id = \''.
			$core->con->escape((int) $this->id).'\') '.
			'AND (email = \''.$core->con->escape($this->email).'\');');
		
		if ($rs->isEmpty())
		{
			throw new Exception(__('Invalid email address or key.'));
		}
		# else
		$this->key = $rs->user_key;

		$this->link = self::pageLink($this->email,$this->key);

		$this->blog_name = $core->blog->name;
		$this->blog_url = $core->blog->url;
	}

	/**
	get the ID of an subscriber
	@param	email <b>string</b> Email address
	@return	<b>integer</b>	Subscriber ID
	*/
	private static function getID($email)
	{
		global $core;

		$rs = $core->con->select('SELECT id FROM '.
			$core->prefix.'comment_subscriber '.
			'WHERE (email = \''.$core->con->escape($email).'\');');
		if ($rs->isEmpty())
		{
			# create an subscriber if it doesn't exist yet
			return(self::createSubscriber($email));
		}
		else
		{
			return($rs->id);
		}
	}

	/**
	create a subscriber
	@param	email <b>string</b> Email address
	@return	<b>integer</b>	Subscriber ID
	*/
	private static function createSubscriber($email)
	{
		global $core;

		# from /dotclear/inc/core/class.dc.blog.php
		# Get ID
		$rs = $core->con->select('SELECT MAX(id) FROM '.$core->prefix.
			'comment_subscriber;');
		
		$id = (integer) $rs->f(0) + 1;
		# from /dotclear/inc/core/class.dc.blog.php

		# create a random key
		$key = http::browserUID(
			$email.crypt::hmac(DC_MASTER_KEY,crypt::createPassword()));

		$cur = $core->con->openCursor($core->prefix.'comment_subscriber');
		$cur->id = $id;
		$cur->email = $email;
		$cur->user_key = $key;
		$cur->status = 1;
		$cur->insert();

		# create the cookie
		$cookie = $key.bin2hex(pack('a32',$id));
		setcookie('subscribetocomments',$cookie,strtotime('+1 year'),'/');

		# email
		$subject = sprintf(subscribeToComments::getSetting('account_subject'),
			$core->blog->name,$core->blog->url,$email,
			self::pageLink($email,$key));
		$content = sprintf(subscribeToComments::getSetting('account_content'),
			$core->blog->name,$core->blog->url,$email,
			self::pageLink($email,$key));
		subscribeToComments::mail($email,$subject,$content);

		return($cur->id);
	}

	/**
	subscribe to a post
	@param	post_id	<b>integer</b>	Post ID
	*/
	public function subscribe($post_id)
	{
		global $core;

		if (!is_numeric($post_id)) {throw new Exception(__('Invalid post ID.'));}

		$post = subscribeToComments::getPost($post_id);

		if (subscribeToComments::getPost($post_id) == false)
		{throw new Exception(__('Invalid post.'));}

		global $core;

		$meta = new dcMeta($core);
		# subscribe the email (id) to the post (id)
		$rs = $meta->getMeta('subscriber',null,$this->id,$post_id);
		if ($rs->isEmpty())
		{
			# insert into $core->prefix.'meta' the id of the subscriber
			$cur = $core->con->openCursor($core->prefix.'meta');
			$cur->post_id = $post_id;
			$cur->meta_type = 'subscriber';
			$cur->meta_id = $this->id;
			$cur->insert();

			if ($core->blog->settings->subscribetocomments->subscribetocomments_subscribe_active)
			{
				# email
				$subject = sprintf(
					subscribeToComments::getSetting('subscribe_subject'),
					$this->blog_name,$this->blog_url,$this->email,$this->link,
					$post['title'],$post['url']);
				$content = sprintf(
					subscribeToComments::getSetting('subscribe_content'),
					$this->blog_name,$this->blog_url,$this->email,$this->link,
					$post['title'],$post['url']);
				subscribeToComments::mail($this->email,$subject,$content);
			}
		}
	}

	/**
	send an email before changing email
	@param	new_email	<b>string</b>	New email
	*/
	public function requestUpdateEmail($new_email)
	{
		global $core;

		$rs = $core->con->select('SELECT id FROM '.$core->prefix.'comment_subscriber '.
			'WHERE (email = \''.$core->con->escape($new_email).'\') LIMIT 1;');
		if (!$rs->isEmpty())
		{
			throw new Exception(__('This email address already exists.'));
		}

		# create a random key
		$key = http::browserUID(
			$new_email.crypt::hmac(DC_MASTER_KEY,crypt::createPassword()));

		$cur = $core->con->openCursor($core->prefix.'comment_subscriber');
		$cur->temp_key = $key;
		$cur->temp_expire = date('Y-m-d H:i:s',strtotime('+1 day'));
		$cur->update('WHERE (id = \''.$core->con->escape($this->id).'\') '.
		'AND (user_key = \''.$core->con->escape($this->key).'\');');

		$url = subscribeToComments::url().
		(($core->blog->settings->system->url_scan == 'query_string') ? '&' : '?').
		'new_email='.urlencode($new_email).'&temp_key='.$key;

		$subject = sprintf(subscribeToComments::getSetting('email_subject'),
			$this->blog_name,$this->blog_url,$this->email,$this->link,$new_email,$url);
		$content = sprintf(subscribeToComments::getSetting('email_content'),
			$this->blog_name,$this->blog_url,$this->email,$this->link,$new_email,$url);

		# email to new email
		subscribeToComments::mail($new_email,$subject,$content);
	}

	/**
	remove subscriptions
	@param	posts	<b>array</b>	Posts
	*/
	public function removeSubscription($posts)
	{
		if (is_array($posts))
		{
			global $core;

			foreach ($posts as $k => $v)
			{
				if (is_numeric($v))
				{
					$core->con->execute('DELETE FROM '.$core->prefix.'meta WHERE '.
						'(post_id = \''.$core->con->escape($v).'\') '.
						'AND (meta_type = \'subscriber\') AND '.
						'(meta_id = \''.$core->con->escape($this->id).'\');');
				}
			}
		}
	}

	/**
	delete subscriptions and account
	*/
	public function deleteAccount()
	{
		global $core;

		# delete subscriptions
		$core->con->execute('DELETE FROM '.$core->prefix.'meta WHERE '.
			'(meta_type = \'subscriber\') '.
			'AND (meta_id = \''.$core->con->escape($this->id).'\');');
		# delete subscriber
		$core->con->execute('DELETE FROM '.$core->prefix.'comment_subscriber '.
			'WHERE (id = \''.$core->con->escape($this->id).'\') '.
			'AND (user_key = \''.$core->con->escape($this->key).'\');');
		self::logout();
	}

	/**
	block emails
	@param	block	<b>boolean</b> Block emails
	*/
	public function blockEmails($block=false)
	{
		global $core;

		# update status
		$cur = $core->con->openCursor($core->prefix.'comment_subscriber');
		$cur->status = (($block) ? -1 : 1);
		$cur->update('WHERE (id = \''.$core->con->escape($this->id).'\') '.
			'AND (user_key = \''.$core->con->escape($this->key).'\');');
	}

	/* /functions used by object */

	/* static functions */
	
	/**
	logout, destroy cookie
	*/
	public static function logout()
	{
		session_regenerate_id();

		# delete the cookie
		unset($_COOKIE['subscribetocomments']);
		setcookie('subscribetocomments','',0,'/');
	}	

	/**
	login with to a key
	@param	email	<b>string</b> Email
	@param	key	<b>string</b> Key
	*/
	public static function loginKey($email,$key)
	{
		self::logout();

		global $core;

		$rs = $core->con->select('SELECT id, user_key FROM '.
			$core->prefix.'comment_subscriber '.
			' WHERE (email = \''.$core->con->escape($email).'\')'.
			' AND (user_key = \''.$core->con->escape($key).'\');');
		if ($rs->isEmpty())
		{
			throw new Exception(__('Invalid email address or key.'));
		}

		session_regenerate_id();

		# create the cookie
		$cookie = $rs->user_key.bin2hex(pack('a32',$rs->id));
		setcookie('subscribetocomments',$cookie,strtotime('+1 year'),'/');
	}

	/**
	resend informations to an email address
	@param	email <b>string</b> Email address
	*/
	public static function resendInformations($email)
	{
		global $core;

		$rs = $core->con->select('SELECT id, email, user_key FROM '.
			$core->prefix.'comment_subscriber '.
			' WHERE (email = \''.$core->con->escape($email).'\')'.
			' AND (status = \'1\');');
		if (!$rs->isEmpty())
		{
			# email
			$subject = sprintf(
				subscribeToComments::getSetting('account_subject'),
				$core->blog->name,$core->blog->url,$rs->email,
				self::pageLink($rs->email,$rs->user_key));
			$content = sprintf(
				subscribeToComments::getSetting('account_content'),
				$core->blog->name,$core->blog->url,$rs->email,
				self::pageLink($rs->email,$rs->user_key));
			subscribeToComments::mail($rs->email,$subject,$content);
		}
		# don't show an error if the email does not exist
		# prevent brut force attacks to guess existing emails
		# 0.5 to 2 seconds
		usleep(rand(50000,2000000));
	}

	/**
	check the cookie
	@return <b>boolean</b>	Subscriber is identified
	*/
	public static function checkCookie()
	{
		$id = self::getCookie('id');
		$key = self::getCookie('key');

		global $core;

		if (!is_numeric($id)) {return;}

		$rs = $core->con->select('SELECT user_key FROM '.
			$core->prefix.'comment_subscriber '.
			'WHERE (id = \''.$core->con->escape($id).'\') '.
			'AND (user_key = \''.$core->con->escape($key).'\');');
		if ($rs->isEmpty())
		{
			return(false);
		}
		# else
		return($key == $rs->user_key);
	}

	/**
	check nonce when a action is requested with $_POST
	*/
	public static function checkNonce()
	{
		# from /dotclear/inc/admin/prepend.php, modified
		if ((empty($_POST['subscribeToCommentsNonce'])) OR
			($_POST['subscribeToCommentsNonce'] != 
				crypt::hmac(DC_MASTER_KEY,session_id()))
		)
		{
			http::head(412);
			header('Content-Type: text/html');
			echo 'Precondition Failed';
			echo '<br /><a href="'.subscribeToComments::url().'">'.
				__('Reload the page').'</a>';
			exit;
		}
	}

	/**
	if emails are blocked
	@return <b>boolean</b>	Emails are blocked
	*/
	public static function blocked()
	{
		$id = self::getCookie('id');
		$key = self::getCookie('key');

		global $core;

		$rs = $core->con->select('SELECT status FROM '.
			$core->prefix.'comment_subscriber '.
			'WHERE (id = \''.$core->con->escape((int) $id).'\') '.
			'AND (user_key = \''.$core->con->escape($key).'\');');
		if ($rs->isEmpty())
		{
			return(false);
		}
		# else
		return($rs->status == -1);
	}

	/**
	get the URL of the subscriptions page with email
	@param	id	<b>interger</b>	Subscriber ID
	@return	<b>string</b> URL
	*/
	public static function pageLink($email,$key)
	{
		global $core;
		
		return(subscribeToComments::url().
		(($core->blog->settings->system->url_scan == 'query_string') ? '&' : '?').'email='.
		urlencode($email).'&key='.$key);
	}

	/**
	get informations from the cookie
	@param	value <b>string</b> Value to get
	@return	<b>string</b>	Subscriber ID
	*/
	public static function getCookie($value)
	{
		if ((!isset($_COOKIE['subscribetocomments']))
			|| (strlen($_COOKIE['subscribetocomments']) != 104))
		{
			return(false);
		}

		$id = substr($_COOKIE['subscribetocomments'],40);
		$id = @unpack('a32',@pack('H*',$id));
		if (is_array($id))
		{
			$id = $id[1];
		}

		if ($value == 'id')
		{
			return($id);
		}
		elseif ($value == 'key')
		{
			return(substr($_COOKIE['subscribetocomments'],0,40));
		}
		elseif ($value == 'email')
		{
			global $core;

			$rs = $core->con->select('SELECT email FROM '.
				$core->prefix.'comment_subscriber '.
				'WHERE (id = \''.$core->con->escape((int) $id).'\');');

			if ($rs->isEmpty())
			{
				return(false);
			}
			# else
			return($rs->email);
		}
		return(false);
	}

	/**
	update the email address
	@param	new_email	<b>string</b>	New email
	*/
	public static function updateEmail($new_email,$temp_key)
	{
		global $core;

		$rs = $core->con->select('SELECT id, email, temp_expire FROM '.
			$core->prefix.'comment_subscriber '.
			'WHERE (temp_key = \''.$core->con->escape($temp_key).'\') LIMIT 1;');
		if ($rs->isEmpty()) {throw new Exception(__('Invalid key.'));}
		$rs_new_email = $core->con->select(
			'SELECT id FROM '.$core->prefix.'comment_subscriber '.
			'WHERE (email = \''.$core->con->escape($new_email).'\') LIMIT 1;');
		
		if (!$rs_new_email->isEmpty())
		{
			throw new Exception(__('This email address already exists.'));
		}
		if ($rs->temp_expire < date('Y-m-d H:i:s'))
		{
			throw new Exception(__('Link expired, request another email.'));
		}

		# create a random key
		$key = http::browserUID(
			$new_email.crypt::hmac(DC_MASTER_KEY,crypt::createPassword()));

		$cur = $core->con->openCursor($core->prefix.'comment_subscriber');
		$cur->email = $new_email;
		$cur->user_key = $key;
		$cur->temp_key = null;
		$cur->temp_expire = null;
		$cur->update('WHERE (id = \''.$core->con->escape((int) $rs->id).'\') '.
		'AND (temp_key = \''.$core->con->escape($temp_key).'\');');

		$subject = sprintf(subscribeToComments::getSetting('account_subject'),
			$core->blog->name,$core->blog->url,$new_email,
			self::pageLink($new_email,$key));
		$content = sprintf(subscribeToComments::getSetting('account_content'),
			$core->blog->name,$core->blog->url,$new_email,
			self::pageLink($new_email,$key));

		# email to new email
		subscribeToComments::mail($new_email,$subject,$content);

		# login with new email
		self::loginKey($new_email,$key);
	}
}