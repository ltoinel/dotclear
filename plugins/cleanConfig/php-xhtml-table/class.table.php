<?php
# ***** BEGIN LICENSE BLOCK *****
#
# This file is part of PHP XHTML Table.
# Copyright 2007,2008,2009 Moe (http://gniark.net/)
#
# PHP XHTML Table is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License, or
# (at your option) any later version.
#
# PHP XHTML Table is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# ***** END LICENSE BLOCK *****

/**
@defgroup Table PHP XHTML Table
@version 20091206
@brief Create XHTML &lt;table&gt; with PHP
*/

/**
@ingroup Table
@brief Construct the table
*/
class table
{

	private $table_extra_html;	///< <b>string</b>		&lt;table&gt; extra HTML

	private $part;	///< <b>string</b>		Part
	protected $parts = array("head","foot","body");	///< <b>array</b>		Parts : &lt;thead&gt;, &lt;tfoot&gt; and &lt;tbody&gt;

	private $caption;	///< <b>string</b>		Caption
	private $head;	///< <b>array</b>		Part object
	private $foot;	///< <b>array</b>		Part object
	private $body;	///< <b>array</b>		Part object

	private $rows;	///< <b>array</b>		Rows
	private $cells;	///< <b>array</b>		Cells

	private $row_extra_html;	///< <b>string</b>	Row extra HTML

	private static $new_line = "\n";	///< <b>string</b>	New line

	/**
	construct table
	@param	table_extra_html	<b>string</b>	&lt;table&gt; extra HTML ("")
	*/
	public function __construct($table_extra_html="")
	{
		$this->table_extra_html = self::arg($table_extra_html);

		$this->part = "";

		$this->caption = "";

		$this->head = new part();
		$this->foot = new part();
		$this->body = new part();

		$this->rows = $this->cells = array();

		$row_extra_html = "";
	}

	/**
	set the caption
	@param	str	<b>string</b>	Caption
	*/
	public function caption($str)
	{
		$this->caption = self::tag("caption",$str);
	}


	/***** parts ****/
	/**
	open a part
	@param	part	<b>string</b>	Part
	@param	extra_html	<b>string</b>	Part's extra HTML ("")
	*/
	public function part($part,$extra_html="")
	{
		$this->closePart();

		if (in_array($part,$this->parts)) {
			$this->part = $part;
		} else {
			throw new Exception("nothing to open, available parts are : ".
				implode(", ",$this->parts));
		}

		$this->{$part}->extraHTML($extra_html);
	}

	/**
	close a part
	*/
	public function closePart()
	{
		$this->closeRow();
		if (!empty($this->part))
		{
			$this->{$this->part}->rows($this->rows);
			$this->part = "";
		}
		$this->cleanRows();
	}

	/***** /parts ***** /

	/***** headers ****/
	/**
	insert an header
	@param	str	<b>string</b>	String ("")
	@param	extra_html	<b>string</b>	Header's extra HTML ("")
	*/
	public function header($str="",$extra_html="")
	{
		$this->cells[] = self::tag("th",$str,$extra_html);
	}

	/**
	insert headers : $table->headers("one",array("two"=>'title="2"'));
	@param	param	<b>param</b>	Strings or arrays
	*/
	public function headers()
	{
		$this->part("head");

		$array = func_get_args();

		foreach ($array as $k)
		{
			if (is_string($k))
			{
				$this->header($k);
			}
			elseif (is_array($k))
			{
				$key = key($k);
				$this->header($key,$k[$key]);
			}
			else
			{
				throw new Exception("wrong type for ".$k);
			}
		}
	}

	/**
	repeat head
	@param	extra_html	<b>string</b>	Row extra HTML ("")
	*/
	public function repeatHead($extra_html="")
	{
		if (!empty($this->head))
		{
			$this->row($extra_html);
			$this->rows[] = $this->head;
			$this->closeRow();
		}
	}
	/***** /headers ****/

	/***** rows ****/
	/**
	open a new row
	@param	extra_html	<b>string</b>	Row extra HTML ("")
	*/
	public function row($extra_html="")
	{
		$this->closeRow();
		$this->row_extra_html = $extra_html;
	}

	/**
	close a row
	*/
	public function closeRow()
	{
		if (!empty($this->cells))
		{
			$this->rows[] =
				self::tag("tr",implode("",$this->cells),$this->row_extra_html);
		}
		$this->cleanCells();
		$this->row_extra_html = "";
	}

