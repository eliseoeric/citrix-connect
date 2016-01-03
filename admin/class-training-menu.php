<?php
//namespace Admin;
//use Admin\Admin_Menu;

class Training_Menu extends Admin_Menu {

	public function __construct() {
		$this->key = 'citrix-connect-training';
		$this->parentKey = 'citrix-connect-config';
		$this->metabox_id = 'citrix-connect-training-metabox';
		$this->prefix = '';
		$this->title = __( 'Training Connect', 'citrix-connect' );

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

	public function get_gravity_forms() {
		$forms = RGFormsModel::get_forms( null, 'title' );
		$form_array = array();
		foreach( $forms as $form ) {
			$form_array[$form->id] = $form->title;
		}

		return $form_array;
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

		$gravity_forms = '/gravityforms/gravityforms.php';

		if( is_plugin_active( $gravity_forms ) ) {
			$cmb->add_field( array(
				'name' => __( 'Gravity Form Registration ID', 'citrix-connect' ),
				'desc' => __( 'Indicate which form is used for GoToTraining Registration.', 'citrix-connect' ),
				'id' => $this->prefix . 'gform_training_reg_id',
				'type' => 'select',
				'show_option_none' => true,
				'options' => $this->get_gravity_forms()
			));

			$cmb->add_field( array(
				'name'    => __( 'Registration Error Message', 'citrix-connect' ),
				'desc'    => __( 'The error message displayed to users should the Citrix API registration fail.', 'citrix-connect' ),
				'id'      => $this->prefix . 'training_error',
				'type'    => 'wysiwyg'
			) );
		}


	}


	// Please note that this debug does not cache the retrieved data.
	public function admin_page_display_debug() {
		wp_enqueue_script( 'datatables' );
		wp_enqueue_style( 'datatables' );
		wp_enqueue_script( 'cc_datatables' );
		$training = new TrainingClient();
		$trainings = $training->getTrainings();

		if( empty( $trainings ) ) {
			$message = "<p class='error'>There are no upcomming webinars at the moment.</p>";
		} else {
			$message = "<p>Below is a list of the upcoming webinars, to ensure that Citrix Connect WP is connecting to GoToWebinar.</p>";
		}

		echo "<h2>Training Debug</h2>";
		echo $message;

		if( count( $trainings ) > 0 )
		{
			echo "<div class='upcomming-data-table'>";
			echo '<table data-order=\'[[ 3, "desc" ]]\'>';
			echo '<thead>';
			echo '<tr>';
			echo '<th>ID</th>';
			echo '<th>Title</th>';
			echo '<th>Start Time</th>';
			echo '<th>End Time</th>';
			echo '<th>Organizers</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			foreach ( $trainings as $training )
			{
				// dd($training);
				$start = date('Y-m-d', strtotime($training->times[0]['startDate']));
				$end = date('Y-m-d', strtotime($training->times[0]['endDate']));

				$organizers_parsed = "";
				foreach ($training->organizers as $organizer) {
					$organizers_parsed .= "<a href='mailto:" . $organizer->email . "'>" .$organizer['givenName'] . ' ' . $organizer['surname'] . "</a> ";
				}

				foreach( $training->times as $session ) 
				{
					echo '<tr>';
					echo '<td>' . $training->id . '</td>';
					echo '<td>' . $training->name . '</td>';
					echo '<td>' . date('Y-m-d', strtotime($session['startDate'])) . '</td>';
					echo '<td>' . date('Y-m-d', strtotime($session['endDate'])) . '</td>';
					echo '<td>' . $organizers_parsed . '</td>';
					echo '</tr>';
				}
				
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}
	}
}