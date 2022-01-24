<?php

namespace Styles;

class Styles
{
	public static function arrToStr(array $arr):string
	{
		return implode(';', array_reduce(array_entries($arr), function ($c, $i) {
			$c[] = "$i[0]:$i[1]";
			return $c;
		}, []));
	}
}
