<?php
/**

	TODO:
	- Getter and Setter for prefix
	- Add a text domain, and use it in this class

**/
//namespace Admin;

abstract class Admin_Menu {

	protected $key = ''; //slug

	protected $parentKey = ''; //optional

	protected $metabox_id = '';

	protected $title = '';

	protected $options_page = '';

	protected $prefix = '';

	public function init() {
		register_setting( $this->key, $this->key );
	}

	public function add_options_page() {
		//included CMB CSS in the head to vaoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	public function admin_page_display() {
		?>
		<div class='wrap cmb2-options-page <?= $this->key ?>'>
			<h2> <?php //esc_html( get_admin_page_title() ) ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
			<?php // add a hook for debug feedback. ?>
			<?php do_action( 'admin_page_display_debug_' . $this->key ); ?>
		</div>
		<?php
	}

	abstract protected function add_options_page_metabox();

	public function settings_notices( $object_id, $updated ) {
		if( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'textdomain' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	public function __get( $field ) {
		if( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}





	// public function getKey() {
	// 	return $this->key;
	// }

	// public function setKey( $key ) {
	// 	$this->key = $key;

	// 	return $this;
	// }

	// public function getoptions_page() {
	// 	return $this->options_page;
	// }

	// public function setoptions_page( $options_page ) {
	// 	$this->options_page = $options_page;

	// 	return $this;
	// }

	// public function getmetabox_id() {
	// 	return $this->metabox_id;
	// }

	// public function setmetabox_id( $metabox_id ) {
	// 	$this->metabox_id = $metabox_id;

	// 	return $this;
	// }

	// public function getTitle() {
	// 	return $this->title;
	// }

	// public function setTitle( $title ) {
	// 	$this->title = $title;

	// 	return $this;
	// }

}