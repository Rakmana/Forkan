<?php
/**
 * Form HTML form & Link Generator
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: form.php,v2.0 09:49 09/03/2010 NP++ Exp $
*/


/**
* FORM
* Html Form manager
*
* @package  WAPL
*/
class form{
	/**
	* Create normal link
	*
	* @param string		$mode [site, kjax, mediax]
	* @param string		$app (Application name)
	* @param string		$job (Job or action name)
	* @param string		$qry (Passed Query or request data)
	* @return string	 Generated link
	*/
	function clink($mode="", $app="", $job="", $qry="",$amp='&'){//create Links function 
	if(konfig::isOn('permalink')){
		//return KURL.'?md='.$mode.'&jb='.$jb.'&'.$qry;
		$app = !empty($app)? SH.$app : $app;
		$job = !empty($job)? SH.$job : $job;
		$qry = !empty($qry)? "?$qry" : $qry;
		return KURL.$mode.$app.$job.$qry;
	}
	else {
	   $mode = !empty($mode)? "?md=".$mode : '?md=site';
		$app = !empty($app)? $amp."ap=".$app : $app;
		$job = !empty($job)? $amp."jb=".$job : $job;
		//return KURL.$mode.$app.$job.$qry;
		$qry = !empty($qry)? $amp.$qry : $qry;
		return KURL.$mode.$app.$job.$qry;
	}
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Create media link [img, css, js, swf]
	*
	* @param string		$type Media type in : [img, js, css]
	* @param string		$path Media File path
	* @param string		$qry Passed Query or request data
	* @return string	Generated link
	*/
	function mlink($type,$path,$qry="",$amp='&'){//media Links function 
	
    switch ($type) {
	   case 'img' : $type = 'wimg';break;
       case 'css' : $type = 'wcss';break;
       case 'js'  : $type = 'wjs'; break;
       case 'swf' : $type = 'wswf'; break;
       
	}
    $qry .= (!empty($qry)?$amp: '').'v='.konfig::get('version'); 
    
    if(konfig::isOn('permalink')){
		//return KURL.'?md='.$mode.'&jb='.$jb.'&'.$qry;
		$type = !empty($type)? SH.$type : $type;
		$path = !empty($path)? SH.$path : $path;
		$qry = !empty($qry)? "?$qry" : $qry;
		return KURL.'mediax'.$type.$path.$qry;
	}
	else{
		$qry = !empty($qry)? $amp.$qry : $qry;
		return KURL.'?md=mediax'.$amp.$type.'='.$path.$qry;
	}
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Create File link 
	*
	* @param string		$path File path
	* @param string		$qry Passed Query or request data
	* @return string	Generated link
	*/
	function flink($path,$qry="",$amp='&'){//files Links function
	if(konfig::isOn('permalink')){	
		$path = !empty($path)? SH.$path : $path;
		$qry = !empty($qry)? "?$qry" : $qry;
		return KURL.'files'.$path.$qry;
	}
	else{
		$path = !empty($path)? $path : $path;
		$qry = !empty($qry)? $amp.$qry : $qry;
		return KURL."?md=site".$amp."ap=fpage".$amp."jb=files=".$path.$qry;
		
	}	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Generate HTML Notify message
	*
	* @param string		$type Message Type in [info, warn, error, alert, help, tip, ok]
	* @param string		$txt Message text
	* @return string	Generated notification Html
	*/
	function notify($type,$txt){ //create notify html
		$id= rand(3,100);
		$html = '<div id="'.$id.'" class="notify '.$type.'">
		<span class="sgn s-'.$type.'" style="float:'.(function_exists('txt')? txt('align') : 'left').';"></span>
		<span class="tipy btn b-exit" style="float:'.(function_exists('txt')? txt('align2') : 'right').'" title="'.(function_exists('txt')? txt("close") : 'Close').'" onclick="hidE(\''.$id.'\');"></span>'.$txt.'</div>';
	return $html;	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Add content to registry & format if ajax 
	*
	* @param string		$cont Contents
	*/
	function jSetContent($cont){
		if(router::isajx()) registry::add('contents','<![CDATA['.$cont.']]>');
		else registry::add('contents',$cont);
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Load then Add HtmlForm to registry & format if ajax 
	*
	* @param string		$path Form file path
	*/
	function sendForm($path){//Load form template
		if(router::isajx()) registry::add('contents','<wfrm><![CDATA['.loader::getForm($path).']]></wfrm>');
		else registry::add('contents',loader::getForm($path));
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Generate Html labeled text for db field
	*
	* @param string		$label Text field label
	* @param string		$field Text field name
	* @param string		$value Text field value
	* @param string		$nowrap nowrap
	* @return string	Generated Html
	*/
	function jSpan($label,$field,$value,$nowrap=''){
	
		$ret ="\n";
		$ret.='<div class="blk '.$nowrap.'">';
		$ret.='	<div class="lbl"><label for="'.$field.'">'.ucfirst($label).' : </label></div>';
		$ret.='	<span id='.$field.' class="jSpan" style="font: bold 11px tahoma; margin:1px 20px;">'.$value.'</span>';
		$ret.='</div>';
		return $ret;
	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Generate Html (input text) for db field
	*
	* @param array		$meta Array of:
								[mode] mode in [new view edit]
								[lbl] Text field label
								[fld] Text field name
								[val] Text field value
								[jvd] Text field validation class e.g. date number..
								[siz] Text field size
								[nowrap] nowrap
	* @return string	Generated Input Html
	*/
	function jInput($meta){
	
	/* e.g  form::jInput(array('fld'=>'nom', 'lbl'=>'Nom', 'val'=>$info['NOM'], 'jvd'=>'required', 'siz'=>'40'))	*/
		//$meta = tools::attr2arry($metadata);
		$nowrap = (isset($meta['nowrap']))? 'nowrap' : '';
		if($meta['mode']=="view"){	
			//if emty value
			if(''==$meta['val']){
				return  form::jSpan($meta['lbl'],$meta['fld'],' ------- ',$nowrap);
			}
			return form::jSpan($meta['lbl'],$meta['fld'],$meta['val'],$nowrap);
		}
		
		$disabled = (isset($meta['mode']) && ($meta['mode']=="view"))? 'disabled="disabled"' : '';
		$val = (isset($meta['mode']) && ($meta['mode']=="new"))? '' : $meta['val'];
		
		$siz = isset($meta['siz'])? $meta['siz'] : '30';
		
		$ret ="\n";
		$ret.='<div class="blk '.$nowrap.'">';
		$ret.=' <div class="lbl"><label for="'.$meta['fld'].'">'.ucfirst($meta['lbl']).' : </label></div>';
		$ret.=' <input type="text" id="'.$meta['fld'].'" name="'.$meta['fld'].'" class="'.$meta['jvd'].'" maxlength="'.$siz.'" size="'.($siz+5).'" value="'.$val.'" '.$disabled.'/>';
		$ret.='</div>';
		
		return $ret;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Generate Html (input chechbox) for db field
	*
	* @param array		$meta Array of:
								[mode] mode in [new view edit]
								[lbl] Text field label
								[fld] Text field name
								[val] Text field value
								[jvd] Text field validation class e.g. date number..
								[nowrap] nowrap
	* @return string	Generated Input Html
	*/
	function jCheck($meta){
	
	/* e.g form::jCheck(array('fld'=>'nationalite', 'lbl'=>'etranger', 'val'=>$info['NATIONALITE'], 'jvd'=>'required')) 	*/
	
		$nowrap = (isset($meta['nowrap']))? 'nowrap' : '';
		if($meta['mode']=="view"){
		
			//if emty value
			if($meta['val']==''){
				return  form::jSpan($meta['lbl'],$meta['fld'],' ------- ',$nowrap);
			}	
		
			$val = ($meta['val']!=0)? 'Oui' : 'Non';
			return form::jSpan($meta['lbl'],$meta['fld'],$val,$nowrap);
		}
		
		$disabled = (isset($meta['mode']) && ($meta['mode']=="view"))? 'disabled="disabled"' : '';
		
		$checked =  ($meta['val'] != 0)? 'checked="checked"' : '';
		$ret ="\n";
		$ret.='<div class="blk '.$nowrap.'">';
		$ret.=' <div class="lbl"><label for="'.$meta['fld'].'">'.ucfirst($meta['lbl']).' : </label></div>';
		$ret.=' <input type="checkbox" id="'.$meta['fld'].'" value="1" name="'.$meta['fld'].'" class="'.$meta['jvd'].'" '.$checked.' '.$disabled.'/>';
		$ret.='</div>';
		
		return $ret;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Generate Html lookup (Select list) for db field
	*
	* @param array		$meta Array of:
								[mode] mode in [new view edit]
								[lbl] Text field label
								[fld] Text field name
								[lookedvalue] Text field value
								[query] Lookup list Query
								[listvalue] Lookup list value
								[listkey] Lookup list key
								[jvd] Text field validation class e.g. date number..
								[col] Text field size
								[nowrap] nowrap
	* @return string	Generated Html
	*/
	function jDBListBox($meta){
		
	/* e.g  form::jDBListBox(array('fld'=>'contrat', 'lbl'=>'Type Contrat','query'=>'SELECT * FROM grh_contrat;', 'lookedvalue'=>$info['CDD_CDI'], 'jvd'=>'required', 'col'=>'20', 'listkey'=>'NOM_CON','listvalue'=>'CODE_CON'))  */
		
		$disabled = (isset($meta['mode']) && ($meta['mode']=="view"))? 'disabled="disabled"' : '';
		$nowrap = (isset($meta['nowrap']))? 'nowrap' : '';
	
		//if emty value
		if(($meta['mode']=="view") and (''==($meta['lookedvalue']))){
			return  form::jSpan($meta['lbl'],$meta['fld'],' ------- ',$nowrap);
			break;
		}	
		
		
		$ret = "\n";
		$ret.= '<div class="blk '.$nowrap.'">';
		$ret.= ' <div class="lbl"><label for="'.$meta['fld'].'">'.ucfirst($meta['lbl']).' : </label></div>';
		$ret.= ' <select id="'.$meta['fld'].'" name="'.$meta['fld'].'" class="'.$meta['jvd'].'" cols="'.$meta['col'].'" '.$disabled.'>';
		$ret.= '	<option value=""> ------- </option>';
		
		$qry = dbase::query($meta['query']);
		while ($list = dbase::fetch($qry)) {
		if(($meta['mode']!="new")and($list[$meta['listvalue']] == $meta['lookedvalue'])){//if selected 
			$ret .= '	<option value="'.$list[$meta['listvalue']].'" selected="selected">'.$list[$meta['listkey']].'</option>';
			if($meta['mode']=="view"){
				return  form::jSpan($meta['lbl'],$meta['fld'],$list[$meta['listkey']],$nowrap);
				break;
			}
		}
		else{
			$ret .= '	<option value="'.$list[$meta['listvalue']].'">'.$list[$meta['listkey']].'</option>';
		} 
		} 
		$ret .=' </select>';		
		$ret .='</div>';
		if($meta['mode']=="view"){
			return  form::jSpan($meta['lbl'],$meta['fld'],'---?---',$nowrap);
			break;
		}
		
		return $ret;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Generate Html fixed (Select list) for db field
	*
	* @param array		$meta Array of:
								[mode] mode in [new view edit]
								[lbl] Text field label
								[fld] Text field name
								[lookedvalue] Text field value
								[list] Option list array
								[jvd] Text field validation class e.g. date number..
								[col] Text field size
								[nowrap] nowrap
	* @return string	Generated Html
	*/
	function jListBox($meta){
		//$meta = tools::attr2arry($metadata);
	
		$disabled = (isset($meta['mode']) && ($meta['mode']=="view"))? 'disabled="disabled"' : '';
		$nowrap = (isset($meta['nowrap']))? 'nowrap' : '';
	
		//if emty value
		if(($meta['mode']=="view") and (''==($meta['lookedvalue']))){
			return  form::jSpan($meta['lbl'],$meta['fld'],' ------- ',$nowrap);
			break;
		}	
			
		$ret = "\n";
		$ret.= '<div class="blk '.$nowrap.'">';
		$ret.= ' <div class="lbl"><label for="'.$meta['fld'].'">'.ucfirst($meta['lbl']).' : </label></div>';
		$ret.= ' <select id="'.$meta['fld'].'" name="'.$meta['fld'].'" class="'.$meta['jvd'].'" cols="'.$meta['col'].'" '.$disabled.'>';
		$ret .= '	<option value=""> ------- </option>';
		
		$founded = false;
		foreach ($meta['list'] as $key => $value) {
		if(($meta['mode']!='new') and ($value == $meta['lookedvalue'])){//if selected 
			$founded = true;
			$ret .= '	<option value="'.$value.'" selected="selected">'.$key.'</option>';
			if($meta['mode']=="view"){
				return  form::jSpan($meta['lbl'],$meta['fld'],$key,$nowrap);
				break;
			}
		}
		else{
			$ret .= '	<option value="'.$value.'">'.$key.'</option>';
		}
		
		} 
		$ret .=' </select>';	
		$ret .='</div>';
		
		if($meta['mode']=="view"){
			return  form::jSpan($meta['lbl'],$meta['fld'],'---?---',$nowrap);
			break;
		}
		
		return $ret;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Generate Html lookup (RadioBox) for db field
	*
	* @param array		$meta Array of:
								[mode] mode in [new view edit]
								[lbl] Text field label
								[fld] Text field name
								[lookedvalue] Text field value
								[query] Lookup list Query
								[listvalue] Lookup list value
								[listkey] Lookup list key
								[jvd] Text field validation class e.g. date number..
								[nowrap] nowrap
	* @return string	Generated Html
	*/
	function jDBRadioBox($meta){
		
	/* e.g  form::jDBListBox(array('fld'=>'contrat', 'lbl'=>'Type Contrat','query'=>'SELECT * FROM grh_contrat;', 'lookedvalue'=>$info['CDD_CDI'], 'jvd'=>'required', 'col'=>'20', 'listkey'=>'NOM_CON','listvalue'=>'CODE_CON'))  */
		
		$disabled = (isset($meta['mode']) && ($meta['mode']=="view"))? 'disabled="disabled"' : '';
		$nowrap = (isset($meta['nowrap']))? 'nowrap' : '';
	
		//if emty value
		if(($meta['mode']=="view") and (''==($meta['lookedvalue']))){
			return  form::jSpan($meta['lbl'],$meta['fld'],' ------- ',$nowrap);
			break;
		}	
		
		
		$ret = "\n";
		$ret.= '<div class="blk">';
		$ret.= ' <div class="lbl"><label for="'.$meta['fld'].'">'.ucfirst($meta['lbl']).' : </label></div>';
	
		
		$qry = dbase::query($meta['query']);
		while ($list = dbase::fetch($qry)) {
		if(($meta['mode']!="new")and($list[$meta['listvalue']] == $meta['lookedvalue'])){//if selected 
			$ret .= '	<label for="'.md5($list[$meta['listkey']]).'">'.$list[$meta['listkey']].'</label>';
			$ret .= '	<input id="'.md5($list[$meta['listkey']]).'" name="'.$meta['fld'].'" value="'.$list[$meta['listvalue']].'" checked="checked" type="radio" class="'.$meta['jvd'].'"></input>';
			if($meta['mode']=="view"){
				return  form::jSpan($meta['lbl'],$meta['fld'],$list[$meta['listkey']],$nowrap);
				break;
			}
		}
		else{
			$ret .= '	<label for="">'.$list[$meta['listkey']].'</label>';
			$ret .= '	<input id="'.md5($list[$meta['listkey']]).'" name="'.$meta['fld'].'" value="'.$list[$meta['listvalue']].'" type="radio" class="'.$meta['jvd'].'"></input>';
			
		} 
		} 		
		$ret .='</div>';	
		if($meta['mode']=="view"){
			return  form::jSpan($meta['lbl'],$meta['fld'],'---?---',$nowrap);
			break;
		}
		
		return $ret;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Generate Html fixed (RadioBox) for db field
	*
	* @param array		$meta Array of:
								[mode] mode in [new view edit]
								[lbl] Text field label
								[fld] Text field name
								[lookedvalue] Text field value
								[list] Option list array
								[jvd] Text field validation class e.g. date number..
								[col] Text field size
								[nowrap] nowrap
	* @return string	Generated Html
	*/
	function jRadioBox($meta){
		//$meta = tools::attr2arry($metadata);
	
		$disabled = (isset($meta['mode']) && ($meta['mode']=="view"))? 'disabled="disabled"' : '';
		$nowrap = (isset($meta['nowrap']))? 'nowrap' : '';
	
		//if emty value
		if(($meta['mode']=="view") and (''==($meta['lookedvalue']))){
			return  form::jSpan($meta['lbl'],$meta['fld'],' ------- ',$nowrap);
			break;
		}	
		
		$ret = "\n";
		$ret.= '<div class="blk">';
		$ret.= ' <div class="lbl"><label for="'.$meta['fld'].'">'.ucfirst($meta['lbl']).' : </label></div>';
	
		
		foreach ($meta['list'] as $key => $value) {
		if(($meta['mode']!='new') and ($value == $meta['lookedvalue'])){//if selected 
			$ret .= '	<label for="'.md5($key).'">'.$key.'</label>';
			$ret .= '	<input id="'.$meta['fld'].'" name="'.$meta['fld'].'" value="'.$value.'" checked="checked" type="radio"  class="'.$meta['jvd'].'" />';
			if($meta['mode']=="view"){
				return  form::jSpan($meta['lbl'],$meta['fld'],$key,$nowrap);
				break;
			}
		}
		else{
			$ret .= '	<label for="'.md5($key).'">'.$key.'</label>';
			$ret .= '	<input id="'.$meta['fld'].'" name="'.$meta['fld'].'" value="'.$value.'" type="radio" class="'.$meta['jvd'].'" />';
		} 
		} 	
		$ret .='</div>';
		if($meta['mode']=="view"){
			return  form::jSpan($meta['lbl'],$meta['fld'],'---?---',$nowrap);
			break;
		}
		
		return $ret;
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* Generate Html image for db field
	*
	* @param array		$meta Array of:
								[mode] mode in [new view edit]
								[onclick] 
								[query] Text field query
								[jvd] Text field validation class e.g. date number..
	* @return string	Generated Html
	*/
	function jDBImage($meta){
		// form::jDBImage(array('query'=>'','onclick'=>'loaduploadify();','jvd'=>''));
		$onclick = $meta['mode'] != 'view' ? $meta['onclick'] : '';
		
		$data = $meta['mode'] != 'new' ? dbase::jfetch($meta['query']) :  '';
		$ret = '<span onclick="'.$onclick.'" class="'.$meta['jvd'].'" style="position:relative;border:1px solid #777;float:right;clear:left;margin:10px;text-align:center;min-height:100px;min-width:80px">';
		
		$ret .=$meta['mode'] != 'new' ? '<object style="padding:5px" data="data:image/bmp;base64,'.base64_encode($data[0]).'" type="image/bmp"></object>': '';
		
		$ret .='</span>';
		
		return $ret;
	
	}
    /**
     * Get loading div html code
     * 
     * @return string Output html
     */
    function getLoadingDiv(){
        return '<div id="jloading">'.txt('loading').'</div><!-- jLoading -->';
    }



}  





?>