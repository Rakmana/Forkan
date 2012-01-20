<?php

	
class user {
	/**
	 * user::init()
	 * 
	 * @return
	 */
	function init(){
		
		/*if(session::jlogged()){
			$juserID =  session::get('juserID');
			//$info = dbase::jfetch("SELECT MATRICULE FROM wpl_users WHERE uid='".$juserID."';");
		}*/
	}

	/**
	 * user::login()
	 * 
	 * @param bool $jForm       get login form
	 * @param bool $redirected  if redirected from another task
	 * @return bool
	 */
     
     //TODO: Will add user  & pass as args form internal login
	function login($jForm=false,$redirected=false){
        
        if(self::jlogged()){
           jawab::setNotification(form::notify('warn',txt('alreadyloggedin'))); 
           return true; 
        }
		
        $r = false;
        
		if(!empty($_REQUEST['juser']) && !empty($_REQUEST['jpass'])){//if login form send
			/*-----------------------------------------------------------*/	
			$user = (tools::jEscape(env::xss_clean($_REQUEST['juser'])));
			$pass = md5(tools::jEscape(env::xss_clean($_REQUEST['jpass'])));

			$pasqry = "SELECT UID,ACCESS FROM wpl_users WHERE username='".$user."' AND password='".$pass."';";
			$usrqry = "SELECT UID FROM wpl_users WHERE username='".$user."';";
			
			//$num = dbase::jNum($qry);	
			if(dbase::jNum($usrqry) == 1){
				if(dbase::jNum($pasqry) == 1){
				
					$info = dbase::jfetch($pasqry);
					
					
					//store user ID to session object
					session::set('juserc',$info['UID']);
					session::set('juserID',$info['UID']);
					session::set('juserAcr',$info['ACCESS']);
					
					//@ToDo add test if cookie not created show notif
					session::set('jlogged',md5(date("Y-m-d")));
					session::jSetCookie(session::jToken(),md5(date("Y-m-d")));
					
					$name = user::getName();
					
                    if(router::isajx()){
                       $ret = '<loggedin>1</loggedin>';
					   $ret.= '<juser>'.$name.'</juser>';
                       jawab::setContent($ret);
                    }
                    else{
                        //$app = kore::getInstance(APPC);    
                        //fpage::index();
                        jawab::addNotification(form::notify('ok',txt('welcome').' '.$name));
                        @header("location: ".$_SERVER["HTTP_REFERER"]);
                    }
                    
					$r = true;
				}
				else{ // if pass not matched
                    if(router::isajx()){
                       $ret = '<loggedin>3</loggedin>';
                       jawab::setContent($ret);
                    }
                    else{
                        jawab::addNotification(form::notify('warn',txt('loginincorrect')));
                        form::sendForm(PAPP.'common'.DS.'login.php');
                    }
				} 
			}
			else{ // if user not found
                    if(router::isajx()){
                       $ret = '<loggedin>2</loggedin>';
                       jawab::setContent($ret);
                    }
                    else{
                        jawab::addNotification(form::notify('warn',txt('loginincorrect')));
                        form::sendForm(PAPP.'common'.DS.'login.php');
                    }
				 
			}
			
			/*-----------------------------------------------------------*/	
				
				
		}
		else{
		  if($redirected){
		      	jawab::setNotification(form::notify('warn',txt('relogin')));
		        form::sendForm(PAPP.'common'.DS.'login.php');
		  }
          
			//if get login form
		  if(isset($_GET['jForm']) or ($jForm != false)){
			 form::sendForm(PAPP.'common'.DS.'login.php');
			 jawab::setAjxTitle(txt('login_ttl'));
			 jawab::setTitle(txt('login_ttl'));
             if($redirected){
		      	jawab::setNotification(form::notify('warn',txt('relogin')));
		      
		  }
             
		  }
		  else {
		      // if vars is empty
                    if(router::isajx()){
                       $ret = '<loggedin>4</loggedin>';
                       jawab::setContent($ret);
                    }
                    else{
                        jawab::addNotification(form::notify('warn',txt('set_all_infos')));
                        form::sendForm(PAPP.'common'.DS.'login.php');
                    }
				 
		  }
	   }
       if($r == false){        
            jawab::setTitle(txt('login'));
            jawab::setAjxTitle(txt('login'));
       }	
	   return $r;	
	}

	/**
	 * user::logout()
	 * 
	 * @return
	 */
	function logout(){

	if(session::get('jlogged') != false){
		//@ToDO 
			session::jDelCookie(session::jToken());
			//setcookie('jsapAdmin','0000',time() - 3600,SH.KBAS.SH);
			session::remove('jlogged');
			session::destroy();//destroy session object
                    if(router::isajx()){
                       $ret = '<loggedout>1</loggedout>';
                       jawab::setContent($ret);
                    }
                    else{
                        jawab::addNotification(form::notify('warn',txt('loggedout')));
                        form::sendForm(PAPP.'common'.DS.'login.php');
			            
                        if(!empty($_GET['nactvt'])){//en cas d'inactivité
				            jawab::setNotification(form::notify('warn',txt('inactivitydetected')));
			            }
                    }
			
            $r = true;
		}
		else{
                    if(router::isajx()){
                       $ret = '<loggedout>0</loggedout>';
                       jawab::setContent($ret);
                    }
                    else{
                        jawab::addNotification(form::notify('warn',txt('alreadyloggedout')));
                        form::sendForm(PAPP.'common'.DS.'login.php');
			        }    
            $r = false;
        }	
	
        
        return $r;
	}

	/**
	 * user::jlogged()
	 * 
	 * @return
	 */
	function jlogged(){
		// USER_ID+BROWSER+IP all of them hashed with md5 funcion...
		
		if(!empty($_COOKIE[session::jToken()]) and ($_COOKIE[session::jToken()]== md5(date("Y-m-d"))) and (session::get('jlogged') == md5(date("Y-m-d")))){
			$ret = true;
		}else{
			$ret =  false;
			//this for security issue if unwanted cookie delete session vars will be freed...
			//session::jDelCookie(session::jToken());
			//session::free();
		}
		return $ret;
	}

	/**
	 * user::exists()
	 * 
	 * @param mixed $eid
	 * @return
	 */
	function exists($eid){
		if(dbase::jNum("SELECT username FROM wpl_users WHERE uid='".$eid."';")==1){	
			return true;
		}
		else 
			return false;
	}

	/**
	 * user::getName()
	 * 
	 * @return
	 */
	function getName(){
		
		$info = dbase::jfetch("SELECT concat(FirstName,' ',LastName) as uname FROM wpl_users WHERE uid='".session::get('juserc')."';");
		return $info['uname'];
	}

	/**
	 * user::getID()
	 * 
	 * @return
	 */
	function getID(){
		return session::get('juserID');
	}

	/**
	 * user::getAcr()
	 * 
	 * @return
	 */
	function getAcr(){
		//$inf = dbase::jfetch("SELECT LEVEL FROM wpl_users WHERE uid='".user::getID()."';");
		//return jpriv::parse($inf['ACCESS']);
		return session::get('juserAcr');
	}

	/**
	 * user::Showlog()
	 * 
	 * @return
	 */
	function Showlog(){
	$logs = dbase::jfetch('SELECT ACTIONS FROM utilis_journal WHERE (JID=1) AND (CODE_UTILISATEUR=1);');
	$ret = "\n";
	$ret = '<pre class="console">'.($logs['ACTIONS']).'</pre>';
	return $ret;
	
	}
	
}

?>
