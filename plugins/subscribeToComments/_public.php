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

l10n::set(dirname(__FILE__).'/locales/'.$_lang.'/public');

$core->tpl->addBlock('SubscribeToCommentsIsActive',
	'subscribeToCommentsIsActive');

function subscribeToCommentsIsActive($attr,$content)
{
	return '<?php if ($core->blog->settings->subscribetocomments_active) : ?>'.
		$content.
		'<?php endif; ?>';
}

$core->blog->settings->addNamespace('subscribetocomments');
if ($core->blog->settings->subscribetocomments->subscribetocomments_active)
{
	# behaviors
	$core->addBehavior('coreAfterCommentCreate',array('subscribeToComments',
		'coreAfterCommentCreate'));
	
	# post.html
	# used by <tpl:SysIf has_tag="SubscribeToCommentsFormChecked">
	$core->tpl->addValue('SubscribeToCommentsFormChecked',
		array('subscribeToCommentsTpl','formChecked'));
	$core->tpl->addValue('SubscribeToCommentsFormLink',
		array('subscribeToCommentsTpl','formLink'));

	# blocks
	$core->tpl->addBlock('SubscribeToCommentsLoggedIf',
		array('subscribeToCommentsTpl','loggedIf'));
	$core->tpl->addBlock('SubscribeToCommentsLoggedIfNot',
		array('subscribeToCommentsTpl','loggedIfNot'));
	$core->tpl->addBlock('SubscribeToCommentsBlockedIf',
		array('subscribeToCommentsTpl','blockedIf'));
	$core->tpl->addBlock('SubscribeToCommentsBlockedIfNot',
		array('subscribeToCommentsTpl','blockedIfNot'));

	# nonce
	$core->tpl->addValue('SubscribeToCommentsNonce',
		array('subscribeToCommentsTpl','getNonce'));

	# page
	$core->tpl->addBlock('SubscribeToCommentsEntryIf',
		array('subscribeToCommentsTpl','entryIf'));
	
	# message
	$core->tpl->addBlock('SubscribeToCommentsIfMessage',
		array('subscribeToCommentsTpl','ifMessage'));
	$core->tpl->addValue('SubscribeToCommentsMessage',
		array('subscribeToCommentsTpl','message'));

	# form	
	$core->tpl->addValue('SubscribeToCommentsURL',
		array('subscribeToCommentsTpl','url'));
	$core->tpl->addValue('SubscribeToCommentsEmail',
		array('subscribeToCommentsTpl','email'));

	# posts
	$core->tpl->addBlock('SubscribeToCommentsEntries',
		array('subscribeToCommentsTpl','entries'));
	
	# link
	$core->tpl->addValue('SubscribeToCommentsSubscribeBlock',
		array('subscribeToCommentsTpl','subscribeBlock'));

	# add code to post.html
	if ($core->blog->settings->subscribetocomments->subscribetocomments_tpl_checkbox === true)
	{
		$core->addBehavior('publicCommentFormAfterContent',
			array('subscribeToCommentsTpl','publicCommentFormAfterContent'));
	}
	if ($core->blog->settings->subscribetocomments->subscribetocomments_tpl_css === true)
	{
		$core->addBehavior('publicHeadContent',
			array('subscribeToCommentsTpl','publicHeadContent'));
	}
	if ($core->blog->settings->subscribetocomments->subscribetocomments_tpl_link === true)
	{
		$core->addBehavior('templateAfterBlock',
			array('subscribeToCommentsTpl','templateAfterBlock'));
	}
}