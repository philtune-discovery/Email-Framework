<?php

namespace HTMLEmail;

use HTMLEmail\Container\Container;
use HTMLEmail\EmailAttributeBuilder\AttributeBuilder;
use NodeBuilder\NodeBuilder;
use NodeBuilder\NodeCollection;

class HTMLEmail
{

	use buildsNodes;
	use collectsChildren;
	use rendersParts;

	protected string $title;
	protected Container $container;
	private string $bgcolor;
	private string $txtcolor;
	private ?string $previewText;
	public NodeCollection $domCollection;

	private function __construct(array $config = [])
	{
		NodeBuilder::setAttributeBuilderClass(AttributeBuilder::class);
		$this->title = $config['title'] ?? 'Email';
		$this->bgcolor = $config['bgcolor'] ?? '#FFFFFF';
		$this->txtcolor = $config['txtcolor'] ?? '#000000';
		$this->previewText($config['preview-text'] ?? null);
		$this->container = new Container($this, $config['container']);
		$this->domCollection = NodeCollection::new();
	}

	public function previewText(string $previewText):self
	{
		$this->previewText = $previewText;
		return $this;
	}

	public static function new(array $config = []):self
	{
		return new static($config);
	}

	public function getContainer():Container
	{
		return $this->container;
	}

}
