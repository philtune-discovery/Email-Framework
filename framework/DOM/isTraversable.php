<?php

namespace DOM;

use App\App;

trait isTraversable
{

	/**
	 * Get the parent hierarchy, return the yielding node
	 */
	public static function inherit(string $parentPath):NodeCollection
	{
		// Start a new root node
		$nodeCollection = new NodeCollection();
		// Save it to the global scope
		self::addToGlobal('yielded', $nodeCollection);
		self::addToGlobal('parent', App::config($parentPath));
		return $nodeCollection;
	}

	public function yield():self
	{
		$child = self::getGlobal()['yielded'];
		return $this->addChild($child);
	}

	public static function getParentOf(string $path):self
	{
		App::config($path);
		return self::getGlobal()['parent'];
	}

	private static array $global = [];

	public static function addToGlobal(string $key, $val)
	{
		self::$global[$key] = $val;
	}

	public static function getGlobal():array
	{
		return self::$global;
	}
}
