<?php


class forkan extends application
{
    public static $name = 'forkan';
    protected $maxid;

    function init()
    {
        loader::import(PAPP.'forkan'.DS.'meta.php');
        metaQuran::init();
        
	    forkan::loadlang('main');
        WPI::setVar('title', WPI::txt('home'));
        //application::loadmedia('css','applications'.SH.APPC.SH.'media'.SH.'forkan.css');
	    //forkan::loadmedia('js','applications'.SH.'forkan'.SH.'media'.SH.'function.js');
    }
    function index()
    {
        
		//metaQuran::rep();
		
		
        //--- this bcoz swf uploader != '&' in url  
        if( isset($_GET['upload'])){
            forkan::upload();
            exit;
        }
        if( isset($_GET['checkupload'])){
            forkan::checkupload();
            exit;
        }
        //---
        
      WPI::setVar('title', WPI::txt('home'));
      
      $index = (tools::Jet('ndx','') != 'NULL')? tools::Jet('ndx','') : '1';
	  $ret = '';//forkan::getPageHTML($index);  

      //jawab::addNotification(form::notify('warn','Welcome To Forkan v1.0...'));
	  
      //$ret = self::getAyaHTML(1);    
      if (WPI::ModeIsAjx()){
        jawab::setContent('<![CDATA['.$ret.']]>');
        
        jawab::setAjxTitle(WPI::txt('home'));
      }
      else
        jawab::addContent($ret);
    }
    /**
     * Render numbers in given string using HTML entities that will show them as 
     * Indian digits (i.e. ١, ٢, ٣, etc.) whatever browser language settings are 
     * (if browser supports UTF-8 character set).
     *         
     * @param string $string String includes some digits here or there
     *                    
     * @return String Original string after replace digits by HTML entities that 
     *                will show given number using Arabic digits
     * @author Khaled Al-Shamaa <khaled.alshamaa@gmail.com>
     */
    public static function arNum($string)
    {
        $html = '';

        $digits = str_split("$string");

        foreach ($digits as $digit) {
            $html .= preg_match('/\d/', $digit) ? "&#x066$digit;" : $digit;
        }
        
        return $html;
    }    
    
