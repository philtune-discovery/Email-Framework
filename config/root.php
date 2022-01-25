<?php use HTMLEmail\HTMLEmail;

return HTMLEmail::new([
	'title'           => 'HTML Email Framework',
	'bgcolor'         => '#dddddd',
	'txtcolor'        => '#000000',
	'container'       => [
		'width'            => 630,
		'background-color' => '#faf8f7',
		'class'            => 'w-100p',
		'border'           => '1px solid #CCCCCC',
	],
	'preview-text'    => 'Preview Text Here',
	'content-section' => 'main',
]);
