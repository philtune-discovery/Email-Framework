<?php

namespace App;

use Error;
use ReflectionClass;
use ReflectionException;

abstract class Singleton
{
	protected function __construct(){}
	protected function __clone(){}
	protected function __wakeup(){}

	public function __get($property)
	{
		try {
			$reflection = new ReflectionClass($this);
			$prop = $reflection->getProperty($property);
			if ( property_exists($this, $property) && !$prop->isPrivate() ) {
				return $this->$property;
			}
		} catch ( ReflectionException $e ) {
			throw new Error($e);
		}
		return null;
	}

	public static function getInstance():self
	{
		static $_this = null;
		if ( $_this === null ) {
			$_this = new static;
		}
		return $_this;
	}
}
