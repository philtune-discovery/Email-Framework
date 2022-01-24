<?php

use DOM\DOM;
use DOM\ElemNode;
use DOM\ConditionalComment;
use DOM\SelfClosing;
use DOM\TextNode;

function selfClosing(string $tagName, ?array $attrs = []):SelfClosing
{
	return SelfClosing::create($tagName, $attrs);
}

function text(string $text):TextNode
{
	return TextNode::create($text);
}

function elem(string $tagName, ?array $attrs = [], array $config = []):ElemNode
{
	return ElemNode::create($tagName, $attrs, $config);
}

/**
 * @param string $condition
 * @param DOM[] $children_
 * @param bool $useWhitespace
 * @return ConditionalComment
 */
function conditional(string $condition, array $children_ = [], bool $useWhitespace = true):ConditionalComment
{
	return ConditionalComment::create($condition, $children_, $useWhitespace);
}
