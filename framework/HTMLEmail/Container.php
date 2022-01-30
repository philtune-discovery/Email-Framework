<?php

namespace HTMLEmail;

use NodeBuilder\NodeBuilder;
use NodeBuilder\ElemNode;
use NodeBuilder\TextNode;

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
	public function getChildren():array
	{
		$_inner = [
			TextNode::new('<!-- Container -->'),
			table([
				'width' => $this->width,
				'class' => $this->class,
			])->addChild(
				$this->htmlEmail->nodeBuilder->domCollection
			),
			TextNode::new('<!-- End Container -->')
		];
		return $this->use ?
			[
				table(['width' => $this->width, 'class' => $this->class])->addChildren([
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
					row(64)
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
