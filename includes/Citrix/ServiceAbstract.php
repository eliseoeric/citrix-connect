<?php
/**

	TODO:
	- Reformat the sendRequest method to use the wp_remote_get API
	- Second todo item

**/
namespace Citrix;

/**
 * Provides common functionality for classes
 * that get/post from/to Citrix APIs.
 *
 * @abstract
 */
abstract class ServiceAbstract
{

  /**
   * List of errors that have occured
   * 
   * @var array
   */
  private $errors = array();

  /**
   * Params to be passed via POST or GET requests
   * to Citrix APIs
   *
   * @var array
   */
  private $params = array();

  /**
   * URL to be called
   * 
   * @var string
   */
  private $url;

  /**
   * Response from Citrix API call
   * 
   * @var array
   */
  private $response;

  /**
   * HTTP METHOD used - POST | GET
   * 
   * @var String
   */
  private $httpMethod = 'POST';

  /**
   * Send API request, but pass the $oauthToken first.
   * 
   * @see \Citrix\Citrix
   *
   * @param string $oauthToken          
   * @return \Citrix\ServiceAbstract
   */
  public function sendRequest($oauthToken = null)
  {
    $url = $this->getUrl();
    try {
      //curl init
      $ch = curl_init();

      if ( FALSE === $ch ){
        throw new \Exception('Failed to Initialize');
      }


      //configure curl
      if ($this->getHttpMethod() == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true); // tell curl you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->getParams())); // define what you want to post
      } else {
        $url = $this->getUrl();
        $query = http_build_query($this->getParams());
        $url = $url . '?' . $query;
      }

      if (! is_null($oauthToken)) {
        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: OAuth oauth_token=' . $oauthToken
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      }
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format

      $output = curl_exec( $ch );
      if( FALSE === $output ){
        throw new \Exception( curl_error( $ch ), curl_errno( $ch ) );
      }

      curl_close( $ch );
      $this->setResponse( $output );
      return $this;

    } catch( \Exception $e ) {
      trigger_error( sprintf(
        'Curl failed with error #%d: %s',
          $e->getCode(), $e->getMessage()
      ), E_USER_ERROR);
    }
  }

  /**
   * Have any errors occurred?
   *
   * @return boolean
   */
  public function hasErrors()
  {
    return empty($this->errors) ? false : true;
  }

  /**
   * Returns the first error
   *
   * @return mixed
   */
  public function getError()
  {
    $error = $this->errors;
    $this->reset();
    return empty($error) ? false : reset($error);
  }

  /**
   * Get all errors
   * 
   * @return array
   */
  public function getErrors()
  {
    $error = $this->errors;
    $this->reset();
    return $error;
  }

  /**
   * Add a new error
   *
   * @param string $message
   * @return \Citrix\ServiceAbstract
   */
  public function addError($message)
  {
    $this->errors[] = $message;
    
    return $this;
  }

  /**
   * Empty the list of errors
   *
   * @return \Citrix\ServiceAbstract
   */
  public function reset()
  {
    $this->errors = array();
    
    return $this;
  }

  /**
   *
   * @return array $params
   */
  public function getParams()
  {
    return $this->params;
  }

  /**
   *
   * @param array $params
   * @return $this
   */
  public function setParams($params)
  {
    $this->params = $params;
    
    return $this;
  }

  /**
   * Add a new param to be passed to API
   *
   * @param string $key
   * @param string $value
   * @return \Citrix\ServiceAbstract
   */
  public function addParam($key, $value)
  {
    $this->params[$key] = $value;
    
    return $this;
  }

  /**
   *
   * @return string $url
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   *
   * @param string $url
   * @return $this
   */
  public function setUrl($url)
  {
    $this->url = $url;
    
    return $this;
  }

  /**
   * Get the resposne
   * @return array $response
   */
  public function getResponse()
  {
    return $this->response;
  }

  /**
   *
   * @param array | string $response
   * @return \Citrix\ServiceAbstract
   */
  public function setResponse($response)
  {
    if (is_object($response)) {
      $this->response = $response;
      return $this;
    }
//    dd($response);
//    $this->response = (array) json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
    if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
      /** In PHP >=5.4.0, json_decode() accepts an options parameter, that allows you
       * to specify that large ints (like Steam Transaction IDs) should be treated as
       * strings, rather than the PHP default behaviour of converting them to floats.
       */
      $this->response = (array) json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
    } else {
      /** Not all servers will support that, however, so for older versions we must
       * manually detect large ints in the JSON string and quote them (thus converting
       *them to strings) before decoding, hence the preg_replace() call.
       */
      $max_int_length = strlen((string) PHP_INT_MAX) - 1;
      $json_without_bigints = preg_replace('/:\s*(-?\d{'.$max_int_length.',})/', ': "$1"', $response);
      $this->response = (array) json_decode($json_without_bigints, true);
    }
    return $this;
    
  }

  /**
   * Get the getHttpMethod
   * @return string $httpMethod
   */
  public function getHttpMethod()
  {
    return $this->httpMethod;
  }

  /**
   * Set the HttpMethod
   * 
   * @param string $httpMethod
   *          - GET | POST
   * @return \Citrix\ServiceAbstract
   */
  public function setHttpMethod($httpMethod)
  {
    $this->httpMethod = $httpMethod;
    
    return $this;
  }
}