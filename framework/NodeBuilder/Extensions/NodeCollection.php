<?php

namespace NodeBuilder\Extensions;

use NodeBuilder\NodeBuilder;

class NodeCollection extends NodeBuilder
{

	public function render():string
	{
		$this->tabNum--;
		return $this->renderChildren();
	}

}
