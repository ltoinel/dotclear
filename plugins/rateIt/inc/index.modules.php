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

if (!defined('DC_CONTEXT_RATEIT') || DC_CONTEXT_RATEIT != 'modules'){return;}

# Load requested module
if (!empty($type) && !isset($rateit_types[$type]))
{
	$core->error->add('Unknow module');
}

# Init some requests
$page_url = $p_url.'&amp;part=modules&amp;type='.$type;
$hidden_fields =
form::hidden(array('p'),'rateIt').
form::hidden(array('part'),'modules').
form::hidden(array('type'),$type).
$core->formNonce();

# Call post actions
if (!$core->error->flag() && !empty($type) && !empty($action)) {
	try {
		$m = $core->callBehavior('adminRateItModuleUpdate',$core,$type,$action,$page_url,$hidden_fields);
		if (!empty($m)) {
			http::redirect($p_url.'&part=modules&type='.$type.'&msg='.$m);
		}
	} catch (Exception $e) {
		$core->error->add($e->getMessage());
	}
}

# Display
echo '
<html>
<head><title>'.__('Rate it').' - '.__('Modules').'</title>'.$header.
dcPage::jsLoad('js/_posts_list.js').
dcPage::jsToolBar().
dcPage::jsPageTabs($tab);

# Call admin page header
if (!$core->error->flag() && !empty($type))
{
	$core->callBehavior('adminRateItModuleHeader',$core,$type,$action,$page_url,$hidden_fields);
}

echo '</head><body>'.$menu;

if (!$core->error->flag() && !empty($type))
{
	echo '<h3>'.$rateit_types[$type].'</h3>';
}
echo $msg;

# Call settings tab and records tab
if (!$core->error->flag() && !empty($type))
{
	echo '<div class="multi-part" id="setting" title="'.__('Settings').'">';

	if ('' == $core->callBehavior('adminRateItModuleSettingsTab',$core,$type,$page_url.'&amp;tab=setting',$hidden_fields.form::hidden(array('tab'),'setting')))
	{
		echo '<p>'.__('There is no setting for this module').'</p>';
	}

	echo '</div><div class="multi-part" id="records" title="'.__('Records').'">';

	if ('' == $core->callBehavior('adminRateItModuleRecordsTab',$core,$type,$page_url.'&amp;tab=records',$hidden_fields.form::hidden(array('tab'),'records')))
	{
		echo '<p>'.__('There is no record for this module').'</p>';
	}

	echo '</div>';
}

dcPage::helpBlock('rateIt_modules');
echo $footer.'</body></html>';
