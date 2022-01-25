<?php

namespace HTMLEmail;

use App\Selector;
use App\Stylesheetz;
use DOM\DOM;
use DOM\ElemNode;
use DOM\TextNode;
use HTMLEmail\OLD\HTMLEmailNodez;

trait Renderable
{

	protected function render():string
	{
		return $this->getDoctype() . "\n" .
			$this->getHtml();
	}

	private function getDoctype():TextNode
	{
		return text('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
	}

	private function getHtml():ElemNode
	{
		return elem('html', [
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
		return elem('head')->addChildren([
			text('<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'),
			text('<meta name="viewport" content="width=device-width, initial-scale=1.0"/>'),
			// Disable auto-scale in iOS 10 Mail entirely
			text('<meta name="x-apple-disable-message-reformatting">'),
			// Stop iOS from converting your phone numbers to hyperlinks
			text('<meta name="format-detection" content="telephone=no"/>'),
			text('<!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"/><!--<![endif]-->'),
			text("<title>$this->title</title>"),
			elem('style', ['type' => 'text/css'])->addChildren([
				text(include_string('./sass/head_styles.css')),
				text(Stylesheetz::getPseudoStylesStr()),
				text(Stylesheetz::getInlineStylesStr()),
				text(HTMLEmail::renderMobileStyles()),
				text("* {" . ( new Selector('font-family') )->getInlineRaw() . "}")
			]),
			conditional('mso', [
				elem('style', ['type' => 'text/css'])->addChildren([
					text('table, td {'),
					text('	border-collapse: collapse;'),
					text('	mso-table-lspace: 0pt;'),
					text('	mso-table-rspace: 0pt;'),
					text('}'),
					/* Desktop Outlook chokes on web font references and defaults
					 to Times New Roman, so we force a safe fallback font. */
					text('* {font-family: Arial, sans-serif !important;}'),
				]),
			]),
			conditional('gte mso 9', [
				text('<xml>'),
				text('	<o:OfficeDocumentSettings>'),
				text('		<o:AllowPNG/>'),
				text('		<o:PixelsPerInch>96</o:PixelsPerInch>'),
				text('	</o:OfficeDocumentSettings>'),
				text('</xml>'),
			]),
		]);
	}

	private function getBody():ElemNode
	{
		return elem('body', [
			'width'   => '100%',
			'bgcolor' => $this->bgcolor,
			'style'   => toStyleStr([
				'-webkit-text-size-adjust' => '100%',
				'-ms-text-size-adjust'     => '100%',
				'margin'                   => 0,
				'padding'                  => 0,
				'color'                    => $this->txtcolor,
			])
		])->addChild(
			elem('table', \getTableAttrs())->addChildren([
				$this->getPreviewText(),
				elem('tr')->addChild(
					elem('td', [
						'bgcolor' => $this->bgcolor,
						'align'   => 'center',
						'valign'  => 'top'
					])->addChildren($this->container->getChildren())
				)
			])
		);
	}

	private function getPreviewText():DOM
	{
		return $this->previewText ?
			elem('tr')->addChild(
				elem('td')->addChildren([
					elem('div', [
						'style' => toStyleStr([
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
						])
					])->addChild(
						text($this->previewText)
					)
				])
			) :
			text('');
	}

}
