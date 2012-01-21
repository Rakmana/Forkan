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
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Check if installed or not yet
	*
	*/
	function isInstalled() {
		$var = konfig::isOn('installed');
		return $var;

	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Check if installed or not yet
	*
	*/
	function install() {
		if(self::isInstalled()){exit;};
		
		$sql = flib::get_contents(KROT.'wpl.sql');
		
		
		dbase::query($sql);
		echo 'installed ...';

	}
}

/**
* JPRIV
* Privillege and access rights (ACR)Class
*
* @package  JSAP
*/
class jpriv{	
	/**
	* Parse ACR code (s)
	*
	* @param string		$rlevel (ACR row code myb 1 or more separ with (,) e.g: '1.2.5:x'
	* @return array		$ACR (ACR array e.g: [j=x, 0=1, 1=2, 2=5] or array of ACR array if more then 1 code)
	*/
	function parse($rlevel){
		
		$levels = explode(',',$rlevel);
		//if more then one 
	  if(count($levels)>1){
		foreach ($levels as $ulvl){
			$ret[] = jpriv::parse($ulvl);
		}	
	  }
	  else{
		$g = explode(':',$rlevel);
		$ret = explode('.',$g[0]);//area 
		$ret['j'] = isset($g[1])? $g[1] : 'v';//mode e:edit | v:view
	  }
		return $ret;
		
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Check if a user has a ACR to do an action
	*
	* @param string		$level (action ACR code)
	* @param string		$ulevel (user ACR code myb > 1)
	* @return bool	 (user has the right? true : false)
	*/
	function saticfy($level,$ulevel){
		$ret = false;
		//select the min table for the loop
		//$min = (count($ulevel) < count($ulevel))? count($ulevel) : count($level);
	  if(is_array($ulevel[0])){
		foreach ($ulevel as $ulvl){	
			//tools::dump($ulvl);
			if(jpriv::saticfy($level,$ulvl) != false){
				$ret = true;
				break;
			}else
				$ret = false;
		}
	  }
	  else{
		$count = min(count($level) , count($ulevel));
		
		if(($ulevel['j'] == $level['j']) //if the same level action right
			or((strtoupper($ulevel['j']) == 'E') and (strtoupper($level['j']) == 'V')) //if edit so allow view
			or((strtoupper($ulevel['j']) == 'D') and (strtoupper($level['j']) == 'E')) //if delete so allow edit
			or((strtoupper($ulevel['j']) == 'D') and (strtoupper($level['j']) == 'V')) //if delete so allow view
			or((strtoupper($ulevel['j']) == 'N') and (strtoupper($level['j']) == 'E')) //if insert so allow edit
			or((strtoupper($ulevel['j']) == 'N') and (strtoupper($level['j']) == 'V')) //if insert so allow view
			or (strtoupper($ulevel['j']) == 'X')){//have all actions rights
		
		  for($i=0;$i<($count-1);$i++ ){
		  
			//if isset 3+2 so all other failure
			if(stristr($ulevel[$i],"+")!= false){
				$d = explode('+',$ulevel[$i]);
				for($j=0;$j<(count($d));$j++ ){
					if($d[$j] == $level[$i]){
						$ret = true;
						break;
					}
					else
						$ret = false;
				}
				if($ret != true) break;
			
			}
			//if isset -2 so all other success
			elseif(stristr($ulevel[$i],"-") != false){
				$m = explode('-',$ulevel[$i]);
				for($j=1;$j<(count($m));$j++ ){
					if($m[$j] == $level[$i]){
						$ret = false;
						break;
					}
					else
						$ret = true;
				}
				if($ret != true) break;
			
			}
			elseif(($ulevel[$i] == $level[$i]) or( strtoupper($ulevel[$i]) == 'X')){
				$ret = true;
				if(strtoupper($ulevel[$i]) == 'X'){//if get (x) so no need to deep under 
					break;
				}
			}
			else{
				$ret = false;
				break;
			}
		  }  
		}
		else{
			$ret = false;
		}
	  }
		
		return $ret;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Check if current user has a ACR to do an action
	*
	* @param string		$level (action ACR code)
	* @return bool	 (c.user has the right? true : false)
	*/
	function jhas($level){
		$jpuser = jpriv::parse(user::getAcr());
		$jpfor  = jpriv::parse($level);
		
		return jpriv::saticfy($jpfor,$jpuser);
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Define the next step is require the given ACR be owned by Current user or exit
	*
	* @param string		$level (action ACR code)
	* @return mixed	 (c.user has the ACR? true : notify('No Permissions') and exit)
	*/
	function jfor($level){
	   
       
        if(user::jlogged() != true){
		if(router::isajx()){
			
            /*$time = kjxProfiler();
		
			
			//organize in xml format
			$response ='<?xml version="1.0" encoding="UTF-8"?>'."\n";
			$response.='<wres>'."\n";
			$response.='	<wntf>'.$notifications.'</wntf>'."\n";
			$response.='	<wctn></wctn>'."\n";
			$response.='	<wpfr>'.$time.'</wpfr>'."\n";
			$response.='</wres>';*/
            jawab::setNotification(form::notify('alert',txt('relogin')));
            jawab::setContent('');
            $response = env::getXMLresponse();
			env::response($response,'xml');
		}else{
			jawab::setNotification(form::notify('alert',txt('relogin')));
            jawab::setContent(loader::getForm(PAPP.'common'.DS.'login.php'));
            loader::htmlpage();
			//tools::dump($jpfor);
			//tools::dump($jpuser);
		}
		dbase::close();
		exit(0);            
        }
        
        
		$jpuser = jpriv::parse(user::getAcr());
		$jpfor  = jpriv::parse($level);
		
		if(jpriv::saticfy($jpfor,$jpuser) != true){	
		//4ajax profiler
		if(router::isajx()){
			$time = kjxProfiler();
		
			$notifications = '<![CDATA['.form::notify('alert',txt('nopermissions')).']]>';
			//organize in xml format
			$response ='<?xml version="1.0" encoding="UTF-8"?>'."\n";
			$response.='<response>'."\n";
			$response.='	<notification>'.$notifications.'</notification>'."\n";
			$response.='	<content></content>'."\n";
			$response.='	<profiler>'.$time.'</profiler>'."\n";
			$response.='</response>';
			env::response($response,'xml');
		}else{
			echo form::notify('alert',txt('nopermissions'));
			//tools::dump($jpfor);
			//tools::dump($jpuser);
		}
		dbase::close();
		exit(0);
		}
		else return true;
		
	}

}
    function get_class_static() {
        $bt = debug_backtrace();
    
        if (isset($bt[1]['object']))
            return get_class($bt[1]['object']);
        else
            return $bt[1]['class'];
    }

/**
* APPLICATION
* Abstract Application Class
*
* @package  WAPL
*/
Abstract Class application {
    public static $name ;	
	/**
	* construct function just for logging 
	*
	* @access private
	*/
	function __construct() {
		btrack('APP: '.get_class($this));
        self::$name = get_class($this);
	}
    public static function icm(){

       //return get_called_class();
       return self::$name;
       //return get_class($this);
    }

	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++		
	/**
	* Abstract Init function where we can load application lang & media file
	*
	*/	
	abstract function init();	
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Abstract Index: default job 
	*
	* @access public
	*/
	abstract function index();	
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Init application view - now just app infos
	*
	* @access public
	*/
	function initView(){
        //self::$name = get_class($this);
	  //konfig::load(APPC,PAPP.APPC.DS.APPC.'.konfig.xml');
	  /*if(!router::isajx()){
		$app = registry::get('app');
		$appinfo = applications::infoBox($app);
		registry::set('appinfo',$appinfo);
        
	  }

        if('' == (registry::get('title'))){
            //--- if has lang file then get title from there
            if('Text:_apptitle' != (txt('apptitle',self::icm()))){
                registry::set('title',txt('apptitle',self::icm()));
            }
            else
		      registry::set('title',konfig::get('name',self::$name));
        }*/
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Load application language file to langs
	*
	* @param string		$name lang id e.g: main
	* @return 
    */
    function loadlang($name='main'){
       $lng = registry::get('lang');
       return tongue::load(PAPP.self::icm().DS.'languages'.DS.$lng.'.'.$name.'.php');
               
    }
    
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Get application string from its lang
	*
	* @param string	  $str string 
	* @return 
    */
    function txt($str){
		$ret = (tongue::txt($str,self::icm()) != 'Text:_'.$str)? tongue::txt($str,self::icm()) : tongue::txt($str);
        return $ret ;
               
    }
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Get application konfig from its xml
	*
	* @param string	  $str string 
	* @return 
    */
    function cfg($str){
        return konfig::get($str,self::icm());
               
    }
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Load application Media file to output
	*
	* @param string		$type (media type in:[css,js])
	* @param string		$path (media file path)
	*/
	function loadmedia($type,$path,$callback=""){
	if(router::issite()){
		if($type=='js'){
			$jpk = konfig::isOn('jpacker',self::icm())? 'jpk=1': '';
			$ret = "jetScript('".form::mlink('js',$path,$jpk)."',function(){".$callback."});";
		}
		if($type=='css'){
			$ret = "loadcss('".form::mlink('css',$path)."',function(){".$callback."});";
		}
		registry::add('js', $ret);
	}
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Load application html Form and add to output
	*
	* @param string	 GET::$fid (form id Via GET)
	*/
	function form(){
		//if(!router::isajx()) return false;
        
        //TODO:FIX this bug mayb GET
		$form = $_REQUEST['fid'];
        if(flib::exists(PAPP.self::icm().DS.$form.'.'.self::icm().'.php')){
		  form::sendForm(PAPP.self::icm().DS.$form.'.'.self::icm().'.php');
        }
        else{
            jawab::setNotification(form::notify('warn','FormID not valide !!'));
            //$cls = self::icm();
            //$cls::index();   
        }
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Load application html Form and add to output
	*
	* @param string	 GET::$fid (form id Via GET)
	*/
	function getForm($form){
        $path = PAPP.self::icm().DS.$form.'.'.self::icm().'.php';
        if(flib::exists($path)){
		  return loader::getForm($path);
        }
        else
            jawab::setNotification(form::notify('warn','FID not found !!'.$path));
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Application Login to system extended  from User::Login
	*
	* @param bool  $jForm (show login form)
	*/
	function login($jForm=false,$red=false){
		user::login($jForm,$red);
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Application Logout to system extended  from User::Logout
	*
	* @access public
	*/
	function logout(){
		user::logout();
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	/**
	* Application konfig edit form : get settings in app.konfig.xml
	*
	* @access public
	*/
	function showkonfig(){
	   jawab::setTitle(tongue::txt('cpanel').' - '.jawab::getTitle());
	   jawab::setAjxTitle(jawab::getTitle());
	   konfig::showkonfig(self::icm());
    }
     /**
     * Save konfig settings to xml file
     * 
     * @param string $section
     * @return
     */
    function savekonfig($section = 'sys')
    {
        
	   jpriv::jfor('1:x');
	$keys =& $GLOBALS['KONFIG']->xml[$section]->xpath("//key[@ui=1]");
        $errors = 0;
		foreach($keys as $key){
			$ktp = $key['type'];
			$knm = $key['name'];
			$kvl = $key[0];
		    if(tools::Jet("$knm-frm") != 'NULL'){
		      if($ktp != 'bol' ){
                konfig::set($knm,tools::Jet("$knm-frm",''),$section);
              }
			  else{
			     $val = (tools::Jet("$knm-frm",'') != '1')? 'off' : 'on'; 
                konfig::set($knm,$val,$section);
              }
		    }
            else{
                $errors++;
                registry::set('contents','<saved>0</saved>');
                return;
            }
       }	
	   
            registry::set('contents','<saved>1</saved>');
		
}	
}
/**
* JAWAB
* Response vars manager
*
* @package  WAPL
*/
class jawab{
   //-------------------------- contents --------------------------------
   function addContent($content){
        return registry::add('contents',$content);
   } 
   function setContent($content){
        return registry::set('contents',$content);
   } 
   function getContent(){
        return registry::get('contents');
   } 
   
   //-------------------------- notifications --------------------------------
   function addNotification($notifs){
        return registry::add('notifications',$notifs);
   } 
   function setNotification($notifs){
        return registry::set('notifications',$notifs);
   } 
   function getNotification($notifs){
        return registry::get('notifications');
   } 
   
   //-------------------------- title --------------------------------
   function addTitle($title){
        return registry::add('title',$title);
   } 
   function setTitle($title){
        return registry::set('title',$title);
   } 
   function getTitle(){
        return registry::get('title');
   }
   
   //-------------------------- ajax title --------------------------------
   function addAjxTitle($title){
        return registry::add('ajxtitle',$title);
   } 
   function setAjxTitle($title){
        return registry::set('ajxtitle',$title);
   } 
   function getAjxTitle(){
        return registry::get('ajxtitle');
   }
    
}

/**
* LOADER
* File & class loader
*
* @package  WAPL
*/
class loader{
	/**
	* Loads a file from any location.
	*
	* @param string $file (file path ( dot notation ).)
	* @return bool (file loaded ? true : false )
	*/
	function import( $file){
	
		if(is_file($file)){
			include_once($file);
            btrack('INC: '.$file);
			return true;}
		else{
			//echo 'file not found: '.$file;
			return false;}
	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++		
	/**
	* show Index.html.php from active skin folder.
	*
	* @access public
	*/
	function htmlpage(){
	
			@header("content-type: text/html;  charset=UTF-8");
			@header("X-Powered-By: ".WPL_ASM." ".WPL_VERSION);
			//check if browser accept gzip encoding format
			$encoding = env::encoding();
			if(($encoding == 'gzip' || $encoding == 'x-gzip')&& konfig::isOn('gzip') && !ini_get('zlib.output_compression') && ini_get('output_handler')!='ob_gzhandler'){
				@header('Content-Encoding: '.$encoding);
				@header('X-Content-Encoded-By: '.WPL_ASM." ".WPL_VERSION);	
				ob_start("ob_gzhandler");
			}
			else
				ob_start();
			//btrack('*******[ SHOWTIME ]******* ');	
			//show index template
			loader::import(PSKN.'index.php');
			loader::import(PSKN.'index.html.php');
			
			ob_end_flush();
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++		
	/**
	* Load html form template file
	*
	* @param string $formPath	(file path ( dot notation ).)
	* @return mixed (file loaded ? form : false )
	*/
	function getForm($formPath=''){
	ob_start();	
	//load form template
	if(include($formPath)){
	$out = ob_get_contents();
	ob_end_clean();
	return $out;
	}
	else
		return false;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++		
	/**
	* Load template file
	*
	* @param string $filePath	(file path ( dot notation ).)
	* @return mixed (file loaded ? Output : false )
	*/
	function getMedia($filePath=''){
	ob_start();	
	//load form template
	if(loader::import($filePath)){
	$out = ob_get_contents();
	ob_end_clean();
	return $out;
	}
	else
		return false;
	}
}
	
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
$app  = PAPP . $class_name . DS . $filename;


if (file_exists($file) == false) {
	if (file_exists($app) != false){
		
		loader::import($app);
		
		/*$kon_cn = 'kon_'.$app;*/
        $cls = kore::getInstance($class_name);

        //load application konfig file
        applications::getAppConfig($class_name);

        $cls->init();
        $cls->initView();
		btrack('AP2: '.$class_name);
		return true;
	}
	else
	return false;
}

include_once ($file);

btrack('LIB: '.$class_name);
} 



?>