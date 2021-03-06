<?php
//namespace Admin\Citrix;
use Citrix\GoToWebinar;

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
        $this->client = $this->auth();
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
                if( WP_DEBUG == true ) {
                    throw new \Exception( $client->getError() );
                } else {
                    new WP_Error( $client->getError() );
                }
            }   

            set_transient( 'goToWebinarClient', $client, DAY_IN_SECONDS );
        }
        // dd($client);    

        return $client;
    }

    public function getTitle( $webinar_id ) {
        $webinar = $this->getTransientWebinar( 'webinar_trans_' . $webinar_id, $webinar_id );
        $title = $webinar->subject;
        return $title;

    }

    public function getRegistrationUrl( $webinar_id ) {
        $webinar = $this->getTransientWebinar( 'webinar_trans_' . $webinar_id, $webinar_id );
        $url = $webinar->registrationUrl;

        return $url;
    }

    public function getTimes( $webinar_id ) {
        $webinar = $this->getTransientWebinar( 'webinar_trans_' . $webinar_id, $webinar_id );
        $times = $webinar->times;

        return $times;
    }

    public function getUpcomming() {
        $goToWebinar = new GoToWebinar( $this->client );

        $webinars = $goToWebinar->getUpcoming();

        return $webinars;
    }

    public function getDescription( $webinar_id ) {
        $webinar = $this->getTransientWebinar( 'webinar_trans_' . $webinar_id, $webinar_id );
        $desc = $webinar->description;

        return $desc;
    }

    public function getRegistrants( $webinar_id ) {
        $goToWebinar = new GoToWebinar( $this->client );

        $registrants = $goToWebinar->getRegistrants( $webinar_id );

        return $registrants;
    }

    public function getStartDate( $id ) {

    }

    public function isPast( $webinar_id ) {
        $webinar = $this->getTransientWebinar( 'webinar_trans_' . $webinar_id, $webinar_id );
        $url = $webinar->registrationUrl;

        if( $url == '' ) {
            $bool = true;
        } else {
            $bool = false;
        }

        return $bool;
    }

    public function getTransientWebinar( $trasient_key, $webinar_id ) {
        $webinar = get_transient( $trasient_key );
        if( false === $webinar ) {
            $goToWebinar = new GoToWebinar( $this->client );
            $webinar = $goToWebinar->getWebinar( $webinar_id );
            set_transient( $trasient_key, $webinar, DAY_IN_SECONDS );
        }

        return $webinar;
    }

    public function register( $webinar_key, $registrantData ) {
        $goToWebinar = new GoToWebinar( $this->client );

        $response = $goToWebinar->register( $webinar_key, $registrantData );

        if( $goToWebinar->hasErrors() ) {
            $response = array( 'has_errors' => true, 'errors' => $goToWebinar->getErrors() );
        } else {
            $response = array( 'has_errors' => false, 'joinUrl' => $goToWebinar->joinUrl );
        }
        return $response;
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