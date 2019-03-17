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
@brief Template
*/
class subscribeToCommentsTpl
{
	/**
	check the box on post.html if a cookie is present
	@return	<b>string</b> PHP block
	*/
	public static function formChecked()
	{
		return("<?php ".
		"if (isset(\$_POST['subscribeToComments'])) {echo(' checked=\"checked\" ');}".
		"elseif (isset(\$_COOKIE['subscribetocomments']))".
		"{echo(' checked=\"checked\" ');}".
		" ?>");
	}

	/**
	get link from post.html to subscriptions page
	@return	<b>string</b> text and PHP block
	*/
	public static function formLink()
	{
		global $core;

		if ($core->blog->settings->subscribetocomments->subscribetocomments_active)
		{
			return("<?php echo(subscribeToComments::url().".
			"((\$core->blog->settings->system->url_scan == 'query_string') ? '&amp;' : '?').".
			"'post_id='.\$_ctx->posts->post_id); ?>");
		}
	}
		
	/**
	if there is a message
	@param	attr	<b>array</b>	Attribute
	@param	content	<b>string</b>	Content
	@return	<b>string</b> PHP block
	*/
	public static function ifMessage($attr,$content)
	{
		return
		"<?php if (\$_ctx->subscribeToComments->message !== null) : ?>"."\n".
		$content.
		"<?php endif; ?>";
	}

	/**
	display a message
	@return	<b>string</b> PHP block
	*/
	public static function message()
	{
		return("<?php if (\$_ctx->subscribeToComments->message !== null) :"."\n".
		"echo(\$_ctx->subscribeToComments->message);".
		"endif; ?>");
	}

	/**
	get nonce
	@return	<b>string</b> Nonce
	*/
	public static function getNonce()
	{
		return "<?php echo(crypt::hmac(DC_MASTER_KEY,session_id())); ?>";
	}

	/**
	if it's a post
	@param	attr	<b>array</b>	Attribute
	@param	content	<b>string</b>	Content
	@return	<b>string</b> PHP block
	*/
	public static function entryIf($attr,$content)
	{
		return
		"<?php if ((isset(\$_GET['post_id'])) AND ".
		"(is_numeric(\$_GET['post_id']))) : "."\n".
		"\$_ctx->posts = \$core->blog->getPosts(".
		"array('no_content' => true, 'post_id' => \$_GET['post_id'],".
		"'post_open_comment' => 1,".
		"'post_type' => subscribeToComments::getAllowedPostTypes())".
		"); "."\n".
		"if (!\$_ctx->posts->isEmpty()) : ?>"."\n".
		$content.
		"<?php unset(\$_ctx->posts); ".
		"endif;"."\n".
		"endif; ?>";
	}

	/**
	if user is not logged in
	@param	attr	<b>array</b>	Attribute
	@param	content	<b>string</b>	Content
	@return	<b>string</b> PHP block
	*/
	public static function loggedIfNot($attr,$content)
	{
		return('<?php if (!$_ctx->subscribeToComments->checkCookie) : ?>'."\n".
		$content."\n".
		"<?php endif; ?>");
	}

	/**
	if user is logged in
	@param	attr	<b>array</b>	Attribute
	@param	content	<b>string</b>	Content
	@return	<b>string</b> PHP block
	*/
	public static function loggedIf($attr,$content)
	{
		return('<?php if ($_ctx->subscribeToComments->checkCookie) : ?>'."\n".
		$content."\n".
		"<?php endif; ?>");
	}

	/**
	if user is not blocked
	@param	attr	<b>array</b>	Attribute
	@param	content	<b>string</b>	Content
	@return	<b>string</b> PHP block
	*/
	public static function blockedIfNot($attr,$content)
	{
		return('<?php if (!$_ctx->subscribeToComments->blocked) : ?>'."\n".
		$content."\n".
		"<?php endif; ?>");
	}

	/**
	if user is blocked
	@param	attr	<b>array</b>	Attribute
	@param	content	<b>string</b>	Content
	@return	<b>string</b> PHP block
	*/
	public static function blockedIf($attr,$content)
	{
		return('<?php if ($_ctx->subscribeToComments->blocked) : ?>'."\n".
		$content."\n".
		"<?php endif; ?>");
	}

	/**
	loop on posts
	@param	attr	<b>array</b>	Attribute
	@param	content	<b>string</b>	Content
	@return	<b>string</b> PHP block
	*/
	public static function entries($attr,$content)
	{
		return("<?php ".
		'$_ctx->meta = new dcMeta($core);'.
		"\$_ctx->posts = \$_ctx->meta->getPostsByMeta(array(".
		"'meta_type' => 'subscriber','meta_id' => ".
		"subscriber::getCookie('id'),".
		"'no_content' => true,".
		"'post_type' => subscribeToComments::getAllowedPostTypes()));".
		"if (!\$_ctx->posts->isEmpty()) :"."\n".
		"while (\$_ctx->posts->fetch()) : ?>"."\n".
		$content.
		"<?php endwhile; "."\n".
		" endif;"."\n".
		'unset($_ctx->meta);'.
		"unset(\$_ctx->posts); ?>");
	}

