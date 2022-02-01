<?php

namespace HTMLEmail;

use HTMLEmail\Container\Container;
use HTMLEmail\EmailAttributeBuilder\EmailAttributeBuilder;
use NodeBuilder\Extensions\NodeCollection;
use NodeBuilder\NodeBuilder;

class HTMLEmail
{

	use buildsNodes;
	use collectsChildren;
	use rendersEmailParts;

	protected string $title;
	protected Container $container;
	private string $bgcolor;
	private string $txtcolor;
	private ?string $previewText;
	public NodeCollection $domCollection;

	private function __construct(array $config = [])
	{
		$this->title = $config['title'] ?? 'Email';
		$this->bgcolor = $config['bgcolor'] ?? '#FFFFFF';
		$this->txtcolor = $config['txtcolor'] ?? '#000000';
		$this->previewText($config['preview-text'] ?? null);
		$this->container = new Container($this, $config['container']);
		$this->domCollection = NodeCollection::new();
		NodeBuilder::setAttributeBuilderClass(EmailAttributeBuilder::class);
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
