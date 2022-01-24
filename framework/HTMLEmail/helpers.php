<?php

use DOM\DOM;
use DOM\ElemNode;
use DOM\SelfClosing;
use DOM\TextNode;
use HTMLEmail\Button;
use HTMLEmail\HTMLEmail;
use HTMLEmail\HTMLEmailNode;
use JetBrains\PhpStorm\ArrayShape;

function email(array $config = []):HTMLEmailNode
{
	if ( $parentPath = $config['inherit'] ?? null ) {
		$parentNode = HTMLEmail::inherit($parentPath);
		if ( $track = $config['track'] ) {
			$parentNode->track($track);
		}
		return $parentNode;
	}
}

/**
 * @param array|numeric $attrs
 * @param ...$children_
 * @return DOM
 */
function col($attrs, ...$children_):DOM
{
	return is_array($attrs) ?
		ElemNode::create('td', $attrs)->addChildren($children_) :
		( empty($children_) ?
			ElemNode::create('td', [
				'width' => $attrs,
				'style' => 'font-size:0px'
			])
			        ->useWhitespace(false)
			        ->addChild(
				        TextNode::create('&nbsp;')
			        ) :
			ElemNode::create('td', [
				'width' => $attrs,
			])->addChildren($children_)
		);
}

function row($height):DOM
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

/**
 * @param string|array $src <p>img 'src' OR config array</p>
 * @param string|array|null $alt <p>img 'alt' OR array of attributes</p>
 * @param string|null $href
 * @param array $attrs
 * @return DOM
 */
function img($src, $alt = null, string $href = null, array $attrs = []):DOM
{
	if ( is_array($src) ) {
		// 1st arg is config array
		$attrs = $src;
		$href = $attrs['href'] ?? null;
	} elseif ( is_array($alt) ) {
		// 2nd arg is attrs
		$attrs = $alt;
		$attrs['alt'] = $attrs['alt'] ?? '';
		$attrs['src'] = $src;
		$href = $attrs['href'] ?? null;
	} else {
		$attrs['src'] = $src;
		$attrs['alt'] = $alt;
	}
	unset($attrs['href']);
	$attrs['src'] = src($attrs['src']);
	if ( $attrs['alt'] ?? null ) {
		$style = ";font-family:'Helvetica Neue',Helvetica,Arial,sans-serif";
		if ( isset($attrs['style']) ) {
			$attrs['style'] .= $style;
		} else {
			$attrs['style'] = $style;
		}
	}
	$imgNode = SelfClosing::create('img', $attrs);
	return $href ?
		ElemNode::create('a', ['href' => $href, 'target' => '_blank'])->addChild($imgNode) :
		$imgNode;
}

/**
 * @param DOM[] $children_
 * @return DOM
 */
function rows(...$children_):DOM
{
	return table()->addChildren($children_);
}

/**
 * @param string|DOM $string_or_node
 * @param array $attrs
 * @return ElemNode
 */
function p($string_or_node, array $attrs = []):ElemNode
{
	$child = is_string($string_or_node) ?
		TextNode::create($string_or_node) :
		$string_or_node;
	return ElemNode::create('tr')->addChild(
		ElemNode::create('td', $attrs)->addChild($child)
	);
}

function paragraphs(array $paragraphs, array $paragraph_attrs = [], $margin_height = 0):array
{
	$result = [];
	foreach ( $paragraphs as $i => $paragraph ) {
		if ( $i !== 0 ) {
			$result[] = row($margin_height);
		}
		$result[] = p($paragraph, $paragraph_attrs);
	}
	return $result;
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

function getTableAttrStr(array $mergeAttrs = [], array $mergeStyles = []):string
{
	return toAttrStr(getTableAttrs($mergeAttrs, $mergeStyles));
}

function table(array $mergeAttrs = [], array $mergeStyles = []):ElemNode
{
	return ElemNode::create('table', getTableAttrs($mergeAttrs, $mergeStyles));
}

function toAttrStr(array $attrs, ...$other_attrs):string
{
	$attrs = array_merge($attrs, ...$other_attrs);
	return implode_kvps(' ', $attrs, fn($a, $b) => "$a=\"$b\"");
}

function toStyleStr(array $arr, ...$other_arrs):string
{
	$arr = array_merge($arr, ...$other_arrs);
	return implode_kvps(';', $arr, fn($a, $b) => "$a:$b");
}

function padded(array $padding, array $children_)
{
	return HTMLEmail::padded($padding, $children_);
}

function a(string $text, string $href, array $attrs = []):ElemNode
{
	return HTMLEmail::a($text, $href, $attrs);
}

include 'email_start.php';
