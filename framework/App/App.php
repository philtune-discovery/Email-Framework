<?php

namespace App;

class App
{

	private array $settings = [];
	private string $resource_path = '';

	protected function __construct()
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

	public function settings(array $settings):bool
	{
		return array_walk($settings, function ($val, $key) {
			return $this->setting($key, $val);
		});
	}

	public static function setSettings(array $settings):bool
	{
		return self::instance()->settings($settings);
	}

	public static function setting(string $key, $val = null)
	{
		$_instance = self::instance();
		if ( $val ) {
			$_instance->settings[$key] = $val;
			return true;
		} else {
			return $_instance->settings[$key] ?? null;
		}
	}

	public static function setResourcePath(string $src_path):void
	{
		$_this = self::instance();
		$_this->resource_path = rtrim($src_path, '/') . '/';
	}

	public static function getResourcePath():string
	{
		$_this = self::instance();
		return rtrim($_this->resource_path);
	}

	/**
	 * @param callable|string $output
	 */
	public static function output($output)
	{
		if ( is_callable($output) ) {
			$output();
		} else {
			echo $output;
		}
	}

	public static function writeToFile($output, string $filename):void
	{
		ob_start();
		static::output($output);
		$output = ob_get_clean();

		$file_handle = fopen($filename, 'w');
		fwrite($file_handle, $output);
		fclose($file_handle);
	}

	public static function config(string $path, array $data = [])
	{
		$config = null;
		$file = SYSTEM_CONFIG_PATH . "$path.php";
		if ( file_exists($file) ) {
			foreach ( $data as $view_key => $view_val ) {
				$$view_key = $view_val;
			}
			$config = include $file;
		}
		return $config;
	}
}
