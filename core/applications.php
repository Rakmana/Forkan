<?php
/**
 * Application Class File 
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: kjax.mode.php,v2.0 22:12 26/02/2010 NP++ Exp $
*/



/**
* APPLICATIONS
* Application Manager Utility
*
* @package  WAPL
*/
class applications{
	/**
	* Load Application Config from its konfig.xml to Global WAPL konfig.
	*
	* @param string		$appname  Application Name
	*/	
	public static function getAppConfig($appname){
		konfig::load($appname,PAPP.$appname.DS.$appname.'.konfig.xml');
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Get Application information
	*
	* @param string		$appname Application Name.
	* @return Array	  [0:path 1:name 2: description 3:author 4:date]
	*/
	public static function getinfo($appname){
		konfig::load($appname,PAPP.$appname.DS.$appname.'.konfig.xml');
		
		$ret['path'] = $appname;
		$ret['name'] = konfig::get('name',$appname);
		$ret['description'] = urldecode(konfig::get('description',$appname));
		$ret['author'] =  konfig::get('author',$appname);
		$ret['date'] =  konfig::get('date',$appname);;
		
		return $ret;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Get All Applications information.
	*
	* @return Array		Array of Application Infos
	*/
	public static function get_apps_info(){
	
		$dirs = flib::dirslist(PAPP);
		foreach($dirs as $dir){
			$ret[$dir] = applications::getinfo($dir);
		}
		return $ret;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Check if Application need authentication login or it is public.
	*
	* @param string		$appname Application Name
	* @return bool		
	*/
	public static function isSecure($appname=APPC){
		return konfig::isOn('Privat',$appname);
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Check if Application need to Connect to DataBase.
	*
	* @param string		$appname Application Name
	* @return bool		
	*/
	public static function needDB($appname){
		$ret = false;
		if(konfig::get('needDB',$appname)){
			$ret = (konfig::get('needDB',$appname) != 'on')? false : true;//0 for disable and the rest enable
		}
		$retf = (applications::isSecure($appname))OR $ret;//if app is secure then it need dbase
		return $retf;
	}
    public static function getID($appname){
        return konfig::get('appid',$appname);
    }
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Put Application Infos into HTML box.
	*
	* @param string		$appname Application Name
	* @return bool		
	*/
	public static function infoBox($app){
		$inf = applications::getinfo($app);
		$ret = '<span class="app-name">:  '.$inf['name'].'</span><br /><span class="app-desc">'.$inf['description'].'</span>';
	
		return $ret;
	}
}  

?>