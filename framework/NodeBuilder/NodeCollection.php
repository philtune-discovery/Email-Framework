<?php

namespace NodeBuilder;

class NodeCollection extends NodeBuilder
{

	public function render():string
	{
		$this->tabNum--;
		return $this->renderChildren();
	}

}
