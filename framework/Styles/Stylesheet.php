<?php

namespace Styles;

use App\Stylesheetz;
use NodeBuilder\Extensions\ConditionalComment;
use NodeBuilder\Extensions\ElemNode;
use NodeBuilder\Extensions\NodeCollection;
use NodeBuilder\Extensions\TextNode;

class Stylesheet
{

	use isSingleton;

	protected array $pseudos = [];

	public static function getStylesheets()
	{
		return NodeCollection::new()->addChildren([
			ElemNode::new('style')->attrs(['type' => 'text/css'])->addChildren([
				TextNode::new(include_string('./sass/head_styles.css')),
				TextNode::new(Stylesheetz::getPseudoStylesStr()),
				TextNode::new(self::getPseudos()),
				TextNode::new("@media only screen and ( max-width: 648px ) {" . Stylesheetz::getMobileStylesStr() . "}"),
				TextNode::new("* { font-family:'Helvetica Neue',Helvetica,Arial,sans-serif }")
			]),
			ConditionalComment::new('mso')->addChildren([
				ElemNode::new('style')->attrs(['type' => 'text/css'])->addChildren([
					TextNode::new('table, td {'),
					TextNode::new('	border-collapse: collapse;'),
					TextNode::new('	mso-table-lspace: 0pt;'),
					TextNode::new('	mso-table-rspace: 0pt;'),
					TextNode::new('}'),
					/* Desktop Outlook chokes on web font references and defaults
					 to Times New Roman, so we force a safe fallback font. */
					TextNode::new('* {font-family: Arial, sans-serif !important;}'),
				]),
			])
		]);
	}

	public static function pseudos(...$pseudo_arrs):void
	{
		array_map(function($pseudo_arr){
			self::pseudo(...$pseudo_arr);
		}, $pseudo_arrs);
	}

	public static function pseudo(string $className, string $pseudo, array $styles):void
	{
		$_this = self::instance();
		if ( !array_key_exists($className, $_this->pseudos) ) {
			$_this->pseudos[$className] = [];
		}
		if ( !array_key_exists($pseudo, $_this->pseudos[$className]) ) {
			$_this->pseudos[$className][$pseudo] = [];
		}
		$_this->pseudos[$className][$pseudo] = array_merge($_this->pseudos[$className][$pseudo], $styles);
	}

	private static function getPseudos():string
	{
		return array_reduce(array_entries(self::instance()->pseudos), function ($str, $pair) {
			list($className, $pseudos) = $pair;
			return $str . array_reduce(array_entries($pseudos), function ($str, $pair) use ($className) {
					list($pseudo, $styles) = $pair;
					return $str .
						".{$className}{$pseudo}{" .
						implode(';', array_map(fn($pair)=>implode(':', $pair) . ' !important', array_entries($styles))) .
						"}";
				}, '');
		}, '');
	}

}
