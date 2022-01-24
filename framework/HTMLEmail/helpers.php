<?php

use DOM\DOM;
use DOM\ElemNode;
use DOM\NodeCollection;
use DOM\SelfClosing;
use DOM\TextNode;
use HTMLEmail\HTMLEmail;
use HTMLEmail\HTMLEmailNode;

function email(array $config = []):HTMLEmailNode
{
	return HTMLEmail::email($config);
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
 * @return SelfClosing|ElemNode
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

function getTableAttrStr(array $mergeAttrs = [], array $mergeStyles = []):string
{
	return HTMLEmail::getTableAttrStr($mergeAttrs, $mergeStyles);
}

function toStyleStr(array $arr, ...$other_arrs):string
{
	return HTMLEmail::toStyleStr($arr, ...$other_arrs);
}

function padded(array $padding, array $children_)
{
	return HTMLEmail::padded($padding, $children_);
}

function a(string $text, string $href, array $attrs = []):ElemNode
{
	return HTMLEmail::a($text, $href, $attrs);
}

function email_root(ElemNode $_body):NodeCollection
{
	return HTMLEmail::getRoot($_body);
}
