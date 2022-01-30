<?php

namespace HTMLEmail\Button;

use NodeBuilder\ConditionalComment;
use NodeBuilder\NodeBuilder;
use NodeBuilder\ElemNode;
use function HTMLEmail\toStyleStr;

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
			'style' => toStyleStr([
				'height'        => $this->toUnit('height'),
				'v-text-anchor' => 'middle',
				'width'         => $this->toUnit('width'),
			]),
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
			->add($_center);

		$_a = ElemNode::new('a')->attrs(['href' => $this->href])->addText($this->text);
		$_clone_a = clone $_a;
		if ( !( $this->buttonStyle('border-color') && $this->buttonStyle('background-image') ) ) {
			$_center->add($_clone_a);
			$_outer = ConditionalComment::new('!mso')->add($_a);
		} else {
			$this->aStyles['mso-hide'] = 'all';
			$_center->attrs(['style' => toStyleStr($this->textStyles)]);
			$_outer =& $_a;
		}

		$a_styles = $this->getAStyles();

		$_a->attrs(['style' => toStyleStr($a_styles)]);
		$_clone_a->attrs(['style' => toStyleStr($a_styles)]);

		if ( $border_color = $this->buttonStyle('border-color') ) {
			$rect->attrs(['strokecolor' => $border_color]);
		} else {
			$rect->attrs(['stroke' => 'f']);
		}

		return ElemNode::new('div')->addChildren([
			ConditionalComment::new('mso')->add($rect),
			$_outer
		]);
	}
}

/*
<!-- No border-color | background-image | border-radius -->
<div><!--[if mso]>
	<v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://abcd"
			style="height:53px;v-text-anchor:middle;width:200px;" stroke="f" fillcolor="#49a9ce">
		<w:anchorlock/>
		<center>
	<![endif]-->
	<a href="http://abcd"
	   style="background-color:#49a9ce;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:53px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;">Show
		me the button!</a>
	<!--[if mso]>
	</center>
	</v:rect>
	<![endif]--></div>


<!-- No border-color | background-image --- WITH border-radius -->
<div><!--[if mso]>
	<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
				 href="http://abcd" style="height:53px;v-text-anchor:middle;width:200px;" arcsize="8%" stroke="f"
				 fillcolor="#49a9ce">
		<w:anchorlock/>
		<center>
	<![endif]-->
	<a href="http://abcd"
	   style="background-color:#49a9ce;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:53px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;">Show
		me the button!</a>
	<!--[if mso]>
	</center>
	</v:roundrect>
	<![endif]--></div>


<!-- No border-color | border-radius --- WITH background-image -->
<div><!--[if mso]>
	<v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://abcd"
			style="height:53px;v-text-anchor:middle;width:200px;" stroke="f" fill="t">
		<v:fill type="tile" src="https://imgur.com/5BIp9d0.gif" color="#49a9ce"/>
		<w:anchorlock/>
		<center style="color:#FFFFFF;font-family:sans-serif;font-size:13px;font-weight:bold;">Show me the button!
		</center>
	</v:rect>
	<![endif]--><a href="http://abcd"
				   style="background-color:#49a9ce;background-image:url('https://imgur.com/5BIp9d0.gif');color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:53px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;mso-hide:all;">Show
		me the button!</a></div>


<!-- No background-image --- WITH border-color & border-radius -->
<div><!--[if mso]>
	<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
				 href="http://abcd" style="height:53px;v-text-anchor:middle;width:200px;" arcsize="8%"
				 strokecolor="#ffffff" fillcolor="#49a9ce">
		<w:anchorlock/>
		<center style="color:#FFFFFF;font-family:sans-serif;font-size:13px;font-weight:bold;">Show me the button!
		</center>
	</v:roundrect>
	<![endif]--><a href="http://abcd"
				   style="background-color:#49a9ce;border:1px solid #ffffff;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:53px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;mso-hide:all;">Show
		me the button!</a></div>


<!-- WITH border-color & border-radius & background-image -->
<div><!--[if mso]>
	<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
				 href="http://abcd" style="height:53px;v-text-anchor:middle;width:200px;" arcsize="8%"
				 strokecolor="#ffffff" fill="t">
		<v:fill type="tile" src="https://imgur.com/5BIp9d0.gif" color="#49a9ce"/>
		<w:anchorlock/>
		<center style="color:#FFFFFF;font-family:sans-serif;font-size:13px;font-weight:bold;">Show me the button!
		</center>
	</v:roundrect>
	<![endif]--><a href="http://abcd"
				   style="background-color:#49a9ce;background-image:url('https://imgur.com/5BIp9d0.gif');border:1px
	solid
	#ffffff;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:53px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;mso-hide:all;">Show
		me the button!</a></div>


<!-- No border-color --- WITH border-radius & background-image -->
<div><!--[if mso]>
	<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
				 href="http://abcd" style="height:53px;v-text-anchor:middle;width:200px;" arcsize="8%" stroke="f"
				 fill="t">
		<v:fill type="tile" src="https://imgur.com/5BIp9d0.gif" color="#49a9ce"/>
		<w:anchorlock/>
		<center style="color:#FFFFFF;font-family:sans-serif;font-size:13px;font-weight:bold;">Show me the button!
		</center>
	</v:roundrect>
	<![endif]--><a href="http://abcd"
				   style="background-color:#49a9ce;background-image:url('https://imgur.com/5BIp9d0.gif');border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:53px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;mso-hide:all;">Show
		me the button!</a></div>


<!-- No border-radius | background-image --- WITH border-color -->
<div><!--[if mso]>
	<v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://abcd"
			style="height:53px;v-text-anchor:middle;width:200px;" strokecolor="#ffffff" fillcolor="#49a9ce">
		<w:anchorlock/>
		<center style="color:#FFFFFF;font-family:sans-serif;font-size:13px;font-weight:bold;">Show me the button!
		</center>
	</v:rect>
	<![endif]--><a href="http://abcd"
				   style="background-color:#49a9ce;border:1px solid #ffffff;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:53px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;mso-hide:all;">Show
		me the button!</a></div>
 */
