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

if (!defined('DC_CONTEXT_RATEIT') || DC_CONTEXT_RATEIT != 'summary'){return;}

$i = $total = 0;
$last = $sort = array();

foreach($rateit_types AS $type => $name){
	$rs = $core->con->select(
	'SELECT rateit_note,rateit_quotient,rateit_time,rateit_ip,rateit_id '.
	'FROM '.$core->prefix.'rateit WHERE blog_id=\''.$core->blog->id.'\' '.
	'AND rateit_type=\''.$core->con->escape($type).'\' '.
	'ORDER BY rateit_time DESC '.$core->con->limit(1));

	$count = $core->rateIt->getCount($type);
	$total += $count;

	if ($rs->isEmpty()) {
		$sort[] = $i;
		$last[$i] = array(
			'name' => $rateit_types[$type],
			'type' => $type,
			'count' => $count,
			'date' => '-',
			'note' => '-',
			'ip' => '-',
			'id' => '-'
		);
		$i++;
	} else {
		$sort[] = strtotime($rs->rateit_time);
		$last[strtotime($rs->rateit_time)] = array(
			'name' => $rateit_types[$type],
			'type' => $type,
			'count' => $count,
			'date' => dt::dt2str(__('%Y-%m-%d %H:%M'),$rs->rateit_time,$core->auth->getInfo('user_tz')),
			'note' => ($rs->rateit_note / $rs->rateit_quotient * $s->rateit_quotient).'/'.$s->rateit_quotient,
			'ip' => $rs->rateit_ip,
			'id' => $rs->rateit_id
		);
	}
}

# Display
echo '
<html>
<head><title>'.__('Rate it').' - '.__('Summary').'</title>'.$header;

# --BEHAVIOR-- adminRateItHeader
$core->callBehavior('adminRateItHeader',$core);

echo
'</head>
<body>'.$menu.'
<div id="summary"><h3>'.__('Summary').'</h3><p>';
if ($total == 0) {
	echo __('There is no vote at this time.');
} elseif ($total == 1) {
	echo __('There is only one vote at this time.');
} else {
	echo sprintf(__('There is a total of %s votes on this blog.'),$total);
}
echo '</p>
<table><tr>
<th colspan="3">'.__('Total').'</th>
<th colspan="4">'.__('Last').'</th>
<tr>
<th>'.__('Name').'</th>
<th>'.__('Type').'</th>
<th>'.__('Votes').'</th>
<th>'.__('Date').'</th>
<th>'.__('Note').'</th>
<th>'.__('Ip').'</th>
<th>'.__('Id').'</th></tr>';
rsort($sort);
foreach($sort AS $k) {
	echo
	'<tr class="line">'.
	'<td class="nowrap"><a href="'.$p_url.'&amp;part=modules&amp;type='.$last[$k]['type'].'&amp;tab=records">'.$last[$k]['name'].'</a></td>'.
	'<td class="nowrap">'.$last[$k]['type'].'</td>'.
	'<td class="nowrap">'.$last[$k]['count'].'</td>'.
	'<td class="nowrap">'.$last[$k]['date'].'</td>'.
	'<td class="nowrap">'.$last[$k]['note'].'</td>'.
	'<td class="nowrap">'.$last[$k]['ip'].'</td>'.
	'<td class="nowrap">'.$last[$k]['id'].'</td>'.
	'</tr>';
}
echo '</table></div>';

echo $footer.'</body></html>';
