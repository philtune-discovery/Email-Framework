<?php

namespace HTMLEmail\NodeBuilder;

use DOM\DOM;
use DOM\ElemNode;
use DOM\NodeCollection;
use DOM\TextNode;
use HTMLEmail\HTMLEmail;

class NodeBuilder
{

	public NodeCollection $domCollection;
	private HTMLEmail $HTMLEmail;

	public function __construct(HTMLEmail $HTMLEmail)
	{
		$this->domCollection = NodeCollection::create();
		$this->HTMLEmail = $HTMLEmail;
	}

	public function __toString():string
	{
		return $this->HTMLEmail;
	}

	public function yield(string $name = 'main'):DOM
	{
		return $this->domCollection;
	}

	public function addDiv(DOM $child):self
	{
		$this->domCollection->addChild(
			ElemNode::create('tr')->addChild(
				ElemNode::create('td')->addChild($child)
			)
		);
		return $this;
	}

	public function addRow($height):self
	{
		$this->domCollection->addChild(ElemNode::create('tr')->addChild(
			ElemNode::create('td', [
				'height' => $height,
				'style'  => 'font-size:0px'
			])
			        ->useWhitespace(false)
			        ->addChild(
				        TextNode::create('&nbsp;')
			        )
		));
		return $this;
	}

	public function addFullWidthImage($src, string $alt = null, string $href = null):self
	{
		if ( is_array($src) ) {
			$src = $src['src'];
			$alt = $src['alt'] ?? null;
			$href = $src['href'] ?? null;
		}
		return $this->addDiv(
			HTMLEmail::img([
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
	public function addColumns(...$children_):self
	{
		$this->domCollection->addChildren([
			ElemNode::create('tr')->addChild(
				ElemNode::create('td')->addChild(
					HTMLEmail::table()->addChild(
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
	public function addTrackingPixels(array $track):self
	{
		$imgs = array_reduce($track, function ($imgs, $src) {
			$imgs[] = img($src);
			return $imgs;
		}, []);
		$this->domCollection->addChild(
			ElemNode::create('tr')->addChild(
				ElemNode::create('td')->addChildren($imgs)
			)
		);
		return $this;
	}

}
