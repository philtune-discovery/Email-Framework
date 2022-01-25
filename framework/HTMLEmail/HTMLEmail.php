<?php

namespace HTMLEmail;

use HTMLEmail\NodeBuilder\NodeBuilder;

class HTMLEmail
{

	use Renderable;
	use hasHelpers;

	protected string $title;
	private Container $container;
	private string $bgcolor;
	private string $txtcolor;
	private ?string $previewText;
	public NodeBuilder $nodeBuilder;

	public function __construct(array $config = [])
	{
		$this->title = $config['title'] ?? 'Email';
		$this->bgcolor = $config['bgcolor'] ?? '#FFFFFF';
		$this->txtcolor = $config['txtcolor'] ?? '#000000';
		$this->previewText($config['preview-text'] ?? null);
		$this->container = new Container($this, $config['container']);
		$this->nodeBuilder = new NodeBuilder($this);
	}

	public static function new(array $config = []):NodeBuilder
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
