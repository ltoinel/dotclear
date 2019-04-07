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

if (!defined('DC_RC_PATH')){return;}

$core->rateIt->loadModules();

require dirname(__FILE__).'/_widgets.php';

$core->addBehavior('publicHeadContent',array('urlRateIt','publicHeadContent'));

if (!$core->blog->settings->rateit->rateit_active) {
	$core->tpl->addBlock('rateIt',array('tplRateIt','disable'));
	$core->tpl->addBlock('rateItIf',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItLinker',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItTitle',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItTotal',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItMax',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItMin',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItMaxCount',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItMinCount',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItNote',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItFullnote',array('tplRateIt','disable'));
	$core->tpl->addValue('rateItQuotient',array('tplRateIt','disable'));
} else {
	$core->tpl->setPath($core->tpl->getPath(),dirname(__FILE__).'/default-templates/tpl/');

	$core->tpl->addBlock('rateIt',array('tplRateIt','rateIt'));
	$core->tpl->addBlock('rateItIf',array('tplRateIt','rateItIf'));
	$core->tpl->addValue('rateItLinker',array('tplRateIt','rateItLinker'));
	$core->tpl->addValue('rateItTitle',array('tplRateIt','rateItTitle'));
	$core->tpl->addValue('rateItTotal',array('tplRateIt','rateItTotal'));
	$core->tpl->addValue('rateItMax',array('tplRateIt','rateItMax'));
	$core->tpl->addValue('rateItMin',array('tplRateIt','rateItMin'));
	$core->tpl->addValue('rateItMaxCount',array('tplRateIt','rateItMaxCount'));
	$core->tpl->addValue('rateItMinCount',array('tplRateIt','rateItMinCount'));
	$core->tpl->addValue('rateItQuotient',array('tplRateIt','rateItQuotient'));
	$core->tpl->addValue('rateItNote',array('tplRateIt','rateItNote'));
	$core->tpl->addValue('rateItFullnote',array('tplRateIt','rateItFullnote'));
}

class urlRateIt extends dcUrlHandlers
{
	# Search rateit files like JS, CSS in default-templates subdirectories
	private static function searchRateItTplFiles($file) {
		if (strstr($file,"..") !== false) {
			return false;
		}
		$paths = $GLOBALS['core']->tpl->getPath();

		foreach($paths as $path) {
			if (preg_match('/tpl(\/|)$/',$path)) {
				$path = path::real($path.'/..');
			}
			if (file_exists($path.'/'.$file)) {
				return $path.'/'.$file;
			}
		}
		return false;
	}

	# Call rateIt service from public for ajax vote
	public static function service($args) {
		global $core;
		$core->rest->addFunction('rateItVote',array('rateItRest','vote'));
		$core->rest->serve();
		exit;
	}

	# If javascript is disabled, vote through page
	public static function postform($args) {
		global $core;

		if (!$core->blog->settings->rateit->rateit_active) {
			self::p404();
			return;
		}
		if (!preg_match('#([^/]+)/([^/]+)$#',$args,$m)) {
			self::p404();
			return;
		}
		if (empty($_POST['linkertype']) || empty($_POST['linkerid']) || empty($_POST['linkeruid'])
            || $_POST['linkertype'] != $m[1] || $_POST['linkerid'] != $m[2] || !isset($_POST[$_POST['linkeruid']])) {
			self::p404();
			return;
		}

		$voted = false;
		$type = $_POST['linkertype'];
		$id = $_POST['linkerid'];
		$note = $_POST[$_POST['linkeruid']];

		# Get know modules
		try {
			$rateit_types = $core->rateIt->getModules();
		} catch (Exception $e) {
			self::p404();
			return;
		}

		# Is know module?
		if (!isset($rateit_types[$type])) {
			self::p404();
			return;
		}

		# Check if user has allready voted for this item
		try {
			$voted = $core->rateIt->voted($type,$id);
		} catch (Exception $e) {
			self::p404();
			return;
		}

		# Not voted yet, do it
		if (!$voted) {
			$core->rateIt->set($type,$id,$note);
			$voted = true;
		}

		# --BEHAVIOR-- publicRateItPageAfterVote
		$core->callbehavior('publicRateItPageAfterVote',$core,$type,$id,$note,$voted);

		http::redirect($core->blog->url);
		return;
	}

	# Serve rateIt files
	public static function files($args) {
		global $core;

		if (!$core->blog->settings->rateit->rateit_active) {
			self::p404();
			return;
		}

		# Use no cache to fix bug on image size (on settings change)
		if ($args == 'linker.css') {
			$s = rateItLibImagePath::getSize($core,'rateIt');

			$style = $core->blog->settings->rateit->rateit_rating_style;
			if ($style == 'twin') {
				$high = '-'.$s['h'].'px';
				$medium = '0px';
				$low = '-'.($s['h']*2).'px';
			} elseif ($style == 'simple') {
				$high = '-'.$s['h'].'px';
				$medium = '0px';
				$low = '-'.($s['h']*2).'px';
			} else { //classic
				$high = '0px';
				$medium = '-'.$s['h'].'px';
				$low = '-'.($s['h']*2).'px';
			}

			$content =
                "div.rating-cancel,div.star-rating{float:left;width:".($s['w']+1)."px;height:".($s['h']-1)."px;text-indent:-999em;cursor:pointer;display:block;background:transparent;overflow:hidden} \n".
                "div.rating-cancel,div.rating-cancel a{background:url(".$core->blog->url.$core->url->getBase('rateItmodule')."/img/delete.png) no-repeat 0 -16px} \n". // not used
                "div.star-rating,div.star-rating a{background:url(".rateItLibImagePath::getUrl($core,'rateIt').") no-repeat 0 ".$high."} \n".
                "div.rating-cancel a,div.star-rating a{display:block;width:".$s['w']."px;height:100%;background-position:0 ".$high.";border:0} \n".
                "div.star-rating-on a{background-position:0 ".$medium."!important} \n".
                "div.star-rating-hover a{background-position:0 ".$low."} \n".
                "div.star-rating-readonly a{cursor:default !important} \n".
                "div.star-rating{background:transparent!important;overflow:hidden!important} \n";

			header('Content-Type: text/css; charset=UTF-8');
			header('Content-Length: '.strlen($content));
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			echo $content;
			exit;
		}

		if (!($f = self::searchRateItTplFiles($args))) {
			self::p404();
			return;
		}

		$allowed_types = array('png','jpg','jpeg','gif','css','js','swf');
		if (!file_exists($f) || !in_array(files::getExtension($f),$allowed_types)) {
			self::p404();
			return;
		}
		$type = files::getMimeType($f);

		header('Content-Type: '.$type.'; charset=UTF-8');
		header('Content-Length: '.filesize($f));
		//header("Cache-Control: no-cache, must-revalidate");
		//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

		if ($type != "text/css" || $core->blog->settings->system->url_scan == 'path_info') {
			readfile($f);
		} else {
			echo preg_replace('#url\((?!(http:)|/)#','url('.$core->blog->url.$core->url->getBase('rateItmodule').'/',file_get_contents($f));
		}
		exit;
	}

	# Include rateIt javascript and CSS to public pages
	public static function publicHeadContent($core) {
		if (!$core->blog->settings->rateit->rateit_active) return;

		$s = rateItLibImagePath::getSize($core,'rateIt');

		if (!$core->blog->settings->rateit->rateit_dispubcss) {
		     	echo
	            	"<style type=\"text/css\"> \n @import url(".
                        	$core->blog->url.$core->url->getBase('rateItmodule')."/linker.css); \n".
        	    	"</style> \n";

		 	echo
			"<style type=\"text/css\"> \n @import url(".
				$core->blog->url.$core->url->getBase('rateItmodule')."/rateit.css); \n".
			"</style> \n";
		}

        if (!$core->blog->settings->rateit->rateit_dispubjs) {

		echo
		"\n<!-- JS for rateit --> \n".
		"<script type=\"text/javascript\" src=\"".
			$core->blog->url.$core->url->getBase('rateItmodule').'/js/jquery.rating.js">'.
		"</script> \n".
		"<script type=\"text/javascript\" src=\"".
			$core->blog->url.$core->url->getBase('rateItmodule').'/js/jquery.rateit.js">'.
		"</script> \n".
		"<script type=\"text/javascript\"> \n".
		"//<![CDATA[\n".
		" \$(function(){ \n".
		"  \$.fn.rateit.defaults.service_url = '".html::escapeJS($core->blog->url.$core->url->getBase('rateItservice').'/')."'; \n".
		"  \$.fn.rateit.defaults.service_func = '".html::escapeJS('rateItVote')."'; \n".
		"  \$.fn.rateit.defaults.image_size = '".$s['h']."'; \n".
		"  \$.fn.rateit.defaults.blog_uid = '".html::escapeJS($core->blog->uid)."'; \n".
		"  \$.fn.rateit.defaults.enable_cookie = '".($core->blog->settings->rateit->rateit_userident > 0 ? '1' : '0')."'; \n".
		"  \$.fn.rateit.defaults.msg_thanks = '".html::escapeJS($core->blog->settings->rateit->rateit_msgthanks)."'; \n".
		"  \$('.rateit').rateit(); \n".
		" });\n".
		"//]]>\n".
		"</script>\n";
	}
	}
}

class tplRateIt
{
	# Remove all rateIt items from public if not active
	public static function disable($a,$b=null) {
		return '';
	}

	# Block: init rateIt items
	public static function rateIt($attr,$content) {
		global $core;

		$style = isset($attr['style']) ? html::escapeHTML($attr['style']) : '';
		$type = isset($attr['type']) ? html::escapeHTML($attr['type']) : '';
		$res = '';

		# --BEHAVIOR-- publicRateItTplBlockRateIt
		$res .= $core->callbehavior('publicRateItTplBlockRateIt',$type,$attr,$content);

		if (!$res) return;

		return
		"<?php \n".
		"\$rateit_style = \"".$style."\"; \n".
		"if (empty(\$rateit_style)) { ".
		" \$rateit_style = \$core->blog->settings->rateit->rateit_rating_style; ".
		"} \n".
		"if (empty(\$rateit_params)) { ".
		"\$rateit_params = array('type'=>'','id'=>''); \n".
		$res.
		"} ".
		"if (!empty(\$rateit_params['type'])) { \n".
		" \$rateit_voted = \$core->rateIt->voted(\$rateit_params['type'],\$rateit_params['id']); \n".
		" \$_ctx->rateIt = \$core->rateIt->get(\$rateit_params['type'],\$rateit_params['id']); \n".
		" ?> \n".
		$content."\n".
		" <?php \n".
		" unset(\$rateit_voted); \n".
		" \$_ctx->rateIt = null; \n".
		"} \n".
		"unset(\$rateit_params); \n".
		"unset(\$rateit_style); \n".
		"?> \n";
	}

	# Block: some rateIt conditions
	public static function rateItIf($attr,$content)
	{
		$operator = isset($attr['operator']) ? self::getOperator($attr['operator']) : '&&';

		if (isset($attr['has_vote']))
		{
			$sign = (boolean) $attr['has_vote'] ? '' : '!';
			$if[] = $sign.'(0 < $_ctx->rateIt->total)';
		}
		if (isset($attr['user_voted']))
		{
			$sign = (boolean) $attr['user_voted'] ? '' : '!';
			$if[] = $sign.'$rateit_voted';
		}
		if (!empty($attr['type']))
		{
			if (substr($attr['type'],0,1) == '!')
			{
				$if[] = '\''.substr($attr['type'],1).'\' != $_ctx->rateIt->type';
			}
			else
			{
				$if[] = '\''.$attr['type'].'\' == $_ctx->rateIt->type';
			}
		}
		if (!empty($attr['style']))
		{
			if (substr($attr['style'],0,1) == '!')
			{
				$if[] = '\''.substr($attr['style'],1).'\' != $rateit_style';
			}
			else
			{
				$if[] = '\''.$attr['style'].'\' == $rateit_style';
			}
		}

		if (empty($if)) return $content;

		return
		"<?php if(".implode(' '.$operator.' ',$if).") : ?>\n".
		$content.
		"<?php endif; ?>\n";
	}

	# Value: title of rateIt item
	public static function rateItTitle($attr)
	{
		global $core;
		$type = isset($attr['type']) ? $attr['type'] : '';
		$f = $core->tpl->getFilters($attr);

		# --BEHAVIOR-- publicRateItTplValueRateItTitle
		$res = $core->callbehavior('publicRateItTplValueRateItTitle',$type,$attr);

		return
		"<?php \n".
		"\$title = ''; \n".
		$res.
		"if (empty(\$title)) \$title = __('Rate this'); \n".
		"echo ".sprintf($f,'$title')."; \n".
		"?> \n";

	}

	# Value: built-in rating form
	public static function rateItLinker($attr)
	{
		global $core;
		$f = $core->tpl->getFilters($attr);
		return '<?php echo rateItContext::linker($rateit_voted,$_ctx->rateIt->type,$_ctx->rateIt->id,$_ctx->rateIt->note,$_ctx->rateIt->quotient,$rateit_style); ?>';
	}

	# Value: full note (ex: 2/5)
	public static function rateItFullnote($attr)
	{
		global $core;
		$f = $core->tpl->getFilters($attr);
		return '<?php echo '.sprintf($f,'rateItContext::value("fullnote",$_ctx->rateIt->type,$_ctx->rateIt->id,$_ctx->rateIt->note."/".$_ctx->rateIt->quotient)').'; ?>';
	}

	# Value: quotient (note on)
	public static function rateItQuotient($attr)
	{
		return self::rateItValue($attr,'quotient');
	}

	# Value: total number of votes
	public static function rateItTotal($attr)
	{
		$r = '';
		if (isset($attr['totaltext']) && $attr['totaltext'] == 1) {

			$none = 'no rate';
			$one = 'one rate';
			$more = '%d rates';

			if (isset($attr['none'])) {
				$none = addslashes($attr['none']);
			}
			if (isset($attr['one'])) {
				$one = addslashes($attr['one']);
			}
			if (isset($attr['more'])) {
				$more = addslashes($attr['more']);
			}

			$r =
			"<?php \n".
			"if (\$_ctx->rateIt->total == 0) { \n".
			"  \$total = sprintf(__('".$none."'),\$_ctx->rateIt->total); \n".
			"} elseif (\$_ctx->rateIt->total == 1) {\n".
			"  \$total = sprintf(__('".$one."'),\$_ctx->rateIt->total); \n".
			"} else { \n".
			"  \$total = sprintf(__('".$more."'),\$_ctx->rateIt->total); \n".
			"} \n".
			"\$_ctx->rateIt->total = \$total; ?>\n";
		}
		return $r.self::rateItValue($attr,'total');
	}

	# Value: higher note
	public static function rateItMax($attr)
	{
		return self::rateItValue($attr,'max');
	}

	# Value: lower note
	public static function rateItMin($attr)
	{
		return self::rateItValue($attr,'min');
	}

	# Value: number of note higher than the average
	public static function rateItMaxCount($attr)
	{
		return self::rateItValue($attr,'maxcount');
	}

	# Value: number of note lower than the average
	public static function rateItMinCount($attr)
	{
		return self::rateItValue($attr,'mincount');
	}

	# Value: final note
	public static function rateItNote($attr)
	{
		return self::rateItValue($attr,'note');
	}

	# Generic value
	private static function rateItValue($a,$r)
	{
		global $core;
		$f = $core->tpl->getFilters($a);
		return '<?php echo '.sprintf($f,'rateItContext::value("'.$r.'",$_ctx->rateIt->type,$_ctx->rateIt->id,$_ctx->rateIt->'.$r.')').'; ?>';
	}

	# Operator for condition (see: rateItIf)
	protected static function getOperator($op)
	{
		switch (strtolower($op))
		{
			case 'or':
			case '||':
				return '||';
			case 'and':
			case '&&':
			default:
				return '&&';
		}
	}
}

class rateItPublicWidget
{
	# Vote form on a public page of an item
	public static function vote($w) {
		global $core, $_ctx;

		if (!$core->blog->settings->rateit->rateit_active) {
            return;
        }

		if ($w->offline) {
			return;
        }

		$style = $core->blog->settings->rateit->rateit_rating_style;
		$rateit_types = $core->rateIt->getModules();

		if (empty($rateit_types)) {
            return;
        }

		# --BEHAVIOR-- publicRateItWidgetVote
		$core->callbehavior('publicRateItWidgetVote',$w,$_ctx);

		$type = $w->type;
		$id = $w->id;
		$title = $w->title;

		if (empty($type)) {
            return;
        }

		$rs = $core->rateIt->get($type,$id);
		$voted = $core->rateIt->voted($type,$id);

		$res = '<div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">';
		$res .= ($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '');

		if ($w->show_fullnote) {
			if ($style == 'classic') {
				$res .= '<p>'.rateItContext::value('fullnote',$type,$id,$rs->note.'/'.$rs->quotient).'</p>';
			} elseif ($style == 'twin') {
				$res .= '<p>'.rateItContext::value('mincount',$type,$id,$rs->mincount).'</p>';
			}
		}

		$res .= rateItContext::linker($voted,$type,$id,$rs->note,$rs->quotient);

		if ($w->show_fullnote && in_array($style,array('twin','simple'))) {
			$res .= '<p>'.rateItContext::value('maxcount',$type,$id,$rs->maxcount).'</p>';
		}

		if ($w->show_note || $w->show_vote || $w->show_higher || $w->show_lower)
		{
			$res .=	'<ul>';
			if ($w->show_note)
			{
				$res .= '<li>'.__('Note:').' '.rateItContext::value('note',$type,$id,$rs->note).'</li>';
			}
			if ($w->show_vote)
			{
				$res .= '<li>'.__('Votes:').' '.rateItContext::value('total',$type,$id,$rs->total).'</li>';
			}
			if ($w->show_higher)
			{
				$res .= '<li>'.__('Higher:').' '.rateItContext::value('max',$type,$id,$rs->max).'</li>';
			}
			if ($w->show_lower)
			{
				$res .= '<li>'.__('Lower:').' '.rateItContext::value('min',$type,$id,$rs->min).'</li>';
			}
			$res .= '</ul></div>';
		}

		return $w->renderDiv($w->content_only,'rateit '.$w->class,'',$res);
	}

	# Ranking
	public static function rank($w)
	{
		global $core, $_ctx;

        if (!$core->blog->settings->rateit->rateit_active) {
            return;
        }

		if ($w->offline)
			return;

		if (($w->homeonly == 1 && $core->url->type != 'default') ||
            ($w->homeonly == 2 && $core->url->type == 'default')) {
			return;
        }

		$p = New arrayObject();
		$p['from'] = '';
		$p['sql'] = '';
		$p['columns'] = array();
		$p['groups'] = array();
		if ($w->sortby == 'POSITIVE') {
			$p['order'] = 'rateit_total ';
			$p['sql'] .= 'AND (RI.rateit_note / rateit_quotient)*2 >= 1 ';
		} else {
			$p['order'] = ($w->sortby && in_array($w->sortby,array('rateit_avg','rateit_total','rateit_time'))) ?
				$w->sortby.' ' : 'rateit_total ';
		}
		$p['order'] .= $w->sort == 'desc' ? 'DESC' : 'ASC';

		if (!in_array($w->sortby,array('POSITIVE','rateit_total'))) {
			$p['order'] .= ',rateit_total DESC ';
		}
		if ($w->sorby != 'rateit_time') {
			$p['order'] .= ',rateit_time DESC ';
		}

		$p['limit'] = abs((integer) $w->limit);
		$p['rateit_type'] = $w->type;

		$rateit_types = $core->rateIt->getModules();

		if (!isset($rateit_types[$w->type])) return;

		# --BEHAVIOR-- publicRateItWidgetRank
		$core->callbehavior('publicRateItWidgetRank',$w,$p,$_ctx);

		$p = $p->getArrayCopy();

		$rs = $core->rateIt->getRates($p);

		if ($rs->isEmpty()) return;

		$q = $core->blog->settings->rateit->rateit_quotient;
		$d = $core->blog->settings->rateit->rateit_digit;

		$res = ($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '').'<ul>';
		$i=0;

		while ($rs->fetch()) {
			$title = html::escapeHTML($rs->title);

			$cut_len = abs((integer) $w->titlelen);
			if (strlen($title) > $cut_len) {
				$title = text::cutString($title,$cut_len).'...';
			}
			if ($rs->rateit_total == 0) {
				$totaltext = __('no rate');
			} elseif ($rs->rateit_total == 1) {
				$totaltext = __('one rate');
			} else {
				$totaltext = sprintf(__('%d rates'),$rs->rateit_total);
			}

			# Fixed issue with plugin planet
			if ($w->type == 'post') {
				$post = $core->blog->getPosts(array('post_id'=>$rs->id));
				$url = $post->getURL();
			} else {
				$url = $rs->url;
			}

			$i++;
			$res .= '<li>'.str_replace(
				array(
					'%rank%',
					'%title%',
					'%note%',
					'%quotient%',
					'%percent%',
					'%count%',
					'%totaltext%',
					'%entryfirstimage%'
				),
				array(
					'<span class="rateit-rank">'.$i.'</span>',
					'<a href="'.$url.'">'.$title.'</a>',
					round($rs->rateit_avg * $q,$d),
					$q,
					floor($rs->rateit_avg * 100),
					$rs->rateit_total,
					$totaltext,
					self::entryFirstImage($core,$w->type,$rs->id)
				),
				$w->text
			).'</li>';
		}
		$res .= '</ul>';

		return $w->renderDiv($w->content_only,'rateitpostsrank rateittype '.$w->class,'',$res);
	}

	# Extra (for ranking) could retrieve first image of a post
	private static function entryFirstImage($core,$type,$id) {
		if (!in_array($type,array('post','gal','galitem'))) {
            return '';
        }

		$rs = $core->blog->getPosts(array('post_id'=>$id,'post_type'=>$type));

		if ($rs->isEmpty()) {
            return '';
        }

		$size = $core->blog->settings->rateit->rateit_firstimage_size;
		if (!preg_match('/^sq|t|s|m|o$/',$size)) {
			$size = 's';
		}

		$p_url = $core->blog->settings->system->public_url;
		$p_site = preg_replace('#^(.+?//.+?)/(.*)$#','$1',$core->blog->url);
		$p_root = $core->blog->public_path;

		$pattern = '(?:'.preg_quote($p_site,'/').')?'.preg_quote($p_url,'/');
		$pattern = sprintf('/<img.+?src="%s(.*?\.(?:jpg|gif|png))"[^>]+/msu',$pattern);

		$src = '';
		$alt = '';

		$subject = $rs->post_excerpt_xhtml.$rs->post_content_xhtml.$rs->cat_desc;
		if (preg_match_all($pattern,$subject,$m) > 0) {
			foreach ($m[1] as $i => $img) {
				if (($src = self::ContentFirstImageLookup($p_root,$img,$size)) !== false) {
					$src = $p_url.(dirname($img) != '/' ? dirname($img) : '').'/'.$src;
					if (preg_match('/alt="([^"]+)"/',$m[0][$i],$malt)) {
						$alt = $malt[1];
					}
					break;
				}
			}
		}
		if (!$src) {
            return '';
        }

		return
            '<div class="img-box">'.
            '<div class="img-thumbnail">'.
            '<a title="'.html::escapeHTML($rs->post_title).'" href="'.$rs->getURL().'">'.
            '<img alt="'.$alt.'" src="'.$src.'" />'.
            '</a></div>'.
            "</div>\n";
	}

	# Extra (for ranking) (see entryFirstImage)
	private static function ContentFirstImageLookup($root,$img,$size) {
		# Get base name and extension
		$info = path::info($img);
		$base = $info['base'];

		if (preg_match('/^\.(.+)_(sq|t|s|m)$/',$base,$m)) {
			$base = $m[1];
		}

		$res = false;
		if ($size != 'o' && file_exists($root.'/'.$info['dirname'].'/.'.$base.'_'.$size.'.jpg')) {
			$res = '.'.$base.'_'.$size.'.jpg';
		} else {
			$f = $root.'/'.$info['dirname'].'/'.$base;
			if (file_exists($f.'.'.$info['extension'])) {
				$res = $base.'.'.$info['extension'];
			} elseif (file_exists($f.'.jpg')) {
				$res = $base.'.jpg';
			} elseif (file_exists($f.'.png')) {
				$res = $base.'.png';
			} elseif (file_exists($f.'.gif')) {
				$res = $base.'.gif';
			}
		}

		if ($res) {
            return $res;
        }

		return false;
	}
}
