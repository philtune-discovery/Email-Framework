<?php

namespace NodeBuilder;

trait collectsChildren
{

	/** @var NodeBuilder[] */
	protected array $children_ = [];

	/**
	 * @param NodeBuilder $_child
	 * @return $this
	 */
	public function add(NodeBuilder $_child):self
	{
		if ( !$this->useWhitespace ) {
			$_child->useWhitespace = false;
		}
		$this->children_[] = $_child;
		return $this;
	}

	/**
	 * @param NodeBuilder[] $children_
	 * @return $this
	 */
	public function addChildren(array $children_):self
	{
		array_map(
			fn(NodeBuilder $_child) => $this->add($_child),
			$children_
		);
		return $this;
	}

	/**
	 * @param string $text
	 * @return $this
	 */
	public function addText(string $text):self
	{
		return $this->add(TextNode::new($text));
	}

	public function addSelfClosing(string $tagName, ?array $attrs = []):self
	{
		return $this->add(
			SelfClosingNode::new($tagName)
			               ->attrs($attrs)
		);
	}

}
