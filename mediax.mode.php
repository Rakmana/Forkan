<?php
/**
 * MediaX mode file for media Requests
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: mediax.mode.php,v1.0 22:12 26/02/2010 NP++ Exp $
*/


defined('WPL_RUN') OR die(' F O R B I D D E N  ! ');

	mediax::init();

    $app = router::getApp();
    //$app::init();
    //load application konfig file
    //applications::getAppConfig(APPC);
    
	$job = router::getJob();


	
	mediax::$job();

	//tools::show("\n\r/*\n\r".btrack('End',true)."\n\r");
exit;
?>