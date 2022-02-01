<?php

namespace NodeBuilder\AttributeBuilder;

use NodeBuilder\NodeBuilder;

class AttributeBuilder
{

	protected string $uid;
	protected array $attrs = [];
	protected array $styles = [];
	protected ?NodeBuilder $nodeBuilder = null;

	public function __construct(?NodeBuilder $nodeBuilder = null)
	{
		$this->uid = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 6);
		if ( $nodeBuilder ) {
			$this->nodeBuilder = $nodeBuilder;
		}
	}

	public static function new():self
	{
		return new static;
	}

	public static function attrs(...$attrs):self
	{
		return static::new()->addAttrs(...$attrs);
	}

	public static function styles(...$styles):self
	{
		return static::new()->addStyles(...$styles);
	}

	public function addAttrs(...$attr_groups):self
	{
		// Extract styles
		array_walk($attr_groups, function (&$attrs) {
			if ( $style = $attrs['style'] ?? null ) {
				if ( is_string($style) ) {
					$styles = [];
					foreach ( explode(';', $style) as $kvp_str ) {
						list($prop, $val) = explode(':', $kvp_str);
						$styles[$prop] = $val;
					}
				} else {
					$styles = $attrs['style'];
				}
				$this->addStyles($styles);
				unset($attrs['style']);
			}
		});
		$this->attrs = array_replace_recursive($this->attrs, ...$attr_groups);
		return $this;
	}

	public function addStyles(...$styles):self
	{
		$styles = array_replace_recursive(...$styles);
		array_walk($styles, function($val, $key) use (&$styles) {
			if ( is_array($val) ) {
				unset($styles[$key]);
			}
		});
		$this->styles = array_merge($this->styles, $styles);
		return $this;
	}

	public function __toString():string
	{
		return $this->render();
	}

	public function render():string
	{
		$attrs = $this->attrs;
		if ( !empty($this->styles) ) {
			$attrs['style'] = $this->implodeStyles();
		}
		return static::implode_kvps(' ', $attrs,
			fn($a, ?string $b):string => is_numeric($a) ?
				$b :
				"$a=\"$b\"");
	}

	protected function implodeStyles():string
	{
		return self::implode_kvps(
			';',
			$this->styles,
			fn(string $a, string $b):string => "$a:$b"
		);
	}

	protected static function implode_kvps(string $separator, array $array, callable $callback):string
	{
		return implode(
			$separator,
			array_map(
				fn($pair) => $callback(...$pair),
				array_entries($array)
			)
		);
	}
}
