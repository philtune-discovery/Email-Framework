<?php

namespace HTMLEmail;

use App\Stylesheetz;

trait HasStylesheet
{
	private static array $styles = ['mobileStyles' => []];

	public static function getStyles():array
	{
		return self::$styles;
	}

	public static function renderMobileStyles(array $mobileStyles = null):?string
	{
		$globalMobileStyles =& self::$styles['mobileStyles'];
		if ( $mobileStyles ) {
			foreach ( $mobileStyles as $selector => $styles ) {
				if ( !array_key_exists($selector, $globalMobileStyles) ) {
					$globalMobileStyles[$selector] = [];
				}
				foreach ( $styles as $prop => $val ) {
					$globalMobileStyles[$selector][$prop] = $val;
				}
			}
			return null;
		} else {
			$mobileStylesStr = array_reduce(
				array_entries($globalMobileStyles),
				fn($a, $selector_styles) => $a . "$selector_styles[0]{" .
					implode(';', array_map(
						fn($prop_val) => "$prop_val[0]:$prop_val[1]",
						array_entries($selector_styles[1])
					)) .
					"}",
				''
			) . Stylesheetz::getMobileStylesStr();
			return "@media only screen and ( max-width: 648px ) {{$mobileStylesStr}}";
		}
	}

}
