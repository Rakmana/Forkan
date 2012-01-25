<?php
define("WPL_RUN",1);
//ob_start();

// load bootstrap
require_once('boot.php');

// configurations
$GLOBALS['konfig'] = array('error_mysql' => true);

// Define path to data folder
define('DATA_PATH', realpath(dirname(__FILE__).'/data'));

//Define our id-key pairs
$applications = array(
	'28e336ac6c9423d946ba02d19c6a2632' => 'APP001', //randomly generated app key 
);
//include our models
//include_once 'models/todo.php';

//wrap the whole thing in a try-catch block to catch any wayward exceptions!
try {
	//*UPDATED*
    
    if(!isset($_REQUEST['ver']) || !isset($_REQUEST['key'])){
	   throw new Exception('Request is Empty !!'); 
    }
	//get the encrypted request
	$enc_request = $_REQUEST['ver'];

	//get the provided app id
	$app_id = $_REQUEST['key'];
	
	//check first if the app id exists in the list of applications
	if( !isset($applications[$app_id]) ) {
		throw new Exception('Key does not exist!');
	}
	
	//decrypt the request
	$params = (trim($enc_request));
	//$params = json_decode(trim(mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $applications[$app_id], base64_decode($enc_request), MCRYPT_MODE_ECB )));
	//var_dump($params);
        
	//check if the request is valid by checking if it's an array and looking for the controller and action
	if( ($_REQUEST == false) || (isset($_REQUEST['cls']) == false) || (isset($_REQUEST['act']) == false) ) {
		throw new Exception('Request is not valid !');
	}
	
	//cast it into an array
	$params = (array) $_REQUEST;
	
	//var_dump($params);
	
	//get the controller and format it correctly so the first
	//letter is always capitalized
	if(strtolower($params['cls']) == 'q'){ $controller = 'forkan';}
	if(strtolower($params['cls']) == 'h'){ $controller = 'hadith';}
	
	//get the action and format it correctly so all the
	//letters are not capitalized, and append 'Action'
	$action = strtolower($params['act']).'Action';

	//check if the controller exists. if not, throw an exception
	if( file_exists("controllers/{$controller}.php") ) {
		include_once "controllers/{$controller}.php";
	} else {
		throw new Exception('Controller is invalid.');
	}
	
	//create a new instance of the controller, and pass
	//it the parameters from the request
	$controller = ucfirst($controller);
	$controller = new $controller($params);
    
	//check if the action exists in the controller. if not, throw an exception.
	if( method_exists($controller, $action) === false ) {
		throw new Exception('Action is invalid.');
	}
	
	//execute the action
	$result['dt'] = $controller->$action();
	$result['st'] = true;
	
} catch( Exception $e ) {
	//catch any exceptions and report the problem
	$result = array();
	$result['st'] = false;
	$result['er'][] = $e->getMessage();
}

//echo the result of the API call
echo json_encode($result);
exit();