    /**
     * forkan::search()
     * 
     * @param string $text
     * @param integer $page
     * @param string $riwayaID
     * @return
     */
    function search($text = '',$page = 1,$riwayaID = '1'){
        $looked     = env::xss_clean((tools::Jet('stxt','')!= 'NULL')? tools::Jet('stxt','') : $text);
		$riwayaID   = (tools::Jet('rid','')!= 'NULL')? tools::Jet('rid','') : $riwayaID;
		$page       = (tools::Jet('pid','')!= 'NULL')? tools::Jet('pid','') : $page;
        
        $lpp = 50;
        $lps = $lpp * ($page-1) ;
        
        $yc = dbase::query("SELECT q.* FROM quran q WHERE (q.text REGEXP '$looked')  and q.riwaya = 0");
        $count = dbase::num($yc);
        
        $ht = '<div id="resultHeader" class="stateSpan">';
        $ht.= '     <span class="syslink " title="'.txt('close').'" onclick="JQ.getPage(gPageID,null,true)"> [X] </span>';
        $ht.= '     <span id="looked" dir="'.txt('dir').'">"'.$looked.'" : </span>';
        $ht.= '     <span id="counter" class="tipy" title="'.forkan::txt('showing').' '.($lps+1).'-'.((($lps+$lpp)> $count)? $count : ($lps+$lpp)).' / ('.$count.')">('.$count.' '.forkan::txt('ayah').')</span> - ';
        
        //-- pagination
        if($count > $lpp){
            $lengt = intval($count/$lpp);
            $lengt = (($count%$lpp)> 0 )? $lengt+1 : $lengt;
            
            if($lengt < 11){$start = 1; $end =  $lengt;}
            elseif($page < 6){$start = 1; $end =  11;}
            elseif($page > $lengt-6){$start = $lengt-11; $end = $lengt;}
            else{$start = $page-5; $end = $page+5;};
            
            
            for($i=$start; $i <= $end;$i++  ){
                $onclick = ($i != $page)? 'onclick="JQ.searchPage(\''.$looked.'\','.$i.');"' : ' style="color:#FFF"';
                $ht .='     <span class="pagingSpan syslink" '.$onclick.' >'.$i.'</span>';
                $ht .= ($i != $end)? '|' : '';
            }
        }
        $ht .= '</div><p id="result">';
        
        dbase::free($yc);
        
        $ys = dbase::query("SELECT q.* FROM quran q WHERE (q.text REGEXP '$looked')  and q.riwaya = 0 ORDER BY q.index LIMIT $lps,$lpp ");
        
        $p = false;
        $c = 1;
        while ($yh = dbase::fetch($ys)) {
            
            $hlighclass = (!$p)? 'hightlight':'';
            //$yh = forkan::getAya($y['index'],$riwayaID);
        
        
            /*-----------------------------*/
        
        $y = forkan::getAya($yh['index'],$riwayaID);
		$s =  metaQuran::getAllAttr('sura',$yh['sura']);
        
   
        //$txt = str_replace('۞','<span class="rub3">  ۞</span><div class="rob3Sep"></div>',$y['text']);
        $ht .= '<div class="result '.$hlighclass.'"><div class="resultbar">';
        $ht .= '    <span id="" class="resultinfo" title="'.$s['tname'].'">'.($lps+$c).'- '.forkan::txt('sura').' '.$s['name'].' #'.$y['aya'].'</span> ';
        $ht .= '    <span id="" class="btn b-pdf " title=" '.forkan::txt('gotoAyaPage').'" onclick=" JQ.gotoAyaPage(\''.$y['sura'].'\',\''.$y['aya'].'\');"></span></div><br /> ';
        $ht .= '    <span id="y'.$y['index'].'" class="ytxt" data="{sura: \''.$y['sura'].'\',aya:\''.$y['aya'].'\', yndx: \''.$y['index'].'\'}" title="'.$s['name'].' : '.$s['tname'].'">'.$y['text'].'</span><span class="yctr" id="yctr-'.$y['index'].'">('.$y['aya'].')</span>'.'</div>';


            /*-----------------------------*/
            
            
            //$ht .= '<div style="display:block;border-top:1px solid #AAA;'.$hlighclass.'">'.forkan::getAyaHTML($y['index'],$riwayaID = '1').'</div>';
            //$ht .= '<div style="display:block;border-top:1px solid #AAA;'.$hlighclass.'">'.$yh['text'].'</div>';
            $p = ($p != false)? false : true ;
            $c++;
        };
        
        if (WPI::ModeIsAjx()){
			jawab::setContent('<![CDATA['.$ht.']]>');
		}
		else
			jawab::addContent($ht);
        return $ht;
        
    }
/********************************[ Ayas functions ]************************************/
    /**
     * Get aya info as array
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return array [index,sura,aya,text,riwaya]
     */
    function getAya($ayaID,$riwayaID = '1'){
        //index  sura  aya  text  riwaya  view  
        $y = dbase::jfetch("SELECT q.* FROM quran q WHERE q.index = $ayaID and q.riwaya = $riwayaID");
        
        return $y;
       
    }
    /**
     * Get translated Aya info as array
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return array [index,sura,aya,text,riwaya]
     */
    function getAyaTr($suraID,$ayaID,$transID = '1'){
        //index  sura  aya  text  TransID  view 
        $y = dbase::jfetch("SELECT q.* FROM translations q WHERE q.sura = $suraID AND aya=$ayaID and q.transID = $transID");
        
        return $y;
       
    }    
    /**
     * Calculate Aya index from SuraID+AyaPos
     * 
     * @param mixed $sura
     * @param mixed $aya
     * @return void
     */
    function getAyaIndex($sura,$aya,$riwayaID = '1'){
        $y = dbase::jfetch("SELECT q.index FROM quran q WHERE q.sura = $sura AND q.aya = $aya and q.riwaya = $riwayaID");

        return $y['index'];        
    }
    /**
     * Calculate SuraID+AyaPos from Aya index
     * 
     * @param mixed $sura
     * @param mixed $aya
     * @return void
     */
    function getAyaFromIndex($index,$riwayaID = '1'){
        $y = dbase::jfetch("SELECT q.sura,q.aya FROM quran q WHERE q.index = $index and q.riwaya = $riwayaID");

        return $y;        
    }    
    /**
     * forkan::getAyaHTML()
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return Generated Aya HTML
     */
    function getAyaHTML($ayaID,$riwayaID = '1'){
        //$si= forkan::getAyaFromIndex($ayaID,$riwayaID);
        //$y = forkan::getAyaTr($si['sura'],$si['aya'],$riwayaID);
        $y = forkan::getAya($ayaID,$riwayaID);
		$s = forkan::getSuraFromAya($ayaID,$riwayaID);

                
		/*
		* @example <p id="one" class="some_class" data="{item_id: 1, item_label: 'Label'}">This is a p</p>
		* @before $.metadata.setType("attr", "data")
		* @after $("#one").metadata().item_id == 1; $("#one").metadata().item_label == "Label"
		*/
        //$txt = str_replace('۞','<span class="rub3">  ۞</span><div class="rob3Sep"></div>',$y['text']);
        $h = '<span id="y'.$y['index'].'" class="ytxt" data="{sura: \''.$y['sura'].'\',aya:\''.$y['aya'].'\', yndx: \''.$y['index'].'\'}">'.$y['text'].'</span><span class="yctr" id="yctr-'.$y['index'].'">'."\x28".$y['aya']."\x29".'</span>';
        //
        return $h;
    }    
    /**
     * forkan::getAyasHTML()
     * 
     * @param mixed $startAya
     * @param mixed $startSura
     * @param mixed $endAya
     * @param mixed $endSura
     * @param string $riwayaID
     * @return Generated Ayas HTML
     */
	function getAyasHTML($startAya,$startSura,$endAya,$endSura,$riwayaID = '1')	{
   
        $s = '';//'<div class="suraCont" id="sura-'.$suraID.'"><span class="suraHead">'.$sn.' c:'.$yc.' - s:'.$ys.'</span><br /><p>';
        
		if($startSura > $endSura){
			return 'Sura Arg not correct ! ';
		}   
		if(($startSura == $endSura) AND ($startAya > $endAya)){
			return 'Aya Arg not correct ! ';
		}      
		
        for($i=$startSura;$i<=($endSura);$i++){
			// if the 1st sura so begin from 1 aya
			if ($i == $startSura){
				if($startSura == $endSura){
					$s .= forkan::getSuraHTML($i,$startAya,$endAya,$riwayaID);
				}
				else
					$s .= forkan::getSuraHTML($i,$startAya,false,$riwayaID);
			}
			//if the last sura so end in juz+1 aya
			elseif($i == $endSura){
				// if next juz begin from sura:2 aya:0 so break
				if($endAya == 0) break;
				
				$s .= forkan::getSuraHTML($i,1,$endAya,$riwayaID);
			}
			else{
				$s .= forkan::getSuraHTML($i,1,false,$riwayaID);
			}
			

        }
        
        //$s .= '</p>';
        return $s;
	}

/********************************[ Tafseer functions ]************************************/
    
