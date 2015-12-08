<?php

namespace Citrix\Auth;

class Client {

	private $authentication;

	public function __construct( $authentication = 'Direct' ) {
		$this->setAuthentication( $authentication );
	}

	public function auth() {
		return $this->getAuthentication()->auth();
	}

	public function getAuthentication() {
		return $this->authentication();
	}

	public function setAuthentication( $authentication ) {
		$class = '\\Citrix\\Authentication\\' . $authentication;
		$this->authentication = new $class();
		return $this;
	}
}