<?php

namespace DOM;

class DOM
{

	use hasUtilities;
	use isTraversable;
	use canRender;

	protected string $tagName = '';
	protected array $attrs = [];
	/** @var DOM[] */
	protected array $children_ = [];

	public function tagName(string $tagName = ''):self
	{
		$this->tagName = $tagName;
		return $this;
	}

	public function attr(string $key, $val):self
	{
		$this->attrs[$key] = $val;
		return $this;
	}

	public function attrs(array $attrs = []):self
	{
		array_walk($attrs, fn($val, $key) => $this->attr($key, $val));
		return $this;
	}

	public function addChild(DOM $child):self
	{
		if ( !$this->useWhitespace ) {
			$child->useWhitespace = false;
		}
		$this->children_[] = $child;
		return $this;
	}

	/**
	 * @param DOM[] $children_
	 * @return $this
	 */
	public function addChildren(array $children_):self
	{
		array_map(
			fn(DOM $child) => $this->addChild($child),
			$children_
		);
		return $this;
	}

	public function addText(string $text):self
	{
		return $this->addChild(TextNode::create($text));
	}

	public function addSelfClosing(string $tagName, ?array $attrs = []):self
	{
		return $this->addChild(SelfClosingNode::create($tagName, $attrs));
	}

}
