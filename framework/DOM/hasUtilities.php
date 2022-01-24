<?php

namespace DOM;

trait hasUtilities
{

	protected static function attrSerialize(array $arr):string
	{
		$results = [];
		foreach ( $arr as $key => $val ) {
			$results [] = is_numeric($key) ?
				$val :
				"$key=\"$val\"";
		}
		return implode(' ', $results);
	}

}
