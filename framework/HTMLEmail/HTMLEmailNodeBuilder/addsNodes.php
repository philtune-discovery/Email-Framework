<?php

namespace HTMLEmail\HTMLEmailNodeBuilder;

use HTMLEmail\HTMLEmail;
use NodeBuilder\ElemNode;
use NodeBuilder\NodeBuilder;

trait addsNodes
{
	/**
	 * @param NodeBuilder[] $children_
	 * @return $this
	 */
	public function add(...$children_):self
	{
		$this->domCollection->addChildren($children_);
		return $this;
	}

	public function addRow(NodeBuilder $child):self
	{
		return $this->add(self::row($child));
	}

	public function addRowPadding($height):self
	{
		return $this->add(self::row_padding($height));
	}

	public function addImgRow($src, string $alt = null, string $href = null):self
	{
		if ( is_array($src) ) {
			$src = $src['src'];
			$alt = $src['alt'] ?? null;
			$href = $src['href'] ?? null;
		}
		return $this->add(
			self::row(
				HTMLEmail::img([
					'src'   => $src,
					'alt'   => $alt,
					'href'  => $href,
					'width' => $this->htmlEmail->getContainer()->getWidth(),
					'style' => "display:block",
					'class' => 'w-100p',
				])
			)
		);
	}

	/**
	 * @param NodeBuilder[]|array $children_
	 * @return $this
	 */
	public function addColumns(...$children_):self
	{
		$children_ = array_map(
			fn($child) => is_array($child) ?
				ElemNode::new('td')
				        ->attrs($child[0])
				        ->addChildren($child[1]) :
				$child,
			$children_
		);
		return $this->add(
			ElemNode::new('tr')->addChild(
				ElemNode::new('td')->addChild(
					HTMLEmail::table()->addChild(
						ElemNode::new('tr')->addChildren(
							$children_
						)
					)
				)
			)
		);
	}

	/**
	 * @param string[] $urls
	 */
	public function addTrackingPixels(array $urls):self
	{
		$imgs_ = array_map(
			fn($url) => HTMLEmail::img($url, null, null, ['height' => 1, 'width' => 1]),
			$urls
		);
		return $this->add(
			ElemNode::new('tr')->addChild(
				ElemNode::new('td')->addChildren($imgs_)
			)
		);
	}

	public function addPadded(array $padding, array $children_):self
	{
		$this->domCollection->addChild(HTMLEmail::padded($padding, $children_));
		return $this;
	}
}
