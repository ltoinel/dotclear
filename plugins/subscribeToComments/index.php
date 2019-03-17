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

l10n::set(dirname(__FILE__).'/locales/'.$_lang.'/admin');

$page_title = __('Subscribe to comments');

# format tables' tbody
function tbody ($array)
{
	foreach ($array as $k => $v)
	{
		echo('<tr><td><code>'.$k.'</code></td><td>'.$v['name'].'</td></tr>');
	}
}

# code for template files
$post_form = '<tpl:SysIf has_tag="SubscribeToCommentsFormChecked">
<tpl:SubscribeToCommentsIsActive>
<p id="subscribeToComments_checkbox">
	<label><input type="checkbox" name="subscribeToComments" id="subscribeToComments"
		{{tpl:SubscribeToCommentsFormChecked}} /> 
	 {{tpl:lang Receive following comments by email}}</label>
	<tpl:SubscribeToCommentsLoggedIf>
		(<strong><a href="{{tpl:SubscribeToCommentsFormLink}}">{{tpl:lang Logged in}}</a></strong>)
	</tpl:SubscribeToCommentsLoggedIf>
</p>
</tpl:SubscribeToCommentsIsActive>
</tpl:SysIf>';

$post_css = '#comment-form #subscribeToComments {
width:auto;
border:0;
margin:0 5px 0 140px;
}';

$post_link = '<tpl:SysIf has_tag="SubscribeToCommentsFormChecked">
<tpl:SubscribeToCommentsIsActive>
<div id="subscribetocomments_block">
	<h3>{{tpl:lang Subscribe to comments}}</h3>
	<p>
		<a href="{{tpl:SubscribeToCommentsFormLink}}">
			<!-- # If the subscriber is logged in -->
			<tpl:SubscribeToCommentsLoggedIf>
				{{tpl:lang Subscribe to receive following comments by email or manage subscriptions}}
			</tpl:SubscribeToCommentsLoggedIf>
			<!-- # If the subscriber is not logged in -->
			<tpl:SubscribeToCommentsLoggedIfNot>
				{{tpl:lang Subscribe to receive following comments by email}}
			</tpl:SubscribeToCommentsLoggedIfNot>
		</a>
	</p>
</div>
</tpl:SubscribeToCommentsIsActive>
</tpl:SysIf>';

# tags to format emails
$tags_global = array(
	'[blogname]' => array('name'=>__('Blog name'),'tag'=>'%1$s'),
	'[blogurl]' => array('name'=>__('Blog URL'),'tag'=>'%2$s'),
	'[email]' => array('name'=>__('Email address'),'tag'=>'%3$s'),
	'[manageurl]' => array(
	'name'=> sprintf(__('%s\'s page URL'),__('Subscribe to comments')),
		'tag'=>'%4$s')
);
$tags_account = array();
$tags_subscribe = array(
	'[posttitle]' => array('name'=>__('Post title'),'tag'=>'%5$s'),
	'[posturl]' => array('name'=>__('Post URL'),'tag'=>'%6$s'),
);
$tags_comment = array(
	'[posttitle]' => array('name'=>__('Post title'),'tag'=>'%5$s'),
	'[posturl]' => array('name'=>__('Post URL'),'tag'=>'%6$s'),
	'[commenturl]' => array('name'=>__('URL to new comment'),'tag'=>'%7$s'),
	'[commentauthor]' => array('name'=>__('Comment author'),'tag'=>'%8$s'),
	'[commentcontent]' => array('name'=>__('Comment content'),
		'tag'=>'%9$s'),
);
$tags_email = array(
	'[newemail]' => array('name'=>
		__('New email address'),'tag'=>'%5$s'),
	'[emailurl]' => array('name'=>
		__('URL to confirm the change of email address'),'tag'=>'%6$s')
);

$core->blog->settings->addNamespace('subscribetocomments');
$settings =& $core->blog->settings->subscribetocomments;

# get languages list to restore settings
$lang_combo = array();

$locales = files::getDirList(dirname(__FILE__).'/locales/');

$langs = l10n::getISOcodes(false,true);

foreach ($locales['dirs'] as $k => $v)
{
	$lang = basename($v);
	if (array_key_exists($lang,$langs))
	{
		$lang_combo[$langs[$lang].' '.
			(($lang == $settings->lang) ? __('(blog language)'): '')] = $lang;
	}
}

