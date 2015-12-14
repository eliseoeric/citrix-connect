<?php 
class BP_Meta_Box_Handler{
	private static $instance;

	private function __construct(){
		add_filter( 'cmb_meta_boxes', array($this, 'register_webinar_metaboxes') );
		// add_filter( 'cmb_meta_boxes', array($this, 'register_training_metaboxes') );
		add_action( 'init', array($this, 'be_initialize_cmb_meta_boxes'), 9999 );
		
		
	}

	public static function init(){
		if(null == self::$instance ){
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function get_staff_list(){
		$staff_posts = get_posts(array('post_type' => 'oxy_staff','posts_per_page'   => -1,));
		$staff_array = array('0'=>'None');
		foreach ($staff_posts as $staff) {
				
				$staff_array[$staff->ID] = $staff->post_title;
			}	
		return $staff_array;
	}

	public function register_webinar_metaboxes($meta_boxes){
		$prefix = '_BPCW_'; // Prefix for all fields
		$staff_array = self::get_staff_list();
	    $meta_boxes['webinar_details'] = array(
	        'id' => 'webinar_details',
	        'title' => 'Webinar Details',
	        'pages' => array('citrix_webinar'), // post type
	        'context' => 'normal',
	        'priority' => 'high',
	        'show_names' => true, // Show field names on the left
	        'fields' => array(
	            array(
	                'name' => 'Subheading',
	                'desc' => 'Subheading for Webinar',
	                'id' => $prefix . 'subheading',
	                'type' => 'text'
	            ),
	            array(
	            	'name' => 'Preamble',
	            	'desc' => 'Featured introduction to the webinar.',
	            	'id' => $prefix . 'preamble',
	            	'type' => 'textarea_small',
	            	),
	            array(
	            	'name' => 'Progam Details',
	            	'desc' => 'Progam details for the webinar.',
	            	'id' => $prefix . 'progam_details',
	            	'type' => 'wysiwyg'
	            	),
	            array(
	            	'name' => 'Learing Objectives',
	            	'desc' => 'What are the learning objectives of this webinar?',
	            	'id' => $prefix . 'learning_objectives',
	            	'type' => 'wysiwyg'
	            	),
	            array(
	            	'name' => 'Registration Details',
	            	'desc' => 'Registration Details for the webinar.',
	            	'id' => $prefix . 'registeration_details',
	            	'type' => 'wysiwyg'
	            	),
	            array(
					'name' => 'Header Image',
					'desc' => 'This is the background image displayed behind the counter/tite',
					'id' => $prefix . 'header_image',
					'type' => 'file',
					'allow' => array( 'url', 'attachment' )
					),
	        ),
	    );
		
		
			

		$meta_boxes['webinar_sidebar_details'] = array(
			'id' => 'webinar_sidebar_details',
			'title' => 'Webinar Siebar Details',
			'pages' => array('citrix_webinar'),
			'context' => 'side',
			'priority' => 'default',
			'show_names' => true,
			'fields' => array(
				array(
					'name' => 'Webinar',
					'desc' => 'The webinar',
					'id' => $prefix . 'webinar_id',
					'type' => 'text',
					),
				array(
				    'name'    => 'Upcoming Post?',
				    'desc'    => 'Is this a featured post?',
				    'id'      => $prefix . 'upcoming',
				    'type'    => 'select',
				    'options' => array(
				        'bp_upcoming_post' => __( 'Upcoming Post', 'cmb' ),
				        'bp_post'   => __( 'Regular Post', 'cmb' ),
				    	),
				    'default' => 'bp_post',
					),
				array(
	            	'name' => 'Price',
	            	'desc' => 'The price of the webinar',
	            	'id' => $prefix . 'price',
	            	'type' => 'text'
	            	),
				array(
					'name' => 'Discount Price',
					'desc' => 'Amount to discount from price',
					'id' => $prefix . 'discount',
					'type' => 'text'
				),
				array(
					'name' => 'PDF File',
					'desc' => 'File used for download link',
					'id' => $prefix . 'pdf_download',
					'type' => 'file',
					'allow' => array( 'url', 'attachment' )
					),
				array(
					'name' => 'Video Link',
					'desc' => 'Link to hosted video of webinar',
					'id' => $prefix . 'video_link',
					'type' => 'text'
					),
				array(
				    'id'          => $prefix . 'speaker_group',
				    'type'        => 'group',
				    'description' => __( 'Key Speakers', 'bpcw' ),
				    'options'     => array(
				        'group_title'   => __( 'Speaker {#}', 'bpcw' ), // since version 1.1.4, {#} gets replaced by row number
				        'add_button'    => __( 'Add Another Speaker', 'bpcw' ),
				        'remove_button' => __( 'Remove Speaker', 'bpcw' ),
				        'sortable'      => true, // beta
					    ),
					    // Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
					    'fields'      => array(
					        array(
					            'name'    => 'Speaker',
							    'desc'    => 'Select a speaker',
							    'id'      => 'speaker',
							    'type'    => 'select',
							    'options' => $staff_array,
							    'default' => '0'
					            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
					        ),
					    ),
					),
				array(
				    'name'    => 'Key Presenter',
				    'desc'    => 'Select a speaker',
				    'id'      => $prefix . 'key_presenter',
				    'type'    => 'select',
				    'options' => $staff_array,
				    'default' => '0',
					),
				array(
				    'name'    => 'Secondary Presenter',
				    'desc'    => 'Select a speaker',
				    'id'      => $prefix . 'second_presenter',
				    'type'    => 'select',
				    'options' => $staff_array,
				    'default' => '0',
					),
				),
			);
		
		 $meta_boxes['training_details'] = array(
	        'id' => 'training_details',
	        'title' => 'Course Details',
	        'pages' => array('citrix_training'), // post type
	        'context' => 'normal',
	        'priority' => 'high',
	        'show_names' => true, // Show field names on the left
	        'fields' => array(
	            array(
	                'name' => 'Subheading',
	                'desc' => 'Subheading for course',
	                'id' => $prefix . 'subheading',
	                'type' => 'text'
	            ),
	            array(
	            	'name' => 'Preamble',
	            	'desc' => 'Featured introduction to the course.',
	            	'id' => $prefix . 'preamble',
	            	'type' => 'textarea_small',
	            	),
	            array(
	            	'name' => 'Course Overview',
	            	'desc' => 'Overview tab for the course.',
	            	'id' => $prefix . 'course_overview',
	            	'type' => 'wysiwyg'
	            	),
	            array(
	            	'name' => 'What You Will Learn',
	            	'desc' => 'What are the learning objectives of this course?',
	            	'id' => $prefix . 'learning_objectives',
	            	'type' => 'wysiwyg'
	            	),
	            array(
	            	'name' => 'Who Should Participate',
	            	'desc' => 'Tab describing who this course is aim towards.',
	            	'id' => $prefix . 'participate_tab',
	            	'type' => 'wysiwyg'
	            	),
	            array(
					'name' => 'Header Image',
					'desc' => 'This is the background image displayed behind the counter/tite',
					'id' => $prefix . 'header_image',
					'type' => 'file',
					'allow' => array( 'url', 'attachment' )
					),
	            array(
					'name' => 'Testimonial',
					'desc' => 'Testimonial Quote for the Course. Please follow format "Quote" - Author',
					'id' => $prefix . 'testimonial',
					'type' => 'text',
					),
	            array(
					'name' => 'Table Description',
					'desc' => 'ex: "Foundation CEM Certification Agenda"',
					'id' => $prefix . 'table_description',
					'type' => 'text',
					),
	            array(
				    'id'          => $prefix . 'date_table',
				    'type'        => 'group',
				    'description' => 'Course Dates',
				    'options'     => array(
				        'group_title'   => __( 'Date {#}', 'bpcw' ), // since version 1.1.4, {#} gets replaced by row number
				        'add_button'    => __( 'Add Another Date', 'bpcw' ),
				        'remove_button' => __( 'Remove Date', 'bpcw' ),
				        'sortable'      => true, // beta
					    ),
					    // Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
					    'fields'      => array(
					        array(
					            'name'    => 'CEM Agenda',
							    'desc'    => 'Foundation CEM Cert Agenda',
							    'id'      => 'agenda_field',
							    'type'    => 'text',
					        ),
					         array(
					            'name'    => 'Duration',
							    'desc'    => 'Date Duration',
							    'id'      => 'duration_field',
							    'type'    => 'text',
					        ),
					          array(
					            'name'    => 'Date',
							    'desc'    => 'Select Date',
							    'id'      => 'date_field',
							    'type'    => 'text_date',
					        ),
					    ),
					),

	        ),
	    );
		
		
			

		$meta_boxes['training_sidebar_details'] = array(
			'id' => 'training_sidebar_details',
			'title' => 'Course Siebar Details',
			'pages' => array('citrix_training'),
			'context' => 'side',
			'priority' => 'default',
			'show_names' => true,
			'fields' => array(
				array(
					'name' => 'Course',
					'desc' => 'The GoToTraining ID',
					'id' => $prefix . 'training_id',
					'type' => 'text',
					),
				array(
	            	'name' => 'Price',
	            	'desc' => 'The price of the course',
	            	'id' => $prefix . 'price',
	            	'type' => 'text'
	            	),
				array(
					'name' => 'Discount Price',
					'desc' => 'Amount to discount from price',
					'id' => $prefix . 'discount',
					'type' => 'text'
				),
				array(
				    'name'    => 'Upcoming Post?',
				    'desc'    => 'Is this a featured post?',
				    'id'      => $prefix . 'upcoming',
				    'type'    => 'select',
				    'options' => array(
				        'bp_upcoming_post' => __( 'Upcoming Post', 'cmb' ),
				        'bp_post'   => __( 'Regular Post', 'cmb' ),
				    	),
				    'default' => 'bp_post',
					),
				array(
				    'id'          => $prefix . 'speaker_group',
				    'type'        => 'group',
				    'description' => __( 'Key Speakers', 'bpcw' ),
				    'options'     => array(
				        'group_title'   => __( 'Speaker {#}', 'bpcw' ), // since version 1.1.4, {#} gets replaced by row number
				        'add_button'    => __( 'Add Another Speaker', 'bpcw' ),
				        'remove_button' => __( 'Remove Speaker', 'bpcw' ),
				        'sortable'      => true, // beta
					    ),
					    // Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
					    'fields'      => array(
					        array(
					            'name'    => 'Speaker',
							    'desc'    => 'Select a speaker',
							    'id'      => 'speaker',
							    'type'    => 'select',
							    'options' => $staff_array,
							    'default' => '0'
					            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
					        ),
					    ),
					),
				array(
				    'name' => 'Promo Image',
				    'desc' => 'Upload an image or enter an URL (if different than default).',
				    'id' => $prefix . 'promo_image',
				    'type' => 'file',
				    'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
					),
				),
			);
		

	    return $meta_boxes;
		
	}

	

	public function be_initialize_cmb_meta_boxes() {
	    if ( !class_exists( 'cmb_Meta_Box' ) ) {
	        include_once( BPCW_DIR . '/library/CMB/init.php' );
    	}
	}
	
}

 ?>