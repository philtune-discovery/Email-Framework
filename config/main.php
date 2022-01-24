<?php

use App\App;

App::setSettings([
	'page_title'      => 'HTML Email Framework',
	'preview_text'    => 'Preview Text Here',
	'container_width' => 630,
	'main_bgclr'      => '#faf8f7',
]);

return email([
	'inherit' => 'template',
	'track'   => [
		'https://ad.doubleclick.net/ddm/trackimp/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;ord=[timestamp];dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ltd=?'
	]
])
	->full_width_img(
		'/img/header.png',
		"HGTV Dream Home Proud Sponsor 2022 | Cabinets To Go - Wow For Less.",
		'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd=',
	)
	->full_width_img(
		'/img/kitchen.jpg',
		null,
		'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd='
	)
	->cols(
		col(
			['width' => perc(314 / 639), 'valign' => 'top'],
			img('/img/cabinets.jpg', ['width' => perc(1)])
		),
		col(
			['width' => perc(299 / 639)],
			rows(
				p(
					'DREAM IT. DO IT.',
					attrList('m_intro-heading')
				),
				row(10),
				p('We’re your one-stop destination for cabinets, closets, flooring, and more. We’ll help you turn your house into your dream home.', attrList('m_intro-p')),
				row(10),
				project_button(
					'Get A Free 3D Design',
					'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd=',
					294
				),
			)
		),
		col(perc(26 / 639))
	)
	->full_width_img(
		'/img/laundry.jpg',
		null,
		'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd='
	)
	->full_width_img(
		'/img/footer.png',
		'Cabinets To Go - Wow For Less. | Get a Free 3D Design',
		'https://ad.doubleclick.net/ddm/trackclk/N636.3140998DISCOVERYCHANNEL/B26331212.312029041;dc_trk_aid=517031567;dc_trk_cid=163820815;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;ltd='
	)
	->row(32);
