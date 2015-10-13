<?php
/**
 *    Beyond Philosophy Citirix Webinar Intergration
 *
 * @package    BPCW
 * @author    Eric Eliseo
 * @license    GPL-2.0+
 * @link        http://thinkgeneric.com
 * @copyright    2014 Eric Eliseo
 */

/**
 *    The core plugin class for BPCW
 *
 * @package BPCW
 * @author Eric Eliseo <eric@thinkgeneric.com>
 */
class BP_Citrix_Webinar {

	private static $instance;

	private function __construct() {
		$this->init();
		$this->resister_admin();

	}

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function init() {
		// let's get language support going, if you need it
		load_theme_textdomain( 'BPCW', plugin_dir_url( __FILE__ ) . '/library/translation' );
		// let's add our custom query variables to the wp_query list
		add_filter( 'query_vars', array( $this, 'add_query_vars_filter' ) );

		// let's make sure we are listening to Gravity Forms for when a webinar submission is made.
		add_filter( 'gform_notification_3', array( $this, 'gf_data_to_webinar' ), 10, 3 );
		add_filter( 'gform_notification_4', array( $this, 'gf_capture_data' ), 10, 3 );


		// Include Classes
		include_once( 'BP_Post_Handler.php' );
		include_once( 'BP_Meta_Box_Handler.php' );
		include_once( 'BP_Webinar_Admin.php' );
		include_once( 'BP_Training_Admin.php' );
		include_once( 'BP_Shortcode_Manager.php' );
		include_once( 'BP_Utility_Functions.php' );
		include_once( 'BP_Webinar_Template.php' );
		include_once( 'BP_Training_Template.php' );
		include_once( 'BP_Form_Capture_Handler.php' );

		//Init Classes
		BP_Post_Handler::init();
		BP_Meta_Box_Handler::init();
		BP_Shortcode_Manager::init();
		BP_Utility_Functions::init();
		BP_Webinar_Template::init();
		BP_Training_Template::init();
		BP_Form_Capture_Handler::init();
	}


	/*
	* expose custom query variable to WP_Query
	*/
	public function add_query_vars_filter( $vars ) {
		$vars[] = "webinar_id";
		$vars[] = "course_id";

		return $vars;
	}

	public function resister_admin() {
		// add the admin options page
		add_action( 'admin_menu', array( $this, 'bp_citrix_cred_menu' ) );

		// add the admin settings and such
		add_action( 'admin_init', array( $this, 'plugin_admin_init' ) );
	}

	/*
	* Add the plugin settings to the admin menu.
	*/
	function plugin_admin_init() {
		register_setting( 'bpcw_options', 'bpcw_options', array( $this, 'bpcw_options_validate' ) );

		add_settings_section( 'bpcw_webinar_settings', 'GoToWebinar Settings', array(
			$this,
			'bpcw_sections_text'
		), 'bpcw-settings' );
		add_settings_field( 'bpcw_webinar_login', 'GotoWebinar Username', array(
			$this,
			'bpcw_webinar_login'
		), 'bpcw-settings', 'bpcw_webinar_settings' );
		add_settings_field( 'bpcw_webinar_password', 'GotoWebinar Password', array(
			$this,
			'bpcw_webinar_password'
		), 'bpcw-settings', 'bpcw_webinar_settings' );
		add_settings_field( 'bpcw_webinar_client_id', 'GotoWebinar Client API ID', array(
			$this,
			'bpcw_webinar_client_id'
		), 'bpcw-settings', 'bpcw_webinar_settings' );
		add_settings_field( 'bpcw_webinar_access_token', 'GotoWebinar Access Token', array(
			$this,
			'bpcw_webinar_access_token'
		), 'bpcw-settings', 'bpcw_webinar_settings' );
		add_settings_field( 'bpcw_webinar_organizer_id', 'GotoWebinar Organizer ID', array(
			$this,
			'bpcw_webinar_organizer_id'
		), 'bpcw-settings', 'bpcw_webinar_settings' );


		add_settings_section( 'bpcw_training_settings', 'GoToTraining Settings', array(
			$this,
			'bpcw_sections_text'
		), 'bpcw-settings' );
		add_settings_field( 'bpcw_training_login', 'GotoTraining Username', array(
			$this,
			'bpcw_training_login'
		), 'bpcw-settings', 'bpcw_training_settings' );
		add_settings_field( 'bpcw_training_password', 'GotoTraining Password', array(
			$this,
			'bpcw_training_password'
		), 'bpcw-settings', 'bpcw_training_settings' );
		add_settings_field( 'bpcw_training_client_id', 'GoToTraining Client API ID', array(
			$this,
			'bpcw_training_client_id'
		), 'bpcw-settings', 'bpcw_training_settings' );
		add_settings_field( 'bpcw_training_access_token', 'GoToTraining Access Token', array(
			$this,
			'bpcw_training_access_token'
		), 'bpcw-settings', 'bpcw_training_settings' );
		add_settings_field( 'bpcw_training_organizer_id', 'GoToTraining Organizer ID', array(
			$this,
			'bpcw_training_organizer_id'
		), 'bpcw-settings', 'bpcw_training_settings' );
	}

