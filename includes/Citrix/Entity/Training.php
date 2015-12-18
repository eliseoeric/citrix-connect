<?php

namespace Citrix\Entity;
use Citrix\GoToTraining;

/**
 * Training Entity
 *
 * Contains all fields for a Training. It also provides additional functionality
 * such as registering a user for a training
 *
 * @uses \Citrix\Entity\EntityAbstract
 * @uses \Citrix\Entity\EntityAware
 *      
 */
class Training extends EntityAbstract implements EntityAware 
{
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
	}
}