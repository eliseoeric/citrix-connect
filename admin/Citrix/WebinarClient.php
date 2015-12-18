<?php
namespace Admin\Citrix;
class WebinarClient {

    protected $apiKey;
    protected $username;
    protected $password;
    protected $client;

    public function __construct() {
        $options = get_option( 'citrix-connect-webinar' );
        $this->apiKey = $options['webinar_api'];
        $this->username = $options['webinar_username'];
        $this->password = $options['webinar_password'];
        $this->client = auth();
    }

    public function auth() {
        //Get the client for transient
        $client = get_transient( 'goToWebinarClient' );

        //Check if transient exists
        if( false === $client ) {
            //If not - create new client and store it
            $client = new \Citrix\Authentication\Direct( $this->getApiKey() );
            $client->auth( $this->getUsername(), $this->getPassword() );

            if($client->hasErrors()) {
                throw new \Exception( $client->getError() );
            }   

            set_transient( 'goToWebinarClient', $client, DAY_IN_SECONDS );
        }    

        return $client;
    }

    public function getTitle( $id ) {
        $goToWebinar = new GoToWebinar( $this->client );

        $webinar = $goToWebinar->getWebinar( $id );
        $title = $webinar['subject'];

        return $title;

    }

    public function getRegistrationUrl( $id ) {
        $goToWebinar = new GoToWebinar( $this->client );

        $webinar = $goToWebinar->getWebinar( $id );
        $url = $webinar['registrationurl'];

        return $url;
    }

    public function getTimes( $id ) {
        $goToWebinar = new GoToWebinar( $this->client );

        $webinar = $goToWebinar->getWebinar( $id );
        $time = $webinar['times'];

        return $times;
    }

    public function getUpcomming() {
        
    }

    public function getDescription( $id ) {

    }

    public function getStartDate( $id ) {

    }

    public function isPast( $id ) {

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