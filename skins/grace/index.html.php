<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"  dir="<?= txt('dir') ?>">
<head>
<title><?= jawab::getTitle() . ' | ' . konfig::get('siteTitle') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link rel="shortcut icon" href="<?= form::mlink('wimg', 'favicon.png') ?>" type="image/png" />
<link href="<?=form::mlink('css', 'scripts/common.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?=form::mlink('css', SKN .'layout.css')?>" rel="stylesheet" type="text/css" />
<link href="<?=form::mlink('css', SKN .'style.css')?>" rel="stylesheet" type="text/css" />
<?=WPI::getVar('headex')?>
<script type="text/javascript"><?=WPI::getVar('jsh')?> </script>
<style type="text/javascript"><?=WPI::getVar('css')?> </style>
<!--[if lt IE 7]>
<style>

.button, .jbutton {
	background-color:#F70;
	border: 1px outset rgb(223,118,44);
    outline: none;
	margin: 5px;
	padding:5px 10px;
	text-align:center;
	color:#fff;
	font:bold 11px tahoma;
	text-decoration:none;
	cursor : hand;
	cursor : pointer;

}
.button:hover, .jbutton:hover {
    
	background-color:#F90;
	border: 1px otuset rgb(223,118,44);
    outline: none;
	color:#EEE;
	text-shadow:none ;

}
.blk .lbl,.blk input,.blk select,.blk span{display:inline;width:auto;}
.blk .lbl{text-align:<?=txt('align2')?>;margin:2px;width:30%;}
</style>
<![endif]-->
</head>
<?php ob_flush();?>
<body id="page1">
<div class="bg3">
<div class="main">
    <div id="header">
        <a href="<?=KURL ?>"><img src="<?=form::mlink('img', SKN .'media'.SH.'laroui.png')?>" class="png logo" title="Laroui H.S.E" alt="larouiHSE"/></a>
        <div class="menu">
        	<ul>
                <li><a href="<?=KURL ?>" class="png"><?=WPI::txt('home')?></a></li>
            </ul>
            <?= fpage::getTopbar() ?> 
        	<ul>
                <li><a href="<?=form::clink('site','jallery')?>" class="png"><?=WPI::txt('gallery')?></a></li>
                <li><a href="<?=form::clink('site','contact')?>" class="png"><?=WPI::txt('contact')?></a></li>
            </ul>          
        </div>
    </div>
    <div id="kontent">		
    	<div class="container"><div class="banner">
        	<div id="featured">

               <div class="indent2">
                   <!-- First Content -->
                   <div id="fragment-1" class="ui-tabs-panel">
                      <img src="<?=form::mlink('img', SKN .'images'.SH.'slide45.jpg')?>" alt="" />
                   </div>
               </div>
               <div class="infos">
                  <h1><?= txt('about') ?></h1>
                  <div class="indent-1">
                  		<?=fpage::getAbout()?>        
			      </div>                  
               </div>
            </div>
        </div></div>
        <div class="bg_cont">
            <div class="bg-cont-top">

                <div class="indent-main2">
                    <div class="container bg">
                        <div class="col-1">
                        	<div class="indent-col1">
                                <div class="tail">        <?=WPI::getLoadingDiv()?>
									<div id="notification"><?= jawab::getNotification('notifications') ?></div>
                                	<div class="container" id="content">
	                               <?= jawab::getContent() ?>
                                    </div>
                                    
                                </div>
                               <h2> upcoming events</h2>
                               <div class="container">
                               		<div class="col-1">
                                    	<h4><a href="#">West Coast Faculty Conference</a></h4>
                                        Catalina Island, CA | Aug 1 - 6, 2010 <br />
                                        <a href="#">Learn More</a> | <a href="#">Register</a>
                                    </div>
                               		<div class="col-2">
                                    	<h4><a href="#">2010 LaFe Conference</a></h4>
                                        Addison, TX | Dec 27 - 31, 2010  <br />
                                        <a href="#">Learn More</a> | <a href="#">Register</a>
                                    </div>
                               </div>
                            </div>
                        </div>
                        <div class="col-2">
                        	<div class="indent-col1" id="sidebar">
                                        

        
<!-- LOGX -->  
        <div id="logx" style="float:<?= txt('align2') ?>">

        <?php if (WPI::isloggedin()) {?>
        <span class="btn tipy b-unlock" title="<?= user::getName() . ' - ' . txt('logout') ?>" onclick="goLogout();"></span>
        <span class="btn tipy b-sets" title="<?=txt('cpanel') ?>" onclick="ShowCpanel();"></span>
        <span class="btn tipy b-usr" title="<?=txt('profile')?>" onclick="showProfile();"></span>
        <span class="btn tipy b-ejc" title="<?= txt('upload') ?>" onclick="loaduploadify('public/uploads');" ></span>
         <?= appendProfiler() ?>
        <?php
        } else {
        ?>
        <span class="btn tipy b-lock" title="<?= txt('login') ?>" onclick="showLoginForm();" ></span>
        <?php };?>
        </div>
<!-- / LOGX -->
								<br />
                            	<h2><?= txt('contents') ?></h2>
                                <div class="container p2">
                                    <div class="col-6">
                                        <?= fpage::getSidebar() ?>
                                    </div>
                                </div>
								<h2><?= txt('links') ?></h2>
                                <div class="container p2">
                                    <div class="col-6">
                                        <?= fpage::getLinkbar() ?>
                                    </div>
                                </div>
								<h1><?=txt('register')?></h1>
                                <div class="container">
                                    <div class="col-6">
										<br />
										<p><?=fpage::txt('request_preregister')?></p>
										<a href="<?= form::clink('site', 'trainning','form', 'fid=register') ?>"><div id="sideregister" class="jbutton" ><?=txt('register')?> </div></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                	
                </div>
            </div>
        </div>
    </div>	 
    <div id="footer">
        <div class="indent-footer"><?= WPI::txt('copyrighttxt') ?></div>
    </div>
 </div>
 </div>
 
	<script src="<?=form::mlink('js', 'scripts/jquery.min.js') ?>" type="text/javascript"></script>
	<script src="<?=form::mlink('js', 'scripts'.SH.'wapl.js')?>" type="text/javascript"></script>
	<script src="<?=form::mlink('js','scripts/corner.js;scripts/jtabs.js;scripts/boxy.js;scripts/tipsy.js;scripts/inputhint.js;scripts/cleditor.js',
            'cache=1&jpk=0&appr=' . APPC)?>" type="text/javascript"></script>
    
    <?=WPI::getVar('jsex')?>
    
    
	<script src="<?=form::mlink('js', SKN .'js'.SH.'cufon-yui.js')?>" type="text/javascript"></script>
	<script src="<?=form::mlink('js', SKN .'js'.SH.'cufon-replace.js')?>" type="text/javascript"></script>
<!--[if lt IE 7]>
   <script type="text/javascript" src="<?=form::mlink('js', SKN .'js'.SH.'ie_png.js')?>"></script>
   <script type="text/javascript">
       //ie_png.fix('.png,.btn,.sgn,.sgng');
   </script>
<![endif]-->
<?php ob_flush();

    
    WPI::addToJS('$.getScript("'.form::mlink('js', SKN . 'functions.js','jpk=0&appr='.APPC).'",function(){'.((applications::isSecure(APPC) AND !WPI::isloggedin() )? '' : '/*showSidebar();*/').'});');
    WPI::BootJS();

 ?>
<script type="text/javascript">/* Cufon.now();*/<?=WPI::getVar('jsh')?> </script>
</body>
</html>



