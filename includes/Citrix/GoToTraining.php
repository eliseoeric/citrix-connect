<?php
namespace Citrix;

use Citrix\Authentication\Authentication;
use Citrix\Entity\Training;
use Citrix\Entity\Consumer;


/**
 * Use this to get/post data from/to Citrix.
 *
 * @uses \Citrix\ServiceAbstract
 * @uses \Citrix\CitrixApiAware
 */
class GoToTraining extends ServiceAbstract implements CitrixApiAware
{

    /**
     * Authentication Client
     *
     * @var Citrix
     */
    private $client;

    //Root api url;
    private $apiUrl;

    /**
     * Begin here by passing an authentication class.
     *
     * @param $client - authentication client
     */
    public function __construct($client)
    {
        $this->setClient($client);
        $this->apiUrl = 'https://api.citrixonline.com/G2T/rest/';
    }

    public function getUpcoming()
    {

    }

    public function getTrainings()
    {   
        $organizerKey = $this->getClient()->getOrganizerKey();
        $url = $this->getApiUrl() . "organizers/{$organizerKey}/trainings";

        $this->setHttpMethod( 'GET' )
            ->setUrl( $url )
            ->sendRequest( $this->getClient()->getAccessToken() )
            ->processResponse();

        return $this->getResponse();
    }

    public function getPast()
    {
        $since = date( DATE_ISO8601, mktime( 0, 0, 0, 7, 1, 2000 ) );
        $until = date( DATE_ISO8601 );

    }

    public function getTraining( $trainingKey )
    {
        $url = $this->getApiUrl() . 'organizers/' . $this->getClient()->getOrganizerKey() . '/trainings/' . $trainingKey;
        $this->setHttpMethod('GET')
            ->setUrl($url)
            ->sendRequest($this->getClient()->getAccessToken())
            ->processResponse(true);

        return $this->getResponse();
    }

    public function createTraining()
    {

    }

    public function getRegistrants( $trainingKey )
    {   
        $organizerKey = $this->getClient()->getOrganizerKey();
        $url = $this->getApiUrl() . "organizers/{$organizerKey}/trainings/{$trainingKey}/registrants";
        
        $this->setHttpMethod( 'GET' )
            ->setUrl( $url )
            ->sendRequest( $this->getClient()->getAccessToken() )
            ->processResponse();

        return $this->getResponse();
    }

    public function getRegistrant( $trainingKey, $registrantKey )
    {   
        $organizerKey = $this->getClient()->getOrganizerKey();
        $url = $this->getApiUrl() . "organizers/{$organizerKey}/trainings/{$trainingKey}/registrants/{$registrantKey}";

        $this->setHttpMethod( 'GET' )
            ->setUrl( $url )
            ->sendRequest( $this->getClient()->getAccessToken() )
            ->processResponse( true );

        return $this->getResponse();
    }

    public function getOnlineRecordings( $trainingKey )
    {
        $url = $this->getApiUrl() . "trainings/{$trainingKey}/recordings";

        $this->setHttpMethod( 'GET' )
            ->setUrl( $url )
            ->sendRequest( $this->getClient()->getAccessToken() )
            ->processResponse( true );

        return $this->getResponse();
    }

    public function getOnlineRecordingDownload()
    {

    }

    /**
   * Register user for a training
   * 
   * @param int $trainingKey
   * @param array $registrantData - email, firstName, lastName (required)
   * @return \Citrix\GoToTraining
   */
    public function register( $trainingKey, $registrantData )
    {   
        $organizerKey = $this->getClient()->getOrganizerKey();
        $url = $this->getApiUrl() . "organizers/{$organizerKey}/trainings/{$trainingKey}/registrants";

        $this->setHttpMethod('POST')
            ->setUrl( $url )
            ->setParams( $registrantData )
            ->sendRequest( $this->getClient()->getAccessToken() )
            ->processResponse();
    }

    public function getClient() {
        return $this->client;
    }

    public function setClient( $client )
    {
        $this->client = $client;

        return $this;
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function setApiUrl( $apiUrl )
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * Each class that makes calls to
     * Citrix API should define how to process
     * the response.
     */
    public function processResponse($single = false){
        $response = $this->getResponse();
        $this->reset();
//        dd($response);
        if(isset($response['int_err_code'])){
            $this->addError($response['msg']);
        }

        if(isset($response['description'])){
            $this->addError($response['description']);
        }

        if($single === true) {
            if(isset($response['trainingKey'])){
                $training = new Training($this->getClient());
                $training->setData($response)->populate();
                $this->setResponse($training);
            }

            if(isset($response['registrantKey'])){
                $training = new Consumer($this->getClient());
                $training->setData($response)->populate();
                $this->setResponse($training);
            }
            if( isset( $response['recordingList'] ) ) {
                // need to build out a recording class I supose.
            }
        } else {
            $collection = new \ArrayObject(array());

            foreach ($response as $entity){
                if(isset($entity['trainingKey'])){
                    $training = new Training($this->getClient());
                    $training->setData($entity)->populate();
                    $collection->append($training);
                }

                if(isset($entity['registrantKey'])){
                    $training = new Consumer($this->getClient());
                    $training->setData($entity)->populate();
                    $collection->append($training);
                }
            }

            $this->setResponse($collection);
        }
    }
}