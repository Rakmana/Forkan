<?php



		
class ForkanData
{
	public $todo_id;
	public $title;
	public $description;
	public $due_date;
	public $is_done;


/********************************[ Ayas functions ]************************************/
    /**
     * Get aya info as array
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return array [index,sura,aya,text,riwaya]
     */
    public static function getAya($ayaID, $ayaCount, $riwayaID = '1'){
        //index  sura  aya  text  riwaya  view  
        $ret = array();
        $q = dbase::query("SELECT q.* FROM quran q WHERE (q.index >= $ayaID AND q.index < ($ayaID+$ayaCount))  AND q.riwaya = $riwayaID");
                
        while ($y = mysqli_fetch_array($q,MYSQLI_NUM)){
            $ret[] = $y;
         
        //echo "<pre>";var_dump($y);echo "</pre>";
        }
   
        //var_dump($ret);     
        
        dbase::free($q);
        
        return (array)$ret;
       
    }

    /**
     * Get translated Aya info as array
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return array [index,sura,aya,text,riwaya]
     */
    public static function getTrans($ayaID,$transID = '1'){
        //index  sura  aya  text  TransID  view 
        $y = dbase::jfetch("SELECT q.* FROM translations q WHERE index=$ayaID and q.transID = $transID");
        
        return $y;
       
    }    
    /**
     * Get Aya index from SuraID+AyaPos
     * 
     * @param mixed $sura
     * @param mixed $aya
     * @return void
     */
    public static function getAyaIndex($sura,$aya,$riwayaID = '1'){
        $y = dbase::jfetch("SELECT q.index FROM quran q WHERE q.sura = $sura AND q.aya = $aya and q.riwaya = $riwayaID");

        return $y['index'];        
    }
    /**
     * Get SuraID+AyaPos from Aya index
     * 
     * @param mixed $sura
     * @param mixed $aya
     * @return void
     */
    public static function getAyaFromIndex($index,$riwayaID = '1'){
        $y = dbase::jfetch("SELECT q.sura,q.aya FROM quran q WHERE q.index = $index and q.riwaya = $riwayaID");

        return $y;        
    }    

}


class metaQuran
{
    public $xml = array();

    public static function init()
    {
        // create the KONFIG object
        metaQuran::load(KROT .'models'.DS.'quran-data.xml');
        // Lets make sure we save serialized data
    }

    public static function load($xmlfile)
    {
        $GLOBALS['WQX'] = kore::getInstance('metaQuran');
        
        $GLOBALS['WQX']->xml['data']  = xmldoc::import($xmlfile);
        $GLOBALS['WQX']->xml['data2'] = xmldoc::import(KROT .'models'.DS.'quran-data.xml');
        //btrack('KFG File loaded: '.$section);
    }

    
    /**
     * Get key Attribute from xml
     * 
     * @param mixed $name
     * @param mixed $index
     * @param mixed $attr
     * @return mixed Attribute OR False
     */
    public static function getAttr($name,$index,$attr)
    {
        $element = @$GLOBALS['WQX']->xml['data']->xpath("//".$name."[@index='$index']");
        
        if (!isset($element[0])){
            return false; 
            
        }
        
        
        
        $atts = $element[0]->attributes();
        if (!isset($atts[$attr])){
            return false; 
        }
        
        $ret = $atts[$attr];
        //tools::dump($ret);
        
        
        return $ret;

    }
    //--- will b removed
	public static function rep(){
        
		$els = $GLOBALS['WQX']->xml['data']->xpath("//sura");
        
        foreach ($els as  $e ){
		    $atts = $e->attributes();
        	$ndx = $atts['index'];
			
			$e2 = $GLOBALS['WQX']->xml['data2']->xpath("//sura[@index='$ndx']");
		    $atts2 = $e2[0]->attributes();
        	$name2 = $atts2['name'];
			
			if (!empty($name2)){
				$atts['name'] = $name2;
			}
		
		}
        
		$GLOBALS['WQX']->xml['data']->asXML(PAPP .'forkan'.DS. 'quran-data.xml');        
        return true;	
	}
    /**
     * Get All attributes of the given key
     * 
     * @param mixed $name
     * @param mixed $index
     * @return
     */
    public static function getAllAttr($name,$index){
        $element = $GLOBALS['WQX']->xml['data']->xpath("//".$name."[@index='$index']");
        
        if (!isset($element[0])){
            return false; 
            
        }

        $ret = $element[0]->attributes();
     
        return $ret;        
    }
    
    /**
     * Get sura metadata
     * [index,ayas,start,name,tname,ename,type(Medinan,Meccan),order,rukus]
     * 
     * @param mixed $index
     * @param mixed $meta 
     * @return mixed Meta OR False
     */
    public static function getSuraMeta($index,$meta){
        return self::getAttr('sura',$index,$meta);
    }
    
    /**
     * Get Juz metadata
     * [index,sura,aya]
     * 
     * @param mixed $index
     * @param mixed $meta
     * @return mixed Meta OR False
     */
    public static function getJuzMeta($index,$meta){
        return self::getAttr('juz',$index,$meta);
    }
    
    /**
     * Get Quarter metadata
     * [index,sura,aya]
     * 
     * @param mixed $index
     * @param mixed $meta
     * @return mixed Meta OR False
     */
    public static function getQuarterMeta($index,$meta){
        return self::getAttr('quarter',$index,$meta);
    }
    
    /**
     * Get Page metadata
     * [index,sura,aya]
     * 
     * @param mixed $index
     * @param mixed $meta
     * @return mixed Meta OR False
     */
    public static function getPageMeta($index,$meta){
        return self::getAttr('page',$index,$meta);
    }


    function xit()
    {
        $GLOBALS['WQX']->xml['data']->asXML(KROT .'models'.DS.'quran-data.xml');
    }

    /**
     * konfig::save()
     * 
     * @param mixed $section
     * @param mixed $xmlfile
     * @return
     */
    function save($section, $xmlfile)
    {
        $GLOBALS['WQX']->xml['data']->asXML($xmlfile);
    }


}