	/**
	delete rows
	*/
	private function cleanRows()
	{
		$this->rows = array();
	}
	/***** /rows ****/

	/***** cells ****/
	/**
	insert a cell
	@param	str	<b>string</b>	String ("")
	@param	extra_html	<b>string</b>	Cell's extra HTML ("")
	*/
	public function cell($str="",$extra_html="")
	{
		$this->cells[] = self::tag("td",$str,$extra_html);
	}

	/**
	insert cells : $t->cells("one",array("two"=>'title="2"'));
	@param	param	<b>param</b>	Strings or arrays
	*/
	public function cells()
	{
		$array = func_get_args();

		foreach ($array as $k)
		{
			if (in_array(gettype($k),array("string","integer","float")))
			{
				$this->cell($k);
			}
			elseif (is_array($k))
			{
				$key = key($k);
				$this->cell($key,$k[$key]);
			}
			else
			{
				throw new Exception("wrong type for ".$k);
			}
		}
	}

	/**
	delete cells
	*/
	private function cleanCells()
	{
		$this->cells = array();
	}
	/***** /cells ****/

	/***** table ****/
	/**
	return XHTML &lt;table&gt;
	@return	<b>string</b> &lt;table;&gt;
	*/
	public function get()
	{
		$this->closeRow();
		$this->closePart();

		$str = "<table".$this->table_extra_html.">".self::$new_line.
		$this->caption.self::$new_line;

		foreach ($this->parts as $part)
		{
			if (!empty($this->{$part}->rows))
			{
				$str .= "<t".$part.self::arg($this->{$part}->extra_html).">".
					self::$new_line;
				$str .= $this->{$part}->get();
				$str .= "</t".$part.">".self::$new_line.self::$new_line;
			}
		}

		$str .= "</table>".self::$new_line;

		return($str);
	}

	/**
	echo XHTML &lt;table&gt;
	@return &lt;table;&gt;
	*/
	public function show()
	{
		echo($this->get());
	}

	/**
	return XHTML &lt;table&gt;
	@return <b>string</b> &lt;table;&gt;

	@see	http://php.net/manual/language.oop5.magic.php#language.oop5.magic.tostring
	*/
	public function __toString()
	{
		return($this->get());
  }
	/***** /table ****/

	/***** format strings ****/
	/**
	add a space before the string if it's not empty
	@param	str	<b>string</b>	String
	@return	<b>string</b>
	*/
	private static function arg($str)
	{
		return((!empty($str)) ? " ".$str : "");
	}

	/**
	create a XHTML tag by changing "$str" to "<$tag $extra_html>$str</$str>"
	@param	tag	<b>string</b>	Tag ("td")
	@param	str	<b>string</b>	String ("")
	@param	extra_html	<b>string</b>	Cell's extra HTML ("")
	@return	<b>string</b>	String
	*/
	private static function tag($tag="td",$str="",$extra_html="")
	{
		/* IE bug */
		if ($str == "") {$str = "&nbsp;";}
		return("<".$tag.self::arg($extra_html).">".$str."</".$tag.">".
			self::$new_line);
	}
	/***** /format strings ****/

}

/**
@ingroup Table
@brief Part of the table
*/
class part
{
	public $rows;	///< <b>array</b>	Rows
	public $extra_html;	///< <b>string</b>	Part's extra HTML
	private static $new_line = "\n";	///< <b>string</b>	New line

	/**
	construct Part
	*/
	public function __construct()
	{
		$this->rows = array();

		$this->extra_html = "";
	}

	/**
	open a part
	@param	extra_html	<b>string</b>	Part's extra HTML ("")
	*/
	public function extraHTML($extra_html="")
	{
		# avoid bug with $table->headers()
		if (empty($extra_html))
		{
			return;
		}
		$this->extra_html = $extra_html;
	}

	/**
	add rows to part
	@param	rows	<b>array</b>	Rows
	*/
	public function rows($rows)
	{
		$this->rows = array_merge($this->rows,$rows);
	}

	/**
	return part
	@return	<b>string</b> part

	@see	http://www.php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
	*/
	public function __toString()
	{
		return(implode(self::$new_line,$this->rows));
	}

	/**
	return part
	@return	<b>string</b> part

	@see	http://www.php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
	__toString() doesn't work with PHP < 5.2.0
	*/
	public function get()
	{
		return(implode(self::$new_line,$this->rows));
	}
}
