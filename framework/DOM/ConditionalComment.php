<?php

namespace DOM;

class ConditionalComment extends DOM
{
	protected string $condition;

	/**
	 * @param string $condition
	 * @param DOM[] $children_
	 * @param bool $useWhitespace
	 */
	public function __construct(string $condition, array $children_ = [], bool $useWhitespace = true)
	{
		$this->condition = $condition;
		$this->useWhitespace = $useWhitespace;
		$this->children_ = $children_;
	}

	/**
	 * @param string $condition
	 * @param DOM[] $children_
	 * @param bool $useWhitespace
	 * @return static
	 */
	public static function create(string $condition, array $children_ = [], bool $useWhitespace = true):self
	{
		return new self($condition, $children_, $useWhitespace);
	}

	public function render():string
	{
		return
			$this->renderTabs() .
			"<!--[if $this->condition]>" . (substr($this->condition, 0, 1) === '!' ? '<!-->' : '') .
			( $this->useWhitespace ? "\n" : "" ) .
			$this->renderChildren() .
			( $this->useWhitespace ? "\n{$this->renderTabs()}" : "" ) .
			(substr($this->condition, 0, 1) === '!' ? '<!--' : '') . "<![endif]-->";
	}
}
