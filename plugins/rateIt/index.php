<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of rateIt, a plugin for Dotclear 2.
#
# Copyright(c) 2014-2015 Nicolas Roudaire <nikrou77@gmail.com> http://www.nikrou.net
#
# Copyright (c) 2009-2010 JC Denis and contributors
# jcdenis@gdwd.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')){return;}

# Check user perms
dcPage::check('admin');

# Get modules
$rateit_types = $core->rateIt->getModules();

# Init some vars
$s = $core->blog->settings->rateit;
$p_url 	= 'plugin.php?p=rateIt';
$msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '';
$start_part = $s->rateit_active ? 'summary' : 'setting';
$default_part = isset($_REQUEST['part']) ? $_REQUEST['part'] : $start_part;
$section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';
$tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'setting';
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';
$combo_types = array_flip($rateit_types);
$combo_types = array_merge(array('-'=>''),$combo_types);

# Common page header
$header =
'<link rel="stylesheet" type="text/css" href="index.php?pf=rateIt/style.css" />'.
dcPage::jsLoad('index.php?pf=rateIt/js/main.js').
'<script type="text/javascript">'."\n//<![CDATA[\n".
dcPage::jsVar('jcToolsBox.prototype.text_wait',__('Please wait')).
dcPage::jsVar('jcToolsBox.prototype.section',$section).
"\n//]]>\n</script>\n";

# Common menu
$menu = '
<form action="'.$p_url.'" method="post" id="modules-menu">'.
dcPage::breadcrumb(
		array(
			html::escapeHTML($core->blog->name) => '',
			'<span class="page-title">'.__('Rate it').'</span>' => ''
		)).
'<p class="field"><label>'.__('Select a module:').'</label>'.
form::combo(array('type'),$combo_types,$type).
'<input type="submit" value="'.__('ok').'" />'.
form::hidden(array('p'),'rateIt').
form::hidden(array('part'),'modules').
form::hidden(array('tab'),'setting').
$core->formNonce().'
</p></form><hr class="clear" />';

# Common page footer
$footer = '<hr class="clear"/><p class="right">
<a class="button" href="'.$p_url.'&amp;part=setting">'.__('Settings').'</a> -
rateIt - '.$core->plugins->moduleInfo('rateIt','version').'&nbsp;
<img alt="'.__('Rate it').'" src="index.php?pf=rateIt/icon.png" />
</p>';

# succes_codes
$succes = array(
	'save_setting' => __('Configuration successfully saved'),
	'del_records' => __('Records succesfully deleted')
);

# errors_codes
$errors = array(
	'save_setting' => __('Failed to save configuration: %s'),
	'del_records' => __('Failed to delete records: %s')
);

# Messages
if (isset($succes[$msg])) {
	$msg = sprintf('<p class="message">%s</p>',$succes[$msg]);
}

# Pages
if (!file_exists(dirname(__FILE__).'/inc/index.'.$default_part.'.php')) {
	$default_part = 'setting';
}
define('DC_CONTEXT_RATEIT',$default_part);
include dirname(__FILE__).'/inc/index.'.$default_part.'.php';
