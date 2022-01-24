<?php

namespace DOM;

class SelfClosing extends DOM
{
	public function __construct(string $tagName, ?array $attrs = [])
	{
		$this->tagName = $tagName;
		$this->attrs = $attrs;
	}

	public static function create(string $tagName, ?array $attrs = []):self
	{
		return new static($tagName, $attrs);
	}

	public function render():string
	{
		return $this->renderTabs() . "<{$this->renderTag()}/>";
	}
}
