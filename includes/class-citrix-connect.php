<?php
/**

	TODO:
	- Need to figure out a better way to either autoload or something the dependencies 
	- Also, need to consolidate or improve how we are hooking the admin menus

**/

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://eliseoeric.com
 * @since      1.0.0
 *
 * @package    Citrix_Connect
 * @subpackage Citrix_Connect/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Citrix_Connect
 * @subpackage Citrix_Connect/includes
 * @author     Eric Eliseo <eric.eliseo@gmail.com>
 */
class Citrix_Connect {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Citrix_Connect_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'citrix-connect';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Citrix_Connect_Loader. Orchestrates the hooks of the plugin.
	 * - Citrix_Connect_i18n. Defines internationalization functionality.
	 * - Citrix_Connect_Admin. Defines all hooks for the admin area.
	 * - Citrix_Connect_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-citrix-connect-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-citrix-connect-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-citrix-connect-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-citrix-connect-public.php';

		/**
		*
		* Load in the CMB2 library for custom metaboxes and admin menu functionality.
		**/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/vendor/CMB2/init.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/abstract-admin-menu.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-citrix-connect-menu.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-webinar-menu.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-training-menu.php';

		$this->loader = new Citrix_Connect_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Citrix_Connect_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Citrix_Connect_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Citrix_Connect_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'cmb2_render_text_password', $plugin_admin, 'cmb2_render_callback_for_text_password', 10, 5);
		$this->loader->add_action( 'cmb2_sanitize_text_email', $plugin_admin, 'cmb2_sanitize_text_email_callback', 10, 2);

		$citrix_connect_menu = new Citrix_Connect_Menu();

		$this->loader->add_action( 'admin_init', $citrix_connect_menu, 'init' );
		$this->loader->add_action( 'admin_menu', $citrix_connect_menu, 'add_options_page' );
		$this->loader->add_action( 'cmb2_admin_init', $citrix_connect_menu, 'add_options_page_metabox' );

		//Yeah this needs to be cleaned up, we can come up with some sort of autogen function or handler for this.

		$webinar_menu = new Webinar_Menu();

		$this->loader->add_action( 'admin_init', $webinar_menu, 'init' );
		$this->loader->add_action( 'admin_menu', $webinar_menu, 'add_options_page' );
		$this->loader->add_action( 'cmb2_admin_init', $webinar_menu, 'add_options_page_metabox' );

		$training_menu = new Training_Menu();

		$this->loader->add_action( 'admin_init', $training_menu, 'init' );
		$this->loader->add_action( 'admin_menu', $training_menu, 'add_options_page' );
		$this->loader->add_action( 'cmb2_admin_init', $training_menu, 'add_options_page_metabox' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Citrix_Connect_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Citrix_Connect_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
