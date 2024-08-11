<?php 
$tvs_press_themeName = 'tvsPress';
$tvs_press_prefix_press = $tvs_press_themeName . "MB_";//imporntant -- becase this is database name 
$tvs_press_OptionsPageSetting = array(
	'name' => $tvs_press_prefix_press . 'meta-box-page',
	'nonce' => 'tvs_studio_press',
	'title' => __('Speaker Information', 'debateLang'),
	'page' => 'press',
	//'context' => 'side',
	'context' => 'normal',
	'priority' => 'default',
	'class' => '',
	'style' => '',
	'title_h2' => true,
	'fields' => array(


		
		array(
			'type' => 'info',
			'name' => $tvs_press_prefix_press . 'link',
			// 'title' => __('Categories Link', 'debateLang'),
			'title' => "",
			'description' => '  <a href=" /wp-admin/edit-tags.php?taxonomy=presslist&post_type=press">Categories Link</a>  ',
			'style' => '',
			'class' => '',
			'class_li' => '',
		),

		array(
			'type' => 'text',
			'name' => $tvs_press_prefix_press . 'pressUrl',
			'title' => __('Text ', 'debateLang'),
			'description' => 'This is text',
			'style' => 'color:#df2424;font-size:12px',
			'class' => 'cssIput',
			'class_li' => 'cssLi',
		),


		array(
			'type' => 'color',
			'name' => $tvs_press_prefix_press . 'color',
			'title' => __('Color ', 'debateLang'),
			'description' => 'This is text',
			'style' => 'font-size:12px',
			'class' => 'colorcssIput',
			'class_li' => 'colorcssLi',
		),


		array(
			'type' => 'textarea',
			'name' => $tvs_press_prefix_press . 'textarea',
			'title' => __('Text area', 'debateLang'),
			'description' => 'This is textarea',
			'style' => '',
			'class' => '',
			'class_li' => '',
		),



		array(
			'type' => 'hidden',
			'name' => $tvs_press_prefix_press . 'hiddenn',
			'title' => __('Hidden text', 'debateLang'),
			'description' => '',
			'style' => '',
			'class' => '',
			'class_li' => '',
		),

		array(
			'type' => 'checkbox',
			'name' => $tvs_press_prefix_press . 'checkbox',
			'title' => __('Checkbox ', 'debateLang'),
			'description' => '',
			'style' => '',
			'class' => '',
			'class_li' => '',
		),


		array(
			'type' => 'date',
			'name' => $tvs_press_prefix_press . 'date',
			'title' => __('Date ', 'debateLang'),
			'description' => '',
			'style' => '',
			'class' => '',
			'class_li' => '',
		),

		array(
			'type' => 'switch',
			'name' => $tvs_press_prefix_press . 'switch',
			'title' => __('Switch ', 'debateLang'),
			'description' => '',
			'style' => '',
			'class' => '',
			'class_li' => '',
		),


		array(
			'type' => 'radio',
			'name' => $tvs_press_prefix_press . 'radio',
			'title' => __('Radio Example ', 'debateLang'),
			'description' => '',
			'style' => '',
			'class' => '',
			'class_li' => '',
			'values' => array(
				"NO","Yes",
			)
		),





		array(
			'type' => 'select',
			'name' => $tvs_press_prefix_press . 'select',
			'title' => __('select ', 'debateLang'),
			'description' => '',
			'style' => '',
			'class' => '',
			'class_li' => '',
			'values' => array(
				"value1"=>"Select 1",
				"value2"=>"Select 2",
				"value3"=>"Select 3",
			)
		),


	)
);