	/*
	* Input field validation callback
	*/
	function bpcw_options_validate( $input ) {
		$newinput['bpcw_webinar_login']        = trim( $input['bpcw_webinar_login'] );
		$newinput['bpcw_webinar_password']     = trim( $input['bpcw_webinar_password'] );
		$newinput['bpcw_webinar_client_id']    = trim( $input['bpcw_webinar_client_id'] );
		$newinput['bpcw_webinar_access_token'] = trim( $input['bpcw_webinar_access_token'] );
		$newinput['bpcw_webinar_organizer_id'] = trim( $input['bpcw_webinar_organizer_id'] );

		$newinput['bpcw_training_login']        = trim( $input['bpcw_training_login'] );
		$newinput['bpcw_training_password']     = trim( $input['bpcw_training_password'] );
		$newinput['bpcw_training_access_token'] = trim( $input['bpcw_training_access_token'] );
		$newinput['bpcw_training_client_id']    = trim( $input['bpcw_training_client_id'] );
		$newinput['bpcw_training_organizer_id'] = trim( $input['bpcw_training_organizer_id'] );

		return $newinput;
	}

	/*
	* Creates the meu item within the settings top level menu
	*/
	function bp_citrix_cred_menu() {
		add_menu_page( 'Citrix Configuration', 'Citrix Configuration', 'manage_options', 'bpcw-settings', array(
			$this,
			'bpcw_options'
		), 'dashicons-admin-network' );
	}

