<?php

namespace HTMLEmail;

use App\App;
use DOM\ElemNode;
use DOM\NodeCollection;

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
}
