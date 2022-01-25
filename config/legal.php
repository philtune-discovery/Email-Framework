<?php

use DOM\NodeCollection;
use HTMLEmail\HTMLEmail;

$a = fn(string $text, string $href, array $styles = []) => a($text, $href, [
	'style' => toStyleStr([
		'color'           => 'blue',
		'text-decoration' => 'none',
	], $styles),
	'class' => 'hvr-td-u',
]);

return NodeCollection::create()->addChild(
	padded([32],
		paragraphs(
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
			], [
			'align' => 'center',
			'style' => toStyleStr([
				'font-family' => "'Helvetica Neue',Helvetica,Arial,sans-serif",
				'font-size'   => '11px',
				'line-height' => '12px',
				'color'       => '#555',
			]),
			'class' => 'm_legal-text',
		],
			15
		)
	)
);
