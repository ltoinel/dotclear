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
@brief Document
*/
class subscribeToCommentsDocument extends dcUrlHandlers
{
	/**
	serve the document
	*/
	public static function page($args)
	{
		global $core;

		if (!$core->blog->settings->subscribetocomments->subscribetocomments_active)
		{
			self::p404();
			return;
		}
		
		$session_id = session_id();
		if (empty($session_id)) {session_start();}
		
		$_ctx =& $GLOBALS['_ctx'];
		
		$_ctx->subscribeToComments = new ArrayObject();
		$_ctx->subscribeToComments->email = '';
		$_ctx->subscribeToComments->checkCookie = false;
		$_ctx->subscribeToComments->blocked = false;
		
		try {
			subscribeToComments::cleanKeys();

			if (((isset($_GET['post_id']))) && (!is_numeric($_GET['post_id'])))
			{
				throw new Exception(__('Invalid post ID.'));
			}

			if (isset($_POST['logout'])) {
				subscriber::checkNonce();
				subscriber::logout();
				subscribeToComments::redirect('loggedout');
			}
			# login with key
			elseif ((isset($_GET['email'])) AND (isset($_GET['key'])))
			{
				subscribeToComments::checkEmail($_GET['email']);
				subscribeToComments::checkKey($_GET['key']);
				subscriber::loginKey($_GET['email'],$_GET['key']);
				subscribeToComments::redirect('loggedin');
			}
			# subscribe
			elseif ((isset($_POST['subscribe'])) AND (isset($_POST['post_id'])))
			{
				subscriber::checkNonce();
				if (isset($_POST['email']))
				{
					subscribeToComments::checkEmail($_POST['email']);
					$email = $_POST['email'];
				}
				elseif (subscriber::checkCookie())
				{
					$email = subscriber::getCookie('email');
				}
				if (!empty($email))
				{
					$subscriber = new subscriber($email);
					$subscriber->subscribe($_POST['post_id']);
					subscribeToComments::redirect('subscribed');
				}
			}
			# request account informations
			elseif ((isset($_POST['resend'])) AND (isset($_POST['email'])))
			{
				subscriber::checkNonce();
				subscribeToComments::checkEmail($_POST['email']);
				subscriber::resendInformations($_POST['email']);
				subscribeToComments::redirect('informationsresent');
			}
			# update the email address
			elseif ((isset($_GET['new_email'])) AND (isset($_GET['temp_key'])))
			{
				subscribeToComments::checkEmail($_GET['new_email']);
				subscribeToComments::checkKey($_GET['temp_key']);
				subscriber::updateEmail($_GET['new_email'],$_GET['temp_key']);
				subscribeToComments::redirect('updatedemail');
			}
			
			# email address
			$_ctx->subscribeToComments->email = '';
			if (isset($_COOKIE['comment_info']))
			{
				$email = explode("\n",$_COOKIE['comment_info']);
				$_ctx->subscribeToComments->email = $email['1'];
				unset($email);
			}

			# subscriber is logged in
			$_ctx->subscribeToComments->checkCookie = subscriber::checkCookie();
			if ($_ctx->subscribeToComments->checkCookie)
			{
				$subscriber = new subscriber(subscriber::getCookie('email'));
				$_ctx->subscribeToComments->email = $subscriber->email;
	
				if ((isset($_POST['requestChangeEmail'])) AND (isset($_POST['new_email'])))
				{
					subscriber::checkNonce();
					subscribeToComments::checkEmail($_POST['new_email']);
					$subscriber->requestUpdateEmail($_POST['new_email']);
					subscribeToComments::redirect('requestsent');	
				}
				elseif ((isset($_POST['remove'])) AND (isset($_POST['entries'])))
				{
					subscriber::checkNonce();
					$subscriber->removeSubscription($_POST['entries']);
					subscribeToComments::redirect('removedsubscriptions');
				}
				elseif (isset($_POST['deleteAccount'])) {
					subscriber::checkNonce();
					$subscriber->deleteAccount();
					subscribeToComments::redirect('accountdeleted');
				}
				elseif (isset($_POST['blockEmails'])) {
					subscriber::checkNonce();
					$subscriber->blockEmails(true);
					subscribeToComments::redirect('emailsblocked');
				}
				elseif (isset($_POST['allowEmails'])) {
					subscriber::checkNonce();
					$subscriber->blockEmails(false);
					subscribeToComments::redirect('emailsallowed');
				}
			}
		}
		catch (Exception $e)
		{
			$_ctx->form_error = $e->getMessage();
		}
		
		$_ctx->subscribeToComments->blocked = subscriber::blocked();
		
		# message
		# inspired by contactMe/_public.php
		switch($args)
		{
			case 'informationsresent' :
				$msg = __('Account informations sent');
				break;
			case 'removedsubscriptions' :
				$msg = __('Subscriptions removed');
				break;
			case 'loggedout' :
				$msg = __('Logged out');
				break;
			case 'loggedin' :
				$msg = __('Logged in');
				break;
			case 'emailsblocked' :
				$msg = __('Emails blocked');
				break;
			case 'emailsallowed' :
				$msg = __('Emails allowed');
				break;
			case 'requestsent' :
				$msg = __('An email has been sent to the new email address');
				break;
			case 'updatedemail' :
				$msg = __('Email address changed');
				break;
			case 'accountdeleted' :
				$msg = __('Account deleted');
				break;
			case 'subscribed' :
				$msg = __('Subscribed to the entry');
				break;
			 default :
			 	$msg = null;
			 	break;
		}
		
		$_ctx->subscribeToComments->message = $msg;
		# /message
		
        $tplset = $core->themes->moduleInfo($core->blog->settings->system->theme,'tplset');
        if (!empty($tplset) && is_dir(dirname(__FILE__).'/../default-templates/'.$tplset)) {
            $core->tpl->setPath($core->tpl->getPath(), dirname(__FILE__).'/../default-templates/'.$tplset);
        } else {
            $core->tpl->setPath($core->tpl->getPath(), dirname(__FILE__).'/../default-templates/'.DC_DEFAULT_TPLSET);
        }
		
		self::serveDocument('subscribetocomments.html','text/html',false,false);
	}
}