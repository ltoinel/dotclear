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

require_once(dirname(__FILE__).'/inc/lib.log404Errors.admin.php');

if (isset($_GET['tab']) && ($_GET['tab'] == 'errors'))
{
	$tab = 'errors';
	
	$counter = $core->con->select('SELECT COUNT(id) AS count '.
		'FROM '.$core->prefix.'errors_log '.
		'WHERE (blog_id = \''.$core->con->escape(
			$core->blog->id).'\') ')->f(0);
}
else
{
	$tab = 'summary';
	
	$counter = $core->con->select('SELECT COUNT(id) AS count '.
		'FROM '.$core->prefix.'errors_log '.
		'WHERE (blog_id = \''.$core->con->escape(
			$core->blog->id).'\') '.
		'GROUP BY url ')->f(0);
}

$msg = (string)'';

$settings =& $core->blog->settings->log404errors;

# pages
$page = !empty($_GET['page']) ? (integer) $_GET['page'] : 1;
$nb_per_page = $settings->nb_per_page;

if (!empty($_GET['nb']) && (integer) $_GET['nb'] > 0) {
	if ($nb_per_page != $_GET['nb']) {
		$show_filters = true;
	}
	$nb_per_page = (integer) $_GET['nb'];
}

$params = array();

$params['limit'] = array((($page-1)*$nb_per_page),$nb_per_page);

$pager = new pager($page,$counter,$nb_per_page,10);
$pager->base_url = $p_url.
'&tab='.$tab.
'&page=%s';

$links = '<p>'.__('Page(s)').' : '.$pager->getLinks().'</p>';
# /pages

# actions
if (!empty($_POST['saveconfig']))
{
	# Enable Log 404 Errors
	$settings->put('active',
		(!empty($_POST['log404errors_active'])),'boolean',
		'Enable Log 404 Errors');
	
	$settings->put('errors_ttl',
		(integer) ($_POST['log404errors_errors_ttl']),
		'integer','Delete 404 errors older than x days');
	
	$settings->put('nb_per_page',
		(($_POST['log404errors_nb_per_page'] >= 1)
			? $_POST['log404errors_nb_per_page'] : 30),
		'integer','Errors per page');
	
	http::redirect($p_url.'&saveconfig=1');
}
elseif (isset($_POST['drop']))
{
	try
	{
		log404Errors::drop();
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
	$msg = __('Errors log has been deleted.');
}

if (isset($_GET['saveconfig']))
{
	$msg = __('Configuration successfully updated.');
	$tab = 'settings';
}

?>
<html>
<head>
  <title><?php echo __('404 Errors'); ?></title>
  <?php echo dcPage::jsPageTabs($tab); ?>
  <script type="text/javascript">
  //<![CDATA[
  	<?php echo dcPage::jsVar('dotclear.msg.confirm_404Errors_drop',
  	__('Are you sure you want to delete the 404 errors?')); ?>
  	$(function() {
			$('input[name="drop"]').click(function() {
				return window.confirm(dotclear.msg.confirm_404Errors_drop);
			});
	});
  //]]>
  </script>
</head>
<body>

	<h2><?php echo(html::escapeHTML($core->blog->name).' &rsaquo; '.__('404 Errors')); ?></h2>

	<?php 
		if (!empty($msg)) {echo '<p class="message">'.$msg.'</p>';}
		if (!$settings->active)
		{
			echo('<p class="message">'.__('The 404 errors are not logged.').'</p>');
		}
	?>

	<?php if (isset($_GET['tab']) && ($_GET['tab'] == 'errors')) { ?>
	
	<p><a href="<?php echo($p_url); ?>&amp;tab=summary" class="multi-part">
	<?php echo(__('Summary')); ?></a></p>

	<div class="multi-part" id="errors" title="<?php echo __('Errors'); ?>">
		<?php echo($links); ?>
		<table class="clear" summary="<?php echo(__('Errors 404')); ?>">
			<thead>
				<tr>
					<th><?php echo(__('Id')); ?></th>
					<th><acronym title="Uniform Resource Locator">URL</acronym></th>
					<th><?php echo(__('Date')); ?></th>
					<th><?php echo(__('IP address')); ?></th>
					<th><?php echo(__('Host name')); ?></th>
					<th><?php echo(__('Referrer')); ?></th>
					<th><?php echo(__('User agent')); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					unset($params['group']);
					log404ErrorsAdmin::show($params);
				?>
			</tbody>
		</table>
		
		<?php echo($links); ?>
		
		<form method="post" action="<?php echo(http::getSelfURI()); ?>">
			<p><?php echo $core->formNonce(); ?></p>
			<p><input type="submit" name="drop" value="<?php echo __('drop'); ?>" /></p>
		</form>
	</div>
	
	<?php } else { ?>
	
	<div class="multi-part" id="summary" title="<?php echo __('Summary'); ?>">
		<?php echo($links); ?>
		<table class="clear" summary="<?php echo(__('404 Errors')); ?>">
			<thead>
				<tr>
					<th><?php echo(__('Count')); ?></th>
					<th><acronym title="Uniform Resource Locator">URL</acronym></th>
					<th><?php echo(__('Date of last error')); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$params['group'] = true;
					log404ErrorsAdmin::show($params);
				?>
			</tbody>
		</table>
		
		<?php echo($links); ?>
		
		<form method="post" action="<?php echo(http::getSelfURI()); ?>">
			<p><?php echo $core->formNonce(); ?></p>
			<p><input type="submit" name="drop" value="<?php echo __('drop'); ?>" /></p>
		</form>
	</div>

	<p><a href="<?php echo($p_url); ?>&amp;tab=errors" class="multi-part">
	<?php echo(__('Errors')); ?></a></p>
	
	<?php } ?>
	
	<div class="multi-part" id="settings" title="<?php echo __('Settings'); ?>">
		<form method="post" action="<?php echo http::getSelfURI(); ?>">
			<fieldset>
				<legend><?php echo(__('Settings')); ?></legend>
				<p>
					<?php echo(form::checkbox('log404errors_active',1,
						$settings->active)); ?>
					<label class="classic" for="log404errors_active">
					<?php echo(__('Log 404 errors')); ?></label>
				</p>
				
				<p>
					<label class="classic">
					<?php echo __('Delete 404 errors older than').' '.
						form::field('log404errors_errors_ttl',3,3,
						$settings->errors_ttl).' '.
						__('days'); ?>
					</label> 
				</p>
				<p class="form-note">
					<?php echo(__('Enter <code>-1</code> to disable this feature.')); ?>
				</p>

				
				<p>
					<label class="classic" for="log404errors_nb_per_page">
					<?php echo __('Errors per page:'); ?>
					</label> 
					<?php echo form::field('log404errors_nb_per_page',7,7,
						$settings->nb_per_page); ?>
				</p>
		
				<p><?php echo $core->formNonce(); ?></p>
				<p><input type="submit" name="saveconfig" value="<?php echo __('Save configuration'); ?>" /></p>
			</fieldset>
		</form>
	</div>

</body>
</html>
