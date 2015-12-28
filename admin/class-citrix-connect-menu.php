<?php
//namespace Admin;

//use Admin\Admin_Menu;
/**

	TODO:
	- Make this a splash page
	- Second todo item

**/

class Citrix_Connect_Menu extends Admin_Menu {

	public function __construct() {
		$this->key = 'citrix-connect-config';
		$this->metabox_id = 'citrix-connect-config-metabox';
		$this->prefix = '_cc_';
		$this->title = __( 'Citrix Connect', 'textdomain' );

		add_action( 'admin_page_display_debug_' . $this->key, array( $this, 'admin_page_display_debug' ) );
	}

	public function add_options_page() {
		//Add an options page or a sub options page
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		parent::add_options_page();
	}

	public function add_options_page_metabox() {
		// hook in the save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

	}

	public function admin_page_display_debug() {
		?>
		<h2>Citrix Connect WordPress Plugin</h2>
		<p>Thank you for using the Citrix Connect Wordpress Plugin.</p>
		<?php
	}
}