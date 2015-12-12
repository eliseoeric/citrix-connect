<?php
// namespace Admin;

// use Admin\Admin_Menu;

class Webinar_Menu extends Admin_Menu {



	public function __construct() {
		$this->key = 'citrix-connect-webinar';
		$this->parentKey = 'citrix-connect-config';
		$this->metabox_id = 'citrix-connect-webinar-metabox';
		$this->prefix = '';
		$this->title = __( 'Webinar Connect', 'citrix-connect' );

		add_action( 'admin_page_display_debug_' . $this->key, array( $this, 'admin_page_display_debug' ) );
	}

	public function add_options_page() {
		//Add an options page or a sub options page
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		// $this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
		$this->options_page = add_submenu_page( $this->parentKey, $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		parent::add_options_page();
	}

	public function admin_page_display_debug() {
		//lets test
		$webinar_options = get_option( $this->key );
		$client = new \Citrix\Authentication\Direct( $webinar_options['webinar_api'] );
		$client->auth($webinar_options['webinar_username'], $webinar_options['webinar_password']);
		dd($client);
		if($client->hasErrors()) {
			throw new \Exception( $client->getError() );
		}
		// dd($client->getAccessToken());
		$goToWebinar = new \Citrix\GoToWebinar( $client );
		$webinars = $goToWebinar->getPast();
		

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
			'desc'    => __( 'GoToWebinar Username', 'citrix-connect' ),
			'id'      => $this->prefix . 'webinar_username',
			'type'    => 'text',
		) );
		$cmb->add_field( array(
			'name' => __( 'Password', 'citrix-connect' ),
			'desc'    => __( 'GoToWebinar Password', 'citrix-connect' ),
			'id'      => $this->prefix . 'webinar_password',
			'type'    => 'text_password',
		) );
		$cmb->add_field( array(
			'name'    => __( 'API ID', 'citrix-connect' ),
			'desc'    => __( 'GoToWebinar Developer API ID', 'citrix-connect' ),
			'id'      => $this->prefix . 'webinar_api',
			'type'    => 'text'
		) );
		$cmb->add_field( array(
			'name'    => __( 'Organization ID', 'citrix-connect' ),
			'desc'    => __( 'GoToWebinar Organization ID', 'citrix-connect' ),
			'id'      => $this->prefix . 'webinar_org_id',
			'type'    => 'text'
		) );
	}
}