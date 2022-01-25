<?php

namespace HTMLEmail;

use App\App;
use App\Stylesheetz;
use DOM\DOM;
use DOM\ElemNode;
use DOM\NodeCollection;
use DOM\SelfClosingNode;
use DOM\TextNode;
use HTMLEmail\Button\Button;

trait hasHelpers
{

	public static function body():ElemNode
	{
		return ElemNode::create('body', [
			'width'   => '100%',
			'bgcolor' => App::setting('body_bgclr'),
			'style'   => toStyleStr([
				'-webkit-text-size-adjust' => '100%',
				'-ms-text-size-adjust'     => '100%',
				'margin'                   => 0,
				'padding'                  => 0,
				'color'                    => App::setting('body_txtclr'),
			])
		]);
	}

	public static function a(string $text, string $href, array $attrs = []):ElemNode
	{
		return ElemNode::create('a', array_merge(['href' => $href, 'target' => '_blank',], $attrs))
		               ->addText($text)
		               ->useWhitespace(false);
	}

	public static function padded(array $padding, array $children_)
	{
		$top_padding = $padding[0] ?? null;
		$right_padding = $padding[1] ?? $top_padding;
		$bottom_padding = $padding[2] ?? $top_padding;
		$left_padding = $padding[3] ?? $right_padding;
		return NodeCollection::create()->addChildren([
			row($top_padding),
			elem('tr')->addChild(
				elem('td')->addChild(
					elem('table', [
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
						elem('tr')->addChildren([
							col($left_padding),
							col([])->addChild(
								elem('table', [
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
							col($right_padding),
						])
					)
				)
			),
			row($bottom_padding)
		]);
	}

	public static function table(array $mergeAttrs = [], array $mergeStyles = []):ElemNode
	{
		return ElemNode::create('table', getTableAttrs($mergeAttrs, $mergeStyles));
	}

	public static function row($height):ElemNode
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
	 * @param DOM[] $children_
	 * @return DOM
	 */
	public static function rows(...$children_):DOM
	{
		return self::table()->addChildren($children_);
	}

	/**
	 * @param array|numeric $attrs
	 * @param ...$children_
	 */
	public static function col($attrs, ...$children_):ElemNode
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

	/**
	 * @param string|DOM $string_or_node
	 * @param array $attrs
	 * @return ElemNode
	 */
	public static function p($string_or_node, array $attrs = []):ElemNode
	{
		$child = is_string($string_or_node) ?
			TextNode::create($string_or_node) :
			$string_or_node;
		return ElemNode::create('tr')->addChild(
			ElemNode::create('td', $attrs)->addChild($child)
		);
	}

	public static function paragraphs(array $paragraphs, array $paragraph_attrs = [], $margin_height = 0):array
	{
		$result = [];
		foreach ( $paragraphs as $i => $paragraph ) {
			if ( $i !== 0 ) {
				$result[] = row($margin_height);
			}
			$result[] = self::p($paragraph, $paragraph_attrs);
		}
		return $result;
	}

	public static function button(string $text, string $href, array $buttonStyles, array $textStyles = []):ElemNode
	{
		return p(
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
		$attrs['src'] = src($attrs['src']);
		if ( $attrs['alt'] ?? null ) {
			$style = ";font-family:'Helvetica Neue',Helvetica,Arial,sans-serif";
			if ( isset($attrs['style']) ) {
				$attrs['style'] .= $style;
			} else {
				$attrs['style'] = $style;
			}
		}
		$imgNode = SelfClosingNode::create('img', $attrs);
		return $href ?
			ElemNode::create('a', ['href' => $href, 'target' => '_blank'])->addChild($imgNode) :
			$imgNode;
	}

	private static array $styles = ['mobileStyles' => []];

	public static function renderMobileStyles(array $mobileStyles = null):?string
	{
		$globalMobileStyles =& self::$styles['mobileStyles'];
		if ( $mobileStyles ) {
			foreach ( $mobileStyles as $selector => $styles ) {
				if ( !array_key_exists($selector, $globalMobileStyles) ) {
					$globalMobileStyles[$selector] = [];
				}
				foreach ( $styles as $prop => $val ) {
					$globalMobileStyles[$selector][$prop] = $val;
				}
			}
			return null;
		} else {
			$mobileStylesStr = array_reduce(
					array_entries($globalMobileStyles),
					fn($a, $selector_styles) => $a . "$selector_styles[0]{" .
						implode(';', array_map(
							fn($prop_val) => "$prop_val[0]:$prop_val[1]",
							array_entries($selector_styles[1])
						)) .
						"}",
					''
				) . Stylesheetz::getMobileStylesStr();
			return "@media only screen and ( max-width: 648px ) {{$mobileStylesStr}}";
		}
	}
}
