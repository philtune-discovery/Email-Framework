<?php

namespace DOM;

trait canRender
{

	protected int $tabNum = 0;
	protected bool $useWhitespace = true;

	public function __toString():string
	{
		return $this->render();
	}

	public function tabNum(int $tabNum):self
	{
		$this->tabNum = $tabNum;
		return $this;
	}

	public function useWhitespace(bool $useWhitespace = true):self
	{
		$this->useWhitespace = $useWhitespace;
		return $this;
	}

	public function render():string
	{
		return '';
	}

	protected function renderTag():string
	{
		if ( $attrs = self::attrSerialize($this->attrs) ) {
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
		return implode("\n", array_map(
			function (DOM $child) {
				$child->tabNum = $this->useWhitespace ?
					$this->tabNum + 1 :
					0;
				return $child;
			},
			$this->children_
		));
	}

}
