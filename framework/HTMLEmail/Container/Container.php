<?php

namespace HTMLEmail\Container;

use HTMLEmail\HTMLEmail;
use NodeBuilder\ElemNode;
use NodeBuilder\NodeBuilder;
use NodeBuilder\TextNode;
use function hex2rgba;
use function HTMLEmail\getTableAttrs;
use function HTMLEmail\row_padding;
use function HTMLEmail\toAttrStr;
use function HTMLEmail\toPx;
use function HTMLEmail\toStyleStr;

class Container
{

	private bool $use;
	private string $bgColor;
	private string $class;
	/**
	 * @var numeric|string
	 */
	private $width;
	private HTMLEmail $htmlEmail;
	private string $border;

	public function __construct(HTMLEmail $htmlEmail, array $config)
	{
		$this->htmlEmail = $htmlEmail;
		$this->use = !empty($config);
		$this->bgColor = $config['background-color'] ?? '#FFFFFF';
		$this->class = $config['class'] ?? null;
		$this->width = $config['width'] ?? '100%';
		$this->border = $config['border'] ?? 'none';
	}

	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @return NodeBuilder[]
	 */
	public function get():array
	{
		$_inner = [
			TextNode::new('<!-- Container -->'),
			HTMLEmail::buildTable([
				'width' => $this->width,
				'class' => $this->class,
			])->addChild(
				$this->htmlEmail->domCollection
			),
			TextNode::new('<!-- End Container -->')
		];
		return $this->use ?
			[
				HTMLEmail::buildTable(['width' => $this->width, 'class' => $this->class])->addChildren([
					ElemNode::new('tr')->addChild(
						ElemNode::new('td')->attrs([
							'bgcolor' => $this->bgColor,
							'style'   => 'background-color:' . hex2rgba($this->bgColor)
						])->addChildren([
							TextNode::new($this->getIEWrapper(fn(string $html):string => "<!--[if mso | IE]>$html<![endif]-->")),
							ElemNode::new('div')->attrs([
								'style' => toStyleStr([
									'max-width' => toPx($this->width),
									'margin'    => '0px auto',
									'border'    => $this->border,
								])
							])->addChildren($_inner),
							TextNode::new("<!--[if mso | IE]></td></tr></table><![endif]-->")
						])
					),
					TextNode::new('<!-- BOTTOM SPACER FIX -->'),
					row_padding(64)
				])
			] :
			$_inner;
	}

	private function getIEWrapper(callable $cb):string
	{
		$table_attr_str = toAttrStr(getTableAttrs(['align' => 'center']));
		$td_attr_str = 'style="' . toStyleStr([
				'line-height'          => '0px',
				'font-size'            => '0px',
				'mso-line-height-rule' => 'exactly',
			]) . '"';
		return $cb("<table $table_attr_str><tr><td $td_attr_str>");
	}

}
