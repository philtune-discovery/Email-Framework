<?php

use App\Selector;
use App\Stylesheetz;
use DOM\ElemNode;
use DOM\NodeCollection;
use HTMLEmail\HTMLEmailNode;

/**
 * @param ElemNode $_body
 * @return NodeCollection
 */
function email_root(ElemNode $_body):NodeCollection
{
	return NodeCollection::create()->addChildren([
		text('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'),
		elem('html', [
			'lang'    => 'en',
			'xmlns'   => 'http://www.w3.org/1999/xhtml',
			'xmlns:o' => 'urn:schemas-microsoft-com:office:office',
			'xmlns:v' => "urn:schemas-microsoft-com:vml",
			'xmlns:w' => "urn:schemas-microsoft-com:office:word",
		])->addChildren([
			elem('head')->addChildren([
				text('<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'),
				text('<meta name="viewport" content="width=device-width, initial-scale=1.0"/>'),
				// Disable auto-scale in iOS 10 Mail entirely
				text('<meta name="x-apple-disable-message-reformatting">'),
				// Stop iOS from converting your phone numbers to hyperlinks
				text('<meta name="format-detection" content="telephone=no"/>'),
				text('<!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"/><!--<![endif]-->'),
				text('<title>' . setting('page_title') . '</title>'),
				stylesheet(
					text(include_string('./sass/head_styles.css')),
					text(Stylesheetz::getPseudoStylesStr()),
					text(Stylesheetz::getInlineStylesStr()),
					text(HTMLEmailNode::renderMobileStyles()),
					text("* {" . ( new Selector('font-family') )->getInlineRaw() . "}")
				),
				conditional('mso', [
					stylesheet(
						text('table, td {'),
						text('	border-collapse: collapse;'),
						text('	mso-table-lspace: 0pt;'),
						text('	mso-table-rspace: 0pt;'),
						text('}'),
						/* Desktop Outlook chokes on web font references and defaults
						 to Times New Roman, so we force a safe fallback font. */
						text('* {font-family: Arial, sans-serif !important;}'),
					),
				]),
				conditional('gte mso 9', [
					text('<xml>'),
					text('	<o:OfficeDocumentSettings>'),
					text('		<o:AllowPNG/>'),
					text('		<o:PixelsPerInch>96</o:PixelsPerInch>'),
					text('	</o:OfficeDocumentSettings>'),
					text('</xml>'),
				]),
			]),
			$_body
		])
	]);
}
