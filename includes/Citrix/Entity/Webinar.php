<?php

namespace Citrix\Entity;

use Citrix\GoToWebinar;

class Webinar extends EntityAbstract implements EntityAware {

	public $id;

	public $subject;

	public $description;

	public $organizerKey;

	public $times = array();

	public $timeZone = 'America/New_York';

	public $registrationUrl;

	public $consumers;

	public function __construct( $client ) {
		$this->setClient( $client );
		$this->consumers = new \ArrayObject();
	}

	public function populate() {
		$data - $this->getData();

		$this->id = (string) $data[ 'webinarKey' ];
		$this->subject = $data[ 'subject' ]; 
		$this->description = $data[ 'description' ];
		$this->organizerKey = $data[ 'organizerKey' ];
		$this->times = $data[ 'times' ];
		$this->timeZone = $data[ 'timeZone' ];
		$this->registrationUrl = isset( $data[ 'registrationUrl' ] ) ? $data[ 'registrationUrl' ] : null;

		return $this;
	}

	public function getRegistrants() {
		$goToWebinar = new GoToWebinar( $this->getClient() );
		$registrants = $goToWebinar->getRegistrants( $this->getId() );

		return $registrants;
	}

	public function registerConsumer( \Citrix\Enity\Consumer $consumer ) {
		$goToWebinar = new GoToWebinar( $this->getClient() );
		$goToWebinar->register( $this->getId(), $consumer->toArray() );

		return $goToWebinar;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
    	$this->description = $description;
    
    	return $this;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
    	$this->id = $id;
    
    	return $this;
	}

	public function getOrganizerKey() {
		return $this->organizerKey;
	}

	public function setOrganizerKey($organizerKey) {
    	$this->organizerKey = $organizerKey;
    
    	return $this;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function setSubject($subject) {
    	$this->subject = $subject;
    
    	return $this;
	}

	public function getTimes() {
		return $this->times;
	}

	public function setTimes($times) {
    	$this->times = $times;
    
    	return $this;
	}

	public function getTimeZone() {
		return $this->timeZone;
	}

	public function setTimesZone($timeZone) {
    	$this->timeZone = $timeZone;
    
    	return $this;
	}

	public function getConsumers() {
		return $this->consumers;
	}

	public function setConsumers($consumers) {
    	$this->consumers = $consumers;
    
    	return $this;
	}

	public function getRegistrationUrl() {
		return $this->registrationUrl;
	}

	public function setRegistrationUrl($registrationUrl) {
    	$this->registrationUrl = $registrationUrl;
    
    	return $this;
	}
}