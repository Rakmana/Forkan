<?php
/**
 * @package JSAP
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version 1.0
*/ 
global $counter;

$counter=array();

$counter['skn'] = 0;
$counter['tng'] = 0;
$counter['plg'] = 0;


/**
 * define common privet path as constant 4 access to it from any where
 */
define('WPL_VERSION', '1.0');
define('WPL_ASM', 'Wapl');



/**
* KERNEL
* Kernel Of JSAP
*
* @package  JSAP
*/
class kore{
	/**
	* Get Instance or create one of given class
	*
	* @param string		$class (class name)
	* @return array		object (Instance of class)
	*/
	public static function getInstance($class){
		static $instances;

		if (!isset( $instances ))
			$instances = array();
		
		if (empty($instances[$class])){
			$instance = new $class;
			
			$instances[$class] =& $instance;
		}
		return $instances[$class];
	}

    public function serialize($class_name){
           $serialized = array();
           foreach(array_keys(get_class_vars($class_name)) as $key)
               eval('$serialized[\''.$key.'\'] = $class->'.$key.';');
           return serialize($serialized);
       }
    public function unserialize($serialized, &$class){
           $data = unserialize($serialized);
           foreach($data as $prop => $val)
               $class->$prop = $val;
           return true;
       }

}
    function get_class_static() {
        $bt = debug_backtrace();
    
        if (isset($bt[1]['object']))
            return get_class($bt[1]['object']);
        else
            return $bt[1]['class'];
    };
	
/**
* auto class loader
*
* @access public
* @param string $class_name	(class file path )
* @return bool (class loaded ? true : false )
*/
function __autoload($class_name) {

$filename = strtolower($class_name) . '.php';
$file = PSYS . $filename;
if(file_exists($file)){
   include_once ($file);
}

} 



?>