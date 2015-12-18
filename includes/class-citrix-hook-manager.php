<?php

class Hook_Manager {
	protected $shortcode_dir;
	protected $post_type_dir;
	// protected $widget_dir;

	public function __construct() {
		$this->shortcode_dir = '/shortcodes/';
		$this->post_type_dir = '/post_types/';
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

	public function register_shortcodes( $shortcodes ) {
		foreach ($shortcodes as $shortcode )
		{
			$this->register_type( $code, $this->shortcode_dir );
		}
	}

	public function register_posts( $post_types ) {
		foreach ( $post_types as $post ) 
		{
			$this->register_type( $type, $this->post_type_dir );
		}
	}
}