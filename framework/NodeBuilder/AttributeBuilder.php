<?php

namespace NodeBuilder;

class AttributeBuilder implements MustRender
{

	private array $attrs = [];

	public function add(...$attrs):self
	{
		$this->attrs = array_merge($this->attrs, ...$attrs);
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
