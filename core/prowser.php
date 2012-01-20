<?php
class prowser {
    
	//---- get public folder imagefile list
    function getListUL(){
        $ls = flib::fileslist(KROT.'public',null,'jpg');
        $ret = 'Public Folder:';
        $ret.= '<ul>';
        foreach ($ls as $fl) {   
            $fl = str_replace(KROT,KURL,$fl); 
            $fl = str_replace(DS,SH,$fl);    
            $ret.= '<li><input name="jfile" id="jfile" type="radio" value="'.($fl).'" title="'.basename($fl).'">   '.basename($fl).'</input></li>';
        };
        $ret.= '</ul>';
        
        return $ret;
    } 
    
    
	//---- Create  imagefile list into ComboBox
    function getListCBX(){
        $ls = flib::fileslist(KROT.'public',null,array('jpg','png','gif'));
        $ret = 'Public Folder:';
        $ret.= '<select name="jfile" id="jfile" >';
        foreach ($ls as $fl) {   
            $fl = str_replace(KROT,KURL,$fl); 
            $fl = str_replace(DS,SH,$fl);    
            $ret.= '<option value="'.($fl).'" style="border-bottom:1px solid #777;" title="'.basename($fl).'"><img src="'.($fl).'" width="50" height="40" />   '.basename($fl).'</option>';
        };
        $ret.= '</select>';
        
        return $ret;
    }
	//---- get language list into ComboBox
	function getLangListCBX(){
        $ls = flib::fileslist(PLNG,null,'php');
        $ret = 'Languages:';
		$cur = registry::get('lang');
		$list = array();
		foreach ($ls as $fl) {   
            $fl = basename($fl);
            $fl = str_replace('.main.php','',$fl); 
            $fl = str_replace(DS,SH,$fl); 
			$list[$fl] = $fl;
        };
		return form::jListBox(array('mode'=>'edit','fld'=>'lang-frm', 'lbl'=>txt('language'),'lookedvalue'=>$cur, 'jvd'=>'required', 'col'=>'40', 'list'=>$list));       
		
		
    } 
	//---- get theme list into ComboBox
	function getThemeListCBX(){
        $ls = flib::dirslist(KROT . 'skins'.DS);
        $ret = 'Theme:';
		$cur = konfig::get('theme');
		$list = array();
		foreach ($ls as $fl) {   
            //$fl = basename(dirname($fl)); 
            //$fl = str_replace(DS,'',$fl); 
            //$fl = str_replace(SH,'',$fl); 
			$list[$fl] = $fl;
        };
		return form::jListBox(array('mode'=>'edit','fld'=>'theme-frm', 'lbl'=>txt('theme'),'lookedvalue'=>$cur, 'jvd'=>'required', 'col'=>'40', 'list'=>$list));       
		
		
    } 
}
?>