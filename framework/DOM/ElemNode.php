<?php

namespace DOM;

class ElemNode extends DOM
{

	protected array $exclude_children = [
//		ElemNode::class
	];

	public function __construct(string $tagName, ?array $attrs = [], array $config = [])
	{
		$this->tagName = $tagName;
		$this->attrs = $attrs;
		$this->useWhitespace = $config['useWhitespace'] ?? true;
		$this->children_ = $config['children'] ?? [];
	}

	public static function create(string $tagName, ?array $attrs = [], array $config = []):self
	{
		return new static($tagName, $attrs, $config);
	}

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

