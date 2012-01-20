/**
 * @version $Id: wapl.js 9853 2009-03-25 15:43:29Z my2cha$
 * @package  WAPL
 * @link http://www.itkane.com/
 * @author     Khedrane Atallah <jnom23@gmail.com>
 * @copyright  (C) 2003 - 2011 Khedrane Atallah
 * @license    http://gnu.org/copyleft/gpl.html GNU GPL
 * @version 1.0

*/


var iniTitle = '<?=(' | ' . konfig::get('siteTitle').' '.konfig::get('version')) ?>';

function getE(id){//Get selector id for all browsers
	if(document.getElementById) {
		return document.getElementById(id);
	} else if(document.all) {
		return document.all[id];
	} else {return;}
}
function replace(id,content){//replace html content by id
	getE(id).innerHTML = content;
}

function add(id,content){//replace html content by id
	getE(id).innerHTML = content+getE(id).innerHTML;
}
function add_loading($target){
  <?php if(konfig::isOn('showloadingDiv')){?>

	$('#jloading').fadeIn(300,function(){$(this).show()});

	if(!$target){
       return;
    }

	var obj = ( typeof ($target)=='object') ? $target : $('#'+$target);
	var wd = obj.outerWidth();
	var ht = obj.outerHeight();
	var tl = obj.offset();
	var bg = (ht>50 && wd>50)? 'background:#FFF url(\""<?=form::mlink('img','media/loading-28.gif')?>\") center center no-repeat;' : '';
//	$('<div class="ajax_loading trans" id="ajax_loading" style="'+bg+'z-index:999998;position:absolute;width:'+wd+'px;height:'+ht+'px;left:'+tl.left+'px;top:'+tl.top+'px;" title="<?=txt('loading')?>"></div>').prependTo('#page');
  
  
  
  <?php } ?>
}
function rem_loading(){
  <?php if(konfig::isOn('showloadingDiv')){?>
  	$('.ajax_loading').remove();
	$('#jloading').fadeOut(300,function(){$(this).hide()});
  <?php } ?>
}
function xml2txt($xml,$tagname){
	var $content = $xml.getElementsByTagName($tagname);
	if($content){
		if($content[0]){
			if($content[0].firstChild){
				return $content[0].firstChild.nodeValue;
			}
		}
	}
	return false;
}
	/**
	* Load url data in DOM Element
	*
	* @access public
	* @return 
	*/
function jLoad(url, id, jdata, type,callback) {
	loader(url, id, function(res, status){
		var cnt = xml2txt(res.responseXML,'wctn');
		if(cnt){//content
			id.empty().append(cnt);
		}   
        //------ callback ------
		if( callback )
			id.each( callback, [res, status] );
		rem_loading();
	},jdata,type);
}

//--- My Own forked version of $.getScript function
function jetScript(url, callback ,jdata, type) {
		if ( typeof url !== "string" )
			return false;

		// Default to a GET request
		if(!type) type = "GET";

		// Request the remote document
		jQuery.ajax({
			url: url,
			type: type,
			data: (!jdata)? '': jdata,
			dataType: 'script',
			cache : true,
			success: function(res, status){
				// If successful: do callback()
				if ( status == "success" || status == "notmodified" ){
					
                    //------ callback ------
					if( callback ){
						callback(res, status);
					};
				}
				else{
					notify('warn','WAPL::AjaxError :  Script Request not success  !');
				}
			}
		});
		return;
}

function loader(url, id, callback ,jdata, type) {
		if ( typeof url !== "string" )
			return false;

		// Default to a GET request
		if(!type) type = "GET";

		var self = this;
		add_loading(id);

		// Request the remote document
		jQuery.ajax({
			url: <?=(!konfig::isOn('AjaxCache')? 'url': 'patchUrl(url)')?> ,
			type: type,
			data: (!jdata)? '': jdata,
			dataType: "xml",
			complete: function(res, status){
				// If successful, inject the HTML into all the matched elements
				if ( status == "success" || status == "notmodified" ){
					//---- notifications ----
                    var ntf = xml2txt(res.responseXML,'wntf');
					if(ntf){
						jNotify(ntf);
					}
                    //------- Titles --------
                    var doctitle = xml2txt(res.responseXML,'doctitle');
					if(doctitle){
						jTitle(doctitle);
					}
                    //------ callback ------
					if( callback )
						id.each( callback, [res, status] );
					rem_loading();
				}
				/*else{
					notify('warn','WAPL::AjaxError :  Get Request not success  !');
				}*/
				rem_loading();
				jinit();
			}
		});
		return;
	}


