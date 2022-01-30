<?php

const LOCALHOST_ROOT = '/Users/ptune/Projects';
define('SYSTEM_PATH', dirname(__DIR__));
define("ROOT_PATH", str_replace([LOCALHOST_ROOT, ' '], ['', '%20'], SYSTEM_PATH));
const LOCAL_RESOURCE_PATH = ROOT_PATH . '/dist/';
const PROD_RESOURCE_PATH = 'https://creative.snidigital.com/newsletters/2022/DH22-CTG/';
const SYSTEM_VIEWS_PATH = __DIR__ . '/../views/';
const SYSTEM_CONFIG_PATH = __DIR__ . '/../config/';

