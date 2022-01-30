<?php

namespace NodeBuilder;

class TextNode extends NodeBuilder
{

	public function render():string
	{
		return $this->renderTabs() . $this->tagName;
	}

}