	/**
	get email address
	@return	<b>string</b> PHP block
	*/
	public static function email()
	{
		return('<?php echo($_ctx->subscribeToComments->email); ?>');	
	}

	/**
	get the URL of the subscriptions page
	@return	<b>string</b> URL
	*/
	public static function url()
	{
		return("<?php echo(subscribeToComments::url()); ?>");
	}

	/**
	display checkbox to subscribe to comments
	*/
	public static function publicCommentFormAfterContent()
	{
		global $_ctx;

		if (subscribeToComments::getPost($_ctx->posts->post_id) == false)
		{return;}

		$checked = null;

		# if checkbox is unchecked, don't check it
		if (isset($_POST['subscribeToComments'])) 
			{$checked = true;}
		elseif (isset($_COOKIE['subscribetocomments']))
			{$checked = true;}
		if ($checked) {$checked =  ' checked="checked" ';}

		$logged = 
		(subscriber::checkCookie())
		?
			$logged = ' (<strong><a href="'.subscribeToComments::url().'">'.
				__('Logged in').'</a></strong>)'
		: '';
		
		echo '<p id="subscribeToComments_checkbox">'.
		'<label><input type="checkbox" name="subscribeToComments" '.
		'id="subscribeToComments"'.$checked.' /> '.
		__('Receive following comments by email').'</label>'.
		$logged.
		'</p>';
	}
	
	/**
	display a CSS rule for default themes
	*/
	public static function publicHeadContent()
	{
		echo '<style type="text/css" media="screen">'."\n".
		'#comment-form #subscribeToComments '.
		'{width:auto;border:0;margin:0 5px 0 140px;}'."\n".
		'</style>';
	}

	/**
	add tpl code after the <tpl:EntryIf comments_active="1">...</tpl:EntryIf> tag
	@param	core	<b>core</b>	Dotclear core
	@param	b	<b>array</b>	tag
	@param	attr	<b>array</b>	attributes
	*/
	
	public static function templateAfterBlock($core,$b,$attr)
	{
		global $_ctx;

		if ($core->url->type == 'feed') {return;}
		
		if (isset($attr['subscribetocomments_block'])
			&& ($attr['subscribetocomments_block'] == '0')) {return;}

		if ($b == 'EntryIf' && isset($attr['comments_active'])
			&& $attr['comments_active'] == 1 && !isset($attr['pings_active']))
		{
			$post_id = $_ctx->posts->post_id;
			if ((!is_numeric($post_id)) OR
				(!in_array($_ctx->posts->post_type,
					subscribeToComments::getAllowedPostTypes())))
			{
				return;
			}
			# else
			return 
			'<?php if (($core->blog->settings->subscribetocomments->subscribetocomments_active) &&
				$_ctx->posts->commentsActive()) : ?>
				<div id="subscribetocomments_block">
					<h3><?php echo __("Subscribe to comments"); ?></h3>
					<p>
						<a href="<?php echo(subscribeToComments::url().
						(($core->blog->settings->system->url_scan == "query_string") ? "&amp;" : "?").
						"post_id=".$_ctx->posts->post_id); ?>">
							<!-- # If the subscriber is logged in -->
							<?php if (subscriber::checkCookie()) : ?>
								<?php echo __("Subscribe to receive following comments by email or manage subscriptions"); ?>
							<?php endif; ?>
							<!-- # If the subscriber is not logged in -->
							<?php if (!subscriber::checkCookie()) : ?>
								<?php echo __("Subscribe to receive following comments by email"); ?>
							<?php endif; ?>
						</a>
					</p>
				</div>
			<?php endif; ?>';
			# strings
			__("Subscribe to receive following comments by email or manage subscriptions");
			__("Subscribe to receive following comments by email");
		}
	}
	
	public static function subscribeBlock($attr,$content)
	{
		# default value
		$id = 'subscribetocomments_block';
		if (isset($attr['id'])) {$id = html::escapeHTML($attr['id']);}
		if (!empty($id)) {$id = ' id="'.$id.'"';}
		
		$class = '';
		if (isset($attr['class']))
		{
			$class = ' class="'.html::escapeHTML($attr['class']).'"';
		}
		
		return 
		'<?php if (($core->blog->settings->subscribetocomments->subscribetocomments_active) &&
			$_ctx->posts->commentsActive()) : ?>
			<div'.$id.''.$class.'>
				<h3><?php echo __("Subscribe to comments"); ?></h3>
				<p>
					<a href="<?php echo(subscribeToComments::url().
					(($core->blog->settings->system->url_scan == "query_string") ? "&amp;" : "?").
					"post_id=".$_ctx->posts->post_id); ?>">
						<!-- # If the subscriber is logged in -->
						<?php if (subscriber::checkCookie()) : ?>
							<?php echo __("Subscribe to receive following comments by email or manage subscriptions"); ?>
						<?php endif; ?>
						<!-- # If the subscriber is not logged in -->
						<?php if (!subscriber::checkCookie()) : ?>
							<?php echo __("Subscribe to receive following comments by email"); ?>
						<?php endif; ?>
					</a>
				</p>
			</div>
		<?php endif; ?>';
	}
}