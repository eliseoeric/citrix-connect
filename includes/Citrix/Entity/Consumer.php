<?php
/**

	TODO:
	- Getter and Setters for the properites
	- Comment out the code as well -- best done in phpStorm

**/

namespace Citrix\Entity;

class Consumer extends EntityAbstract implements EntityAware {

	public $id;

	public $firstName;

	public $lastName;

	public $email;

	public $status;

	public $registrationDate;

	public $joinUrl;

	public $timeZone = 'America/New_York';

	public function __construct( $client ) {
		$this->setClient( $client );
	}

	public function populate() {
		$data = $this->getData();

		$this->firstName = $data[ 'firstName' ];
		$this->lastName = $data[ 'lastName' ];
		$this->email = $data[ 'email' ];

		if( isset( $data[ 'registrantKey' ] ) ) {
			$this->id = $data[ 'registrantKey' ];
		}

		if( isset( $data[ 'status' ] ) ) {
			$this->status = $data[ 'status' ]; 
		}

		if( isset( $data[ 'registrationDate' ] ) ) {
			$this->registrationDate = $data[ 'registrationDate' ]; 
		}

		if( isset( $data[ 'joinUrl' ] ) ) {
			$this->joinUrl = $data[ 'joinUrl' ]; 
		}

		if( isset( $data[ 'timeZone' ] ) ) {
			$this->timeZone = $data[ 'timeZone' ]; 
		}
	}
}