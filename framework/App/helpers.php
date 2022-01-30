<?php

use App\App;

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
