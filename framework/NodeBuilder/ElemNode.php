<?php

namespace NodeBuilder;

class ElemNode extends NodeBuilder
{

	public function render():string
	{
		return
			$this->renderTabs() .
			"<{$this->renderTag()}>" .
			( $this->useWhitespace ? "\n" : "" ) .
			$this->renderChildren() .
			( $this->useWhitespace ? "\n{$this->renderTabs()}" : "" ) .
			"</$this->tagName>";
	}

}

