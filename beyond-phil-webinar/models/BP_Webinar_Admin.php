<?php

class BP_Webinar_Admin {

	private static $instance;

	private function __construct() {

	}

	public static function resister_admin() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function get_oAuth() {
		$options = get_option( 'bpcw_options' );

		$user      = $options['bpcw_webinar_login'];
		$password  = $options['bpcw_webinar_password'];
		$client_id = $options['bpcw_webinar_client_id'];

		$oauth_url = "https://api.citrixonline.com/oauth/access_token?grant_type=password&user_id={$user}&password={$password}&client_id={$client_id}";

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $oauth_url );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			"Content-type: application/json",
			"Accept: application/json"
		) );

		$output = curl_exec( $ch );
		if ( ! $output ) {
			echo curl_errno( $ch ) . ': ' . curl_error( $ch );
		}
		curl_close( $ch );

		return json_decode( $output, true );
	}

	public static function get_webinars() {
		$options = get_option( 'bpcw_options' );

		$access_token  = "Authorization: OAuth oauth_token=" . $options['bpcw_webinar_access_token'];
		$organizer_key = $options['bpcw_webinar_organizer_id'];

		$url = "https://api.citrixonline.com/G2W/rest/organizers/{$organizer_key}/historicalWebinars?fromTime=2006-04-16T18:00:00Z&toTime=2014-04-26T18:00:00Z";

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			"Content-type: application/json",
			"Accept: application/json",
			$access_token,
		) );

		$output = curl_exec( $ch );
		if ( ! $output ) {
			echo curl_errno( $ch ) . ': ' . curl_error( $ch );
		}
		curl_close( $ch );

		// return $access_token;
		// $webinars = json_decode($output, true);
		// foreach ($webinars as $webinar) {
		// 	echo $webinar['subject'];
		// }
		return json_decode( $output, true );

	}

	public static function get_webinar( $id ) {
		$options = get_option( 'bpcw_options' );

		$access_token  = "Authorization: OAuth oauth_token=" . $options['bpcw_webinar_access_token'];
		$organizer_key = $options['bpcw_webinar_organizer_id'];

		$url = "https://api.citrixonline.com/G2W/rest/organizers/{$organizer_key}/webinars/{$id}";

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			"Content-type: application/json",
			"Accept: application/json",
			$access_token,
		) );
		$output = curl_exec( $ch );
		if ( ! $output ) {
			echo curl_errno( $ch ) . ': ' . curl_error( $ch );
		}
		curl_close( $ch );

		return json_decode( $output, true );

	}

	public static function get_registrants( $webinarKey ) {
		$options = get_option( 'bpcw_options' );

		$access_token  = "Authorization: OAuth oauth_token=" . $options['bpcw_webinar_access_token'];
		$organizer_key = $options['bpcw_webinar_organizer_id'];

		$url = "https://api.citrixonline.com/G2W/rest/organizers/{$organizer_key}/webinars/{$webinarKey}/registrants";

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			"Content-type: application/json",
			"Accept: application/json",
			$access_token,
		) );
		$output = curl_exec( $ch );
		if ( ! $output ) {
			echo curl_errno( $ch ) . ': ' . curl_error( $ch );
		}
		curl_close( $ch );

		return json_decode( $output, true );
	}

	public static function create_registrant( $gf_data ) {


		$options = get_option( 'bpcw_options' );

		$access_token  = "Authorization: OAuth oauth_token=" . $options['bpcw_webinar_access_token'];
		$organizer_key = $options['bpcw_webinar_organizer_id'];

		$url = "https://api.citrixonline.com/G2W/rest/organizers/{$organizer_key}/webinars/{$gf_data[6]}/registrants";

		$ch = curl_init();


		// need to preg replace the spaces with +

		$data = array(
			'firstName'    => $gf_data['10'],
			'lastName'     => $gf_data['11'],
			'email'        => $gf_data[3],
			'address'      => $gf_data[12],
			'city'         => $gf_data[22],
			'state'        => $gf_data[13],
			'zipCode'      => $gf_data[16],
			'country'      => $gf_data[14],
			'phone'        => $gf_data[2],
			'industry'     => $gf_data[15],
			'numEmply'     => $gf_data[17],
			'organization' => $gf_data[8],
			'jobTitle'     => $gf_data[9],


		);

		$output = array();

		$jsonData = json_encode( $data, 128 );

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $jsonData );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			"Content-type: application/json",
			"Accept: application/json",
			$access_token,
		) );
		$output = curl_exec( $ch );
		if ( ! $output ) {
			echo curl_errno( $ch ) . ': ' . curl_error( $ch );
		}
		curl_close( $ch );

		return json_decode( $output, true );
	}
}


?>
