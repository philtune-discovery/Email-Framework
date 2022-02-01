<?php

namespace HTMLEmail;

use NodeBuilder\Extensions\ConditionalComment;
use NodeBuilder\Extensions\ElemNode;
use NodeBuilder\Extensions\TextNode;
use NodeBuilder\NodeBuilder;
use Styles\Stylesheet;

trait rendersEmailParts
{

	public function __toString():string
	{
		return $this->render();
	}

	protected function render():string
	{
		return $this->getDoctype() . "\n" .
			$this->getHtml();
	}

	private function getDoctype():TextNode
	{
		return TextNode::new('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
	}

	private function getHtml():ElemNode
	{
		return ElemNode::new('html')->attrs([
			'lang'    => 'en',
			'xmlns'   => 'http://www.w3.org/1999/xhtml',
			'xmlns:o' => 'urn:schemas-microsoft-com:office:office',
			'xmlns:v' => "urn:schemas-microsoft-com:vml",
			'xmlns:w' => "urn:schemas-microsoft-com:office:word",
		])
		               ->addChild($this->getHead())
		               ->addChild($this->getBody());
	}

	private function getHead():ElemNode
	{
		return ElemNode::new('head')->addChildren([
			TextNode::new('<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'),
			TextNode::new('<meta name="viewport" content="width=device-width, initial-scale=1.0"/>'),
			// Disable auto-scale in iOS 10 Mail entirely
			TextNode::new('<meta name="x-apple-disable-message-reformatting">'),
			// Stop iOS from converting your phone numbers to hyperlinks
			TextNode::new('<meta name="format-detection" content="telephone=no"/>'),
			TextNode::new('<!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"/><!--<![endif]-->'),
			TextNode::new("<title>$this->title</title>"),
			Stylesheet::getStylesheets(),
			ConditionalComment::new('gte mso 9')->addChildren([
				TextNode::new('<xml>'),
				TextNode::new('	<o:OfficeDocumentSettings>'),
				TextNode::new('		<o:AllowPNG/>'),
				TextNode::new('		<o:PixelsPerInch>96</o:PixelsPerInch>'),
				TextNode::new('	</o:OfficeDocumentSettings>'),
				TextNode::new('</xml>'),
			]),
		]);
	}

	private function getBody():ElemNode
	{
		return ElemNode::new('body')->attrs([
			'width'   => '100%',
			'bgcolor' => $this->bgcolor
		])->style([
			'-webkit-text-size-adjust' => '100%',
			'-ms-text-size-adjust'     => '100%',
			'margin'                   => 0,
			'padding'                  => 0,
			'color'                    => $this->txtcolor,
		])->addChild(
			static::buildTable()->addChildren([
				$this->getPreviewText(),
				ElemNode::new('tr')->addChild(
					ElemNode::new('td')->attrs([
						'bgcolor' => $this->bgcolor,
						'align'   => 'center',
						'valign'  => 'top'
					])->addChildren($this->container->get())
				)
			])
		);
	}

	private function getPreviewText():NodeBuilder
	{
		return $this->previewText ?
			ElemNode::new('tr')->addChild(
				ElemNode::new('td')->addChildren([
					ElemNode::new('div')->style([
						'display'     => 'none !important',
						'visibility'  => 'hidden',
						'mso-hide'    => 'all',
						'font-size'   => '1pt',
						'color'       => '#ffffff',
						'line-height' => '1px',
						'max-height'  => 'none',
						'max-width'   => 'none',
						'opacity'     => '0',
						'overflow'    => 'hidden'
					])->addChild(
						TextNode::new($this->previewText)
					)
				])
			) :
			TextNode::new('');
	}

}
