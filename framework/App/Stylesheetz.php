<?php

namespace App;

class Stylesheetz
{
	private const INLINE_STYLES_FILE = './sass/inline_styles.css';
	private const MOBILE_WRAPPER_PATTERN = '/@media \(max-width: \d+px\){(.+?})}/';
	private const PSEUDO_CLASS_SELECTOR_PATTERN = '/\.([\w-]+):[\w]+{[^}]+}/';
	private const CLASS_SELECTOR_PATTERN = '/\.([^{]+){([^}]+)}/';
	protected string $inline_styles_str;
	protected string $mobile_styles_str;
	protected array $mobile_styles_keys;
	protected string $pseudo_styles_str;
	protected array $pseudo_styles_keys;
	protected array $desktop_styles_arr;

	protected function __construct()
	{
		if ( file_exists(self::INLINE_STYLES_FILE) ) {

			// Change double quotes back to single (artifact of Sass compiler)
			$this->inline_styles_str = str_replace('"', "'", include_string(self::INLINE_STYLES_FILE));

			preg_match_all(self::PSEUDO_CLASS_SELECTOR_PATTERN, $this->inline_styles_str, $matches);
			$this->pseudo_styles_str = array_reduce($matches[0], function($carry, $item){
				$carry .= $item;
				return $carry;
			}, '');

			$my_matches = [];
			$this->inline_styles_str = preg_replace_callback(self::PSEUDO_CLASS_SELECTOR_PATTERN, function($matches) use(&$my_matches){
				$my_matches[] = $matches[1];
				return '';
			}, $this->inline_styles_str);
			$this->pseudo_styles_keys = $my_matches;

			/* Extract mobile styles */
			preg_match_all(self::MOBILE_WRAPPER_PATTERN, $this->inline_styles_str, $matches);
			$this->mobile_styles_str = array_reduce($matches[1], function($carry, $item){
				return $carry .= $item;
			}, '');

			preg_match_all(self::CLASS_SELECTOR_PATTERN, $this->mobile_styles_str, $matches);
			$this->mobile_styles_keys = array_reduce($matches[1], function($carry, $item){
				$carry[] = $item;
				return $carry;
			}, []);

			$desktop_only_styles_str = preg_replace(self::MOBILE_WRAPPER_PATTERN, '', $this->inline_styles_str);
			/* Remove mobile styles to get just desktop styles */
			$desktop_arr = [];
			preg_match_all(self::CLASS_SELECTOR_PATTERN, $desktop_only_styles_str, $matches);
			foreach ( $matches[1] as $i => $id ) {
				$desktop_arr[$id] = $matches[2][$i];
			}
			$this->desktop_styles_arr = $desktop_arr;

		}
	}

	protected function __clone()
	{
	}

	protected function __wakeup()
	{
	}

	public static function instance():self
	{
		static $_this = null;
		if ( $_this === null ) {
			$_this = new static;
		}
		return $_this;
	}

	public static function getMobileStylesStr()
	{
		return self::instance()->mobile_styles_str;
	}

	public static function getPseudoStylesStr()
	{
		return self::instance()->pseudo_styles_str;
	}

	public static function hasPseudoStylesFor($name)
	{
		return in_array($name, self::instance()->pseudo_styles_keys);
	}

	public static function hasMobileStylesFor($name)
	{
		return in_array($name, self::instance()->mobile_styles_keys);
	}

	public static function getDesktopStylesArr()
	{
		return self::instance()->desktop_styles_arr;
	}

	public static function getInlineStylesFor($name)
	{
		return self::instance()->desktop_styles_arr[$name] ?? '';
	}

	public static function renderMobileStyles(array $mobileStyles = null):?string
	{
		$globalMobileStyles = [];
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
