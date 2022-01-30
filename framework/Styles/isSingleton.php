<?php

namespace Styles;

trait isSingleton
{

	private function __construct()
	{
	}

	protected function __clone()
	{
	}

	protected function __wakeup()
	{
	}

	public static function instance():self
	{
		static $_this = null;
		if ( $_this === null ) {
			$_this = new static;
		}
		return $_this;
	}

}
