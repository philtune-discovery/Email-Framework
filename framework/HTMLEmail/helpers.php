<?php

use DOM\DOM;
use DOM\ElemNode;
use DOM\SelfClosingNode;
use DOM\TextNode;
use HTMLEmail\HTMLEmail;

if ( !function_exists('email') ) {
	function email()
	{

	}
}

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
	return implode_kvps(';', $arr, fn($a, $b) => "$a:$b");
}

function toAttrStr(array $attrs, ...$other_attrs):string
{
	$attrs = array_merge($attrs, ...$other_attrs);
	return implode_kvps(' ', $attrs, fn($a, $b) => "$a=\"$b\"");
}

function getTableAttrStr(array $mergeAttrs = [], array $mergeStyles = []):string
{
	return toAttrStr(getTableAttrs($mergeAttrs, $mergeStyles));
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
	return HTMLEmail::col($attrs, ...$children_);
}

function row($height):ElemNode
{
	return HTMLEmail::row($height);
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
 * @param DOM[] $children_
 * @return DOM
 */
function rows(...$children_):DOM
{
	return HTMLEmail::rows(...$children_);
}

/**
 * @param string|DOM $string_or_node
 * @param array $attrs
 * @return ElemNode
 */
function p($string_or_node, array $attrs = []):ElemNode
{
	return HTMLEmail::p($string_or_node, $attrs);
}

function paragraphs(array $paragraphs, array $paragraph_attrs = [], $margin_height = 0):array
{
	return HTMLEmail::paragraphs($paragraphs, $paragraph_attrs, $margin_height);
}

;

/**
 * @param TextNode[] $textNodes
 * @return DOM
 */
function stylesheet(...$textNodes):DOM
{
	return ElemNode::create('style', [
		'type' => 'text/css'
	])->addChildren($textNodes);
}

function button(string $text, string $href, array $buttonStyles, array $textStyles = []):DOM
{
	return HTMLEmail::button($text, $href, $buttonStyles, $textStyles);
}

function body():ElemNode
{
	return HTMLEmail::body();
}

function table(array $mergeAttrs = [], array $mergeStyles = []):ElemNode
{
	return HTMLEmail::table($mergeAttrs, $mergeStyles);
}

function padded(array $padding, array $children_)
{
	return HTMLEmail::padded($padding, $children_);
}

function a(string $text, string $href, array $attrs = []):ElemNode
{
	return HTMLEmail::a($text, $href, $attrs);
}
