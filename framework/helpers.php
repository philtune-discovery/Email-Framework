<?php

include 'App/helpers.php';
include 'View/helpers.php';
include 'HTMLEmail/helpers.php';

function perc($int):string
{
	return ( $int * 100 ) . '%';
}

function array_entries(array $array):array
{
	return array_map(fn($key) => [$key, $array[$key]], array_keys($array));
}

function include_string(string $path):string
{
	ob_start();
	include $path;
	return ob_get_clean();
}

/**
 * Convert hexadecimal color string to rgb(a) string
 * @param string $hex_color
 * @return string
 */
function hex2rgba(string $hex_color):string
{
	// Remove '#' from the input
	$hex_color = str_replace('#', '', $hex_color);
	// Return black if provided color isn't 3, 4, 6, or 8 characters
	if ( !in_array(strlen($hex_color), [3,4,6,8]) ) {
		return 'rgb(0,0,0,1)';
	}
	// If using 3 or 4 character shorthand, convert to full
	if ( strlen($hex_color) <= 4 ) {
		// Split into array of single characters
		$hex_arr = str_split($hex_color);
		// Double each character
		$hex_arr = array_map(fn($c) => $c[0] . $c[0], $hex_arr);
	} else {
		// Split into array of character couplets
		$hex_arr = str_split($hex_color, 2);
	}
	// If only three groups, add opaque alpha couplet
	if ( count($hex_arr) === 3 ) {
		$hex_arr[] = 'ff';
	}
	// Apply hexadecimal to decimal conversion on each group
	$dec_arr = array_map('hexdec', $hex_arr);
	// Divide the alpha channel by 255 (converts to 0...1)
	$dec_arr[3] /= 255;
	// Glue the pieces back together and return inside 'rgba()'
	return 'rgba(' . implode(",", $dec_arr) . ')';
}
