<?php

namespace HTMLEmail\Button;

use NodeBuilder\ConditionalComment;
use NodeBuilder\ElemNode;
use NodeBuilder\NodeBuilder;

class Button
{
	protected string $text;
	protected string $href;
	protected array $buttonStyles;
	protected array $textStyles;
	private array $aStyles = [];

	public static function create():self
	{
		return new static;
	}

	public function text(string $text):self
	{
		$this->text = $text;
		return $this;
	}

	public function href(string $href):self
	{
		$this->href = $href;
		return $this;
	}

	private static function toRgba(string $val):string
	{
		return substr($val, 0, 1) === '#' ? hex2rgba($val) : $val;
	}

	public function setButtonStyles(array $buttonStyles):self
	{
		// todo: merge back into one ::styles() call then split to ->buttonStyles and ->textStyles manually
		$this->buttonStyles = $buttonStyles;
		return $this;
	}

	public function setTextStyles(array $textStyles):self
	{
		//		$textStyles['color'] = self::toRgba($textStyles['color']);
		$this->textStyles = $textStyles;
		return $this;
	}

	public function buttonStyle(string $key, $val = null)
	{
		if ( $val ) {
			$this->buttonStyles[$key] = $val;
		}
		return $this->buttonStyles[$key] ?? null;
	}

	private function toUnit(string $key):string
	{
		$attr = $this->buttonStyle($key);
		return is_numeric($attr) ?
			"{$attr}px" :
			$attr;
	}

	private function getAStyles():array
	{
		return [];
		return array_merge(
			[
				'display'                  => 'inline-block',
				'text-align'               => 'center',
				'text-decoration'          => 'none',
				'-webkit-text-size-adjust' => 'none',
				'background-color'         => self::toRgba($this->buttonStyle('background-color')),
				'line-height'              => $this->toUnit('height'),
				'width'                    => $this->toUnit('width'),
			],
			$this->aStyles,
			$this->textStyles
		);
	}

	public function toDOM():NodeBuilder
	{
		$_center = ElemNode::new('center');

		if ( $border_radius = $this->toUnit('border-radius') ) {
			$this->aStyles['border-radius'] = $border_radius;
			$arcsize = round(floatval($this->buttonStyle('border-radius')) / floatval($this->buttonStyle('height')) * 100) . '%';
			$rect = ElemNode::new('v:roundrect')
			                ->attrs(compact('arcsize'));
		} else {
			$rect = ElemNode::new('v:rect');
		}

		$rect->attrs([
			'href'  => $this->href,
			'style' => [
				'height'        => $this->toUnit('height'),
				'v-text-anchor' => 'middle',
				'width'         => $this->toUnit('width'),
			],
		]);

		if ( $background_image = $this->buttonStyle('background-image') ) {
			$this->aStyles['background-image'] = "url($background_image)";
			$rect->addSelfClosing('v:fill', [
				'type'  => 'tile',
				'src'   => $background_image,
				'color' => $this->buttonStyle('background-color'),
			]);
		} else {
			$rect->attrs(['fillcolor' => $this->buttonStyle('background-color')]);
		}

		$rect
			->addSelfClosing('w:anchorlock')
			->addChild($_center);

		$_a = ElemNode::new('a')->attrs(['href' => $this->href])->addText($this->text);
		$_clone_a = clone $_a;
		if ( !( $this->buttonStyle('border-color') && $this->buttonStyle('background-image') ) ) {
			$_center->addChild($_clone_a);
			$_outer = ConditionalComment::new('!mso')->addChild($_a);
		} else {
			$this->aStyles['mso-hide'] = 'all';
			$_center->attrs(['style' => $this->textStyles]);
			$_outer =& $_a;
		}

		$a_styles = $this->getAStyles();

		$_a->attrs(['style' => $a_styles]);
		$_clone_a->attrs(['style' => $a_styles]);

		if ( $border_color = $this->buttonStyle('border-color') ) {
			$rect->attrs(['strokecolor' => $border_color]);
		} else {
			$rect->attrs(['stroke' => 'f']);
		}

		return ElemNode::new('div')->addChildren([
			ConditionalComment::new('mso')->addChild($rect),
			$_outer
		]);
	}
}
