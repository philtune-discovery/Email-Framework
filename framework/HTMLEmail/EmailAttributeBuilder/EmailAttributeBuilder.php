<?php

namespace HTMLEmail\EmailAttributeBuilder;

use NodeBuilder\AttributeBuilder\AttributeBuilder;
use Styles\Stylesheet;

class EmailAttributeBuilder extends AttributeBuilder
{

	public function addStyles(...$styles):self
	{
		$styles = array_replace_recursive(...$styles);
		array_walk($styles, function($val, $key) use (&$styles) {
			if ( is_array($val) ) {
				if ( substr($key, 0, 1) === ':' ) {
					$this->nodeBuilder->attrs(['class' => $this->uid]);
					Stylesheet::pseudo($this->uid, $key, $val);
				}
				unset($styles[$key]);
			}
		});
		$this->styles = array_merge($this->styles, $styles);
		return $this;
	}

	public function config(array $config):self
	{
		foreach ( $config as $key => $val ) {
			if ( is_array($val) ) {
				if ( substr($key, 0, 1) === ':' ) {
					Stylesheet::pseudo($this->uid, $key, $val);
					$this->nodeBuilder->attrs(['class' => $this->uid]);
				}
			} else {
				$this->styles[$key] = $val;
			}
		}
		return $this;
	}
}
