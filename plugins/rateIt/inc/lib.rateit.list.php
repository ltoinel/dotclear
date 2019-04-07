<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of rateIt, a plugin for Dotclear 2.
#
# Copyright(c) 2014 Nicolas Roudaire <nikrou77@gmail.com> http://www.nikrou.net
#
# Copyright (c) 2009-2010 JC Denis and contributors
# jcdenis@gdwd.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')){return;}

# Generic rateIt admin list
class rateItExtList
{
	protected $core;
	protected $rs;
	protected $rs_count;
	protected $base_url;

	public function __construct($core,$rs,$rs_count,$base_url=null)
	{
		$this->core =& $core;
		$this->rs =& $rs;
		$this->rs_count = $rs_count;
		$this->base_url = $base_url;
		$this->html_prev = __('&#171;prev.');
		$this->html_next = __('next&#187;');

		$this->html_none = '<p><strong>'.__('No entry').'</strong></p>';
		$this->html = '%1$s';
		$this->html_pager =  '<p>'.__('Page(s)').' : %1$s</p>';
		$this->html_table = '<table class="clear">%1$s%2$s</table>';
		$this->html_headline = '<tr %2$s>%1$s</tr>';
		$this->html_headcell = '<th %2$s>%1$s</th>';
		$this->html_line = '<tr %2$s>%1$s</tr>';
		$this->html_cell = '<td %2$s>%1$s</td>';
		$this->headlines = '';
		$this->headcells = '';
		$this->lines = '';
		$this->cells = '';

		$this->init();
	}

	public function headline($cells,$head='')
	{
		$line = '';
		foreach($cells AS $content => $extra)
		{
			$line .= sprintf($this->html_headcell,$content,$extra);
		}
		$this->headlines .= sprintf($this->html_headline,$line,$head);
	}

	public function line($cells,$head='')
	{
		$line = '';
		foreach($cells AS $k => $cell)
		{
			$line .= sprintf($this->html_cell,$cell[0],$cell[1]);
		}
		$this->lines .= sprintf($this->html_line,$line,$head);
	}

	public function display($page,$nb_per_page,$enclose_block='')
	{
		if ($this->rs->isEmpty())
		{
			echo $this->html_none;
		}
		else
		{
			$pager = new pager($page,$this->rs_count,$nb_per_page,10);
			$pager->base_url = $this->base_url;
			$pager->html_prev = $this->html_prev;
			$pager->html_next = $this->html_next;
			$pager->var_page = 'page';

			while ($this->rs->fetch())
			{
				$this->setLine();
			}

			echo
			sprintf($this->html,
				sprintf($enclose_block,
					sprintf($this->html_pager,$pager->getLinks()).
					sprintf($this->html_table,$this->headlines,$this->lines).
					sprintf($this->html_pager,$pager->getLinks())
				)
			);
		}
	}
}
