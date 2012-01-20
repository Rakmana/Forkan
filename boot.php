<?php
/**
 * Boot file for Wapl
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . itkane.com
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: boot.php,v2.0 22:12 26/02/2010 NP++ Exp $
 */


defined('WPL_RUN') OR die(' F O R B I D D E N  ! ');
/**
 * Some ini settings are required
 */
//ini_set('max_input_time','600');
//ini_set('memory_limit','204M');
ini_set('post_max_size', '32M');
ini_set('upload_max_filesize', '64M');
ini_set('short_open_tag ', 'On');
ini_set('session.name', 'WPSS');
ini_set('session.use_only_cookies ', '1');

//ini_set('arg_separator.output',     '&amp;');
//ini_set('magic_quotes_runtime',     0);
//ini_set('magic_quotes_sybase',      0);
//ini_set('session.cache_expire',     200000);
//ini_set('session.cache_limiter',    'none');
//ini_set('session.cookie_lifetime',  2000000);
//ini_set('session.gc_maxlifetime',   200000);
//ini_set('session.save_handler',     'user');
//ini_set('session.use_only_cookies', 1);
//ini_set('session.use_trans_sid',    0);
//ini_set('url_rewriter.tags',        '');
//date_default_timezone_set("GMT+0");


iconv_set_encoding("internal_encoding", "UTF-8");

// mbstring is old and has it's functions around for older versions of PHP.
// if mbstring is not loaded, we go into native mode.
if (extension_loaded('mbstring')){	mb_internal_encoding('UTF-8');}

/**
 * Browser Ajent detection : 4me No IE6.0
 */
if (preg_match('/MSIE 6.0/', $_SERVER['HTTP_USER_AGENT'])) {
    echo '<strong style="color:red">You are using MSIE 6.0 , Please Upgrade Your Browser!</strong>';
    exit;
}

define('PSYS', KROT . 'core' . DS);
define('PXML', KROT . 'core' . DS . 'store' . DS);
define('PLNG', KROT . 'languages' . DS);
define('PAPP', KROT . 'applications' . DS);
define('PPLG', KROT . 'plugins' . DS);
define('PJS',  KROT . 'scripts' . DS);

define('PPUB', KROT . 'public' . DS);
/**
 * this public addresses 4 security we make it into publik url format
 */
define('KURL', 'http://' . $_SERVER['HTTP_HOST'] .(KBAS != '/' ?  SH . KBAS . SH : KBAS));
define('KMDA', KURL . 'media' . SH);
define('KAPP', KURL . 'applications' . SH);
define('KPLG', KURL . 'plugins' . SH);
define('KURI', KURL . $_GET);
define('KJS', KMDA . 'scripts' . SH);
define('KPUB', KURL . 'public' . SH);
define('KLNG', KURL . 'languages' . SH);
//----------
define('AMPER','&');//&amp;

/***************************/
btrack('Constants defined');


/**
 * load the kernel file
 */
require_once (PSYS . 'kernel.php');
/*/////////////////////*/
btrack('KERNEL Loaded');


/**
 * Set error reports level
 */
//error_reporting(0);

/**
 * Check PHP version > 5 
 */
$php_version_error = ' <b>You need to upgrade to PHP > 5.0.1</b>';

if (version_compare(phpversion(), '5', '<') == true) {
    die($php_version_error);
}
/************************************************************/


/**
 * Initialize base components
 */
registry::init();
konfig::init();

/**
 * Set template variables
 */
registry::set('notifications', '');
registry::set('contents', '');


/**
 * Disable php errors 
 */
if (konfig::isOn('error_php')) {
    error_reporting(E_ALL);
} else
    error_reporting(0);

debugger::init();
router::init();
if (!router::ismediax())
    session::init();


if (!router::ismediax()){
    dbase::init();
    
	if(!kore::isInstalled()) {
		kore::install();
	}
	
	};
    
user::init();


/**
 * Get System default lang and theme 
 */
$thm = konfig::get('theme');

$lng = tools::Jet('lang','');

if(($lng != 'NULL') AND tongue::islang($lng)){
    session::jSetCookie('jlang',$lng);
    registry::set('lang',$lng);
}
else{
    $lng = (session::jGetCookie('jlang') != false)? session::jGetCookie('jlang') : konfig::get('lang');
    registry::set('lang',$lng);
}

/**
 * Define paths and urls for lang and theme 
 */
define('SKN', 'skins' . SH . $thm . SH); //acive Skin short url
define('KSKN', KURL . 'skins' . SH . $thm . SH); //active Skin full url
define('PSKN', KROT . 'skins' . DS . $thm . DS); //active Skin full path

//--- check if a valid skin selected
if(!flib::is_file(PSKN.'index.html.php')){
    die('Skin file not loaded !! ');
}
btrack('SKN-selected: ' . $thm);
btrack('LNG-selected: ' . $lng);

/**
 * Initialize Language
 */
tongue::init();


/*/////////////////////*/
btrack('Booting OK');


?>