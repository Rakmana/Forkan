<?php

class session{


	/**
	 * session::init()
	 * 
	 * @return
	 */
	public static function init(){	
	
		session::icookies();
		//session ID
		if(session_id() ==""){
			session::free();
		}	
		session_id(session::jToken());
		session_start();
		
		session::counter();
		
		
		// Send modified header for IE 6.0 Security Policy
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
		
		// Lets make sure we save serialized data
		register_shutdown_function(array('session','xit'));
	}

	/**
	 * session::counter()
	 * 
	 * @return
	 */
	public static function counter(){
		if (!isset($_SESSION['counter'])) {
		   $_SESSION['counter'] = 0;
		} else {
		   $_SESSION['counter']++;
		}
	}

	//maybe we create new function for handling this
	/**
	 * session::icookies()
	 * 
	 * @param mixed $path
	 * @param mixed $domain
	 * @return
	 */
	public static function icookies($path='', $domain='') {
	    $path2   = ($path != '')? $path : (KBAS != '/' ?  SH . KBAS . SH : KBAS) ;
	    $domain2 = ($domain != '')? $domain : (KBAS != '/' ?  SH . KBAS . SH : KBAS);
		if (function_exists('session_set_cookie_params')) {
		   session_set_cookie_params(0, $path2, $domain2);
		} else {
		   ini_set('session.cookie_lifetime', 0);
		   ini_set('session.cookie_path', $path2);
		   ini_set('session.cookie_domain', $domain2);
		}
	}

	/**
	 * session::jSetCookie()
	 * 
	 * @param mixed $name
	 * @param mixed $value
	 * @param integer $expire
	 * @param mixed $path
	 * @return
	 */
	public static function jSetCookie($name,$value,$expire=0,$path=''){
	    $path2 = ($path != '')? $path : (KBAS != '/' ?  SH . KBAS . SH : KBAS);
	    //$domain2 = ($domain != '')? $domain : KBAS;
		if(!setcookie($name,$value,$expire,$path2)){
			return false;}
		else 
			return true;
	}

	/**
	 * session::jDelCookie()
	 * 
	 * @param mixed $name
	 * @param integer $expire
	 * @param mixed $path
	 * @return
	 */
	public static function jDelCookie($name,$expire=0,$path=''){
	    $path2 = ($path = '')? (KBAS != '/' ?  SH . KBAS . SH : KBAS) : $path;
		if(!setcookie($name,'0',time()-3600,$path2))
		return false;
	}
	
	/**
	 * session::jGetCookie()
	 * 
	 * @param mixed $name
	 * @return
	 */
	public static function jGetCookie($name){
		if(!empty($_COOKIE[$name])){
			return $_COOKIE[$name];
		}
		else 
			return false;
	}
	
	/**
	 * session::set()
	 * 
	 * @param mixed $key
	 * @param mixed $data
	 * @return
	 */
	public static function set($key,$data){
		$_SESSION[$key] = $data;
		return;
	}

	/**
	 * session::remove()
	 * 
	 * @param mixed $key
	 * @return
	 */
	public static function remove($key){
		empty($_SESSION[$key]);
		unset($_SESSION[$key]);
		return true;
	}

	/**
	 * session::get()
	 * 
	 * @param mixed $key
	 * @return
	 */
	public static function get($key){
		if(isset($_SESSION[$key])){
			$ret =& $_SESSION[$key];
			return $ret;
		}
		else return false;
	}

	/**
	 * session::jToken()
	 * 
	 * @return
	 */
	public static function jToken(){
		return 'WPL-'.md5(user::getID()+'-'+$_SERVER['HTTP_USER_AGENT']+'@'+tools::realip());
	}

	/**
	 * session::free()
	 * 
	 * @return
	 */
	public static function free(){
		session_unset();
	}
	
	/**
	 * session::destroy()
	 * 
	 * @return
	 */
	public static function destroy(){
		@session_unset();
		@session_destroy();
	}

	/**
	 * session::xit()
	 * 
	 * @return
	 */
	public static function xit(){
		//session::destroy();
	
	}
} 

?>