<?php

namespace DOM;

class TextNode extends DOM
{

	protected string $string = '';

	public function __construct(string $string)
	{
		$this->string = $string;
	}

	public static function create(string $string):self
	{
		return new self($string);
	}

	public function render():string
	{
		return $this->renderTabs() . $this->string;
	}
}
