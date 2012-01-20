<?php
/**
 * Site mode file for index.html Home Page
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: site.mode.php, v2.0 22:12 26/02/2010 NP++ Exp $
*/

defined('WPL_RUN') OR die(' F O R B I D D E N  ! ');
/**
* Set template variables
*/
registry::set('css','') ;//header css
registry::set('aljs','') ;//auto loaded javascript code
registry::set('jsh','') ;//header javascript code
registry::set('js','') ;// body javascript code
registry::set('headex','') ;// head components
registry::set('jsex','') ;// js external components

ob_start();


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

//jpriv::jfor('1.2.5:v');
loader::htmlpage();


/* testing area
	
	tools::dump(get_declared_classes());
*/

dbase::close();

?>