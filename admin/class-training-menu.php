<?php
// namespace Admin;

// use Admin\Admin_Menu;

class Training_Menu extends Admin_Menu {



	public function __construct() {
		$this->key = 'citrix-connect-training';
		$this->parentKey = 'citrix-connect-config';
		$this->metabox_id = 'citrix-connect-training-metabox';
		$this->prefix = '';
		$this->title = __( 'Training Connect', 'citrix-connect' );
	}

	public function add_options_page() {
		//Add an options page or a sub options page
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		// $this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
		$this->options_page = add_submenu_page( $this->parentKey, $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

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
			'name' => __( 'Username', 'citrix-connect' ),
			'desc'    => __( 'GoToTraining Username', 'citrix-connect' ),
			'id'      => $this->prefix . 'training_username',
			'type'    => 'text',
		) );
		$cmb->add_field( array(
			'name' => __( 'Password', 'citrix-connect' ),
			'desc'    => __( 'GoToTraining Password', 'citrix-connect' ),
			'id'      => $this->prefix . 'training_password',
			'type'    => 'text_password',
		) );
		$cmb->add_field( array(
			'name'    => __( 'API ID', 'citrix-connect' ),
			'desc'    => __( 'GoToTraining Developer API ID', 'citrix-connect' ),
			'id'      => $this->prefix . 'training_api',
			'type'    => 'text'
		) );
		$cmb->add_field( array(
			'name'    => __( 'Organization ID', 'citrix-connect' ),
			'desc'    => __( 'GoToTraining Organization ID', 'citrix-connect' ),
			'id'      => $this->prefix . 'training_org_id',
			'type'    => 'text'
		) );
	}
}