function loadForm(url, id, target, callback, jdata,type) {
    if ( typeof url !== "string" )
        return false;
    add_loading(target);

		// Default to a GET request
		if(!type) type = "GET";

    var self = this;

    // Request the remote document
    jQuery.ajax({
        url: <?=(!konfig::isOn('AjaxCache')? 'url': 'patchUrl(url)')?>,
        type: type,
        data: (!jdata)? '': jdata,
        dataType: "xml",
        complete: function(res, status){
            // If successful, inject the HTML into all the matched elements
            if ( status == "success" || status == "notmodified" ){
                var response = xml2txt(res.responseXML,'wfrm');
                if(response) {
                    id.empty();
                    $(response).fadeIn(700).appendTo(id);
                    //alert('00: '+response);
                }
                else{
                    notify('warn','WAPL::AjaxError : Response Not Structured !');
                }
                //jbox.show();jbox.setContent(response);
                //alert(response);
                
                //---- notifications ----
                var ntf = xml2txt(res.responseXML,'wntf');
				if(ntf){
					jNotify(ntf);
				}
                //------- Titles --------
                var doctitle = xml2txt(res.responseXML,'doctitle');
				if(doctitle){
					jTitle(doctitle);
				}
                //------ callback ------
				if( callback )
					id.each( callback, [res, status] );
				rem_loading();
                /*
                if( callback ) callback(res, status);
                rem_loading();

                var ntf = xml2txt(res.responseXML,'notification');
                if(ntf){ jNotify(ntf);}*/

            }
            rem_loading();
            jinit();
        }
    });
    return;
}
function jTitle($title){
  $('html head title').text($title+iniTitle);
}
function hidE(id){//hide element
	/*var element = getE(id);
	element.style.display = 'none';*/
	$('#'+id).fadeOut(500,function(){$(this).hide();});

	//$('#'+id).remove();
}

function show(id){//show element
	var element = getE(id);
	element.style.display = 'block';
}

function showhide($id){//show element
	var element = getE($id);
	if(element.style.display == 'none') show($id);
	else hidE($id);
}

function notify($type,$msg){
	var $id = new Date().getTime() + "" + parseInt(Math.random()*100000);
	if(!$type) {$type = 'warn';}
	var html = ('<div id="'+$id+'" class="notify '+$type+'"><span class="sgn s-'+$type+'" style="float:<?=txt('align')?>;"></span><span class="btn b-exit" style="float:<?=txt('align2')?>" title="<?=txt('close')?>" onclick="hidE(\''+$id+'\')"></span>'+$msg+'</div>');
	jNotify(html);
return $id;
}

function jNotify(html,riset){
    //if(!riset) riset = true;
	if(riset != false){$("#notification").empty();}
	//$('.notify').corner("keep");
	$(html).corner("keep").fadeIn(300).fadeOut(100).fadeIn(100).appendTo("#notification");
    window.location.hash = "notification";
    //window.location.replace(window.location+"#notification");
	return true;
}

function hideNotify(){
var obj = getE('notify');
obj.innerHTML = '';
obj.className = 'notify';
}
/*==================================================
  Cookie functions
  ==================================================*/
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie= name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires.toGMTString() : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}
function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return false;
    } else {
        begin += 2;
    }
    var end = document.cookie.indexOf(";", begin);
    if (end == -1) {
        end = dc.length;
    }
    return unescape(dc.substring(begin + prefix.length, end));
}
function deleteCookie(name, path, domain) {
    if (getCookie(name)) {
        document.cookie = name + "=" +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            "; expires=Thu, 01-Jan-70 00:00:01 GMT";
    }
}
/*==================================================
  / end Cookie functions
  ==================================================*/