	/*
	* Renders the plugin options and settings screen
	*/
	function bpcw_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficent permissions to access this page.' ) );
		}
		?>
		<div class="wrap">
			<form action="options.php" method="post">
				<?php settings_fields( 'bpcw_options' ); ?>
				<?php do_settings_sections( 'bpcw-settings' ); ?>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary"
				                         value="Save Changes"></p>

				<h1>To Do</h1>

				<p> We need to make the gravity forms id and notification id for the webinar registration dynamic!</p>
				<?php

				$trainings = BP_Webinar_Admin::get_webinars();
				var_dump( $trainings );


				?>
			</form>
		</div>
	<?php

	}

	/*
	* Renders the instruction text
	*/

	function bpcw_sections_text() {
		echo '<p>Please enter the Citrix Goto Webinar username and password below.';
	}

	/**
	 *    The GoToWebinar Callbacks
	 *
	 */

	/*
	* Renders the webinar username field
	*/

	function bpcw_webinar_login() {
		$options = get_option( 'bpcw_options' );
		echo "<input id='bpcw_webinar_login' name='bpcw_options[bpcw_webinar_login]' size='30' type='text' value='{$options['bpcw_webinar_login']}' />";
	}

	/*
	*  Renders the webinar password field
	*/
	function bpcw_webinar_password() {
		$options = get_option( 'bpcw_options' );
		echo "<input id='bpcw_webinar_password' name='bpcw_options[bpcw_webinar_password]' size='30' type='password' value='{$options['bpcw_webinar_password']}' />";
	}

	/*
	* Renders the client api id field
	*/

	function bpcw_webinar_client_id() {
		$options = get_option( 'bpcw_options' );
		echo "<input id='bpcw_webinar_client_id' name='bpcw_options[bpcw_webinar_client_id]' size='30' type='text' value='{$options['bpcw_webinar_client_id']}' />";
	}

	/*
	* Gets the access token for citrix api and stores it as wp_option
	*/
	function bpcw_webinar_access_token() {
		$options = get_option( 'bpcw_options' );
		if ( $options['bpcw_webinar_access_token'] == null ) {
			$oauth                                = BP_Webinar_Admin::get_oAuth();
			$options['bpcw_webinar_access_token'] = $oauth['access_token'];
		}

		echo "<input id='bpcw_webinar_access_token' name='bpcw_options[bpcw_webinar_access_token]' size='30' type='text' value='{$options['bpcw_webinar_access_token']}' />";

	}

	/*
	* Gets the access token for citrix api and stores it as wp_option
	*/
	function bpcw_webinar_organizer_id() {
		$options = get_option( 'bpcw_options' );
		if ( $options['bpcw_webinar_organizer_id'] == null ) {
			$oauth                                = BP_Webinar_Admin::get_oAuth();
			$options['bpcw_webinar_organizer_id'] = $oauth['organizer_key'];
		}

		echo "<input id='bpcw_webinar_organizer_id' name='bpcw_options[bpcw_webinar_organizer_id]' size='30' type='text' value='{$options['bpcw_webinar_organizer_id']}' />";
	}

	/**
	 *    The GoToTraining Callbacks
	 *
	 */

	/*
	* Renders the training username field
	*/

	function bpcw_training_login() {
		$options = get_option( 'bpcw_options' );
		echo "<input id='bpcw_training_login' name='bpcw_options[bpcw_training_login]' size='30' type='text' value='{$options['bpcw_training_login']}' />";
	}

	/*
	*  Renders the training password field
	*/
	function bpcw_training_password() {
		$options = get_option( 'bpcw_options' );
		echo "<input id='bpcw_training_password' name='bpcw_options[bpcw_training_password]' size='30' type='password' value='{$options['bpcw_training_password']}' />";
	}

	/*
	* Renders the client api id field
	*/

	function bpcw_training_client_id() {
		$options = get_option( 'bpcw_options' );
		echo "<input id='bpcw_training_client_id' name='bpcw_options[bpcw_training_client_id]' size='30' type='text' value='{$options['bpcw_training_client_id']}' />";
	}

	/*
	* Gets the access token for citrix api and stores it as wp_option
	*/
	function bpcw_training_access_token() {
		$options = get_option( 'bpcw_options' );
		if ( $options['bpcw_training_access_token'] == null ) {
			$oauth                                 = BP_Training_Admin::get_oAuth();
			$options['bpcw_training_access_token'] = $oauth['access_token'];
		}

		echo "<input id='bpcw_training_access_token' name='bpcw_options[bpcw_training_access_token]' size='30' type='text' value='{$options['bpcw_training_access_token']}' />";
	}

	/*
	* Gets the access token for citrix api and stores it as wp_option
	*/
	function bpcw_training_organizer_id() {
		$options = get_option( 'bpcw_options' );
		if ( $options['bpcw_training_organizer_id'] == null ) {
			$oauth                                 = BP_Training_Admin::get_oAuth();
			$options['bpcw_training_organizer_id'] = $oauth['organizer_key'];
		}

		echo "<input id='bpcw_training_organizer_id' name='bpcw_options[bpcw_training_organizer_id]' size='30' type='text' value='{$options['bpcw_training_organizer_id']}' />";
	}

	public static function gf_data_to_webinar( $notification, $entry, $form ) {
		// Check to make sure the notification is correct.
		if ( $notification['name'] === 'Send to Citrix' ) {
			BP_Webinar_Admin::create_registrant( $form );
			// var_dump($form);
		} else {
			return $notification;
		}


	}

	public static function gf_capture_data( $notification, $entry, $form ) {
		if ( $notification['name'] === 'Send to Citrix' ) {
			BP_Training_Admin::create_registrant( $form );

		} else {
			return $notification;
		}
	}


}

?>