<?php
/**
 * WAPL - Web Applications Platform
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . itkane.com
 * @license http://gnu.org/copyleft/gpl.html 'GNU GPL v3.0'
 * @version $Id: index.php,v2.0 22:12 26/02/2010 NP++ Exp $
*/ 

//tracher file
include_once ("core/tracker.php");
btrack('TRK init');

//some constants
define( 'WPL_RUN' , 1 );
define( 'DS',     DIRECTORY_SEPARATOR );
define( 'SH',    '/' );
define( 'KROT',  (dirname(__FILE__)).DS );

if(str_replace('\\','/',$_SERVER["DOCUMENT_ROOT"]) != str_replace('\\','/',dirname(__FILE__)))
    define( 'KBAS',  (basename(dirname(__FILE__))) );
else   
    define( 'KBAS',  '/' );
//define( 'KBAS',  (basename(dirname(__FILE__))) );

//booting file
include_once ("boot.php");


/*
* include Mode [mediax, site, kjax]
*/
router::SwitchToMode();

//tools::dump($_COOKIE);
?>