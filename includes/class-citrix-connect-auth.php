<?php
/**

	TODO:
	- Need to set up Exception or Error handling
	- Comment the code properly
	- pull in the $client_id, $password and $user_id from get_options() or pass to constructor

**/

class Citrix_Connect_Auth {
	
	protected $client_id;
	protected $password;
	protected $user_id;
	protected $access;

	public function __construct() {
		$this->client_id = '';
		$this->user_id = '';
		$this->password = '';

		$this->access_field = 'citrix_access';

		$this->set_access();
	}

	public function set_access() {
		$access = $this->get_access();
		$this->access = $access;

	}

	public function get_access() {

		$access = get_transient( $this->access_field );

		if( empty( $access ) ) {
			$access = $this->request_access();
			$this->set_access_transient( $access );
		} 

		return $access;
	}

	public function set_access_transient( $access ) {
		set_transient ($this->access_field, $access, DAY_IN_SECONDS );

	}

	public function request_data( $url, $args, $array() ) {
		$defaults = array(
			'httpversion' => '1.1',
			'headers' => array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'Authorization' => 'OAuth oauth_token=' . $this->access[ 'access_token' ];
			)
		);

		$args = wp_parse_args( $args, $defaults );
		$http_request = wp_remote_get( $url, $args );
		$body = json_decode( $http_request[ 'body' ], true );

		if( ! empty( $body[ 'int_err_code' ] ) ) {
			$this->get_access();
			$this->request_data( $url, $args );
		} else {
			return $body;
		}
	}

	public function request_access() {
		$url = 'https://api.citrixonline.com/oauth/access_token?grant_type=password&user_id=' . $this->user_id . '&password=' . $this->password . '&client_id=' . $this->client_id;
        $args = array(
        	'headers' => array(
        		'Accept' => 'application/json',
        		'Content-Type' => 'application/json'
    		)
    	);

    	$result = wp_remote_get( $url, $args );

    	return $json_decode( $result[ 'body' ], true );
	}
}