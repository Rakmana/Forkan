<?php
class registry{
public $vars = array();
public $status = array();

	
	/**
	 * registry::init()
	 * 
	 * @return
	 */
	public static function init(){
		// create the registry object
		$GLOBALS['REGISTRY'] = kore::getInstance('registry');
					
		// Lets make sure we save serialized data
		register_shutdown_function(array($GLOBALS['REGISTRY'],'xit'));
	}	

	/**
	 * registry::create()
	 * 
	 * @param mixed $key
	 * @param mixed $var
	 * @return
	 */
	public static function create($key, $var) {
		if (isset($GLOBALS['REGISTRY']->vars[$key]) == true) {
			$error='Sorry, key already exist!';
			debugger::warn($error);
		}
		registry::set($key,$var);
		return true;
	}
	/**
	 * registry::set()
	 * 
	 * @param mixed $key
	 * @param mixed $var
	 * @return
	 */
	public static function set($key, $var) {
		$GLOBALS['REGISTRY']->vars[$key] = $var;
		//btrack('Key: '.$key);
	}
	/**
	 * registry::get()
	 * 
	 * @param mixed $key Name
	 * @param bool $ref ()Passe by Referance)
	 * @return
	 */
	public static function get($key,$ref=false) {
		if (@!isset($GLOBALS['REGISTRY']->vars[$key])) {
			//debugger::warn("sorry, Key not found :  #$key!");
			return null;
		}
		if($ref !=false){
			$ret =& $GLOBALS['REGISTRY']->vars["$key"];
			return $ret;
		}
		else
			return $GLOBALS['REGISTRY']->vars["$key"];
	}
	/**
	 * registry::add()
	 * 
	 * @param mixed $key
	 * @param mixed $var
	 * @return
	 */
	public static function add($key, $var) {
		if(isset($GLOBALS['REGISTRY']->vars[$key])){
		$GLOBALS['REGISTRY']->vars[$key] .= $var;}
	}
	/**
	 * registry::remove()
	 * 
	 * @param mixed $key
	 * @return
	 */
	public static function remove($key) {
		unset($GLOBALS['REGISTRY']->vars[$key]);
	}
	
	/**
	 * registry::xit()
	 * 
	 * @return
	 */
	public static function xit(){
	}
	
	
}
?>