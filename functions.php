<?php

use HTMLEmail\HTMLEmail;
use NodeBuilder\Extensions\ElemNode;
use NodeBuilder\NodeBuilder;
use Styles\Stylesheet;

const COMP_WIDTH = 639;
const CONTAINER_WIDTH = 630;

Stylesheet::pseudos(
	['hvr-td-u', ':hover', ['text-decoration' => 'underline']]
);

function myEmail(string $title, string $previewText = 'Preview Text Here'):HTMLEmail
{
	return HTMLEmail::new([
		'title'        => $title,
		'bgcolor'      => '#dddddd',
		'txtcolor'     => '#000000',
		'container'    => [
			'width'            => CONTAINER_WIDTH,
			'background-color' => '#faf8f7',
			'class'            => 'w-100p',
			'border'           => '1px solid #CCCCCC',
		],
		'preview-text' => $previewText,
	]);
}

function comp_perc(float $width):string
{
	return perc($width / COMP_WIDTH);
}

function comp_rel(float $size):float
{
	return $size * CONTAINER_WIDTH / COMP_WIDTH;
}

function img_col(string $img_path, float $width_from_comp, array $column_attrs = []):ElemNode
{
	return HTMLEmail::buildColumn(
		array_merge(['width' => comp_perc($width_from_comp)], $column_attrs),
		HTMLEmail::buildImg(
			src($img_path),
			null,
			[
				'width' => comp_rel($width_from_comp),
				'class' => 'w-100p'
			]
		)
	);
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
		'height'           => comp_rel(51),
		'border-radius'    => comp_rel(7),
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

function myLegal():NodeBuilder
{
	$a = function (string $text, string $href, array $addl_styles = []):string {
		return HTMLEmail::buildLink($text, $href)
		                ->style([
			                'color'           => 'blue',
			                'text-decoration' => 'none',
		                ], $addl_styles)
		                ->attrs(['class' => 'hvr-td-u'])
		                ->render();
	};
	return HTMLEmail::buildPadded(
		[32],
		HTMLEmail::buildTextRows(
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
				'style' => [
					'font-family' => "'Helvetica Neue',Helvetica,Arial,sans-serif",
					'font-size'   => '11px',
					'line-height' => '12px',
					'color'       => '#555',
				],
			],
			15
		)
	);
}
