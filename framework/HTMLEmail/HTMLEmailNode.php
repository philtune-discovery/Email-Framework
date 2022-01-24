<?php

namespace HTMLEmail;

use DOM\DOM;
use DOM\ElemNode;
use DOM\NodeCollection;
use DOM\TextNode;

class HTMLEmailNode
{

	use HasStylesheet;

	private array $yield_stack = [];
	private NodeCollection $nodeCollection;

	public function __toString():string
	{
		return $this->nodeCollection;
	}

	public static function create():self
	{
		$_instance = new static;
		$_instance->nodeCollection = NodeCollection::create();
		return $_instance;
	}

	public static function use(string $parentPath):self
	{
		$_instance = new self();
		$_instance->nodeCollection = DOM::inherit($parentPath);
		return $_instance;
	}

	public static function createDiv():ElemNode
	{
		return ElemNode::create('tr')->addChild(
			ElemNode::create('td')
		);
	}

	public function div(DOM $child):self
	{
		$this->nodeCollection->addChild(
			ElemNode::create('tr')->addChild(
				ElemNode::create('td')->addChild($child)
			)
		);
		return $this;
	}

	public function row($height):ElemNode
	{
		return ElemNode::create('tr')->addChild(
			ElemNode::create('td', [
				'height' => $height,
				'style'  => 'font-size:0px'
			])
			        ->useWhitespace(false)
			        ->addChild(
				        TextNode::create('&nbsp;')
			        )
		);
	}

	public function full_width_img($src, string $alt = null, string $href = null):HTMLEmailNode
	{
		if ( is_array($src) ) {
			$src = $src['src'];
			$alt = $src['alt'] ?? null;
			$href = $src['href'] ?? null;
		}
		return $this->div(
			img([
				'src'   => $src,
				'alt'   => $alt,
				'href'  => $href,
				'width' => setting('container_width'),
				'style' => "display:block",
				'class' => 'w-100p',
			])
		);
	}

	/**
	 * @param DOM[]|array $children_
	 * @return $this
	 */
	public function cols(...$children_):self
	{
		$this->nodeCollection->addChildren([
			ElemNode::create('tr')->addChild(
				ElemNode::create('td')->addChild(
					table()->addChild(
						ElemNode::create('tr')->addChildren(
							array_map(function ($child) {
								if ( is_array($child) ) {
									$colAttrs = $child[0];
									$childNodes = $child[1];
									$child = ElemNode::create('td', $colAttrs)->addChildren($childNodes);
								}
								return $child;
							}, $children_)
						)
					)
				)
			)
		]);
		return $this;
	}

	/**
	 * @param string[] $track
	 */
	public function track(array $track)
	{
		$imgs = array_reduce($track, function ($imgs, $src) {
			$imgs[] = img($src);
			return $imgs;
		}, []);
		$this->nodeCollection->addChild(
			ElemNode::create('tr')->addChild(
				ElemNode::create('td')->addChildren($imgs)
			)
		);
	}
}