    /**
     * forkan::getTafseer()
     * 
     * @param string $type [f:tafseer , r:translation]
     * @param mixed $suraID 
     * @param mixed $ayaID 
     * @param string $tafseerID
     * @return
     */
    function getTafseer($type = 'f',$suraID,$ayaID,$tafseerID='1'){
        //index  sura  aya  text  tafseerID  view  
        $t = ($type != 'r') ? dbase::jfetch("SELECT t.* FROM tafseer t      WHERE t.sura = $suraID AND aya=$ayaID and t.tafseerID = $tafseerID") :
                              dbase::jfetch("SELECT t.* FROM translations t WHERE t.sura = $suraID AND aya=$ayaID and t.transID   = $tafseerID") ;
                    
        
        return $t;
       
    }    
    /**
     * forkan::getTafseerHTML()
     * 
     * @param string $type [f:tafseer , r:translation]
     * @param string $suraID
     * @param string $ayaID
     * @param string $tafseerID
     * @return
     */
    function getTafseerHTML($type = 'f',$suraID='0',$ayaID='0',$tafseerID = '1'){
		$type      = (tools::Jet('typ','') != 'NULL')? tools::Jet('typ','') : $type;
		$suraID    = (tools::Jet('sid','')!= 'NULL')? tools::Jet('sid','') : $suraID;
		$ayaID     = (tools::Jet('yid','')!= 'NULL')? tools::Jet('yid','') : $ayaID;
		$tafseerID = (tools::Jet('tid','')!= 'NULL')? tools::Jet('tid','') : $tafseerID;
		
        $y = forkan::getTafseer($type,$suraID,$ayaID,$tafseerID);
        $h = '<span class="ftxt" id="'.$type.'-'.$tafseerID.'-'.$y['index'].'">'.forkan::txt('sura').' '.metaQuran::getSuraMeta($y['sura'],'name').' #'.$y['aya'].' : '.$y['text'].'</span>';
        //
		if (WPI::ModeIsAjx()){
			jawab::setContent('<![CDATA['.$h.']]>');
		}
		else
			jawab::addContent($h);
    
        return $h;
    }
	
/********************************[ Suras functions ]************************************/
	 /**
     * forkan::getSuraFromAya()
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return
     */
    function getSuraFromAya($ayaID,$riwayaID = '1'){
        $y = dbase::jfetch("SELECT sura FROM quran q WHERE q.index = '$ayaID' and q.riwaya = '$riwayaID'");
        $s = metaQuran::getAllAttr('sura',$y['sura']);    
        
        return $s;    
    }
    

