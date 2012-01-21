<?php
/**
 * WAPL Debugger & Logging Class
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: debugger.php,v2.0 09:54 09/03/2010 NP++ Exp $
*/



/**
* DEBUGGER
* WAPL Debuging system 
* Require Registry.cls + Form.cls
*
* @package  WAPL
*/	
class debugger{
	private $description = 'System Debugger & Logging Class';

	/**
	* Initialize Debugger 
	*
	*/	
	static Function init(){
		
		// If we opened the file lets make sure we close it
		register_shutdown_function(array('debugger','xit'));
		
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Log function
	*
	* @access public
	* @param string		$typ [EMRG, ALERT, CRITIC, ERROR, WARN, NOTIC, INFO, DEBUG]
	* @param string		$txt log entry text
	* @param bool		$show show in output html 
	*/
	public static function logger($typ,$txt,$show=false){
		
		$ip = str_replace('.','-',tools::realip());
		
		$date = tools::now('d/m/Y');
		$path = 'n'.tools::now('dmY_H-i-s',true);
		$path = str_replace(':','-',$path);
		
		$data = $txt.'@'.$_SERVER['QUERY_STRING'];//'<![CDATA[*******]]>
		$type = strtolower($typ);
	
		
		$id= rand(3,100);
			$loghtml = form::notify($type,$txt);
		
		if($show != false){
			registry::add('notifications',$loghtml);		
		}else
			return $loghtml;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* X : MySQL : le MySQL est inutilisable
	*
	* @access public
	* @param string $txt	The text of event to log.
	* @return HTML error or XML if Ajax Mode
	*/
	public static function halt($txt){
		$html = debugger::logger('EMRG',$txt,false);
		
	
		
		//4ajax profiler
		if(router::isajx()){
			$time = kjxProfiler();
			//organize in xml format
			$response ='<?xml version="1.0" encoding="UTF-8"?>'."\n";
			$response.='<wres>'."\n";
			$response.='	<wntf><![CDATA['.$html.']]></wntf>'."\n";
			$response.='	<wcnt></wcnt>'."\n";
			$response.='	<wpfr>'.$time.'</wpfr>'."\n";
			$response.='</wres>';
			$ret = $response;
			env::response($response,'xml');
		}else{
			echo $html;
			//tools::dump($jpfor);
			//tools::dump($jpuser);
		}
		
		//dbase::close();
		exit(0);
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* 0 : Urgence : le système est inutilisable
	*
	* @access public
	* @param string $txt	The text of event to log.
	* @return $logs array
	*/
	public static function emrg($txt,$show=true){
		debugger::logger('EMRG',$txt,$show);
	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* 1 : Alerte: une mesure corrective doit être prise immédiatement
	*
	* @access public
	* @param string $txt	The text of event to log.
	* @return $logs array
	*/
	public static function alert($txt,$show=true){
		debugger::logger('ALERT',$txt,$show);	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* 2 : Critique : états critiques
	*
	* @access public
	* @param string $txt	The text of event to log.
	* @return $logs array
	*/
	public static function critic($txt,$show=true){
		debugger::logger('CRITIC',$txt,$show);	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* 3 : Erreur: états d'erreur
	*
	* @access public
	* @param string $txt	The text of event to log.
	* @return $logs array
	*/
	public static function error($txt,$show=true){
		debugger::logger('ERROR',$txt,$show);	
	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* 4 : Avertissement: états d'avertissement
	*
	* @access public
	* @param string $txt	The text of event to log.
	* @return $logs array
	*/
	public static function warn($txt,$show=true){
		debugger::logger('WARN',$txt,$show);	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* 5 : Notice: normal mais état significatif
	*
	* @access public
	* @param string $txt	The text of event to log.
	* @return $logs array
	*/
	public static function notice($txt,$show=true){
		debugger::logger('NOTIC',$txt,$show);	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* 6 : Information: messages d'informations
	*
	* @access public
	* @param string $txt	The text of event to log.
	* @return $logs array
	*/
	public static function info($txt,$show=true){
		debugger::logger('OK',$txt,$show);
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* 7 : Debug: messages de déboguages
	*
	* @access public
	* @param string $txt	The text of event to log.
	* @return $logs array
	*/
	public static function debug($txt,$show=true){
		debugger::logger('DEBUG',$txt,$show);
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Save logFile when exit
	*
	* @access public
	* @return  bool
	*/
	public static function xit(){
	
	}



}
?>