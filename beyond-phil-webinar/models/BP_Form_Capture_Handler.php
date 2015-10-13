<?php

/*
* Class that handles the posting of Gravity Forms to
* Click Dimensions Form Capture. Please ensure that you
* have created a form capture previously, within the Click
* Dimensions platform. For more info see http://help.clickdimensions.com/integrating-forms-using-form-capture/
*
*
*/

class BP_Form_Capture_Handler {

	//Intial class setup.
	private static $instance;

	private function __construct() {
		$this->register_hooks();

	}

	//Initialize the class
	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	// Here we are registering the action hooks for the form capture.
	// 'gform_post_submissions_x' corresponds to a specific Gravity Form, wher x is the form ID
	// array($this, 'your_custom_form_capture') corresponds to a form capturee function within this class.
	public function register_hooks() {
		add_action( 'gform_post_submission_1', array( $this, 'contact_form_to_dynamics' ), 10, 2 );
		add_action( 'gform_post_submission_2', array( $this, 'newsletter_to_dynamics' ), 10, 2 );
		add_action( 'gform_post_submission_4', array( $this, 'training_reg_to_dynamics' ), 10, 2 );
		add_action( 'gform_post_submission_3', array( $this, 'webinar_reg_to_dynamics' ), 10, 2 );
	}


	// Main Contact Form Capture. 
	public function contact_form_to_dynamics( $entry, $form ) {

		$uuid = $_COOKIE['cuvid']; // The Cookie view id. This is required.

		// Each key value pair below is the mapping of the form data to 
		// an input field that Click Dimensions is listening for.  For 
		// consistency, please ensure that key is equal to the respective
		// Gravity Forms input id.
		$data = array(
			"input_1_21"     => $entry['21'], //first name
			"input_1_22"     => $entry['22'], //last name
			'input_1_2'     => $entry['2'], //email address
			'input_1_4'     => $entry['4'], //company
			'input_1_13'    => $entry['13'], //job title
			'input_1_8'     => $entry['8'], //reason
			'input_1_10'     => $entry['10'], //location
			//'input_1_20'     => $entry['20'], state
			'input_1_19'     => $entry['19'], //phone
			'input_1_16'     => $entry['16'], //industry
			'input_1_17'     => $entry['17'], //org size
			'input_1_6'     => $entry['6'], //message
            'input_1_23'    => $entry['23'], //heard from
			'cd_visitorkey' => $uuid,
		);
  
         //sets state to a string and not null
          if ($data['input_1_10'] != 'United States') {
             $data['input_1_20'] = '--';
          } else {
             $data['input_1_20'] = $entry['20'];
          }


		// Use Wordpress to post the data remotely to the Click Dimensions Action URL that is provided.
		$res = wp_remote_post( 'http://analytics.clickdimensions.com/forms/h/a7SLCtSfte0a4I9nzBXCg', array( 'body' => $data ) );

	}

	// Newsletter in Footer Form Capture.
	public function newsletter_to_dynamics( $entry, $form ) {

		// Each key value pair below is the mapping of the form data to 
		// an input field that Click Dimensions is listening for.  For 
		// consistency, please ensure that key is equal to the respective
		// Gravity Forms input id.
		$uuid = $_COOKIE['cuvid'];

		$data = array(
			"input_1.3"     => $entry['1.3'], //first name
			'input_1.6'     => $entry['1.6'], //last name
			'input_2_2'     => $entry['2'], //email address
			'cd_visitorkey' => $uuid,
		);
		// Use Wordpress to post the data remotely to the Click Dimensions Action URL that is provided.
		$res = wp_remote_post( 'http://analytics.clickdimensions.com/forms/h/a91wWE4zjDE6OoNn7PRsJg', array( 'body' => $data ) );

	}

	public function training_reg_to_dynamics( $entry, $form ) {

		// Each key value pair below is the mapping of the form data to 
		// an input field that Click Dimensions is listening for.  For 
		// consistency, please ensure that key is equal to the respective
		// Gravity Forms input id.
		$uuid = $_COOKIE['cuvid'];

		$training = BP_Training_Admin::get_training( $entry['9'] );

		$data = array(
			"input_4_10"    => $entry['10'], //first name  1
			'input_4_11'    => $entry['11'], //last name 1
			'input_4_4'     => $entry['4'], //email address 1
			'input_4_3'     => $entry['3'], //phone number 1
			'input_4_12'    => $entry['12'], //street address  1
			'input_4_13'    => $entry['13'], //city  1
			'input_4_9'     => $entry['9'], //course id  1
			'input_4_14'    => $entry['14'], //state  1
			'input_4_15'    => $entry['15'], //zip code 1
			'input_4_16'    => $entry['16'], //Country 1
			'input_4_17'    => $entry['17'], //job title 1
			'input_4_18'    => $entry['18'], //Lead company 1
			'input_4_20'    => $entry['20'], // how did you hear about us 1
			'input_4_23'    => $entry['23'], //discount code 1
            'input_4_25'    => $entry['25'], //Industry
            'input_4_26'    => $entry['26'], //Organization size
            'input_4_66'    => 'Training - '.$training['name'], //training title
			'cd_visitorkey' => $uuid,
		);

          //sets state to a string and not null
          if ($data['input_4_16'] != 'United States') {
             $data['input_4_14'] = '--';
          } else {
             $data['input_4_14'] = $entry['14'];
          }
          
		// Use Wordpress to post the data remotely to the Click Dimensions Action URL that is provided.
		$res = wp_remote_post( 'http://analytics.clickdimensions.com/forms/h/agVYEhpFaMkS0M86WqkwEX', array( 'body' => $data ) );
	}


	public function webinar_reg_to_dynamics( $entry, $form ) {

		// Each key value pair below is the mapping of the form data to 
		// an input field that Click Dimensions is listening for.  For 
		// consistency, please ensure that key is equal to the respective
		// Gravity Forms input id.
		$uuid = $_COOKIE['cuvid'];

		$webinar = BP_Webinar_Admin::get_webinar( $entry['6'] );

		$data = array(
			"input_3_10"    => $entry['10'], //first name
			'input_3_11'    => $entry['11'], //last name
			'input_3_2'     => $entry['2'], //phone number
			'input_3_3'     => $entry['3'], // email address
			'input_3_8'     => $entry['8'],  //Company
			'input_3_12'    => $entry['12'], //steet address
			'input_3_9'     => $entry['9'], //Job title
			'input_3_22'    => $entry['22'],  //City
			'input_3_16'    => $entry['16'], //zip code
			//'input_3_13'    => $entry['13'],  //state
			'input_3_14'    => $entry['14'],  //country
			'input_3_15'    => $entry['15'],  //industry
			'input_3_17'    => $entry['17'],  //Organization Size
			'input_3_6'     => $entry['6'],   //webinar_id
			'input_3_24'    => $entry['24'],   //discount code
            'input_3_25'    => $entry['25'], //How did you hear about us?
			'input_3_66'    => 'Webinar - '.$webinar['subject'], //Webinar title
			'cd_visitorkey' => $uuid,
		);
  
                   //sets state to a string and not null
                   if ($data['input_3_14'] != 'United States') {
                      $data['input_3_13'] = '--';
                   } else {
                      $data['input_3_13'] = $entry['13'];
                   }
  
		// Use Wordpress to post the data remotely to the Click Dimensions Action URL that is provided.
		$res = wp_remote_post( 'http://analytics.clickdimensions.com/forms/h/al6JcLNp590Sx39I6FuHhQ', array( 'body' => $data ) );
	}
}
