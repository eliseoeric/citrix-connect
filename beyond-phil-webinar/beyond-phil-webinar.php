<?php 
/**
*	Citrix Connect for WordPress
*	
*	Allows integration of Citrix Webinars and Trainings Regisration for WordPress websites.
*
*
*	@package	CCWP
*	@author 	Social Faucet <dev@socialfaucet.com>
*	@license	GLP-2.0+
*	@link 		http://socialfaucet.com
*	@copyright	2015 Social Faucet
*
*	@wordpress-plugin
*	Plugin Name: Citrix Connect for WP 
*	Plugin URI: 
*	Description: Allows integration of Citrix Webinars and Trainings Regisration for WordPress websites.
*	Version: 0.5
*	Author: Social Faucet
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