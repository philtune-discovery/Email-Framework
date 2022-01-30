<?php

namespace NodeBuilder;

trait rendersParts
{

	public function __toString():string
	{
		return $this->render();
	}

	protected function renderTag():string
	{
//		$implode_kvps = fn(string $separator, array $array, callable $callback) => implode($separator, array_map(fn($pair) => $callback(...$pair), array_entries($array)));
//		$serialize_attrs = fn(array $arr) => $implode_kvps(' ', $arr, fn($a, $b) => is_numeric($a) ? $b : "$a=\"$b\"");
		if ( $attrs = $this->attrBuilder->render() ) {
			$attrs = " $attrs";
		}
//		if ( $attrs = $serialize_attrs($this->attrs) ) {
//			$attrs = " $attrs";
//		}
		return "$this->tagName$attrs";
	}

	protected function renderTabs():string
	{
		return str_repeat("\t", max($this->tabNum, 0));
	}

	protected function renderChildren():string
	{
		$children_ = array_map(
			function (NodeBuilder $_child) {
				$_child->tabNum = $this->useWhitespace ?
					$this->tabNum + 1 :
					0;
				return $_child;
			},
			$this->children_
		);
		return implode("\n", $children_);
	}

}