unset($locales,$langs);

$msg = '';

$default_tab = 'settings';

$available_tags = array();

try
{
	# install the plugin
	if (!empty($_POST['enable']))
	{
		if (!empty($_POST['subscribetocomments_active']))
		{
			# Enable Subscribe to comments
			$settings->put('subscribetocomments_active',
				true,'boolean','Enable Subscribe to comments');
			
			# put settings, will set empty settings
			subscribeToComments::setDefaultSettings(false,$settings->lang);
			
			http::redirect($p_url.'&saveconfig=1');
		} else {
			# Disable Subscribe to comments
			$settings->put('subscribetocomments_active',
				false,'boolean','Enable Subscribe to comments');
				
			http::redirect($p_url);
		}
	}
	# test email
	elseif (isset($_POST['test']))
	{
		# mail
		$title = sprintf(__('Test email from your blog - %s'),$core->blog->name);
		$content = sprintf(__('The plugin % works.'),__('Subscribe to comments'));
		subscribeToComments::checkEmail($_POST['test_email']);
		subscribeToComments::mail($_POST['test_email'],$title,$content);
		http::redirect($p_url.'&test=1');
	}
	# restore default settings
	elseif (isset($_POST['restore']))
	{
		subscribeToComments::setDefaultSettings(true,$_POST['lang']);
		
		http::redirect($p_url.'&restore=1');
	}
	# save settings
	elseif (!empty($_POST['saveconfig']))
	{
		# Enable Subscribe to comments
		$settings->put('subscribetocomments_active',
			(!empty($_POST['subscribetocomments_active'])),'boolean',
			'Enable Subscribe to comments');

		subscribeToComments::checkEmail(
			$_POST['subscribetocomments_email_from']);
		# Define From: header of outbound emails
		$settings->put('subscribetocomments_email_from',
			$_POST['subscribetocomments_email_from'],
			'text','Define From: header of outbound emails');			

		# Allowed post types
		$settings->put('subscribetocomments_post_types',
			serialize($_POST['post_types']),
			'string','Allowed post types');

		# Account subject
		$settings->put('subscribetocomments_account_subject',
			subscribeToComments::format($available_tags,$_POST['account_subject'],false,true),
			'text','Account subject');
		# Account content
		$settings->put('subscribetocomments_account_content',
			subscribeToComments::format($available_tags,$_POST['account_content'],false,true),
			'text','Account content');

		$available_tags = $tags_subscribe;
		# Send an email for each subscription
		$settings->put('subscribetocomments_subscribe_active',
			(!empty($_POST['subscribetocomments_subscribe_active'])),'boolean',
			'Send an email for each subscription');
		# Subscription subject
		$settings->put('subscribetocomments_subscribe_subject',
			subscribeToComments::format($available_tags,$_POST['subscribe_subject'],false,true),'text','Subscription subject');
		# Subscription content
		$settings->put('subscribetocomments_subscribe_content',
			subscribeToComments::format($available_tags,$_POST['subscribe_content'],false,true),'text','Subscription content');

		$available_tags = $tags_comment;
		# Comment subject
		$settings->put('subscribetocomments_comment_subject',
			subscribeToComments::format($available_tags,$_POST['comment_subject'],false,true),'text','Comment subject');
		# Comment content
		$settings->put('subscribetocomments_comment_content',
			subscribeToComments::format($available_tags,$_POST['comment_content'],false,true),'text','Comment content');

		$available_tags = $tags_email;
		# Email subject
		$settings->put('subscribetocomments_email_subject',
			subscribeToComments::format($available_tags,$_POST['email_subject'],false,true),'text','Email subject');
		# Email content
		$settings->put('subscribetocomments_email_content',
			subscribeToComments::format($available_tags,$_POST['email_content'],false,true),'text','Email content');

		http::redirect($p_url.'&saveconfig=1');
	}
	elseif (!empty($_POST['saveconfig_display']))
	{
		# display
		$settings->put('subscribetocomments_tpl_checkbox',
			(!empty($_POST['subscribetocomments_tpl_checkbox'])),'boolean',
			'Checkbox in comment form');
		$settings->put('subscribetocomments_tpl_css',
			(!empty($_POST['subscribetocomments_tpl_css'])),'boolean',
			'Add CSS rule');
		$settings->put('subscribetocomments_tpl_link',
			(!empty($_POST['subscribetocomments_tpl_link'])),'boolean',
			'Link to Subscribe to comments page');

		$core->blog->triggerBlog();
		
		http::redirect($p_url.'&saveconfig=1&tab=display');
	}
	elseif (!empty($_POST['delete_subscribers']))
	{
		if (isset($_POST['subscribers']))
		{
			foreach ($_POST['subscribers'] as $email)
			{
				$subscriber = new subscriber($email);
				$subscriber->deleteAccount();
			}
			
			http::redirect($p_url.'&subscribers_deleted=1&tab=subscribers');
		}
		else
		{
			http::redirect($p_url.'&tab=subscribers');
		}
	}
}
catch (Exception $e)
{
	$core->error->add($e->getMessage());
}

