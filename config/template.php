<?php

return email_root(
	body()->addChildren([
		table()->addChild(
			elem('tr')->addChildren([
				elem('td', [
					'bgcolor' => '#dddddd',
					'align'   => 'center',
					'valign'  => 'top'
				])->addChildren([
					table(['width' => 630, 'class' => 'w-100p'])->addChildren([
						elem('tr')->addChild(
							elem('td')->addChildren([
								elem('div', [
									'style' => toStyleStr([
										'display'     => 'none !important',
										'visibility'  => 'hidden',
										'mso-hide'    => 'all',
										'font-size'   => '1pt',
										'color'       => '#ffffff',
										'line-height' => '1px',
										'max-height'  => 'none',
										'max-width'   => 'none',
										'opacity'     => '0',
										'overflow'    => 'hidden'
									])
								])->addChild(
									text(setting('preview_text'))
								)
							])
						),
						elem('tr')->addChild(
							elem('td', [
								'bgcolor' => setting('main_bgclr'),
								'style'   => 'background-color:' . hex2rgba(setting('main_bgclr'))
							])->addChildren([
								text(( function () {
									$table_attr_str = getTableAttrStr(['align' => 'center', 'width' => 630, 'class' => 'w-100p']);
									$td_attr_str = 'style="' . toStyleStr([
											'line-height'          => '0px',
											'font-size'            => '0px',
											'mso-line-height-rule' => 'exactly',
										]) . '"';
									$html = "<table $table_attr_str><tr><td $td_attr_str>";
									return "<!--[if mso | IE]>$html<![endif]-->";
								} )()),
								elem('div', [
									'style' => toStyleStr([
										'max-width' => '630px',
										'margin'    => '0px auto',
										'border'    => '1px solid #cccccc',
									])
								])->addChildren([

									/****** PLACEHOLDER ******/

									text('<!-- Container -->'),
									text('<!-- foo -->'),
									table(['width' => 630, 'class' => 'w-100p'])
										->yield()
										->addChild(config('legal')),
									text('<!-- End Container -->')


									/****** END PLACEHOLDER ******/

								]),
								text("<!--[if mso | IE]></td></tr></table><![endif]-->")
							])
						),
						text('<!-- BOTTOM SPACER FIX -->'),
						row(64)
					])
				])
			])
		)
	])
);
