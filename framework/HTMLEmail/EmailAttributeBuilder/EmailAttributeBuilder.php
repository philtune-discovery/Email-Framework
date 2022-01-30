<?php

namespace HTMLEmail\EmailAttributeBuilder;

use NodeBuilder\AttributeBuilder;
use NodeBuilder\NodeBuilder;
use Styles\Stylesheet;

class EmailAttributeBuilder extends AttributeBuilder
{

	protected NodeBuilder $dom;
	protected string $uid;
	protected array $styles = [];
	protected array $pseudos = [];

	private function __construct()
	{
		$this->uid = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10);
	}

	public function config(array $config):self
	{
		foreach ( $config as $key => $val ) {
			if ( is_array($val) ) {
				if ( substr($key, 0, 1) === ':' ) {
					Stylesheet::pseudo($this->uid, $key, $val);
					$this->dom->attrs(['class' => $this->uid]);
				}
			} else {
				$this->styles[$key] = $val;
			}
		}
		return $this;
	}
}
