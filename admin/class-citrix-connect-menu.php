<?php
// namespace Admin;

// use Admin\Admin_Menu;
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
	}

	public function add_options_page() {
		//Add an options page or a sub options page
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		parent::add_options_page();
	}

	public function add_options_page_metabox() {
		// hook in the save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );
		$cmb = new_cmb2_box( array(
			'id' 			=> $this->metabox_id,
			'hookup' 		=> false,
			'cmb_styles' 	=> false,
			'show_on' 		=> array(
				// These are important, don't remove
				'key' => 'options-page',
				'value' =>	array( $this->key, )
			),
		) );
		// Set our CMB2 fields
		$cmb->add_field( array(
			'name' => __( 'Logo', 'textdomain' ),
			'desc'    => __( 'Upload an image or enter a URL', 'textdomain' ),
			'id'      => $this->prefix . 'logo',
			'type'    => 'file',
			// Optional:
			'options' => array(
				'url' => false, // Hide the text input for the url
				'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
			),
		) );
		$cmb->add_field( array(
			'name' => __( 'Frontpage Video URL', 'textdomain' ),
			'id'   => $this->prefix . 'video_url',
			'type' => 'text_url',
		) );
		$cmb->add_field( array(
			'name'    => __( 'GA ID', 'textdomain' ),
			'desc'    => __( 'Account ID for Google Analytics', 'textdomain' ),
			'id'      => $this->prefix . 'gaid',
			'type'    => 'text_small'
		) );
		$cmb->add_field( array(
			'name'    => __( 'Open Weather API Key', 'textdomain' ),
			'desc'    => __( 'API key used for Open Weather', 'textdomain' ),
			'id'      => $this->prefix . 'open_weather_api_key',
			'type'    => 'text'
		) );
	}
}