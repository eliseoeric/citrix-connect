<?php

namespace Citrix\Entity;

abstract class EntityAbstract {
	
	protected $client;

	protected $data;
	

	protected function getClient() {
		return $this->client;
	}

	protected function setClient( $client ) {
		$this->client = $client;

		return $this;
	}

	protected function getData() {
		return $this->data;
	}

	protected function setData( $data ) {
		$this->data = $data;

		return $this;
	}

	public function toArray() {
		$toUnset = array( 'client', 'data' );
		$toArray = get_object_vars( $this );

		foreach( $toUnset as $value ) {
			if( isset($toArray[ $value ] ) ) {
				unset( $toArray[ $value ] );
			}
		}

		return $toArray;
	}


}