<?php

namespace HTMLEmail\EmailAttributeBuilder;

use NodeBuilder\AttributeBuilder as NodeAttributeBuilder;
use NodeBuilder\NodeBuilder;
use Styles\Stylesheet;
use function HTMLEmail\toStyleStr;

class AttributeBuilder extends NodeAttributeBuilder
{

	protected NodeBuilder $nodeBuilder;
	protected string $uid;
	protected array $styles = [];
	protected array $pseudos = [];

	public function __construct()
	{
		$this->uid = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 6);
	}

	public function addAttrs(...$attr_groups):self
	{
		array_walk($attr_groups, function(&$attrs) {
			if ( ($style = $attrs['style'] ?? null) && is_string($style) ) {
				$attrs['style'] = [];
				foreach (explode(';', $style) as $kvp_str ) {
					list($prop, $val) = explode(':', $kvp_str);
					$attrs['style'][$prop] = $val;
				}
			}
		});
		$this->attrs = array_merge($this->attrs, ...$attr_groups);
		return $this;
	}

	public function render():string
	{
		if ( $style = $this->attrs['style'] ?? null ) {
			if ( is_string($style) ) {
				json_out($style);
			}
			$this->attrs['style'] = toStyleStr($style);
		}
		return parent::render();
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