if (isset($_GET['test']))
{
	$msg = __('Test email sent.');
}
elseif (isset($_GET['restore']))
{
	$msg = __('Settings restored.');
}
elseif (isset($_GET['saveconfig']))
{
	$msg = __('Configuration successfully updated.');
}
elseif (isset($_GET['subscribers_deleted']))
{
	$msg = __('Subscribers deleted successfully.');
}

if (isset($_GET['tab']))
{
	$default_tab = $_GET['tab'];
}

?>
<html>
<head>
	<title><?php echo $page_title; ?></title>
	<?php echo dcPage::jsPageTabs($default_tab); ?>
	<style type="text/css">
		p.code {
			border:1px solid #ccc;
			width:100%;
			overflow:auto;
			white-space:pre;
		}
		textarea {width:100%;}
	</style>
	<script type="text/javascript">
	//<![CDATA[
		$(document).ready(function() {
			// loading
			function initDisplay() {
				$('#display input[type="checkbox"]').each(function() {
						if ($(this).attr('checked')) {
							$('#'+$(this).attr('id').replace('subscribetocomments','code')).slideUp(0);
						} else {
							$('#'+$(this).attr('id').replace('subscribetocomments','code')).slideDown(0);
						}
					});
			}
			// if the active tab is "display"
			initDisplay();
			// if the active tab is not "display"
			$(".multi-part").tabload(function() {
				initDisplay();
			});
			
			// "dynamic" display
			$('#display input[type="checkbox"]').each(function() {
				$(this).css({margin:'10px'});
				$(this).click(function() {
					if ($(this).attr('checked')) {
						$('#'+$(this).attr('id').replace('subscribetocomments','code')).slideUp("slow");
					} else {
						$('#'+$(this).attr('id').replace('subscribetocomments','code')).slideDown("slow");
					}
				});
			});
			$('#restore_button').click(function() {
				return(window.confirm('<?php echo __('Restore default settings? The old settings will be deleted.'); ?>'));
			});
			
			$('input[name="delete_subscribers"]').click(function() {
				return window.confirm('<?php echo __('Delete selected subscribers?'); ?>');
			});
		});
	//]]>
	</script>
</head>
<body>
<?php

	echo dcPage::breadcrumb(
		array(
			html::escapeHTML($core->blog->name) => '',
			'<span class="page-title">'.$page_title.'</span>' => ''
		));

if (!empty($msg)) {
  dcPage::success($msg);
}
?>

