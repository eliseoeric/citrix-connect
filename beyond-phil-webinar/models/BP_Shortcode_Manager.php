<?php 

class BP_Shortcode_Manager {
	private static $instance;

	private function __construct(){
		self::register_shortcodes();
	}

	public static function init(){
		if(null == self::$instance){
			self::$instance = new self;
		}

		return self::$instance;
	}


	public function register_shortcodes(){
		add_shortcode( 'citrix_heading', array($this, 'get_bp_heading_from_url') );
		add_shortcode( 'bp_login_menu', array($this, 'get_userpro_login') );
	}

	public function get_bp_heading_from_url($atts){
		if ($atts['type'] == "webinar"){
			$citrix = BP_Webinar_Admin::get_webinar(get_query_var( 'webinar_id' ));
			return "<h2 class='bp-heading-dark'>". $citrix['subject']."</h2>";
		} else if ($atts['type'] == 'training'){
			$citrix = BP_Training_Admin::get_training(get_query_var( 'course_id' ));
			return "<h2 class='bp-heading-dark'>". $citrix['name']."</h2>";
		} else{
			$citirx['subject'] = "This is not a valid form.";
		}

	}

	public function get_userpro_login($atts){
		$current_user = wp_get_current_user();

		$login_menu = '<div class="widget_nav_menu"><ul class="menu">';

		if(!(is_user_logged_in())){
			//display login
			$login_item = '<li class="popup-login menu-item menu-item-type-post_type menu-item-object-page"><a href="/profile/">Login </a><li>';
			//display register
			$register_item = '<li class="popup-register menu-item menu-item-type-post_type menu-item-object-page"><a href="/profile/register">Register</a></li>';

			$login_menu .= $login_item . $register_item . '</ul></div>';
		} else {
			if(!empty($current_user->user_firstname)){
				$user_name = '<li class="menu-item menu-item-type-post_type menu-item-object-page"> Hello, ' . $current_user->user_firstname .'</li>';
			} else {
				$user_name = '<li class="menu-item menu-item-type-post_type menu-item-object-page"> Hello, ' . $current_user->display_name .'</li>';
			}
			
			$login_item = '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="'. wp_logout_url(  home_url() ). '">Logout </a><li>';

			$register_item = '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="/profile/">View Profile</a></li>';

			$login_menu .= $user_name . $register_item .$login_item. '</ul></div>';
		}

		return $login_menu;

	}

		

}