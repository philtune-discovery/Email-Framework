<?php

namespace HTMLEmail;

use HTMLEmail\EmailAttributeBuilder\EmailAttributeBuilder;
use HTMLEmail\HTMLEmailNodeBuilder\HTMLEmailNodeBuilder;
use NodeBuilder\NodeBuilder;

class HTMLEmail
{

	use canRender;
	use buildsNodes;
	use hasUtilities;

	protected string $title;
	protected Container $container;
	private string $bgcolor;
	private string $txtcolor;
	private ?string $previewText;
	public HTMLEmailNodeBuilder $nodeBuilder;

	private function __construct(array $config = [])
	{
		$this->title = $config['title'] ?? 'Email';
		$this->bgcolor = $config['bgcolor'] ?? '#FFFFFF';
		$this->txtcolor = $config['txtcolor'] ?? '#000000';
		$this->previewText($config['preview-text'] ?? null);
		$this->container = new Container($this, $config['container']);
		$this->nodeBuilder = new HTMLEmailNodeBuilder($this);
		NodeBuilder::setAttributeBuilderClass(EmailAttributeBuilder::class);
	}

	public function getContainer():Container
	{
		return $this->container;
	}

	public static function new(array $config = []):HTMLEmailNodeBuilder
	{
		$_htmlEmail = new static($config);
		return $_htmlEmail->nodeBuilder;
	}

	public function __toString():string
	{
		return $this->render();
	}

	public function previewText(string $previewText):self
	{
		$this->previewText = $previewText;
		return $this;
	}

}
