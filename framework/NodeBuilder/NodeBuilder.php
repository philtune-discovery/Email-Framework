<?php

namespace NodeBuilder;

abstract class NodeBuilder implements MustRender
{
	use rendersParts;
	use collectsChildren;

	protected string $tagName = '';
	private static string $attributeBuilderClass = AttributeBuilder::class;
	protected int $tabNum = 0;
	protected bool $useWhitespace = true;
	private AttributeBuilder $attrBuilder;

	public function __construct(string $tagName = '')
	{
		$this->tagName = $tagName;
		$this->attrBuilder = new self::$attributeBuilderClass();
	}

	/**
	 * @param string $tagName
	 * @return static
	 */
	public static function new(string $tagName = ''):self
	{
		return new static($tagName);
	}

	public static function setAttributeBuilderClass(string $attributeBuilderClass)
	{
		if ( is_subclass_of($attributeBuilderClass, AttributeBuilder::class) ) {
			self::$attributeBuilderClass = $attributeBuilderClass;
		} else {
			json_out([AttributeBuilder::class, $attributeBuilderClass, get_parent_class($attributeBuilderClass)]);
		}
	}

	public function attrs(...$attrs):self
	{
		$this->attrBuilder->addAttrs(...$attrs);
		return $this;
	}

	public function style(...$styles):self
	{
		$this->attrBuilder->addStyles(...$styles);
		return $this;
	}

	public function useWhitespace(bool $useWhitespace = true):self
	{
		$this->useWhitespace = $useWhitespace;
		return $this;
	}

}
