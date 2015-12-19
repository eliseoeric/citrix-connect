<?php

class Hook_Manager {
	protected $shortcode_dir;
	protected $post_type_dir;
	protected $metabox_dir;
	// protected $widget_dir;

	public function __construct() {
		$this->shortcode_dir = '/shortcodes/';
		$this->post_type_dir = '/post_types/';
		$this->metabox_dir = '/metaboxes/';
		// $this->widget_dir = '/widgets/';

		add_action( 'after_switch_theme', function() {
			flush_rewrite_rules();
		});
	}

	public function register_type( $file, $dir ) {
		if( $file == '' || is_null( $file ) ) {
			return;
		}

		require_once( plugin_dir_path( __FILE__ ) . $dir . $file . '.php' );
	}

	public function register_metaboxes( $metaboxes ) {
		foreach( $metaboxes as $metabox )
		{
			$this->register_type( $metabox, $this->metabox_dir );
			add_filter( 'cmb2_init', $metabox );
		}
	}

	public function register_shortcodes( $shortcodes ) {
		foreach ($shortcodes as $shortcode )
		{
			$this->register_type( $shortcode, $this->shortcode_dir );
		}
	}

	public function register_posts( $post_types ) {
		foreach ( $post_types as $post ) 
		{
			$this->register_type( $post, $this->post_type_dir );
		}
	}
}