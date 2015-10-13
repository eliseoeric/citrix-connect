<?php 
/**
*	Beyond Philosophy Citrix Webinar Integration
*	
*	Wordpress Plugin intergrates Citirx Webinar Registration into 
* 	The Beyond Philosophy Site.
*
*
*	@package	BPCW
*	@author 	Social Faucet <dev@socialfaucet.com>
*	@license	GLP-2.0+
*	@link 		http://socialfaucet.com
*	@copyright	2015 Social Faucet
*
*	@wordpress-plugin
*	Plugin Name: Beyond Philosophy Citrix Webinar Integration 
*	Plugin URI: 
*	Description: Allows integration of Citrix Webinars Regisration for Beyond Philosophy Website
*	Version: 0.5
*	Author: Think Generic
*	Author URI: http://www.socialfaucet.com
*/


//If this file is called directly, abort.
if(!defined('WPINC')){
	die;
}

define('BPCW_DIR', plugin_dir_path( __FILE__ ));


include_once( BPCW_DIR .'/models/BP_Citrix_Webinar.php');


BP_Citrix_Webinar::get_instance();



 ?>