<?php
//namespace Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://eliseoeric.com
 * @since      1.0.0
 *
 * @package    Citrix_Connect
 * @subpackage Citrix_Connect/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Citrix_Connect
 * @subpackage Citrix_Connect/admin
 * @author     Eric Eliseo <eric.eliseo@gmail.com>
 */
class Citrix_Connect_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Citrix_Connect_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Citrix_Connect_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/citrix-connect-admin.css', array(), $this->version, 'all' );
		wp_register_style( 'datatables', '//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Citrix_Connect_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Citrix_Connect_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/citrix-connect-admin.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'datatables', '//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js', array( 'jquery' ), '1.0', true );
		wp_register_script( 'cc_datatables', plugin_dir_url( __FILE__ ) . 'js/citrix-connect-datatables.js', array(), '1.0', true );

	}

	public function cmb2_render_callback_for_text_password( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		echo $field_type_object->input( array( 'type' => 'password' ) );
	}

	public function cmb2_sanitize_text_password_callback( $override_value, $value ) {
		return $value;
	}

}
