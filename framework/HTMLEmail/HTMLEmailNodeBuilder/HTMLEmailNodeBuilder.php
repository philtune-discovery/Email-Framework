<?php

namespace HTMLEmail\HTMLEmailNodeBuilder;

use HTMLEmail\HTMLEmail;
use NodeBuilder\NodeCollection;

class HTMLEmailNodeBuilder
{

	use buildsBlocks;
	use addsNodes;

	public NodeCollection $domCollection;
	private HTMLEmail $htmlEmail;

	public function __construct(HTMLEmail $htmlEmail)
	{
		$this->domCollection = NodeCollection::new();
		$this->htmlEmail = $htmlEmail;
	}

	public function __toString():string
	{
		return $this->htmlEmail;
	}

}
