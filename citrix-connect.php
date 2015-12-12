<?php
/**

	TODO:
	- Figure out an elegant solutions for autolaoding everythign
	- Second todo item

**/

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://eliseoeric.com
 * @since             1.0.0
 * @package           Citrix_Connect
 *
 * @wordpress-plugin
 * Plugin Name:       Citrix Connect
 * Plugin URI:        http://#
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Eric Eliseo
 * Author URI:        http://eliseoeric.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       citrix-connect
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-citrix-connect-activator.php
 */
function activate_citrix_connect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-citrix-connect-activator.php';
	Citrix_Connect_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-citrix-connect-deactivator.php
 */
function deactivate_citrix_connect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-citrix-connect-deactivator.php';
	Citrix_Connect_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_citrix_connect' );
register_deactivation_hook( __FILE__, 'deactivate_citrix_connect' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-citrix-connect.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_citrix_connect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-autoloader.php';
	// instantiate the loader
	// dd(plugin_dir_path( __FILE__ ) . 'includes/Citrix');
	$loader = new Psr4AutoloaderClass();
	// register the autoloader
	$loader->register();

	// register the base directories for the namespace prefix
	$loader->addNamespace('Citrix', plugin_dir_path( __FILE__ ) . 'includes/Citrix' );
	// $loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/tests');
	$plugin = new Citrix_Connect();
	$plugin->run();

}
run_citrix_connect();

function dd( $var ) {
	echo "<pre>";
	var_dump( $var );
	echo "</pre>";
}

function list_classes(){
	dd(get_declared_classes());
}
// list_classes();