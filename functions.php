<?php

use DOM\DOM;

/**
 * @param string $text
 * @param string $href
 * @param numeric|string $width
 * @return DOM
 */
function project_button(string $text, string $href, $width):DOM
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
	return button($text, $href, $buttonStyles, $textStyles);
}
