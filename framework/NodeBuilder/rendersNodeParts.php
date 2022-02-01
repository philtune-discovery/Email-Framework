<?php

namespace NodeBuilder;

trait rendersNodeParts
{

	public function __toString():string
	{
		return $this->render();
	}

	protected function renderTag():string
	{
		if ( $attrs = $this->attrBuilder->render() ) {
			$attrs = " $attrs";
		}
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