    function getSuraHTML($suraID,$From = 1,$To = false,$riwayaID = '1'){
        $yc = ($To != false)? $To : intval(metaQuran::getSuraMeta($suraID,'ayas'));
        $ys = intval(metaQuran::getSuraMeta($suraID,'start'));
        $sn = metaQuran::getSuraMeta($suraID,'name');
        $st = metaQuran::getSuraMeta($suraID,'ename');
        $sd = metaQuran::getSuraMeta($suraID,'tname');
        $sk = (metaQuran::getSuraMeta($suraID,'type') == 'Meccan')? forkan::txt('Meccan'): forkan::txt('Medinan');
        $so = metaQuran::getSuraMeta($suraID,'order');
        
        $h = '<div class="sheader" id="sura-'.$suraID.'">';
		$h.= '<div class="sorder">'.forkan::txt('suraOrder').': '.$so.'</div>';
		$h.= '<div class="sname" title="'.$sd.' : '.$st.'">'.forkan::txt('sura').' '.$sn.'</div>';
		$h.= '<div class="sdesc"> : '.$sk.' - '.forkan::txt('ayaCount').': '.metaQuran::getSuraMeta($suraID,'ayas').'</div>';
		$h.= '</div>';
                
        $s = ($From > 1)? '' : $h;
        for($i=$ys+$From;$i<=($ys+$yc);$i++){
     
        $s .= forkan::getAyaHTML($i,$riwayaID).' ';

        }
        
        //$s .= '<p></div>';
        return $s;
    }
    /**
     * Create Sura select Combo box
     * 
     * @return $ht generated HTML.select
     */
    function createSuraCBX(){
        
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
    function createPageCBX(){
        
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
    function createJuzCBX(){
        
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
    function createQiraatCBX(){
        
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
    function createTafseerCBX(){
        
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
    function metaSuraXml2Js(){
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

/********************************[ Juzs functions ]************************************/
    function getJuzHTML($juzID,$riwayaID = '1'){
		/**
		* Juz Start
		*/
        $jss = intval(metaQuran::getQuarterMeta($juzID,'sura'));
        $jsy = intval(metaQuran::getQuarterMeta($juzID,'aya'));
		/**
		* Juz End
		*/	
		if(intval($juzID == 240)){
			$jes = 114;
			$jey = 6;
		}
        else{
			$jes = intval(metaQuran::getQuarterMeta($juzID+1,'sura'));
			$jey = intval(metaQuran::getQuarterMeta($juzID+1,'aya')-1);
		}
        //$sn = metaQuran::getSuraMeta($suraID,'name');
        
        $s = '<p>';//'<div class="suraCont" id="sura-'.$suraID.'"><span class="suraHead">'.$sn.' c:'.$yc.' - s:'.$ys.'</span><br /><p>';
                
        $s .= forkan::getAyasHTML($jsy,$jss,$jey,$jes,$riwayaID);
        
        $s .= '</p>';
        return $s;
    }

/********************************[ Pages functions ]************************************/
	function getPageHTML($pageID='1',$riwayaID = '1'){
		$riwayaID = (tools::Jet('rid','')!= 'NULL')? tools::Jet('rid','') : $riwayaID;
		$pageID = (tools::Jet('pid','')!= 'NULL')? tools::Jet('pid','') : $pageID;

	/**
		* Page Start
		*/
        $pss = intval(metaQuran::getPageMeta($pageID,'sura'));
        $psy = intval(metaQuran::getPageMeta($pageID,'aya'));
		/**
		* Page End
		*/	
		if(intval($pageID == 604)){
			$pes = 114;
			$pey = 6;
		}
        else{
			$pes = intval(metaQuran::getPageMeta($pageID+1,'sura'));
			$pey = intval(metaQuran::getPageMeta($pageID+1,'aya')-1);
		}
        //$sn = metaQuran::getSuraMeta($suraID,'name');
        
        $s = '<p>';//'<div class="suraCont" id="sura-'.$suraID.'"><span class="suraHead">'.$sn.' c:'.$yc.' - s:'.$ys.'</span><br /><p>';
                
        $s .= forkan::getAyasHTML($psy,$pss,$pey,$pes,$riwayaID);
        
        $s .= '</p>';
		
		if (WPI::ModeIsAjx()){
			jawab::setContent('<![CDATA['.$s.']]>');
		}
		else
			jawab::addContent($s);	
			
        return $s;
    }





/****************************************************************************************/

    
	/**
	 * page exists
	 * 
	 * @param mixed $pid
	 * @return
	 */
	function exists($pid){
		/*if(dbase::jNum("SELECT PID FROM wpl_pages WHERE PID='".$pid."';")==1){	
			return true;
		}
		else 
			return false;*/
	}
 function cpanel()
    {
	    jpriv::jfor('1:x');
        registry::set('cfg_sec', 'sys');
        form::sendForm(PAPP . 'common' . DS . 'konfig.php');
    }
    function files()
    {
        $f = isset($_GET['f']) ? $_GET['f'] : '';
        if (is_readable($f)) {
            //$out = (flib::get_contents($f));
            //tools::show($out);
			readfile($f);
            exit;
        } else
            debugger::error('File Not Found');
        exit;
    }
    function upload()
    {

	     if ( !empty($_FILES)) {
            $tempFile = tools::unescape($_FILES['Filedata']['tmp_name']);
            $targetPath = $_SERVER["DOCUMENT_ROOT"]. $_POST['folder']. DS;//PPUB . 'uploads' . DS;
            // im tired to do this but is still not complete...
            $file = iconv('UTF-8', 'Windows-1256', $_FILES['Filedata']['name']);
            $targetFile = str_replace('//', '/', $targetPath) . $file;
            //---- check extension not be script
            $fext = array('php','php3','phps','exe','so','inc','shell','bat');
            $ext = flib::extension($file);
            if (in_array($ext ,$fext)){
               echo 'FORBIDDEN file type !!' ;
               exit;
                
            };
            // $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
            // $fileTypes  = str_replace(';','|',$fileTypes);
            // $typesArray = split('\|',$fileTypes);
            // $fileParts  = pathinfo($_FILES['Filedata']['name']);

            // if (in_array($fileParts['extension'],$typesArray)) {
            // Uncomment the following line if you want to make the directory if it doesn't exist
            // mkdir(str_replace('//','/',$targetPath), 0755, true);

            move_uploaded_file($tempFile, $targetFile);
            //echo "1";
            echo "http://".$_SERVER["HTTP_HOST"].$_POST['folder']. SH . $_FILES['Filedata']['name'];
            // } else {
            // 	echo 'Invalid file type.';
            // }
            exit;
        }
    }
    function checkupload()
    {

	    //jpriv::jfor('1:x');
        $fileArray = array();
        foreach ($_POST as $key => $value) {
            if ($key != 'folder') {
                if (file_exists($_SERVER["DOCUMENT_ROOT"]. $_POST['folder'].DS. $value)) {
                    $fileArray[$key] = $value;
                }
            }
        }
        echo json_encode($fileArray);
        exit;
    }
    function mediaBrowser(){
        $ret = (router::isajx())? '<![CDATA['.prowser::getListCBX().']]>' : prowser::getListCBX();
        jawab::setContent($ret);
    }

}
?>