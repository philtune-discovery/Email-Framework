<?php

namespace HTMLEmail;

use NodeBuilder\NodeBuilder;
use NodeBuilder\ElemNode;
use NodeBuilder\NodeCollection;
use NodeBuilder\SelfClosingNode;
use NodeBuilder\TextNode;
use HTMLEmail\Button\Button;

trait buildsNodes
{

	public static function a(string $text, string $href):ElemNode
	{
		return ElemNode::new('a')
		               ->attrs(['href' => $href, 'target' => '_blank'])
		               ->addText($text)
		               ->useWhitespace(false);
	}

	/**
	 * @param array $padding
	 * @param NodeBuilder[] $children_
	 * @return NodeBuilder|NodeCollection
	 */
	public static function padded(array $padding, array $children_)
	{
		$top_padding = $padding[0] ?? null;
		$right_padding = $padding[1] ?? $top_padding;
		$bottom_padding = $padding[2] ?? $top_padding;
		$left_padding = $padding[3] ?? $right_padding;
		return NodeCollection::new()->addChildren([
			static::buildRow($top_padding),
			ElemNode::new('tr')->addChild(
				ElemNode::new('td')->addChild(
					ElemNode::new('table')->attrs([
						'width'       => "100%",
						'cellpadding' => "0",
						'cellspacing' => "0",
						'border'      => "0",
						'role'        => "presentation",
						'style'       => toStyleStr([
							'mso-cellspacing' => '0px',
							'mso-padding-alt' => '0px 0px 0px 0px'
						]),
					])->addChild(
						ElemNode::new('tr')->addChildren([
							static::buildColumn($left_padding),
							static::buildColumn([])->addChild(
								ElemNode::new('table')->attrs([
									'width'       => "100%",
									'cellpadding' => "0",
									'cellspacing' => "0",
									'border'      => "0",
									'role'        => "presentation",
									'style'       => toStyleStr([
										'mso-cellspacing' => '0px',
										'mso-padding-alt' => '0px 0px 0px 0px'
									]),
								])->addChildren($children_)
							),
							static::buildColumn($right_padding),
						])
					)
				)
			),
			static::buildRow($bottom_padding)
		]);
	}

	public static function table(array $mergeAttrs = [], array $mergeStyles = []):ElemNode
	{
		return ElemNode::new('table')->attrs(getTableAttrs($mergeAttrs, $mergeStyles));
	}

	/**
	 * @param string|numeric $height
	 * @return ElemNode
	 */
	public static function buildRow($height):ElemNode
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

	/**
	 * @param NodeBuilder[] $children_
	 * @return NodeBuilder
	 */
	public static function rows(...$children_):NodeBuilder
	{
		return self::table()->addChildren($children_);
	}

	/**
	 * @param array|numeric $attrs
	 * @param ...$children_
	 */
	public static function buildColumn($attrs, ...$children_):ElemNode
	{
		return is_array($attrs) ?
			ElemNode::new('td')->attrs($attrs)->addChildren($children_) :
			( empty($children_) ?
				ElemNode::new('td')->attrs([
					'width' => $attrs,
					'style' => 'font-size:0px'
				])
				        ->useWhitespace(false)
				        ->addChild(
					        TextNode::new('&nbsp;')
				        ) :
				ElemNode::new('td')->attrs([
					'width' => $attrs,
				])->addChildren($children_)
			);
	}

	/**
	 * @param string|NodeBuilder $string_or_node
	 * @param array $attrs
	 * @return ElemNode
	 */
	public static function buildTextRow($string_or_node, array $attrs = []):ElemNode
	{
		$child = is_string($string_or_node) ?
			TextNode::new($string_or_node) :
			$string_or_node;
		return ElemNode::new('tr')->addChild(
			ElemNode::new('td')
			        ->attrs($attrs)
			        ->addChild($child)
		);
	}

	/**
	 * @param array $paragraphs
	 * @param array $paragraph_attrs
	 * @param string|numeric $margin_height
	 * @return ElemNode[]
	 */
	public static function buildTextRows(array $paragraphs, array $paragraph_attrs = [], $margin_height = 0):array
	{
		$result = [];
		foreach ( $paragraphs as $i => $paragraph ) {
			if ( $i !== 0 ) {
				$result[] = row($margin_height);
			}
			$result[] = self::buildTextRow($paragraph, $paragraph_attrs);
		}
		return $result;
	}

	public static function buildButton(string $text, string $href, array $buttonStyles, array $textStyles = []):ElemNode
	{
		return static::buildTextRow(
			Button::create()
			      ->text($text)
			      ->href($href)
			      ->setButtonStyles($buttonStyles)
			      ->setTextStyles($textStyles)
			      ->toDOM(),
			['nowrap'],
		);
	}

	/**
	 * @param string|array $src <p>img 'src' OR config array</p>
	 * @param string|array|null $alt <p>img 'alt' OR array of attributes</p>
	 * @param string|null $href
	 * @param array $attrs
	 * @return SelfClosingNode|ElemNode
	 */
	public static function img($src, $alt = null, string $href = null, array $attrs = [])
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
		if ( $attrs['alt'] ?? null ) {
			$style = ";font-family:'Helvetica Neue',Helvetica,Arial,sans-serif";
			if ( isset($attrs['style']) ) {
				$attrs['style'] .= $style;
			} else {
				$attrs['style'] = $style;
			}
		}
		$imgNode = SelfClosingNode::new('img')->attrs($attrs);
		return $href ?
			ElemNode::new('a')->attrs(['href' => $href, 'target' => '_blank'])->addChild($imgNode) :
			$imgNode;
	}
}
