<?php

namespace NodeBuilder\Extensions;

use NodeBuilder\NodeBuilder;

class TextNode extends NodeBuilder
{

	public function render():string
	{
		return $this->renderTabs() . $this->tagName;
	}

}
