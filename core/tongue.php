<?php
/**
 * WAPL Language Loader & Manager class
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: tongue.php,v2.0 19:06 12/03/2011 NP++ Exp $
*/



/**
* TONGUE
* WAPL Langueges Loader & Manager
* Require Registry.cls + Debugger.cls + Konfig.cls + *flib.cls
*
* @package  WAPL
*/
 
class tongue{
	var $description = 'System Languages Manager Class';
/**
* __Constractor
* load site default language
*/
public static function init() {
global $lang;

	$lang['wpl'] = array(
		'info' => array('id' =>'1'),
		'txt' => array(
			// load default principal string 
			'dir'			 => "ltr",
			'align'			 => "left",
			'align2'		 => "right",
			'charset'		 => "utf-8",
            'wapl'			 => "Wapl",
			'welcome'		 => "Welcome",
			'copyrighttxt'	 => "All rights reserved",
            'poweredby'		 => "Powred by: ",
            'designedby'	 => "Design by: ",
			'betaversion'	 => "Wapl Beta Version"
		)		
	);
	
	if(!tongue::load()){ loader::import(PLNG.'EN-GB'.'.main.php');}
	
	//update copyright 2 current year
	$lang['wpl']['txt']['copyrighttxt'] = "&copy; ".date('Y').'. '.konfig::get('siteTitle').'. '.$lang['wpl']['txt']['copyrighttxt'].
	' | '.$lang['wpl']['txt']['poweredby'].' <a href="http://demo.itkane.com/wapl" target="_blank">'.$lang['wpl']['txt']['wapl'].' '.WPL_VERSION.' </a> | '.$lang['wpl']['txt']['designedby'].' <a href="http://blog.itkane.com/" target="_blank">Khedrane Jnom</a>';
		
    registry::set('lgid',$lang['wpl']['info']['id']);
}
/**
* Get language File and load Selected languages  strings into this object
* 
* @param $file 	lang file
* @param $reset	 reset txt array	
* @return  $ret	message
*/
public static function load( $f="/"){
global $lang;


if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

	$lng  = registry::get('lang');
	$file = ($f!="/")? $f : PLNG.$lng.'.main.php';

	// check for language file existence
	if (flib::exists($file) && loader::import($file)){

		$ret = true;
		/*/////////////////////*/
		btrack('TNG: '.basename($file));
	}
	else{
		
		debugger::alert('Language files not found :   '.$lng.'  !! ');
        
		$ret = false;
	};

	return $ret;	
}
/**
* get lacalized string
*
* @param	string	$string		string to localize	
* @param	string	$id		application id	
* @return	string	$str	localized txt
*/
public static function txt($string,$id='wpl'){
	try{
        $str = (!empty($GLOBALS['lang']["$id"]['txt'][$string])) ? $GLOBALS['lang']["$id"]['txt'][$string] : 'Text:_'.$string;
	
        return $str;
    }
    catch(Exception $e){
        return false;
    }
}
/**
* Check if its a valid language ID e.g:  Ar-DZ, en-gb...
*
* @param	string	$lang	language id	
* @return	bool	$ret	
*/
public static function islang($lang){
	$lang = strtoupper($lang);
	if(false != explode('-',$lang) && flib::exists(PLNG.$lang.'.main.php')){
		return true;
	}else
		return false;

}

public static function change(){
$langID = $_GET['tid'];
if(tongue::islang($langID)){
	konfig::set('lang',$langID);
	$ret = tongue::txt('langselected').':   '.$langID;
}else {
$ret = tongue::txt('langnotfound').':   '.$langID;
debugger::warn($ret,false);}

	registry::set('contents',$ret);
}


public static function isLoaded(){
	if(@!empty($GLOBALS['lang']['wpl']['txt']['lang'])){
		return true;
	}
	else
		return false;
}
public static function isRTL(){
	if($GLOBALS['lang']['wpl']['txt']['align'] != 'left'){
		return true;
	}
	else
		return false;
}
public static function getID(){
	if(@!empty($GLOBALS['lang']['wpl']['info']['id'])){
		return $GLOBALS['lang']['wpl']['info']['id'];
	}
	else
		return '1';
}

	//---- get language list switcher
	function getLangSwitcher(){
        $ls = flib::fileslist(PLNG,null,'php');
        
		$cur = registry::get('lang');
		$list = array();
		foreach ($ls as $fl) {   
            $fl = basename($fl);
            $fl = str_replace('.main.php','',$fl); 
            $fl = str_replace(DS,SH,$fl); 
			$list[$fl] = $fl;
        };
		return form::jListBox(array('mode'=>'edit','fld'=>'lang-frm', 'lbl'=>txt('language'),'lookedvalue'=>$cur, 'jvd'=>'', 'col'=>'40', 'list'=>$list));       
		
		
    } 
}

function txt($str,$id='wpl'){
return tongue::txt($str,$id);
}

function RTL(){
	if($GLOBALS['lang']['wpl']['txt']['align'] != 'left'){
		return 'rtl.';
	}
	else
		return '';
}
?>