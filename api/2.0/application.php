<?php

Class Forkan {
        /**
     * Create Sura select Combo box
     * 
     * @return $ht generated HTML.select
     */
     public static function createSuraCBX(){
        
        $els = $GLOBALS['WQX']->xml['data']->xpath("//sura");
        $ht = '<select id="suraCBX" class="bottomCBX tipy" name="suraCBX" title="'.forkan::txt('sura').'">';
        foreach ($els as  $e ){
		    $atts = $e->attributes();
        	$ndx  = $atts['index'];
        	$name = $atts['name'];
			$ht .= '<option value="'.$atts['index'].'"> -  '.forkan::txt('sura').' '.$atts['name'].' : '.$atts['tname'].'</option>';
		
		}
        
		$ht .= '</select>'  ;
        
        return $ht;
    }
    /**
     * Create Page select Combo box
     * 
     * @return $ht generated HTML.select
     */
    public static function createPageCBX(){
        
        $els = $GLOBALS['WQX']->xml['data']->xpath("//page");
        $ht = '<select id="pageCBX" class="bottomCBX tipy" name="pageCBX" title="'.forkan::txt('page').'">';
        foreach ($els as  $e ){
		    $atts = $e->attributes();
        	$ndx  = $atts['index'];
        	$sura = $atts['sura'];
            
            
            $pn = forkan::txt('page').' '.$ndx;
            $sn = forkan::txt('sura').' '.metaQuran::getSuraMeta($sura,'name');
            $sn.= ' : '.metaQuran::getSuraMeta($sura,'tname');
			
            $ht .= '<option value="'.$atts['index'].'" title="'.$sn.'"> -  '.$pn.'  </option>';
		
		}
        
		$ht .= '</select>'  ;
        
        return $ht;
    }
    /**
     * Create Juz select Combo box
     * 
     * @return $ht generated HTML.select
     */
    public static function createJuzCBX(){
        
        $els = $GLOBALS['WQX']->xml['data']->xpath("//juz");
        $ht = '<select id="juzCBX" class="bottomCBX tipy" name="juzCBX" title="'.forkan::txt('juz').'">';
        foreach ($els as  $e ){
		    $atts = $e->attributes();
        	$ndx  = $atts['index'];
        	$sura = $atts['sura'];
            
            
            $jn = forkan::txt('juz').' '.$ndx;
			
            $ht .= '<option value="'.$atts['index'].'"> -  '.$jn.'  </option>';
		
		}
        
		$ht .= '</select>'  ;
        
        return $ht;
    }
    /**
     * Create Qiraat select Combo box
     * 
     * @return $ht generated HTML.select
     */
    public static function createQiraatCBX(){
        
        $els = $GLOBALS['WQX']->xml['data']->xpath("//qiraat");
        $ht = '<select id="qiraatCBX" class="bottomCBX tipy" name="qiraatCBX" title="'.forkan::txt('qiraat').'">';
        foreach ($els as  $e ){
		    $atts = $e->attributes();
        	$ndx  = $atts['index'];
            
            
            $qn = ' '.$atts['sheikh'];
			
            $ht .= '<option value="'.$atts['url'].'" '.(($ndx != 6)?'' : 'selected="selected" ').'> -  '.$qn.'  </option>';
		
		}
        
		$ht .= '</select>'  ;
        
        return $ht;
    }
    /**
     * Create Tafseer select Combo box
     * 
     * @return $ht generated HTML.select
     */
    public static function createTafseerCBX(){
        
        $els = $GLOBALS['WQX']->xml['data']->xpath("//tafseer");
        $ht = '<select id="tafseerCBX" class="bottomCBX tipy" name="qiraatCBX" title="'.forkan::txt('tafseer').'">';
        foreach ($els as  $e ){
		    $atts = $e->attributes();
        	$ndx  = $atts['index'];
            
            
            $qn = ' '.$atts['name'];
			
            $ht .= '<option value="'.$atts['type'].'.'.$atts['id'].'" title="'.$atts['author'].'"> -  '.$qn.'  </option>';
		
		}
        
		$ht .= '</select>'  ;
        
        return $ht;
    }
    public static function metaSuraXml2Js(){
    // [start, ayas, order, rukus, name, tname, ename, type]
	//[],
	//[0, 7, 5, 1, 'الفاتحة', "Al-Faatiha", 'The Opening', 'Meccan'],
//index="1" ayas="7" start="0" name="الْفَاتِحَة" tname="Al-Faatiha" ename="The Opening" type="Meccan"     
        $els = $GLOBALS['WQX']->xml['data']->xpath("//sura");
        $ht = '[],';
        foreach ($els as  $e ){
		    $atts = $e->attributes();
            
			
            $ht .= '['.$atts['start'].','.$atts['ayas'].','.$atts['order'].','./*$atts['rukus'].*/',';
            $ht .= "'".$atts['name']."',\"".$atts['tname']."\",'".$atts['ename']."','".$atts['type']."'],";
		
		}
        
		$ht .= '[6236, 1]'  ;
        
        return $ht;    
    }

} 

?>