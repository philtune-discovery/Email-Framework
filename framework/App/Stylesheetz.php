<?php

namespace App;

class Stylesheetz extends Singleton
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
		parent::__construct();
	}

	public static function getInlineStylesStr()
	{
		return self::getInstance()->inline_styles_str;
	}

	public static function getMobileStylesStr()
	{
		return self::getInstance()->mobile_styles_str;
	}

	public static function getPseudoStylesStr()
	{
		return self::getInstance()->pseudo_styles_str;
	}

	public static function getPseudoStylesKeys()
	{
		return self::getInstance()->pseudo_styles_keys;
	}

	public static function hasPseudoStylesFor($name)
	{
		return in_array($name, self::getInstance()->pseudo_styles_keys);
	}

	public static function hasMobileStylesFor($name)
	{
		return in_array($name, self::getInstance()->mobile_styles_keys);
	}

	public static function getDesktopStylesArr()
	{
		return self::getInstance()->desktop_styles_arr;
	}

	public static function getInlineStylesFor($name)
	{
		return self::getInstance()->desktop_styles_arr[$name] ?? '';
	}
}