function OpenMediaBrowser(){
    //Boxy.load('<?=form::clink('kjax','fpage','mediaBrowser','v2')?>')  ;
    jLoad('<?=form::clink('kjax','fpage','mediaBrowser','v2')?>',$('#sfiles'),null,'GET',function(){
          
      $('#sfiles').find("select").change(function(){
        var v = $(this).val();
        $('#sfiles').parent().find(":text").val(v);
      });
    });
    
}  
function loaduploadify($folder,callback){
//jbox.show();
//jbox.tween(400,300);
new Boxy('<div id="uploadifyDIV" style="width:400px;text-align:center;padding:auto;"><span id="uploadUrl"></span><div id="fileQueue" style="width:300px"><input type="file" name="uploadify" id="uploadify" /></div></div>');


add_loading($('#uploadifyDIV'));
loadcss('<?=form::mlink('wcss','scripts/uploadify.css')?>');
loadScript('<?=form::mlink('wjs','scripts/swfobject.js')?>',function(){
loadScript('<?=form::mlink('wjs','scripts/uploadify.js')?>',function(){
//$('<span id="uploadUrl"></span><div id="fileQueue"><input type="file" name="uploadify" id="uploadify" />').prependTo('#uploadifyDIV');
//show('uploadifyDIV');

	$("#uploadify").uploadify({
		'uploader'       : '<?=form::mlink('swf','media/uploadify.swf')?>',
		'expressInstall' : '<?=form::mlink('swf','media/expressInstall.swf')?>',
		'script'         : '<?=KURL?>?upload=1',
		'checkScript'    : '<?=KURL?>?checkupload=1',
		'cancelImg'      : '<?=form::mlink('img','media/cancel.png')?>',
		'folder'         : $folder,// 'uploads'
		'queueID'        : 'fileQueue',
		'buttonText'     : 'Browse',
		'auto'           : true,
		'multi'          : false,
		'onComplete'     : function(event, ID, fileObj, response, data){
		 		//---- notifications ----
                notify('info','<a href="'+unescape(response)+'" target="_blanc"><?=txt('link')?></a>');
                /*getE("mcbattc").value = unescape(response);//'<?=form::flink('public'.SH.'uploads'.SH)?>'+unescape(response)+'';
				$('#uploadifyDIV').remove();
				$('#up').attr('title','1 : '+unescape(response));
				$('#up').empty().append('1');*/
              //------ callback ------
			  if( callback )
				 this.each( callback, [event, ID, fileObj, response, data] );
				
		}
	});
});
rem_loading();
});
}




//format ad return a time stamp
function timestamp(){
	var now = new Date();
	var hours = now.getUTCHours(); if(hours < 10) { hours = '0' + hours; };
	var mins = now.getUTCMinutes(); if(mins < 10) { mins = '0' + mins; };
	var secs = now.getUTCSeconds(); if(secs < 10) { secs = '0' + secs; };
	var mils = now.getUTCMilliseconds(); if(mils < 100) { mils = '00' + mils; } else if(mils < 10) { mils = '0' + mils; };

	return hours + ':' + mins + ':' + secs;
}
function timenow(){
var timeSpan = getE('timeSpan');
var now = timestamp();
timeSpan.innerHTML = now;
setTimeout("timenow()", 1000);
}

/**
 * Add random number to url to stop IE from caching
 *
 * @example url("data/test.html")
 * @result "data/test.html?10538358428943"
 *
 * @example url("data/test.php?foo=bar")
 * @result "data/test.php?foo=bar&10538358345554"
 */
function patchUrl(value) {
	return value + (/\?/.test(value) ? "&" : "?") + new Date().getTime() + "" + parseInt(Math.random()*100000);
}

function randomiz() {
	return  new Date().getTime() + "" + parseInt(Math.random()*100000);
}
var callbackNum = 0;
function Log(msg){
  callbackNum++;
  var console = $('#callbacks-console');
  console.append('<div>'+callbackNum+' :: '+msg+'</div>');
  // A barbarity, but ok for this time
  console.scrollTop(99999);
  return true;
}






function jencoder(str){
    st = str.replace(/s/gm, '--T');
    st = st.replace(/v/gm, '--W');
return st;
}

