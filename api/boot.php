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


// mbstring is old and has it's functions around for older versions of PHP.
// if mbstring is not loaded, we go into native mode.
if (extension_loaded('mbstring')){	mb_internal_encoding('UTF-8');}

define('DS', DIRECTORY_SEPARATOR);
define('KROT', dirname(__FILE__). DS);
define('PSYS', KROT . 'core' . DS);
define('PXML', KROT . 'core' . DS . 'store' . DS);
define('PLNG', KROT . 'languages' . DS);
define('PAPP', KROT . 'applications' . DS);
define('PJS',  KROT . 'scripts' . DS);
/**
 * this public addresses 4 security we make it into publik url format
 *//*
define('KURL', 'http://' . $_SERVER['HTTP_HOST'] .(KBAS != '/' ?  SH . KBAS . SH : KBAS));
define('KMDA', KURL . 'media' . SH);
define('KAPP', KURL . 'applications' . SH);
define('KPLG', KURL . 'plugins' . SH);
define('KURI', KURL . $_GET);
define('KJS', KMDA . 'scripts' . SH);
define('KPUB', KURL . 'public' . SH);
define('KLNG', KURL . 'languages' . SH);*/
//----------
define('AMPER','&');//&amp;




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



?>