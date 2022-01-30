<?php

namespace NodeBuilder;

class ConditionalComment extends NodeBuilder
{

	public function render():string
	{
		return
			$this->renderTabs() .
			"<!--[if $this->tagName]>" . (substr($this->tagName, 0, 1) === '!' ? '<!-->' : '') .
			( $this->useWhitespace ? "\n" : "" ) .
			$this->renderChildren() .
			( $this->useWhitespace ? "\n{$this->renderTabs()}" : "" ) .
			(substr($this->tagName, 0, 1) === '!' ? '<!--' : '') . "<![endif]-->";
	}

}
