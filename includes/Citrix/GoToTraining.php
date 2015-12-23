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

    /**
     * Begin here by passing an authentication class.
     *
     * @param $client - authentication client
     */
    public function __construct($client)
    {
        $this->setClient($client);
    }

    public function getUpcoming()
    {

    }

    public function getTrainings()
    {

    }

    public function getPast()
    {

    }

    public function getTraining( $trainingKey )
    {
        $url = 'https://api.citrixonline.com/G2T/rest/organizers/' . $this->getClient()->getOrganizerKey() . '/trainings/' . $trainingKey;
        $this->setHttpMethod('GET')
            ->setUrl($url)
            ->sendRequest($this->getClient()->getAccessToken())
            ->processResponse(true);

        return $this->getResponse();
    }

    public function createTraining()
    {

    }

    public function getRegistrants()
    {

    }

    public function register()
    {

    }

    public function getClient() {

    }

    public function setClient()
    {

    }

    /**
     * Each class that makes calls to
     * Citrix API should define how to process
     * the response.
     */
    public function processResponse($single = false){
        $response = $this->getResponse();
        $this->reset();

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