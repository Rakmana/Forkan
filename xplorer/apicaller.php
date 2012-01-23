<?php

define("APISERVER",'http://localhost/forkan/api/');

class ApiCaller
{
	//some variables for the object
	private $_app_id;
	private $_app_key;
	private $_api_url;
	
	//construct an ApiCaller object, taking an
	//APP ID, APP KEY and API URL parameter
	public function __construct( $app_key, $api_url)
	{
		//$this->_app_id = $app_id;
		$this->_app_key = $app_key;
		$this->_api_url = $api_url;
	}
	
	//send the request to the API server
	//also encrypts the request, then checks
	//if the results are valid
	public function sendRequest($request_params)
	{ 
	   //wrap the whole thing in a try-catch block to catch any wayward exceptions!
       try {
		//encrypt the request parameters
		//$enc_request = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->_app_key, json_encode($request_params), MCRYPT_MODE_ECB));
     	
		$enc_request = (json_encode($request_params));
	 
		//var_dump($enc_request);       
		//create the params array, which will
		//be the POST parameters
		$params = array();
		$params['byn'] = $enc_request;
		$params['rmz'] = $this->_app_key;
		//initialize and setup the curl handler
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_api_url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
        //var_dump($params);

		//execute the request
		$result_r = curl_exec($ch);

		//json_decode the result
		$result = @json_decode($result_r);
		
		//check if we're able to json_decode the result correctly
		if( ($result == false) || (isset($result->st) == false) ) {
			throw new Exception('Request was not correct :'.($result_r));
		}
		
		//if there was an error in the request, throw an exception
		if( $result->st == false ) {
			throw new Exception($result->er[0]);
		}
		
        curl_close($ch);
        
		//if everything went great, return the data
		return $result->dt;
        
      } catch( Exception $e ) {
	    //catch any exceptions and report the problem

	    $GLOBALS['errors'][] = $e->getMessage();
        return null;
      }


        
	}
}