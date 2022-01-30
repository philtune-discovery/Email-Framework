<?php

namespace Styles;

use App\Stylesheetz;
use NodeBuilder\ConditionalComment;
use NodeBuilder\ElemNode;
use NodeBuilder\NodeCollection;
use NodeBuilder\TextNode;
use HTMLEmail\HTMLEmail;

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
				TextNode::new(HTMLEmail::renderMobileStyles()),
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

}
