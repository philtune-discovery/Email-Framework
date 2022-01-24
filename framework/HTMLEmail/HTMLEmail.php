<?php

namespace HTMLEmail;

use App\App;
use DOM\DOM;

class HTMLEmail
{

	use hasHelpers;

	public static function include(string $path)
	{
		// include file that returns an HTMLEmail
		// this will register the parent
		App::config($path);
		return DOM::getGlobal()['parent'];
	}

	public static function inherit(string $parentPath):HTMLEmailNode
	{
		return HTMLEmailNode::use($parentPath);
	}
}
