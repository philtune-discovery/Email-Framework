<?php

use App\App;
use App\Selector;

function attrList($name, array $addlAttrs = []):array
{
	return ( new Selector($name, $addlAttrs) )
		->getAttrsList();
}

function attrStr($name, array $addlAttrs = []):string
{
	return ( new Selector($name, $addlAttrs) )
		->getAttrsStr();
}

function settings(array $settings):bool
{
	return App::setSettings($settings);
}

function setting(string $key, $val = null)
{
	return App::setting($key, $val);
}

function src($src, $path = null):string
{
	$path = $path ?: App::getResourcePath();
	return $path . ltrim($src, '/');
}

function config(string $path, array $data = [])
{
	return App::config($path, $data);
}
