<?php

namespace NodeBuilder;

trait hasChildren
{

	/** @var NodeBuilder[] */
	protected array $children_ = [];

	/**
	 * @param NodeBuilder $child
	 * @return $this
	 */
	public function addChild(NodeBuilder $child):self
	{
		if ( !$this->useWhitespace ) {
			$child->useWhitespace = false;
		}
		$this->children_[] = $child;
		return $this;
	}

	/**
	 * @param NodeBuilder[] $children_
	 * @return $this
	 */
	public function addChildren(array $children_):self
	{
		array_map(
			fn(NodeBuilder $child) => $this->addChild($child),
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
