<?php

namespace Citrix;

use Citrix\Auth\Authentication;
use Citrix\Entity\Webinar;
use Citrix\Entity\Consumer;

class GoToWebinar extends ServicesAbstract implements CitrixApiAware {
	
	private $client;


	public function __construct( $client ) {
		$this->setClient( $client ); 
	}

	public function getUpcoming() {
		$url = 'https://api.citrixonline.com/G2W/rest/organizers/' . $this->getClient()->getOrganizerKey() . '/upcomingWebinars';
		$this->setHttpMethod( 'GET' )
			->setUrl( $url )
			->sendRequest( $this->getClient()->getAccessToken() )
			->processResponse();

		return $this->getResponse();
	}

	public function getWebinars() {
		$url = 'https://api.citrixonline.com/G2W/rest/organizers/' . $this->getClient()->getOrganizerKey() . '/webinars';
		$this->setHttpMethod( 'GET' )
			->setUrl( $url )
			->sendRequest( $this->getClient()->getAccessToken() )
			->processResponse();

		return $this->getResponse();
	}

	public function getPastWebinars() {
		$this->getPast();
	}

	public function getPast() {
		$since = date(DATE_ISO8601, mktime(0, 0, 0, 7, 1, 2000));
    	$until = date(DATE_ISO8601);
    	$url = 'https://api.citrixonline.com/G2W/rest/organizers/' . $this->getClient()->getOrganizerKey() . '/historicalWebinars';

    	$this->setHttpMethod( 'GET' )
    		->setParams( ['fromTime' => $since, 'toTime' => $until ])
			->setUrl( $url )
			->sendRequest( $this->getClient()->getAccessToken() )
			->processResponse();

		return $this->getResponse();
	}

	public function getWebinar( $webinarKey ) {
		$url = 'https://api.citrixonline.com/G2W/rest/organizers/' . $this->getClient()->getOrganizerKey() . '/webinars/' . $webinarKey;
		$this->setHttpMethod( 'GET' )
			->setUrl( $url )
			->sendRequest( $this->getClient()->getAccessToken() )
			->processResponse( true );

		return $this->getResponse();
	}

	public function createWebinar( $params ) {
		$url = 'https://api.citrixonline.com/G2W/rest/organizers/' . $this->getClient()->getOrganizerKey() . '/webinars';
		$this->setHttpMethod( 'POST' ) // pretty sure you can use use setHttpMethod(), as POST is the default case here.
			->setParams( $params )
			->setUrl( $url )
			->sendRequest( $this->getClient()->getAccessToken() )
			->processResponse(); // not 100% sure if we need to keep processRequest here

		return $this->getResponse();
	}

	public function getRegistrants( $webinarKey, $registrantKey ) {
		$url = 'https://api.citrixonline.com/G2W/rest/organizers/' . $this->getClient()->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants/'.$registrantKey;
    	$this->setHttpMethod( 'GET' )
			->setUrl( $url )
			->sendRequest( $this->getClient()->getAccessToken() )
			->processResponse( true );

		return $this->getResponse();
	}

	public function getAttendees( $webinarKey ) {
		$url = 'https://api.citrixonline.com/G2W/rest/organizers/' . $this->getClient()->getOrganizerKey() . '/webinars/' . $webinarKey . '/attendees';
		$this->setHttpMethod( 'GET' )
			->setUrl( $url )
			->sendRequest( $this->getClient()->getAccessToken() )
			->processResponse();

		return $this->getResponse();
   
	}

	public function register( $webinarKey, $registrantData ) {
		$url = 'https://api.citrixonline.com/G2W/rest/organizers/' . $this->getClient()->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants';
		$this->setHttpMethod( 'POST' )
			->setParams( $registrantData )
			->setUrl( $url )
			->sendRequest( $this->getClient()->getAccessToken() )
			->processResponse();

		return $this;
	}

	public function getClient() {
		return $this->client;
	}

	public function setClient( $client ) {
		$this->client = $client;

		return $thisl
	}

	public function processResponse( $single = false ) {
		$response = $this->getResponse();
		$this->reset();

		if( isset($response[ 'int_err_code' ] ) ) {
			$this->addError($response[ 'msg' ] );
		}

		if( isset( $response[ 'description' ] ) ) {
			$this->addError($response[ 'description' ] );
		}

		if( $single === true ) {
			if( isset( $response[ 'webinarKey' ] ) ) {
				$webinar = new Webinar( $this->getClient() );
				$webinar->setData( $response )->populate();
				$this->setResponse( $webinar );
			}
			if( isset( $response[ 'registrantKey' ] ) ) {
				$webinar new Consumer( $this->getClient() );
				$webinar->setData( $response )->populate();
				$this->setResponse( $webinar );
			}
		} else {
			$collection = new \ArrayObject( array() ); //hmmm

			foreach( $response as $entity ) {
				if( isset( $entity[ 'webinarKey'] ) ) {
					$webinar = new Webinar( $this->getClient() );
					$webinar->setData( $entity )->populate();
					$collection->append( $webinar );
				}
				if( isset( $entity[ 'registrantKey' ] ) ) {
					$webinar = new Consumer( $this->getClient() );
					$webinar->setData( $entity )->populate();
					$collection->append( $webinar );
				}
			}

			$this->setResponse( $collection );
		}
	}
}