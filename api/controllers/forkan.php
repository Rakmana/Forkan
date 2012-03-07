<?php

//include our models
include_once 'models/forkan.php';

metaQuran::init();

class Forkan
{
	private $_params;
	
	public function __construct($params)
	{
		$this->_params = $params;
	}
	
	public function getAction()
	{
		//read all the todo items while passing the username and password to authenticate
		$items = ForkanData::getAya($this->_params['yid'],$this->_params['nbr']);
		//var_dump($items);
		//return the list
		return  (array)$items;
	}/********************************[ Suras functions ]************************************/
	 /**
     * forkan::getSuraFromAya()
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return
     */
    public function getSuraAction(){
	    $ayaID = $this->_params['yid'];
		$riwayaID = '1';
        $y = dbase::jfetch("SELECT sura FROM quran q WHERE q.index = '$ayaID' and q.riwaya = '$riwayaID'");
        $s = metaQuran::getAllAttr('sura',$y['sura']);    
        
        return (array) $s;    
    }

}

?>