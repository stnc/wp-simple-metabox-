<?php 
/*
Plugin Name:stnc meta 
Plugin URI:			
Description: video 
Version: 1.11.97
Author: Chrom Themes
Text Domain: chrom_video
Domain Path: /languages/

*/ 

function stnc_engine_admin_enqueue_style()
{   
  wp_enqueue_style('stncstyle', plugins_url('assets/css/ss-system-custom-post-admin.css', __FILE__),"","1.4.28");
  wp_enqueue_style('stncpico', plugins_url('assets/css/bootsrap-grid.css', __FILE__),"","1.7");
}

add_action('admin_enqueue_scripts', 'stnc_engine_admin_enqueue_style');



	 include('class.metabox-engine.php');
	 include('metabox_options.php');
	 $tvsDebate_press_options['0'] = $tvs_press_OptionsPageSetting;
	 new ssSytemMetaboxEngine($tvsDebate_press_options, 'tvs_engine-setting', true);

