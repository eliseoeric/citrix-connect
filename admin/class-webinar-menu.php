<?php
// namespace Admin;
// use Admin\Admin_Menu;
// use Admin\Citrix\WebinarClient;

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

	// Please note that this debug does not cache the retrieved data.
	public function admin_page_display_debug() {
		wp_enqueue_script( 'datatables' );
		wp_enqueue_style( 'datatables' );
		wp_enqueue_script( 'cc_datatables' );
		$webinar = new WebinarClient();
		$upcomming = $webinar->getUpcomming();

		if( empty( $upcomming ) ) {
			$message = "<p class='error'>There are no upcomming webinars at the moment.</p>";
		} else {
			$message = "<p>Below is a list of the upcoming webinars, to ensure that Citrix Connect WP is connecting to GoToWebinar.</p>";
		}

		echo "<h2>Webinar Debug</h2>";
		echo $message;

		if( count( $upcomming ) > 0 )
		{
			echo "<div class='upcomming-data-table'>";
			echo '<table data-order=\'[[ 3, "desc" ]]\'>';
			echo '<thead>';
			echo '<tr>';
			echo '<th>ID</th>';
			echo '<th>Title</th>';
			echo '<th>Start Time</th>';
			echo '<th>End Time</th>';
			echo '<th>URL</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			foreach ( $upcomming as $webinar )
			{
//				dd($webinar);
				$start = date('Y-m-d', strtotime($webinar->times[0]['startTime']));
				$end = date('Y-m-d', strtotime($webinar->times[0]['endTime']));

				echo '<tr>';
				echo '<td>' . $webinar->id . '</td>';
				echo '<td>' . $webinar->subject . '</td>';
				echo '<td>' . $start . '</td>';
				echo '<td>' . $end . '</td>';
				echo '<td><a href="' . $webinar->registrationUrl . '">Registration Url</a></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}
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