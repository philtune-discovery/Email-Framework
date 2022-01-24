<?php

settings([
	'page_title'        => 'HTML Email Framework',
	'preview_text'      => 'Preview Text Here',
	'comp_width'        => 1260,
	'mobile_comp_width' => 630,
	'container_width'   => 630,
	'body_bgclr'        => '#dddddd',
	'body_txtclr'       => '#000000',
	'main_bgclr'        => '#ffffff',
]);
const LOCALHOST_ROOT = '/Users/ptune/Projects';
define('SYSTEM_PATH', dirname(__DIR__));
define("ROOT_PATH", str_replace([LOCALHOST_ROOT, ' '], ['', '%20'], SYSTEM_PATH));
const LOCAL_RESOURCE_PATH = ROOT_PATH . '/dist/';
const PROD_RESOURCE_PATH = 'https://creative.snidigital.com/newsletters/2022/DH22-CTG/';
const SYSTEM_VIEWS_PATH = __DIR__ . '/../views/';
const SYSTEM_CONFIG_PATH = __DIR__ . '/../config/';

