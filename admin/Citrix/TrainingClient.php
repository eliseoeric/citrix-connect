<?php

use Citrix\GoToTraining;

class TrainingClient
{
	protected $apiKey;
	protected $username;
	protected $password;
	protected $client;

	public function __construct() {
		$options = get_option( 'citrix-connect-training' );
		$this->apiKey = $options['training_api'];
		$this->username = $options['training_username'];
		$this->password = $options['training_password'];
		$this->client = $this->auth();
	}

	public function auth() {
		//Get the client for transient
		$client = get_transient( 'goToTrainingClient' );

		//Check if transient exists
		if( false === $client ) {
			//If not - create new client and store it
			$client = new \Citrix\Authentication\Direct( $this->getApiKey() );
			$client->auth( $this->getUsername(), $this->getPassword() );

			if($client->hasErrors()) {
				throw new \Exception( $client->getError() );
			}

			set_transient( 'goToTrainingClient', $client, DAY_IN_SECONDS );
		}

		return $client;
	}

	public function getTrainings()
	{
		$goToTraining = new GoToTraining( $this->client );

		$trainings = $goToTraining->getTrainings();

		return $trainings;
	}

	public function getTransientTraining( $trasient_key, $training_id ) {
		$training = get_transient( $trasient_key );
		if( false === $training ) {
			$goToTraining = new GoToTraining( $this->client );
			$training = $goToTraining->getTraining( $training_id );
			set_transient( $trasient_key, $training, DAY_IN_SECONDS );
		}

		return $training;
	}

	public function getClient() {
		return $this->client;
	}

	public function getApiKey() {
		return $this->apiKey;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}
}