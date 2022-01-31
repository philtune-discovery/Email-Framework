<?php

namespace HTMLEmail;

use NodeBuilder\ElemNode;
use NodeBuilder\NodeBuilder;
use NodeBuilder\SelfClosingNode;

function getTableAttrs(array $mergeAttrs = [], array $mergeStyles = []):array
{
	return array_merge([
		'width'       => '100%',
		'cellpadding' => 0,
		'cellspacing' => 0,
		'border'      => 0,
		'role'        => 'presentation',
		'style'       => toStyleStr([
			'max-width'       => '100%',
			'mso-cellspacing' => '0px',
			'mso-padding-alt' => '0px 0px 0px 0px'
		], $mergeStyles),
	], $mergeAttrs);
}

function toStyleStr(array $arr, ...$other_arrs):string
{
	$arr = array_merge($arr, ...$other_arrs);
	return implode_kvps(';', $arr, function ($a, $b) {
		if ( is_array($b) ) {
			json_out($b);
		}
		return "$a:$b";
	});
}

function toAttrStr(array $attrs, ...$other_attrs):string
{
	$attrs = array_merge($attrs, ...$other_attrs);
	return implode_kvps(' ', $attrs, fn($a, $b) => "$a=\"$b\"");
}

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
	return HTMLEmail::a($text, $href);
}
