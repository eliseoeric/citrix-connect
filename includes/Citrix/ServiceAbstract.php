<?php
/**

	TODO:
	- Reformat the sendRequest method to use the wp_remote_get API
	- Second todo item

**/

namespace Citrix;

abstract class ServiceAbstract {
	private $errors = array();

	private $params = array();

	private $url;

	private $response;

	private $httpMethod = 'POST';

	public function sendRequest( $oauthToken = null ) {
		$url = $this->getUrl();
		$ch = curl_init();

		if( $this->getHttpMethod() == 'POST' ) {
			curl_setopt($ch, CURLOPT_POST, true); //tell curl you want to post something
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $this->getParams())); //define what you want to post
		} else {
			$url = $this->getUrl();
			$query = http_build_query( $this->getParams() );
			$url = $url . '?' . $query;
		}

		if( ! is_null( $oauthToken ) ) {
			$headers = array( 
				'Content-Type: application/json',
				'Accpet: application/json',
				'Authorization: OAuth oauth_token=' . $oauth_token
			);
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //return the output in string format
		$output = curl_exec($ch); //execute
		curl_close($ch); //close the curl handle

		$this->setResponse( $output );
		return $this;
	}

	public function hasErrors() {
		return empty( $this->errors ) ? false : true;
	}

	public function getError() {
		$error = $this->errors;
		$this->reset();
		return empty( $error ) ? false : true;
	}

	public function getErrors() {
		$error = $this->errors;
		$this->reset();
		return $error;
	}

	public function addError( $message ) {
		$this->errors[] = $message;

		return $this;
	}

	public function reset() {
		$this->errors = array();

		return $this;
	}

	public function getParams() {
		return $this->params;
	}

	public function setParams( $params ) {
		$this->params = $params;

		return $this;
	}

	public function addParam( $key, $value ) {
		$this->params[ $key ] = $value;

		return $this;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl( $url ) {
		$this->url = $url;

		return $this;
	}

	public function getResponse() {
		return $this->response;
	} 

	public function setResponse( $response ) {
		if( is_object( $response ) ) {
			$this->response = $response;
			return $this;
		}

		$this->response = (array) json_decode( $response, true, 512, JSON_BIGINT_AS_STRING );
		return $this;
	}

	public function getHttpMethod() {
		return $this->httpMethod;
	}

	public function setHttpMethod( $httpMethod ) {
		$this->httpMethod = $httpMethod;

		return $this;
	}
}