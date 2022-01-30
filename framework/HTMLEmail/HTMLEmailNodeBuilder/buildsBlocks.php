<?php

namespace HTMLEmail\HTMLEmailNodeBuilder;

use HTMLEmail\HTMLEmail;
use NodeBuilder\ElemNode;
use NodeBuilder\NodeBuilder;
use NodeBuilder\TextNode;

trait buildsBlocks
{

	public static function row(NodeBuilder $child):ElemNode
	{
		return ElemNode::new('tr')->addChild(
			ElemNode::new('td')->addChild($child)
		);
	}

	/**
	 * @param string|numeric $height
	 * @return ElemNode
	 */
	public static function row_padding($height):ElemNode
	{
		return ElemNode::new('tr')->addChild(
			ElemNode::new('td')->attrs([
				'height' => $height,
				'style'  => 'font-size:0px'
			])
			        ->useWhitespace(false)
			        ->addChild(
				        TextNode::new('&nbsp;')
			        )
		);
	}
}