function wpl_goLogin(jParam,cb1,cb2,cb3,cb4){


    loader('<?=form::clink("kjax", APPC, "login")?>',efired,function(res,status){
        var LOGGEDIN = xml2txt(res.responseXML,'loggedin');
        //alert('r:'+LOGGEDIN);
        
        if(LOGGEDIN === '1'){
            if(cb1) efired.each( cb1, [res, status] );
        }
        //---- Username incorrect
        if(LOGGEDIN === '2'){
            if(cb2) efired.each( cb2, [res, status] );
        }
        //---- Password incorrect
        else if(LOGGEDIN === '3'){
            if(cb3) efired.each( cb3, [res, status] );

        }
        //--- Empty post var 
        else if(LOGGEDIN === '4'){
            if(cb4) efired.each( cb4, [res, status] );

        };
    },jParam,"POST");
}

function wpl_goLogout(param,Sender,cb2,cb1){
    if(!param) param = '';
    if(param != '')  param = '&'+param;//if not emty set query symbol

    loader('<?=form::clink("kjax", APPC, "logout","wpl")?>'+param,Sender,function(res,status){

        var LOGGEDOUT = xml2txt(res.responseXML,'loggedout');
        if(LOGGEDOUT === '0'){
            if(cb1) Sender.each( cb1, [res, status] );
            //$('#logx').empty().append('<span class="btn tipy b-lock" title="<?=txt('login')?>"></span>');
        }
        else if(LOGGEDOUT === '1'){
            JUserLoggedin = false;
            if(cb2) Sender.each( cb2, [res, status] );
            /*$('#logx').empty().append('<span class="btn tipy b-lock" title="<?=txt('login')?>" ></span>');
            var response = xml2txt(res.responseXML,'form');
            $("#content").empty().append(response);//clear content div and show login form
            hideSidebar();*/
        };


});
}
function goSavekonfig(){
//jvalidation
if(!isValid($('#formkonfig'))){
return false;
}
var  jParam = {
    v : '2.0'


<?php

	$keys =& $GLOBALS['KONFIG']->xml['sys']->xpath("//key[@ui=1]");
	foreach($keys as $key){
			$ktp = $key['type'];
			$knm = $key['name'];
		 //if($key['ui'] != '0'){
		    if($ktp != 'bol' ){
    echo ",'$knm-frm' : $('#$knm-frm').val()";	

			}
			else{
    echo ",'$knm-frm' : ($('#$knm-frm:checked').val())? '1' : '0'";
}
		//}
    }
?>
};

	loader('<?=form::clink("kjax","fpage","savekonfig","v")?>',efired,function(res,status){
			var SAVED = xml2txt(res.responseXML,'saved');
			if((!SAVED)){
			//	notify('alert','<?=txt('edit_failed')?>');
			}
			if(SAVED === '0'){
				notify('alert','<?=txt('edit_failed')?>');
			}

			if(SAVED === '1'){
				notify('ok','<?=txt('edit_success')?>');
				location.reload();

			};
			
		},jParam,'POST');
}



//Inactivite check & logout automaticly
<?php if(konfig::isOn('InactivityCheck')){?>
var now = new Date();
var lastEvent= now.getTime();//last Event TimeStamp
$(document.body).bind( 'mousemove' ,  function () {
    var now = new Date();
    lastEvent = now.getTime() ;

//alert('is move');
} );
<?php } ?>
function checkInactivite(){
    var now = new Date();
    var nowTime = now.getTime() ;
    //alert('is checked:  '+ nowTime +'-'+ lastEvent);
    if(((nowTime - lastEvent) > <?=konfig::get("InactivityDelay")?>) && JUserLoggedin){//23min
        //alert('Inactivite detected ! ');
        if($.isFunction(goLogout) ){
            goLogout('nactvt=1');
        }
        else wpl_goLogout('nactvt=1',$('body'),function(res,status){
            JUserLoggedin = false;
            var response = xml2txt(res.responseXML,'form');
            $("#content").empty().append(response);//clear content div and show login form
    
            },function(res,status){}
        );

        JUserLoggedin = false;
        clearTimeout(id);
        return;
    }
    var id = setTimeout("checkInactivite()", 10000);//every 10 sec
}


