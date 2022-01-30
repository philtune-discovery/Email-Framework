<?php

namespace NodeBuilder;

class AttributeBuilder implements MustRender
{

	protected array $attrs = [];

	public function addAttrs(...$attr_groups):self
	{
		$this->attrs = array_merge_recursive($this->attrs, ...$attr_groups);
		return $this;
	}

	public function addStyles(...$styles):self
	{
		$this->addAttrs(['style' => array_merge_recursive($styles)]);
		return $this;
	}

	public function render():string
	{
		return static::serialize($this->attrs);
	}

	private static function serialize(array $arr):string
	{
		return static::implode_kvps(' ', $arr,
			fn($a, $b) => is_numeric($a) ?
				$b :
				"$a=\"$b\"");
	}

	private static function implode_kvps(string $separator, array $array, callable $callback):string
	{
		return implode($separator, array_map(fn($pair) => $callback(...$pair), array_entries($array)));
	}
}
