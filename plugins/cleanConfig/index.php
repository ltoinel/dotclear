<?php
# ***** BEGIN LICENSE BLOCK *****
#
# This file is part of clean:config, a plugin for Dotclear 2
# Copyright (C) 2007,2009,2010 Moe (http://gniark.net/)
#
# clean:config is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License v2.0
# as published by the Free Software Foundation.
#
# clean:config is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public
# License along with this program. If not, see
# <http://www.gnu.org/licenses/>.
#
# Icon (icon.png) and images are from Silk Icons :
# <http://www.famfamfam.com/lab/icons/silk/>
#
# ***** END LICENSE BLOCK *****

if (!defined('DC_CONTEXT_ADMIN')) {exit;}

l10n::set(dirname(__FILE__).'/locales/'.$_lang.'/admin');

require_once(dirname(__FILE__).'/php-xhtml-table/class.table.php');
require_once(dirname(__FILE__).'/inc/lib.cleanconfig.php');

$default_tab = 'blog_settings';

$msg = (string)'';

# actions
$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : '';

if ((isset($_POST['delete'])) AND (($limit == 'blog') OR ($limit == 'global')))
{
	if (count($_POST['settings']) == 0)
	{
		$msg = __('No setting deleted.');
		$default_tab = $limit.'_settings';
	}
	else
	{
		foreach ($_POST['settings'] as $setting)
		{
			$id = explode('|',$setting);
			cleanconfig::delete($id[0],$id[1],$limit);
		}
		$msg = '<div class="message"><p>'.

		http::redirect($p_url.'&settingsdeleted=1&limit='.$limit);
	}
}

if (isset($_GET['settingsdeleted']))
{
	$msg = __('The selected settings have been deleted.');

	$default_tab = $limit.'_settings';
}

?>
<html>
<head>
	<title><?php echo __('clean:config'); ?></title>
	<?php echo dcPage::jsPageTabs($default_tab); ?>
	<style type="text/css">
		.ns-name { background: #A2CBE9; color: #000; padding-top: 0.3em; padding-bottom: 0.3em; font-size: 1.1em; }
	</style>
	<!-- from /dotclear/plugins/widgets -->
	<script type="text/javascript">
	//<![CDATA[
		<?php echo dcPage::jsVar('dotclear.msg.confirm_cleanconfig_delete',
		__('Are you sure you want to delete settings?')); ?>
		$(document).ready(function() {
			$('.checkboxes-helpers').each(function() {
				dotclear.checkboxesHelpers(this);
			});
			$('input[name="delete"]').click(function() {
				return window.confirm(dotclear.msg.confirm_cleanconfig_delete);
			});
			$('td[class="ns-name"]').css({ cursor:"pointer" });
			$('td[class="ns-name"]').click(function() {
				$("."+$(this).children().filter("strong").text()).toggleCheck();
				return false;
			});
		});
	//]]>
	</script>
</head>
<body>

	<h2><?php echo html::escapeHTML($core->blog->name); ?> &rsaquo; <?php echo __('clean:config'); ?></h2>

	<?php
		if (!empty($msg)) {echo '<p class="message">'.$msg.'</p>';}
	?>

	<div class="multi-part" id="blog_settings" title="<?php echo __('blog settings'); ?>">
		<?php echo(cleanconfig::settings('blog')); ?>
	</div>

	<div class="multi-part" id="global_settings" title="<?php echo __('global settings'); ?>">
		<?php echo(cleanconfig::settings('global')); ?>
	</div>

	<div class="multi-part" id="versions" title="<?php echo __('versions'); ?>">
		<p><?php printf(__('This function has been moved to the %s plugin.'),
			'<a href="http://plugins.dotaddict.org/dc2/details/versionsManager">'.
			__('Versions Manager').'</a>'); ?></p>
	</div>

</body>
</html>
