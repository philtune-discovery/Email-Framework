<?php

namespace View;

class View
{
	private string $view_path;
	private array $view_data;
	private ?View $parent_view = null;

	private function __construct(string $view_name)
	{
		$this->view_path = SYSTEM_VIEWS_PATH . "$view_name.php";

		if ( !file_exists($this->view_path) ) {
			$caller = debug_backtrace()[0];
			json_out([
				'error' => $this->view_path . ' does not exist',
				'file'  => $caller['file'],
				'line'  => $caller['line'],
				'stack' => debug_backtrace(),
			]);
		}
	}

	public function data(array $view_data = []):View
	{
		$clone = clone $this;
		$clone->view_data = $view_data;
		return $clone;
	}

	/**
	 * @param string $view_name
	 * @param array $view_data
	 * @return static
	 */
	public static function make(string $view_name, array $view_data = []):View
	{
		return ( new static($view_name) )->data($view_data);
	}

	public static function setCurrentParentView(string $parent_view_path):void
	{
		$current = View::current();
		$current->parent_view = static::make($parent_view_path);
	}

	public function render():string
	{
		$clone = clone $this;
		if ( is_array($clone->view_data) ) {
			foreach ( $clone->view_data as $view_key => $view_val ) {
				$$view_key = $view_val;
			}
		}
		View::pushStack($clone);
		$html = include_string($clone->view_path);
		View::popStack();

		if ( $parent_view = $clone->getParentView() ) {
			$html = $parent_view
				->setPlaceholder($html)
				->addSections($clone->sections)
				->render();
		}

		return $html;
	}

	public function __toString():string
	{
		return $this->render();
	}

	private static array $stack = [];

	private static function pushStack(View $view):void
	{
		self::$stack[] = $view;
	}

	private static function popStack():void
	{
		array_pop(self::$stack);
	}

	public static function current():View
	{
		$_stack = self::$stack;
		return $_stack[count($_stack) - 1];
	}

	private array $sections = [];

	public function addSections(array $sections):View
	{
		$this->sections = array_merge($this->sections, $sections);
		return $this;
	}

	private string $current_section = '';

	public static function makeSection(string $name, string $value = null)
	{
		$_current = self::current();
		if ( $value ) {
			$_current->addSections([$name => $value]);
		} else {
			$_current->current_section = $name;
			ob_start();
		}
		return null;
	}

	public static function endSection()
	{
		$_current = self::current();
		$_current->addSections([$_current->current_section => ob_get_clean()]);
		return null;
	}

	private string $placeholder = '';

	public static function showSection(string $name = null, string $default = null):string
	{
		$_current = self::current();
		return $name ?
			$_current->sections[$name] ?? $default :
			$_current->placeholder;
	}

	public function setPlaceholder(string $placeholder):View
	{
		$this->placeholder = $placeholder;
		return $this;
	}

	public function getParentView():?View
	{
		return $this->parent_view ?: null;
	}

}
