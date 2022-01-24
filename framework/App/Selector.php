<?php

namespace App;

class Selector
{
	private string $name;
	private $inline_raw;
	private string $inline_styles = '';
	private array $attr_list = [];
	public bool $has_mobile_styles;
	public bool $has_pseudo_styles;
	private array $addlAttrs;

	public function __construct(string $name, array $addlAttrs = [])
	{
		$this->name = $name;
		$this->inline_raw = Stylesheetz::getInlineStylesFor($name);
		$this->has_pseudo_styles = Stylesheetz::hasPseudoStylesFor($name);
		$this->has_mobile_styles = Stylesheetz::hasMobileStylesFor($name);
		$this->addlAttrs = $addlAttrs;
		$this->process();
	}

	public function getInlineRaw()
	{
		return $this->inline_raw;
	}

	public function getInlineStyles()
	{
		return $this->inline_styles;
	}

	public function getAttrsList():array
	{
		return $this->attr_list;
	}

	public function getAttrsStr():string
	{
		return implode(' ', $this->attr_list);
	}

	private function process()
	{
		// Generate attributes from CSS while removing them from the raw styles
		$inline_styles = preg_replace_callback_array([
			'/(^|;)width:\s*([\d.\%]+)(?:px)?;?/'  => function ($matches) {
				$this->attr_list[] = "width=\"{$matches[2]}\"";
				return $matches[1];
			},
			'/(^|;)height:\s*([\d.\%]+)(?:px)?;?/' => function ($matches) {
				$this->attr_list[] = "height=\"{$matches[2]}\"";
				return $matches[1];
			},
			'/background-color:\s*([^;]+);?/'      => function ($matches) {
				$this->attr_list[] = "bgcolor=\"{$matches[1]}\"";
				return '';
			},
			'/text-align:\s*([^;]+);?/'            => function ($matches) {
				$this->attr_list[] = "align=\"{$matches[1]}\"";
				return '';
			},
			'/vertical-align:\s*([^;]+);?/'        => function ($matches) {
				$this->attr_list[] = "valign=\"{$matches[1]}\"";
				return '';
			},
			'/(^|;)cellpadding:\s*([^;]+);?/'      => function ($matches) {
				$this->attr_list[] = "cellpadding=\"{$matches[2]}\"";
				return $matches[1];
			},
			'/(^|;)cellspacing:\s*([^;]+);?/'      => function ($matches) {
				$this->attr_list[] = "cellspacing=\"{$matches[2]}\"";
				return $matches[1];
			},
			'/border-width:\s*([^;]+);?/'          => function ($matches) {
				$this->attr_list[] = "border=\"{$matches[1]}\"";
				return '';
			},
			'/role:\s*([^;]+);?/'                  => function ($matches) {
				$this->attr_list[] = "role=\"{$matches[1]}\"";
				return '';
			},
			'/arcsize:\s*([^;]+);?/'               => function ($matches) {
				$this->attr_list[] = "arcsize=\"{$matches[1]}\"";
				return '';
			},
			'/stroke:\s*([^;]+);?/'                => function ($matches) {
				$this->attr_list[] = "stroke=\"{$matches[1]}\"";
				return '';
			},
			'/fillcolor:\s*([^;]+);?/'             => function ($matches) {
				$this->attr_list[] = "fillcolor=\"{$matches[1]}\"";
				return '';
			},
		], $this->inline_raw);
		if ( $addl_style = $this->addlAttrs['style'] ?? null ) {
			$inline_styles .= ";$addl_style";
		}
		// Take any trailing semicolon off
		$this->inline_styles = trim($inline_styles, ';');


		// If there are any inline styles remaining, add them to the attributes list
		if ( $this->inline_styles ) {
			$this->attr_list[] = "style=\"{$this->inline_styles}\"";
		}
		if ( $this->has_mobile_styles || $this->has_pseudo_styles ) {
			$this->attr_list[] = "class=\"{$this->name}\"";
		}
	}
}
