<?php


//--- function to make param via array system without error
function rg($name,$args){
   if (isset($args[$name])){
     return $args[$name];
   } 
   else{
     return false;
   }
};
	
    
    	
class ForkanData
{



/********************************[ Ayas functions ]************************************/
    /**
     * Get aya info as array
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return array [index,sura,aya,text,riwaya]
     */
    public static function getAyas($args){
        //index  sura  aya  text  riwaya  view  
	    return  R::getAll('SELECT * FROM quran WHERE `riwaya` = :rw AND `index` >= :id AND `index` < (:id+:nbr) ',array(':rw'=>1,':id'=>rg('id',$args),':nbr'=> rg('nbr',$args)));

       
    }  
    /**
     * Get Ayas per Page 
     * 
     * @param mixed $args = [pageID, riwayaID]
     * @return array [index,sura,aya,text,riwaya]
     */
    public static function getAyasPerPage($args){
        //index  sura  aya  text  riwaya  view  
		$p1 = metaQuran::getAllAttr('page',rg('id',$args),'sura');
		$p2 = metaQuran::getAllAttr('page',rg('id',$args)+1);
		
		$start = (ForkanData::getAyaIndex($p1['sura'],$p1['aya'],1));
		$end   = (ForkanData::getAyaIndex($p2['sura'],$p2['aya'],1))-$start;
//var_dump($start);
//exit;
	    return  R::getAll('SELECT * FROM quran WHERE `riwaya` = :rw AND `index` >= :id AND `index` < (:id+:end) ',array(':rw'=>1,':id'=>$start,':end'=>$end));

       
    } 
    /**
     * Get Tafseer per Page 
     * 
     * @param mixed $args = [pageID, riwayaID]
     * @return array [index,sura,aya,text,riwaya]
     */
    public static function getTafseerPerPage($args){
        //index  sura  aya  text  riwaya  view  
		$p1 = metaQuran::getAllAttr('page',rg('id',$args),'sura');
		$p2 = metaQuran::getAllAttr('page',rg('id',$args)+1);
		
		$start = (ForkanData::getAyaIndex($p1['sura'],$p1['aya'],1));
		$end   = (ForkanData::getAyaIndex($p2['sura'],$p2['aya'],1))-$start;
//var_dump($start);
//exit;
	    return  R::getAll('SELECT * FROM tafseer WHERE `tafseerID` = :TF AND `index` >= :id AND `index` < (:id+:end) ',array(':TF'=>1,':id'=>$start,':end'=>$end));

       
    }     

/********************************[ Other functions ]************************************/

    public static function getSuras(){
    // [start, ayas, order, rukus, name, tname, ename, type]
	//[],
	//[0, 7, 5, 1, 'الفاتحة', "Al-Faatiha", 'The Opening', 'Meccan'],
//index="1" ayas="7" start="0" name="الْفَاتِحَة" tname="Al-Faatiha" ename="The Opening" type="Meccan"     
        $els = $GLOBALS['WQX']->xml['data']->xpath("//sura");
        $ht = array();//'[],';       
		$i = 1;
        foreach ($els as  $e ){
		    $atts = $e->attributes();
            
            //--- add 1 to start aya
            $atts['start'] += 1;     
			
			//--- add index
			$atts['index'] = $i;
			
            $ht[] = $atts;
            
            $i++;
		}
        
		//$ht .= '[6236, 1]'  ;
        
        return $ht;    
    }

    public static function getPages(){
    // [index, sura, aya]   
	// <page index="1" sura="1" aya="1"/>
        $els = $GLOBALS['WQX']->xml['data']->xpath("//page");
        $ht = array();//'[],';
        foreach ($els as  $e ){
		    $atts = $e->attributes();
            
            //--- add 1 to start aya
            $atts['start'] += 1;

			
            $ht[] = $atts;
            //$ht .= '['.$atts['start'].','.$atts['ayas'].','.$atts['order'].','./*$atts['rukus'].*/',';
            //$ht .= "'".$atts['name']."',\"".$atts['tname']."\",'".$atts['ename']."','".$atts['type']."'],";
		
		}
        
		//$ht .= '[6236, 1]'  ;
        
        return $ht;    
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
        $y = R::getRow("SELECT q.index FROM quran q WHERE q.sura = :sura AND q.aya = :aya and q.riwaya = :riwayaID",array('sura'=>$sura,'aya'=>$aya,'riwayaID'=>$riwayaID));
//var_dump($y);
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

