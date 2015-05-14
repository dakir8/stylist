<?php
require_once 'common.php';

$salon_cat = array(
'連鎖', '速剪', '懷舊', '大眾', '潮流', '時尚', '星級'
);
$salon_service = array(
'洗吹','洗剪吹','焗油','染髮','技巧挑染','電髮','陶瓷曲髮','數碼曲髮','負離子直髮','套餐'
);
$hair_type = array('長髮','短髮', '曲髮', '中','直髮');
$region_type = array('韓風','日風','歐美');
$age_type = array('成熟女仕','年輕活力','陽光','經典');
$place_type = array('婚宴','上班','運動');

$salon = array(
	array(
		'_id' => 's1',
		'name' => 'HAIR NET SALON ONE',
		'lat' => '22.3736053',
		'lng' => '114.1153778',
		'section' => '荃灣',
		'address' => '新界荃灣青山公路荃灣段289-301號 昌華大廈',
		'phone' => '24055099',
		'business_hour' => '10:00-18:30',
		'base_plan' => array(
			array(
				'name' => '洗吹',
				'price' => '51-100',
			),
			array(
				'name' => '洗剪吹',
				'price' => '101-200',
			)
		),
		'mark' => 0.0,
		'photo' => array(),
		// 相關髮評
		// 相關髮型 + 相片 + USER?
	),
	array(
		'_id' => 's2',
		'name' => 'ys Salon',
		'lat' => '22.3826918',
		'lng' => '114.1912465',
		'section' => '沙田',
		'address' => '新界沙田好運中心地下12號B',
		'phone' => '26428891',
		'business_hour' => '10:30-18:30',
		'base_plan' => array(
			array(
				'name' => '洗吹',
				'price' => '51-100',
			),
			array(
				'name' => '洗剪吹',
				'price' => '101-200',
			),
			array(
				'name' => '焗油',
				'price' => '0-200',
			)
		),
		'mark' => 0.0,
		'photo' => array(),
	),
);
$news = array(
	'_id' => 'n1',
	'title' => '',
	'content' => '',
	'date' => new MongoDate('2015-05-02 10:00:00'),
	'related_salon' => array(),
	'related_stylist' => array(),
);
$hair = array(
	array(
		'_id' => 'h1',
		'member_name' => '',
		'date' => new MongoDate('2015-05-01 10:00:00'),
		'like_count' => 0,
		'photo' => '',
		'star' => false,
		'stylist' => '',
		'salon' => '',
		'type' => array(
			'_id' => 'ht1',
			'name' => '',
			'hair_type' => '長髮',
			'region_type' => '韓風',
			'age_type' => '成熟女仕',
			'place_type' => '婚宴'
		)
	)
);
$stylist = array(
	array(
		'_id' => 'm1',
		'name' => '',
		'work_year' => '',
		'gender' => 'M',
		'salon' => '',
		'worked_salon' => array(
			'salon' => '',
			'period' => ''
		),
		'like_count' => 0
	)
);
$comment = array(
	
);