<?php

use NodeBuilder\NodeBuilder;
use NodeBuilder\ElemNode;
use HTMLEmail\HTMLEmail;
use HTMLEmail\HTMLEmailNodeBuilder\HTMLEmailNodeBuilder;

function myEmail(string $title, string $previewText = 'Preview Text Here'):HTMLEmailNodeBuilder
{
	return HTMLEmail::new([
		'title'        => $title,
		'bgcolor'      => '#dddddd',
		'txtcolor'     => '#000000',
		'container'    => [
			'width'            => 630,
			'background-color' => '#faf8f7',
			'class'            => 'w-100p',
			'border'           => '1px solid #CCCCCC',
		],
		'preview-text' => $previewText,
	]);
}

/**
 * @param string $text
 * @param string $href
 * @param numeric|string $width
 * @return NodeBuilder
 */
function myButton(string $text, string $href, $width):NodeBuilder
{
	$buttonStyles = [
		'height'           => 51,
		'border-radius'    => 7,
		'background-color' => '#60285f',
		'width'            => $width
	];
	$textStyles = [
		'color'          => '#ffffff',
		'font-family'    => 'Arial, sans-serif',
		'font-size'      => '19px',
		'font-weight'    => 'bold',
		'text-transform' => 'uppercase',
		'letter-spacing' => '0.05em',
	];
	return HTMLEmail::buildButton($text, $href, $buttonStyles, $textStyles);
}

/**
 * @return ElemNode[]
 */
function myLegal():array
{
	$a = function (string $text, string $href, array $addl_styles = []):ElemNode {
		$attrs = [
			'style' => toStyleStr(array_merge([
				'color'           => 'blue',
				'text-decoration' => 'none'
			], $addl_styles)),
			'class' => 'hvr-td-u'
		];
		return a($text, $href)->attrs($attrs);
	};
	return text_rows(
		[
			"Trouble viewing images? {$a('Click here', '{{email_preview_link}}')} to view this message in your browser.",
			"HGTV, HGTV Dream Home, HGTV Dream Home Giveaway and their associated logos are trademarks of Scripps Networks, LLC. Photos &copy; 2020 Scripps Networks, LLC. Used with permission; all rights reserved.",
			"Discovery Communications, LLC is sending this email on behalf of Cabinets To Go. You have received this newsletter because you indicated that you had an interest in receiving information about future special offers from Discovery Communications, its affiliates, and its third party sponsors. If you wish to unsubscribe from future Discovery Communications' special offers, please click on the UNSUBSCRIBE link below and we'll remove your name from our mailing list. If you opt in to receive correspondence directly from Cabinets to Go, such correspondence will be covered by the Cabinets to Go privacy policy.",
			implode(' &nbsp;|&nbsp; ', array_map(fn($link) => $a($link['text'], $link['href']), [
				[
					'text' => 'Unsubscribe',
					'href' => '{{ unsubscribe_link }}?sp=sn_partner',
				],
				[
					'text' => 'Privacy Policy',
					'href' => 'https://corporate.discovery.com/privacy-policy/',
				],
				[
					'text' => 'User Agreement',
					'href' => 'https://corporate.discovery.com/visitor-agreement/',
				],
				[
					'text' => 'HGTV.com',
					'href' => 'https://hgtv.com/',
				],
			])) . "<br/>This email was sent to {$a('{{ user.email }}', 'mailto:{{ user.email }}', ['color' => '#444444'])}.",
			"Copyright &copy; {{ \"now\" | date: \"%Y\" }}&nbsp;Discovery&nbsp;Communications,&nbsp;LLC<br/>8403 Colesville Road | Silver Spring, MD 20910 | All Rights Reserved"
		],
		[
			'align' => 'center',
			'style' => toStyleStr([
				'font-family' => "'Helvetica Neue',Helvetica,Arial,sans-serif",
				'font-size'   => '11px',
				'line-height' => '12px',
				'color'       => '#555',
			]),
		],
		15
	);
}
