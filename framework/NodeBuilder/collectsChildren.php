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
	public function addChild(NodeBuilder $_child):self
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
			fn(NodeBuilder $_child) => $this->addChild($_child),
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
		return $this->addChild(TextNode::new($text));
	}

	public function addSelfClosing(string $tagName, ?array $attrs = []):self
	{
		return $this->addChild(
			SelfClosingNode::new($tagName)
			               ->attrs($attrs)
		);
	}

}
