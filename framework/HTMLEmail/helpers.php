<?php

namespace HTMLEmail;

use NodeBuilder\Extensions\ElemNode;
use NodeBuilder\Extensions\SelfClosingNode;
use NodeBuilder\NodeBuilder;

/**
 * @param string|numeric $input
 * @return string
 */
function toPx($input):string
{
	if ( is_string($input) && ( strtolower(substr($input, -2)) === 'px' || substr($input, -1) === '%' ) ) {
		return $input;
	}
	return "{$input}px";
}


function col($attrs, ...$children_):ElemNode
{
	return HTMLEmail::buildColumn($attrs, ...$children_);
}

/**
 * @param string|numeric $height
 * @return ElemNode
 */
function row_padding($height):ElemNode
{
	return HTMLEmail::buildRowPadding($height);
}

/**
 * @param string|array $src <p>img 'src' OR config array</p>
 * @param string|array|null $alt <p>img 'alt' OR array of attributes</p>
 * @param string|null $href
 * @param array $attrs
 * @return SelfClosingNode|ElemNode
 */
function img($src, $alt = null, string $href = null, array $attrs = [])
{
	return HTMLEmail::img($src, $alt, $href, $attrs);
}

/**
 * @param NodeBuilder[] $children_
 * @return NodeBuilder
 */
function rows(...$children_):NodeBuilder
{
	return HTMLEmail::buildRows(...$children_);
}

/**
 * @param string|NodeBuilder $string_or_node
 * @param array $attrs
 * @return ElemNode
 */
function text_row($string_or_node, array $attrs = []):ElemNode
{
	return HTMLEmail::buildTextRow($string_or_node, $attrs);
}

/**
 * @param array $paragraphs
 * @param array $paragraph_attrs
 * @param string|numeric $margin_height
 * @return ElemNode[]
 */
function text_rows(array $paragraphs, array $paragraph_attrs = [], $margin_height = 0):array
{
	return HTMLEmail::buildTextRows($paragraphs, $paragraph_attrs, $margin_height);
}

function button(string $text, string $href, array $buttonStyles, array $textStyles = []):NodeBuilder
{
	return HTMLEmail::buildButton($text, $href, $buttonStyles, $textStyles);
}

function padded(array $padding, array $children_)
{
	return HTMLEmail::buildPadded($padding, $children_);
}

function a(string $text, string $href):ElemNode
{
	return HTMLEmail::buildLink($text, $href);
}
