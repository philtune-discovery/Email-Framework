<?php

spl_autoload_register('myAutoloader');

function myAutoloader($className)
{
	foreach ( [
		          './framework/',
		          './'
	          ] as $path ) {
		$path .= str_replace('\\', '/', $className) . '.php';
		if ( file_exists($path) ) {
			include $path;
			return;
		}
	}
	throw new Error('Couldn\'t find class ' . $className);
}
