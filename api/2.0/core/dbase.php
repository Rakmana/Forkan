<?php
/**
 * WAPL MySQL DataBase Interface
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: dbase.php,v2.0 09:50 09/03/2010 NP++ Exp $
*/ 

   $GLOBALS['QUERIES_COUNT'] = 0;
   $GLOBALS['DBlinkID'] = 0;

/**
* DBASE
* Database Interface
*
* @package  WAPL
*/	
class dbase {

	/**
	* Initialize Database Inteface
	*
	*/
	public static function init(){
	  dbase::connect();
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Connect To Global Database
	* Dbase Params get from config.xml
	*
	*/
	public static function connect() {
	if(!dbase::isConnected()){//if not connected then connect
		$host = 'localhost';
		$user = 'root';
		$pass = '1723';
		$base = 'skybook';
        
		$GLOBALS['DBlinkID'] = mysqli_connect("$host","$user","$pass") or die("JBase::ConnectDB : <br />" .(($GLOBALS['konfig']['error_mysql'] != false )? (mysqli_connect_error()) : 'JME-'.mysqli_connect_errno()));
		
		mysqli_select_db($GLOBALS['DBlinkID'],"$base") or die(dbase::jError("JBase::SelectDB"));
		dbase::query("SET CHARACTER SET 'utf8'");

		$GLOBALS['CONNECTED'] = true;
        return true;
	} 
	}

	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Halt on DBase error and show Error Msg if admin
	*
	* @param string		$Text User message
	* @return string	Generated error in Notification Area
	*
	*/
	public static function jError($text){
		 echo '<div style="padding-left:40px;text-align:left;">JBase::Error: <br />'.(($GLOBALS['konfig']['error_mysql'] != false )? $text."<br />JError:  <small>".mysqli_error($GLOBALS['DBlinkID'])."</small>" : 'JME-'.mysqli_errno($GLOBALS['DBlinkID']))."</div>";
         exit();
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Get DataBase Link ID.
	*
	* @return mixed	DataBase Link ID
	*
	*/
	public static function getID(){
	 return $GLOBALS['DBlinkID'];
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Check If DataBase Connection.
	*
	* @return bool	Connected or not
	*
	*/
	public static function isConnected(){
		if(isset($GLOBALS['CONNECTED']) && $GLOBALS['CONNECTED'] = true){
			return true;
		}
		else return false;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Execute SQL Query in DataBase.
	*
	* @param String	SQL Query
	* @return mixed	Query ID or Error iNotify
	*
	*/
	public static function query($Query_String) {
		$GLOBALS['Query_ID'] = mysqli_query(dbase::getID(),$Query_String)or die(dbase::jError("JBase::Query => <small>".$Query_String."</small>\n"));
        
        $GLOBALS['QUERIES_COUNT']++ ;
        //@btrack('QRY Dbase Query °'.$GLOBALS['QUERIES_COUNT']);
		
        return $GLOBALS['Query_ID'];
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Execute SQL Query in DataBase.
	*
	* @param String	SQL Query
	* @return mixed	Query ID or Error iNotify
	*
	*/
	public static function jfetch($Query_string) {
		$array = mysqli_fetch_assoc(dbase::query($Query_string));
		//dbase::free();
		return $array;
	}
	public static function fetch($Query_ID) {
		if($Query_ID == "") $Query_ID=$GLOBALS['Query_ID'];

		return mysqli_fetch_assoc($Query_ID);//or die
	}
	public static function jNum($Query_string) {
		$num = mysqli_num_rows(dbase::query($Query_string));
		dbase::free();
		return $num;
	}
	public static function num($Query_ID="") {
		if($Query_ID == "") $Query_ID=$GLOBALS['Query_ID'];
		$num = mysqli_num_rows($Query_ID);//or die(dbase::jError('jBase::Num => '.$Query_ID));
		return $num;
	}
	public static function free($Query_ID="") {
		if($Query_ID == "") $Query_ID=$GLOBALS['Query_ID'];
		mysqli_free_result($Query_ID);
	}
	public static function close(){
	mysqli_close($GLOBALS['DBlinkID']);
	}
	}
   
    dbase::init();

?>
