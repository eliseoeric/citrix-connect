<?php
/**

	TODO:
	- Use WP_Error to handle errors in this class.
	- Get api creds from the WP Admin Menu.

**/

namespace Citrix\Auth;

use Citrix\ServiceAbstract;
use Citrix\CitrixApiAware;

class Direct extends ServiceAbstract implements CitrixApiAware {

	private $authorizeUrl = 'https://api.citrixonline.com/oauth/access_token';

	private $apiKey;

	private $access_token;

	private $organizerKey;

	public function __construct( $apiKey = null ) {
		$this->setApiKey( $apiKey );
	}

	public function auth( $username, $password ) {
		if( is_null( $this->getApiKey() ) ) {
			$this->addError( 'Direct Authentication requires API Key. Please provide an API key.');
			return $this;
		}

		if( is_null( $username ) || is_null( $password) ) {
			$this->addError( 'Direct Authentication requires an username and password. Please provide an username and password.' );
			return $this;
		}

		$params = array(
			'grant_type' => 'password',
			'user_id' => $username;
			'password' => $password,
			'client_id' => $this->getApiKey()
		);

		$this->setHttpMethod('GET')
			->setUrl( $this->authorizeUrl )
			->setParams( $params )
			->sendRequest()
			->processResponse();

		return $this;
	}

	public function getApiKey() {
		return $this->apiKey;
	}

	public function setApiKey( $apiKey ) {
		$this->apiKey = $apiKey;

		return $this->apiKey;
	}

	public function processResponse() {
		$response = $this->getResponse();

		if( empty($response) ) {
			return $this;
		}

		if( isset( $response[ 'int_err_code' ] ) ) {
			$this->addError( $response[ 'msg' ] );
			return $this; 
		}

		$this->setAccessToken( $response[ 'access_token' ] );
		$this->setOrganizerKey( $response[ 'organizer_key' ] );
		return $this;
	}

	public function getAccessToken() {
		return $this->accessToken;
	}

	public function setAccessToken() {
		$this->accessToken = $accessToken;

		return $this;
	}

	public function getAuthorizeUrl() {
		return $this->authorizeUrl;
	}

	public function setAuthorizeUrl( $authorizeUrl ) {
		$this->authorizeUrl = $authorizeUrl;

		return $this;
	}

	public function getOrganizerKey() {
		return $this->organizerKey;
	}

	public function setOrganizerKey( $organizerKey ) {
		$this->organizerKey = $organizerKey;
		
		return $this;
	}
}