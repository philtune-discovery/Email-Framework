<?php

use function HTMLEmail\col;
use function HTMLEmail\row_padding;
use function HTMLEmail\rows;
use function HTMLEmail\text_row;
use function HTMLEmail\toStyleStr;

echo myEmail('HTML Email Framework')
	->addImgRow(
		src('/img/header.png'),
		"HGTV Dream Home Proud Sponsor 2022 | Cabinets To Go - Wow For Less.",
		'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd=',
	)
	->addImgRow(
		src('/img/kitchen.jpg'),
		null,
		'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd='
	)
	->addColumns(
		img_col('/img/cabinets.jpg', 314, ['valign' => 'top']),
		col(
			['width' => comp_perc(299)],
			rows(
				text_row(
					'Dream It. Do It.',
					['style' => toStyleStr([
						'font-family'    => "'Helvetica Neue',Helvetica,Arial,sans-serif",
						'font-weight'    => 700,
						'font-size'      => "32px",
						'color'          => "#444244",
						'text-transform' => 'uppercase',
					])]
				),
				row_padding(10),
				text_row(
					"We're your one-stop destination for cabinets, closets, flooring, and more. We’ll help you turn your house into your dream home.",
					['style' => toStyleStr([
						'font-family' => "'Helvetica Neue',Helvetica,Arial,sans-serif",
						'font-weight' => 300,
						'line-height' => 1.27778,
						'font-size'   => "18px",
						'font-style'  => "italic",
						'color'       => "#666666",
					])]
				),
				row_padding(10),
				myButton(
					'Get A Free 3D Design',
					'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd=',
					comp_rel(299)
				),
			)
		),
		col(comp_perc(26))
	)
	->addImgRow(
		src('/img/laundry.jpg'),
		null,
		'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd='
	)
	->addImgRow(
		src('/img/footer.png'),
		'Cabinets To Go - Wow For Less. | Get a Free 3D Design',
		'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd='
	)
	->addTrackingPixels([
		'https://ad.doubleclick.net/ddm/trackimp/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;ord=[timestamp];dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ltd=?',
	])
	->add(myLegal());