<?php if (!$settings->subscribetocomments_active)
{ ?>
	<form method="post" action="<?php echo http::getSelfURI(); ?>">
		<p><?php echo(__('The plugin is disabled.')); ?></p>
		<p>
			<?php echo(form::checkbox('subscribetocomments_active',1,
				$settings->subscribetocomments_active)); ?>
			<label class="classic" for="subscribetocomments_active">
			<?php printf(__('Enable %s'),__('Subscribe to comments')); ?></label>
		</p>

		<p><?php echo $core->formNonce(); ?></p>
		<p><input type="submit" name="enable" value="<?php echo __('Save configuration'); ?>" /></p>
	</form>
<?php } else { ?>
	<div class="multi-part" id="settings" title="<?php echo __('Settings'); ?>">
		<form method="post" action="<?php echo http::getSelfURI(); ?>">
			<p>
				<?php echo(form::checkbox('subscribetocomments_active',1,
					$settings->subscribetocomments_active)); ?>
				<label class="classic" for="subscribetocomments_active">
				<?php printf(__('Enable %s'),__('Subscribe to comments')); ?></label>
			</p>
			<p>
				<label for="subscribetocomments_email_from">
				<?php echo(__('Define From: header of outbound emails:').
					form::field('subscribetocomments_email_from',80,80,
					$settings->subscribetocomments_email_from)); ?>
				</label>
			</p>

			<h3><?php echo(__('Post types')); ?></h3>
			<p><?php printf(__('Enable %s with the following post types:'),
				__('Subscribe to comments')); ?></p>
			<?php
				$available_post_types = subscribeToComments::getPostTypes(true);
				$post_types = subscribeToComments::getAllowedPostTypes();
				if (!empty($available_post_types))
				{
					echo '<ul>';
					foreach ($available_post_types as $type)
					{
						echo('<li>'.form::checkbox(array('post_types[]',$type),$type,
							in_array($type,$post_types)).
						' <label class="classic" for="'.$type.'">'.$type.
						'</label></li>');
					}
					echo '</ul>';
				}
				else
				{
					echo('<p>'.__('No entry yet. Create a new entry.').'</p>');
				}
			?>
			
			<h3><?php echo(__('Email formatting')); ?></h3>
			<p><?php echo(__('You can format the emails using the following tags.').' '.
			__('Each tag will be replaced by the associated value.')); ?></p>
			<h3><?php echo(__('Tags available in all the contexts')); ?></h3>

			<table class="clear">
				<thead>
					<tr><th><?php echo(__('Tag')); ?></th><th><?php echo(__('Value')); ?></th></tr>
				</thead>
				<tbody>
					<?php tbody($tags_global); ?>
				</tbody>
			</table>

			<fieldset>
				<legend><?php echo(__('Email sent when an account is created or if a subscriber request it')); ?></legend>
				<p>
					<label for="account_subject"><?php echo(__('Subject:').
						form::field('account_subject',80,255,
						html::escapeHTML(subscribeToComments::format($tags_global,
						subscribeToComments::getSetting('account_subject'),true)))); ?>
					</label>
				</p>
				<p>
					<label for="account_content"><?php echo(__('Content:').
						form::textarea('account_content',80,15,
						html::escapeHTML(subscribeToComments::format($tags_global,
						subscribeToComments::getSetting('account_content'),true)))); ?>
					</label>
				</p>
			</fieldset>

			<fieldset>
				<legend><?php echo(__('Email sent when a subscriber subscribe to the comments of a post')); ?></legend>
				<p>
					<?php echo(form::checkbox('subscribetocomments_subscribe_active',1,
						$settings->subscribetocomments_subscribe_active)); ?>
					<label class="classic" for="subscribetocomments_subscribe_active">
					<?php echo(__('Send an email for each subscription to the comments of a post')); ?></label>
				</p>
				<h3><?php echo(__('Available tags')); ?></h3>
				<table class="clear">
					<thead>
						<tr><th><?php echo(__('Tag')); ?></th><th><?php echo(__('Value')); ?></th></tr>
					</thead>
					<tbody>
						<?php tbody($tags_subscribe); ?>
					</tbody>
				</table>
				<p>
					<label for="subscription_subject"><?php echo(__('Subject:').
						form::field('subscribe_subject',80,255,
						html::escapeHTML(subscribeToComments::format($tags_subscribe,
						subscribeToComments::getSetting('subscribe_subject'),true)))); ?>
					</label>
				</p>
				<p>
					<label for="subscription_content"><?php echo(__('Content:').
						form::textarea('subscribe_content',80,15,
						html::escapeHTML(subscribeToComments::format($tags_subscribe,
						subscribeToComments::getSetting('subscribe_content'),true)))); ?>
					</label>
				</p>
			</fieldset>

			<fieldset>
				<legend><?php echo(__('Email sent when a new comment is published')); ?></legend>
				<h3><?php echo(__('Available tags')); ?></h3>
				<table class="clear">
					<thead>
						<tr><th><?php echo(__('Tag')); ?></th><th><?php echo(__('Value')); ?></th></tr>
					</thead>
					<tbody>
						<?php tbody($tags_comment); ?>
					</tbody>
				</table>
				<p>
					<label for="comment_subject"><?php echo(__('Subject:').
						form::field('comment_subject',80,255,
						html::escapeHTML(subscribeToComments::format($tags_comment,
						subscribeToComments::getSetting('comment_subject'),true)))); ?>
					</label>
				</p>
				<p>
					<label for="comment_content"><?php echo(__('Content:').
						form::textarea('comment_content',80,15,
						html::escapeHTML(subscribeToComments::format($tags_comment,
						subscribeToComments::getSetting('comment_content'),true)))); ?>
					</label>
				</p>
			</fieldset>

			<fieldset>
				<legend><?php echo(__('Email sent when a subscriber want to change his email address')); ?></legend>
				<h3><?php echo(__('Available tags')); ?></h3>
				<table class="clear">
					<thead>
						<tr><th><?php echo(__('Tag')); ?></th><th><?php echo(__('Value')); ?></th></tr>
					</thead>
					<tbody>
						<?php tbody($tags_email); ?>
					</tbody>
				</table>
				<p>
					<label for="email_subject"><?php echo(__('Subject:').
						form::field('email_subject',80,255,
						html::escapeHTML(subscribeToComments::format($tags_email,
						subscribeToComments::getSetting('email_subject'),true)))); ?>
					</label>
				</p>
				<p>
					<label for="email_content"><?php echo(__('Content:').
						form::textarea('email_content',80,15,
						html::escapeHTML(subscribeToComments::format($tags_email,
						subscribeToComments::getSetting('email_content'),true)))); ?>
					</label>
				</p>
			</fieldset>

			<p><?php echo $core->formNonce(); ?></p>
			<p><input type="submit" name="saveconfig" value="<?php echo __('Save configuration'); ?>" /></p>
		</form>
	</div>

	<div class="multi-part" id="display" title="<?php echo __('Display'); ?>">
		<h3><?php echo(__('Display')); ?></h3>
		<p><?php echo(
			__('This plugin needs to add some code on the post page.').' '.
			__('This can be done automatically by checking the following checkboxes.')); ?></p>
		<p><?php echo(__('If you want to customize the display on the post page (the post.hml file of your theme), uncheck the following checkboxes and follow the instructions under each checkbox:')); ?></p>
		<p><?php printf(__('You can use the plugin <strong>%s</strong> to edit the file <strong>post.html</strong>.'),
			__('Theme Editor')); ?></p>
		<form method="post" action="<?php echo http::getSelfURI(); ?>">
			<fieldset>
				<legend><?php printf(__('Install %s on the post page.'),
					__('Subscribe to comments')); ?></legend>
				<p>
					<?php echo(form::checkbox('subscribetocomments_tpl_checkbox',1,
						$settings->subscribetocomments_tpl_checkbox)); ?>
					<label class="classic" for="subscribetocomments_tpl_checkbox">
						<?php printf(__('Add the <strong>%s</strong> checkbox in the comment form'),
							__('Receive following comments by email')); ?>
					</label>
				</p>
				<div class="code" id="code_tpl_checkbox">
					<h4><?php echo(__('or')); ?></h4>
					<p><?php echo(__('insert this in the comment form (suggestion: in the <code>&lt;fieldset&gt;</code> before the <code>&lt;/form&gt;</code> tag):')); ?></p>
					<p class="code"><code><?php 
						echo html::escapeHTML($post_form);
					?></code></p>
				</div>
				
				<hr />
				
				<p>
					<?php printf(__('If the <strong>%s</strong> checkbox is not displayed correctly and the blog use Blowup or Blue Silence theme, check this:'),
							__('Receive following comments by email')); ?>
				</p>
				<p>
					<?php echo(form::checkbox('subscribetocomments_tpl_css',1,
						$settings->subscribetocomments_tpl_css)); ?>
					<label class="classic" for="subscribetocomments_tpl_css">
						<?php printf(__('Add a CSS rule to style the <strong>%1$s</strong> checkbox'),
							__('Receive following comments by email')); ?>
					</label>
				</p>
				<div class="code" id="code_tpl_css">
					<h4><?php echo(__('or')); ?></h4>
					<p><?php echo(__('add this CSS rule at the end of the file <strong>style.css</strong>')); ?></p>
					<p class="code"><code><?php 
						echo($post_css);
					?></code></p>
				</div>
				
				<hr />
				
				<p>
					<?php echo(form::checkbox('subscribetocomments_tpl_link',1,
						$settings->subscribetocomments_tpl_link)); ?>
					<label class="classic" for="subscribetocomments_tpl_link">
						<?php printf(__('Add a link to the <strong>%s</strong> page between the comments and the trackbacks'),
						__('Subscribe to comments')); ?>
					</label>
				</p>
				<p><?php printf(__('The code will appear after the %s tag.'),
					'<code>&lt;tpl:EntryIf comments_active="1"&gt;</code>');
					echo(' ');
					printf(__('If you don\'t want the code to appear, add the %s attribute to the %s tag.'),
					'<code>subscribetocomments_block="0"</code>',
					'<code>&lt;tpl:EntryIf comments_active="1" subscribetocomments_block="0"&gt;</code>');
					echo(' '); ?></p>
				<div class="code" id="code_tpl_link">
					<h4><?php echo(__('or')); ?></h4>
					<p><?php echo __('insert this anywhere on the page (suggestion: just after the <code>&lt;/form&gt;</code> tag):'); ?></p>
					<p class="code"><code>{{tpl:SubscribeToCommentsSubscribeBlock}}</code></p>
					<p><?php echo(__('The default id of the returned block is <code>subscribetocomments_block</code>, you can specify your own id and class.').' '.
						__('Examples:')); ?></p>
					<ul>
						<li><code>{{tpl:SubscribeToCommentsSubscribeBlock id="myID" class="myClass"}}</code></li>
						<li><?php echo(__('no id:')); ?> <code>{{tpl:SubscribeToCommentsSubscribeBlock id="" class="foo"}}</code></li>
					</ul>
					<h5><?php echo(__('or')); ?></h5>
					<p class="code"><code><?php
						echo html::escapeHTML($post_link);
					?></code></p>
				</div>
			</fieldset>

			<p><?php echo $core->formNonce(); ?></p>
			<p><input type="submit" name="saveconfig_display" value="<?php echo __('Save configuration'); ?>" /></p>
		</form>
	</div>
	
	<div class="multi-part" id="restore" title="<?php echo __('Restore'); ?>">
		<h3><?php echo(__('Restore default settings')); ?></h3>
		<form method="post" action="<?php echo http::getSelfURI(); ?>">
			<p>
				<label for="lang">
				<?php echo(__('Language:').
					form::combo('lang',$lang_combo,$settings->lang));
				?>
				</label>
			</p>
			<p><?php echo $core->formNonce(); ?></p>
			<p><input type="submit" name="restore" id="restore_button" value="<?php echo __('Restore default settings'); ?>" /></p>
		</form>
	</div>
	
	<div class="multi-part" id="subscribers" title="<?php echo __('Subscribers'); ?>">
		<h3><?php echo(__('Subscribers')); ?></h3>
		<?php
			$query = 'SELECT DISTINCT S.id, S.email, S.user_key, '.
				' COUNT(C.comment_id) AS comments_count, '.
				' MIN(C.comment_status) AS lowest_comment_status, '.
				' MIN(C.comment_dt) AS first_comment_dt '.
				'FROM '.$core->prefix.'comment_subscriber S '.
				' LEFT OUTER JOIN '.$core->prefix.'comment C '.
					'ON (S.email = C.comment_email) '.
				'INNER JOIN '.$core->prefix.'meta M ON '.
				(($core->con->driver() == 'pgsql') ?
				# CAST = PostgreSQL compatibility :
				# PGSQL need datas of the same type to compare
				'(S.id = CAST(M.meta_id AS integer))': 
				'(S.id = M.meta_id)').
				'INNER JOIN '.$core->prefix.'post P ON '.
				(($core->con->driver() == 'pgsql') ?
				# CAST = PostgreSQL compatibility :
				# PGSQL need datas of the same type to compare
				'(M.post_id = CAST(P.post_id AS integer))': 
				'(M.post_id = P.post_id)').
				' AND (M.meta_type = \'subscriber\') '.
				' AND P.blog_id = \''.$core->con->escape($core->blog->id).'\''.
				' GROUP BY S.id'.
				' ORDER BY lowest_comment_status DESC, '.
					'comments_count DESC, '.
					'first_comment_dt ASC';
			
			
			$rs = $core->con->select($query);
			
			if ($rs->isEmpty())
			{
				echo('<p>'.__('no subscriber').'</p>');
			}
			else
			{
				# display subscribers with at least one comment marked as spam
				$lowest_comment_status = -1;
				# display subscribers with no comments
				$comments_count = 1;
				
				echo('<p>'.__('Click on a subscriber in order to manage its subscriptions.').'</p>');
				
				echo('<form method="post" action="'.http::getSelfURI().'">'.
					'<fieldset>'.
					'<legend>'.__('Delete subscribers').'</legend>'.
					'<table>'.
					'<thead>'.
						'<tr>'.
							'<th colspan="2">'.__('Subscriber').'</th>'.
							'<th>'.__('Comments').'</th>'.
							'<th>'.__('First comment date').'</th>'.
						'</tr>'.
					'</thead>'.
					'<tbody>');
				while ($rs->fetch())
				{
					if ((is_numeric($rs->lowest_comment_status ))
						&& ($rs->lowest_comment_status < $lowest_comment_status))
					{
						echo('<tr>'.
							'<th colspan="4">'.
							__('Subscribers with at least one comment marked as spam:').
							'</th>'.
							'</tr>');
						
						$lowest_comment_status = $rs->lowest_comment_status;
					}
					else if ($rs->comments_count < $comments_count)
					{
						echo('<tr>'.
							'<th colspan="4">'.
							__('Subscribers with no comments:').
							'</th>'.
							'</tr>');
						
						$comments_count = $rs->comments_count;
					}
					
					echo('<tr class="line">'.
						'<td>'.form::checkbox('subscribers[]',
							html::escapeHTML($rs->email)).'</td>'.
						'<td>'.'<a href="'.
						subscriber::pageLink($rs->email, $rs->user_key).'">'.
						$rs->email.'</a> '.'</td>'.
						'<td>'.$rs->comments_count.'</td>'.
						'<td>'.(strlen($rs->first_comment_dt > 0)
							? $rs->first_comment_dt : '&nbsp;').'</td>'.
						'</tr>');
				}
				echo('</tbody>'.
					'</table>'.
					'<p>'.$core->formNonce().'</p>'.
					'<p><input type="submit" name="delete_subscribers" value="'.
						__('Delete subscribers').'" /></p>'.
					'</fieldset>'.
					'</form>');
			}
		?>
	</div>

	<div class="multi-part" id="test" title="<?php echo __('Test'); ?>">
		<h3><?php echo(__('Test')); ?></h3>
		<p><?php echo __('To use this plugin, you have to test if the server can send emails:'); ?></p>
			<form method="post" action="<?php echo http::getSelfURI(); ?>">
				<fieldset>
					<legend><?php echo __('Test'); ?></legend>
					<p>
						<label for="test_email"><?php echo(__('Email address')); ?></label>
						<?php echo(form::field('test_email',40,255,
							html::escapeHTML($core->auth->getInfo('user_email')))); ?>
					</p>
					<p><?php printf(
						__('This will send a email, if you don\'t receive it, try to <a href="%s">change the way Dotclear send emails</a>.'),
							'http://doc.dotclear.net/2.0/admin/install/custom-sendmail'); ?></p>
					<p><?php echo $core->formNonce(); ?></p>
					<p><input type="submit" name="test" value="<?php echo __('Try to send an email'); ?>" /></p>
				</fieldset>
			</form>
  </div>

	<hr />
	
	<p>
		<?php printf(__('URL of the %s page:'),__('Subscribe to comments')); ?>
		<br />
		<code><?php echo(subscribeToComments::url()); ?></code>
		<br />
		<a href="<?php echo(subscribeToComments::url()); ?>">
		<?php printf(__('View the %s page'),__('Subscribe to comments')); ?></a>	
	</p>
	<?php dcPage::helpBlock('subscribeToComments'); ?>
<?php } ?>
</body>
</html>