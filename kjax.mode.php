<?php
/**
 * Kjax mode file for Ajax requests
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: kjax.mode.php,v2.0 22:12 26/02/2010 NP++ Exp $
*/


defined('WPL_RUN') OR die(' F O R B I D D E N  ! ');

//load application konfig file
applications::getAppConfig(APPC);
/*
//check if app need dbase interface
if(applications::needDB(APPC) != false){
	dbase::init();
};*/


//load application kontroller
$main =& router::getApp();

//check if app hase security layer
if(applications::isSecure(APPC) AND (user::jlogged() != true)){
    $redirec = (registry::get('job') != 'login')? true : false;
    WPI::setVar('job',WPI::txt('login'));
    //--- Add notification 
    //registry::add('notifications',form::notify('warn',WPI::txt('relogin')));
	$main->login(true,$redirec);	
	// Tracker
	btrack('JOB-Loading: Login');
}
else{
	$job =& router::getJob();
	$main->$job();
}


//$main->$job();

//4ajax profiler
/*$time = kjxProfiler();
$notifications = (registry::get('notifications') != '')? '<![CDATA['.registry::get('notifications').']]>' : '';
//organize in xml format
$response = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$response.= '	<wres>'."\n";
$response.= '	<wntf>'.$notifications.'</wntf>'."\n";
$response.= '	<wctn>'.(registry::get('contents')).'</wctn>'."\n";
if(registry::get('ajxtitle') != ''){
    $response.= '	<doctitle>'.(registry::get('ajxtitle')).'</doctitle>'."\n";
}
$response.= '	<wpfr>'.$time.'</wpfr>'."\n";
$response.= '</wres>';*/
//<content><![CDATA['.(registry::get('contents')).']]></content>

$response = env::getXMLresponse();
env::response($response,'xml');


dbase::close();

?>