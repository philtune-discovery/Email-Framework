<?php

namespace NodeBuilder\Extensions;

use NodeBuilder\NodeBuilder;

class SelfClosingNode extends NodeBuilder
{

	public function render():string
	{
		return $this->renderTabs() . "<{$this->renderTag()}/>";
	}

}
