<?php

namespace HTMLEmail;

use DOM\DOM;

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

	/**
	 * @return DOM[]
	 */
	public function getChildren():array
	{
		$_inner = [
			text('<!-- Container -->'),
			table(['width' => $this->width, 'class' => $this->class])
				->addChild($this->htmlEmail->nodeBuilder->domCollection)
				->addChild(config('legal')),
			text('<!-- End Container -->')
		];
		return $this->use ?
			[
				table(['width' => $this->width, 'class' => $this->class])->addChildren([
					elem('tr')->addChild(
						elem('td', [
							'bgcolor' => $this->bgColor,
							'style'   => 'background-color:' . hex2rgba($this->bgColor)
						])->addChildren([
							text($this->getIEWrapper(fn(string $html):string => "<!--[if mso | IE]>$html<![endif]-->")),
							elem('div', [
								'style' => toStyleStr([
									'max-width' => toPx($this->width),
									'margin'    => '0px auto',
									'border'    => $this->border,
								])
							])->addChildren($_inner),
							text("<!--[if mso | IE]></td></tr></table><![endif]-->")
						])
					),
					text('<!-- BOTTOM SPACER FIX -->'),
					row(64)
				])
			] :
			$_inner;
	}

	private function getIEWrapper(callable $cb):string
	{
		$table_attr_str = getTableAttrStr(['align' => 'center']);
		$td_attr_str = 'style="' . toStyleStr([
				'line-height'          => '0px',
				'font-size'            => '0px',
				'mso-line-height-rule' => 'exactly',
			]) . '"';
		return $cb("<table $table_attr_str><tr><td $td_attr_str>");
	}

}
