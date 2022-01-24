<?php

use View\View;

function view(string $view_name, array $view_data = []):View
{
	return View::make($view_name, $view_data);
}

function inherit(string $parent_view_path):void
{
	View::setCurrentParentView($parent_view_path);
}

function placeholder(string $name = null, string $default = null):string
{
	return View::showSection($name, $default);
}
