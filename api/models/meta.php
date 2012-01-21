<?php

class metaQuran
{
    public $xml = array();

    function init()
    {
        // create the KONFIG object
        metaQuran::load(PAPP .'forkan'.DS. 'quran-data.xml');
        // Lets make sure we save serialized data
    }

    function load($xmlfile)
    {
        $GLOBALS['WQX'] = kore::getInstance('metaQuran');
        
        $GLOBALS['WQX']->xml['data']  = xmldoc::import($xmlfile);
        $GLOBALS['WQX']->xml['data2'] = xmldoc::import(PAPP .'forkan'.DS. 'quran-ext-data.xml');
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
    function getAttr($name,$index,$attr)
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
	function rep(){
        
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
    function getAllAttr($name,$index){
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
    function getSuraMeta($index,$meta){
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
    function getJuzMeta($index,$meta){
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
    function getQuarterMeta($index,$meta){
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
    function getPageMeta($index,$meta){
        return self::getAttr('page',$index,$meta);
    }


    function xit()
    {
        $GLOBALS['WQX']->xml['data']->asXML(PAPP .'forkan'.DS. 'quran-data.xml');
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
?>