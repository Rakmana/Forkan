

function showhome(){
    jLoad('<?= form::clink('kjax', APPC,'index','w') ?>', $('#content'));
    
}
/*
function showSidebar(){
$('#jbody').animate({ margin<?= ucfirst(txt('align')) ?>:"150px" } , 700,function(){
//$('#sidebar').css('display','block');
$('#sidebar').animate({<?= ucfirst(txt('align')) ?>:"0px"}, 500, function(){$(this).show()});
});
}

function hideSidebar(){
$('#sidebar').animate({<?= ucfirst(txt('align')) ?>:"0px"}, 500, function(){
$(this).hide();
$('#jbody').animate({ margin<?= ucfirst(txt('align')) ?>:"0px" } , 700 );
});

}
*/
function showLoginForm() {
/*<?=(applications::isSecure(APPC)? 'hideSidebar();' : '')?>*/
loadForm('<?= form::clink('kjax', APPC, 'login', 'jForm') ?>', $('#content'),$('#logx'));
}


function ShowCpanel() {
loadForm('<?= form::clink('kjax', 'fpage', 'form', 'fid=cpanel') ?>', $('#content'),$('#logx'));
}

function goLogin(){
    //jvalidation
    if(!isValid($('#formLogin'))){
        return false;
    }

    var  jParam = {
            juser : encodeURI(getE('juser').value),
            jpass : encodeURI(getE('jpass').value)
        };

    wpl_goLogin(jParam,function(res,status){
            rem_loading();
            $('#formLogin').remove();
            //showSidebar();

            var JUSER = xml2txt(res.responseXML,'juser');//user full name
            $('#logx').empty().append('<span class="btn tipy b-unlock" title="'+JUSER+' - <?=txt('logout')?>" onclick="goLogout();"></span>'+
            '<span class="btn tipy b-sets" title="<?=txt('cpanel')?>" onclick="ShowCpanel();"></span>'+
            '<span class="btn tipy b-usr" title="<?=txt('profile')?>" onclick="showProfile();"></span>'+
            '<span class="btn tipy b-ejc" title="<?= txt('upload') ?>" onclick="loaduploadify(`\'public/uploads\');" ></span>');
            notify('ok','<?=txt('welcome')?>:  '+JUSER);
            JUserLoggedin = true;
            //add_loading('content');
            /*jLoad('<?=form::clink('kjax', APPC)?>', $('#content'));
                //---- update sidebar
                loadForm('<?=form::clink('kjax','fpage','form','fid=sidebar')?>', $('#sidebar'),efired);
                //---- update Topbar
                loadForm('<?=form::clink('kjax','fpage','form','fid=topbar')?>', $('#main_nav'),efired);
                //---- update Linkbar
                loadForm('<?=form::clink('kjax','fpage','form','fid=linkbar')?>', $('#wlinks'),efired);
                //---- update About
                loadForm('<?=form::clink('kjax','fpage','form','fid=about')?>', $('#aboutbox'),efired);
             */
				location.reload();
            
            }
            
            
            ,function(res,status){
            notify('alert','<?=txt('usernameincorrect')?>');
            /*<?=(applications::isSecure(APPC)? 'hideSidebar();' : '')?>*/
            pressed = false;}
            
            ,function(res,status){
            notify('alert','<?=txt('passordincorrect')?>');
            /*<?=(applications::isSecure(APPC)? 'hideSidebar();' : '')?>*/
            pressed = false;}
            
            
            ,function(res,status){
             
			 showLoginForm();
			 $('#logx').empty().append('<span class="btn tipy b-lock" title="<?=txt('login')?>" onclick="showLoginForm();"></span>');
			notify('alert','<?=txt('relogin')?>');
            /*<?=(applications::isSecure(APPC)? 'hideSidebar();' : '')?>*/
            pressed = false;}
    )

}


function goLogout(param){
if(!param) param = '';


wpl_goLogout(param,$('#logx'),function(res,status){
    JUserLoggedin = false;
    $('#logx').empty().append('<span class="btn tipy b-lock" title="<?= txt('login') ?>"  onclick="showLoginForm();"></span>');
    var response = xml2txt(res.responseXML,'form');
    //$("#content").empty().append(response);//clear content div and show login form
    /*<?=(applications::isSecure(APPC)? 'hideSidebar();' : '')?>*/
    /*
    jLoad('<?=form::clink('kjax','fpage')?>',$('#content'));
    //---- update sidebar
    loadForm('<?=form::clink('kjax','fpage','form','fid=sidebar')?>', $('#sidebar'),efired);
    //---- update Topbar
    loadForm('<?=form::clink('kjax','fpage','form','fid=topbar')?>', $('#main_nav'),efired);
    //---- update Linkbar
    loadForm('<?=form::clink('kjax','fpage','form','fid=linkbar')?>', $('#wlinks'),efired);
    //---- update About
    loadForm('<?=form::clink('kjax','fpage','form','fid=about')?>', $('#aboutbox'),efired);*/
	
	location.reload();
                                
},function(res,status){
    $('#logx').empty().append('<span class="btn tipy b-lock" title="<?= txt('login') ?>" onclick="showLoginForm();"></span>');
    notify('warn','<?=txt('alreadyloggedout')?>');
});


}