/* this function for logout when walk away from myWAPL
$(window).unload( function (e) {
deleteCookie("jsp","<?=SH . KBAS . SH?>");
//alert("      Au  revoire .    ");
} );
*/

/* Form filter #########################################################*/

function jvalidate($frm){
var errorClass = "erroni";

//  numeric 0-9
$('.jnumeric').bind('keyup focusout change',function(e){

//if is required field
if($(this).hasClass('jrequired') && !($(this).val()) ){
//alert('required');
$(this).addClass(errorClass);
$(this).next().remove();
$(this).parent().append(jValidError('<?=

txt('jrequired')

?>'));

}
else{
if (!(/^[0-9\.]+$/.test($(this).val())) && $(this).val()){
$(this).addClass(errorClass);
$(this).next().remove();
$(this).parent().append(jValidError('<?=

txt('jnumeric')

?>'));
}
else{
$(this).removeClass(errorClass);
$(this).next().remove();
$(this).parent().append(jValidOk());
};
}
});

//  date yyyy-mm-dd
$('.jdate').bind('keyup focusout change',function(e){
var val = $(this).val();


// 10 char limiter
if(val.length >= 10){
$(this).val(val.substring(0,10)) ;
val = $(this).val();//redefine value after this
}


//autoFormate
if((e.which != 8)//if not (backspace) pressed
&& (e.which != 46)//if not (suppr) pressed
&& (e.which != 109)//if not (-)(numpade) pressed
&& (e.which != 54 )//if not (-) pressed
){
if($(this).val().length > 4 && $(this).val().charAt(4) != '-' ){//if value length position aaaa-
//$(this).val($(this).val()+'-') ;
$(this).val(val.slice(0,4)+'-'+val.slice(4)) ;
val = $(this).val();
};
if($(this).val().length > 7 && $(this).val().charAt(7) != '-'){//if value length position  aaaa-mm-
$(this).val(val.slice(0,7)+'-'+val.slice(7)) ;
val = $(this).val();
}
};

//if is required field
if($(this).hasClass('jrequired') && !($(this).val()) ){
$(this).addClass(errorClass);
$(this).next().remove();
$(this).parent().append(jValidError('<?=txt('jrequired')?>'));
$(this).focus();
}
else{
if (!(/^[0-9]{4}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}$/.test($(this).val())) //date format aaa-mm-jj
//&& /Invalid|NaN/.test(new Date($(this).val())) // is valid date no 0000-15-35
&& $(this).val()){ // not empty
$(this).addClass(errorClass);
$(this).next().remove();
$(this).parent().append(jValidError('<?=txt('jdate')?>'));
//alert('   Must be only a numbers !   ');
}
else{
$(this).removeClass(errorClass);
$(this).next().remove();
$(this).parent().append(jValidOk());
}
}
});

//  required
$('.required').bind('keyup focusout change',function(e){
if (!($(this).val()) ){
$(this).addClass(errorClass);
$(this).next().remove();
$(this).parent().append(jValidError('<?=txt('jrequired')?>'));
$(this).focus();
}
else{
$(this).removeClass(errorClass);
$(this).next().remove();
$(this).parent().append(jValidOk());
}
});

/*
/^[0-9]{4}\-\[0-9]{1,2}\-\[0-9]{1,2}$/
/^[0-9\-\(\)\ ]+$/
/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/

*/
}

function isValid($frm){

var $form = ( typeof ($frm)=='object') ? $frm : $('#'+$frm);
//if form not filled so this event not fired we fire it manualy here
$form.find('.jnumeric').focusout();
$form.find('.jdate').focusout();
$form.find('.required').focusout();

if($form.find('.notValid').length > 0){
// show message
notify('alert','<?=txt('checkError')?>');
// focus the 1st elem invalid
$form.children('.notValid:first').focus();
return false;
}
else
return true;
}

function jValidOk(){
var ret = '<div class="logErr"><span class="sgn s-ok"></span></div>';
return ret;
}

function jValidError(txt){
var ret = '<div class="logErr notValid"><span class="sgn s-stop" title="'+txt+'"></span></div>';
return ret;
}
