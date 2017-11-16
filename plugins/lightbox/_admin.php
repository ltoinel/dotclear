<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of lightbox, a plugin for Dotclear 2.
#
# Copyright (c) Olivier Meunier and contributors
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) { return; }

// dead but useful code, in order to have translations
__('lightBox').__('lightBox like effect on images using jquery modal');

$core->addBehavior('adminBlogPreferencesForm',array('lightBoxBehaviors','adminBlogPreferencesForm'));
$core->addBehavior('adminBeforeBlogSettingsUpdate',array('lightBoxBehaviors','adminBeforeBlogSettingsUpdate'));

class lightBoxBehaviors
{
	public static function adminBlogPreferencesForm($core,$settings)
	{
		$settings->addNameSpace('lightbox');
		echo
		'<div class="fieldset"><h4>lightBox</h4>'.
		'<p><label class="classic">'.
		form::checkbox('lightbox_enabled','1',$settings->lightbox->lightbox_enabled).
		__('Enable lightBox').'</label></p>'.
		'</div>';
	}

	public static function adminBeforeBlogSettingsUpdate($settings)
	{
		$settings->addNameSpace('lightbox');
		$settings->lightbox->put('lightbox_enabled',!empty($_POST['lightbox_enabled']),'boolean');
	}
}
