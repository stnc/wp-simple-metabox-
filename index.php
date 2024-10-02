<?php 

function stnc_engine_admin_enqueue_style()
{   
  wp_enqueue_style('stncstyle', plugins_url('assets/css/ss-system-custom-post-admin.css', __FILE__),"","1.4.28");
  wp_enqueue_style('stncpico', plugins_url('assets/css/bootsrap-grid.css', __FILE__),"","1.7");
}

add_action('admin_enqueue_scripts', 'stnc_engine_admin_enqueue_style');



	 include('metabox_options.php');
	 $tvsDebate_press_options['0'] = $tvs_press_OptionsPageSetting;
	 new ssSytemMetaboxEngine($tvsDebate_press_options, 'tvs_engine-setting', true);

