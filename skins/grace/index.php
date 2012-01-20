<?php

defined('WPL_RUN') OR die(' F O R B I D D E N  ! ');
    function AppInfoBox(){
		$inf = applications::getinfo(APPC);
		return '<span class="app-name">:  '.$inf['name'].'</span><br /><span class="app-desc">'.$inf['description'].'</span>';
    };
    
    
?>