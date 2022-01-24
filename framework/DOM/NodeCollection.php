<?php

namespace DOM;

class NodeCollection extends DOM
{
	public static function create():self
	{
		return new static;
	}

	public function render():string
	{
		$this->tabNum--;
		return $this->renderChildren();
	}
}
