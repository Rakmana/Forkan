<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"  dir="<?= txt('dir') ?>">
<head>
<title><?= jawab::getTitle() . ' | ' . konfig::get('siteTitle') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Read, Search, and Listen to the Holy Quran Karim with tafseers and translations in various languages."  />
<meta name="keywords" content="Quran Kareem Translation Tafseer Qur'an Kuran forkan Koran Recitation Mp3 Quraan Online Application Web " />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link rel="shortcut icon" href="<?= form::mlink('wimg', 'favicon.png') ?>" type="image/png" />
<link href="<?=form::mlink('css', 'scripts/common.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?=form::mlink('css', SKN .'style.css')?>" rel="stylesheet" type="text/css" />
<link href="<?=form::mlink('css', SKN .'style.pr.css')?>" rel="stylesheet" type="text/css" media="print" />
<link href="<?=form::mlink('css', 'scripts/ui/jquery-ui.css') ?>" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]>
    <link href="<?=form::mlink('css', SKN .'style.ie.css')?>" rel="stylesheet" type="text/css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?=form::mlink('css', 'scripts/sm2.css')?>" />

<?=WPI::getVar('headex')?>
<script type="text/javascript"><?=WPI::getVar('jsh')?> </script>
<style type="text/css"><?=WPI::getVar('css')?> </style>  
<style type="text/css">

@font-face {
	font-family: 'raky';
	src: url('<?=KPUB.'fonts'.SH.'jvolt.eot'?>');
	src:local('me_quran'), 
		url('<?=KPUB.'fonts'.SH.'jvolt.ttf'?>') format('truetype');
	
	font-weight: normal;
	font-style: normal;
}


@font-face {
	font-family: 'uthmanic';
	src: url('<?=KPUB.'fonts'.SH.'uthmanicHafsv09.eot'?>');
	src: local('KFGQPC Uthman Taha Naskh'),
		 url('<?=KPUB.'fonts'.SH.'uthmanicHafsv09.otf'?>') format('opentype'),
		 url('<?=KPUB.'fonts'.SH.'uthmanicHafsv09.woff'?>') format('woff');
	
	font-weight: normal;
	font-style: normal;
}
</style>      

</head>
<?php ob_flush();?>
<body id="page2">

<div id="jPreloaderDiv" style="display:<?=(!konfig::isOn('showPreloader'))?'none':'block';?>;position:fixed;top:0;left:0;width:100%;height:100%;font:normal 10px tahoma;background:transparen;z-index:9999999;">
	<br />
	<div style="position:relative;width:250px;height:100px;margin:auto;">
	<div id="preHolder" style="display:inline-block;position:relative;border:1px solid #777;width:200px;height:7px;background:#DDD;">
		<div id="jProgress" style="position:absolute;width:10px;height:7px;background:#09E url('<?=form::mlink('img','media/jcrop.gif')?>') center center repeat-x;"></div>
	</div>
	<small style="display:inline-block;color:#333"><span id="jPercent">5</span>%</small>
	</div>
</div>

<div id="page">
	<div id="header" class="">
		<a href="<?=KURL ?>">
        <div id="logo" class="">
			<div style="padding-top:20px"><?= konfig::get('siteTitle') ?> <?= konfig::get('version') ?>:<br /> <?= konfig::get('siteSlogan') ?></div>
			
		</div></a>
	
		<!-- Top bar-->
		<div id="topbar">
			<!-- -->
		   <div id="help">
                <span id="" class=""></span> 
           </div>	
           
           <div id="searchbox">
                <input type="text" value="<?=forkan::txt('search')?>" title="<?=forkan::txt('search')?>" size="30" name="stxt" id="searchEdit" />
                <span id="searchBTN" class="btn b-loop"></span> 
           </div>
		</div>
		<!-- / Top bar-->	
	</div>	
	
    
<!-- content-->

	<div id="quranTab" class="jtab">
		
 
			
       <div id="contentdiv" class="scroll">
			<table id="pageTable" style="border-collapse: collapse;" width="100%" border="0" cellpadding="0" cellspacing="0" >
			  <tbody>
              <tr>
				<td class="jttl" nowrap="nowrap" style="position: relative;">&nbsp;</td>
				<td class="jttc" nowrap="nowrap" align="center">&nbsp;
                    <div id="suraSpan" class="stateSpan" style="<?=txt('align')?>: 40px;"> ------- </div>
                    <div id="pageSpanDiv" class="stateSpan" style="text-align: center;left:47%;left: 47%;">
                        <span id="pagePrev" class="syslink tipy" title="<?=txt('prev')?>" onclick="JQ.getPrevPage()">►</span>
                        <span id="pageSpan" class=""> --- </span>
                        <span id="pageNext" class="syslink tipy" title="<?=txt('next')?>" onclick="JQ.getNextPage()">◄</span>
                    
                    </div>
                    <div id="juzSpan" class="stateSpan" style="<?=txt('align2')?>: 40px;"> ------- </div>
                </td>
				<td class="jttr" nowrap="nowrap">&nbsp;</td>
			  </tr>
			  <tr>
				<td class="jtml" nowrap="nowrap">&nbsp;</td>
				<td class="jtmc" valign="top">
							<!-- Quran viewer iframe -->
							<p id="content" align="justify" ><?= jawab::getContent() ?></p>
							
						   <!-- /Quran viewer iframe -->
						   
						   
							
							
				</td>
				<td class="jtmr" nowrap="nowrap">&nbsp;</td>
			  </tr>
			  <tr>
				<td class="jtbl" nowrap="nowrap">&nbsp;</td>
				<td class="jtbc" nowrap="nowrap">&nbsp;</td>
				<td class="jtbr" nowrap="nowrap">&nbsp;</td>
			  </tr>
			</tbody></table>
	   </div>
			
		

	<!--</div>-->
	
	
	</div>
	


	
	<div id="bottombar" class="">
        <div id="playrDiv"><div id="jPlayr"><div class="ui360"><a id="soundlink" class="tipy" href="<?=KPUB.'audio/'?>001001.mp3" title="<?=forkan::txt('play_this')?>"></a></div></div></div>
        <div id="overAya">

    	    <div id="notification"><?= jawab::getNotification('notifications') ?></div>		
			<div id="ayabar">
		        <?=WPI::getLoadingDiv()?>
                <div id="playerbar">
                    <span id="volumeup" class="btn_<?=forkan::txt('align')?> tipy syslink"  title="<?=forkan::txt('volume_max')?>">+</span>
                    <span id="volumeSpan" class="btn_middle sgn s-warn tipy" title="<?=forkan::txt('sound_volume')?>">90</span>
                    <span id="volumedown" class="btn_<?=forkan::txt('align2')?> tipy syslink" title="<?=forkan::txt('volume_min')?>">-</span>
                </div>
                <div id="tefseerbar">
                    <span id="tfsResizerup" class="btn_<?=forkan::txt('align')?> tipy syslink"  title="<?=forkan::txt('resize_max')?>">+</span>
                    <span id="btnToggleTafseer" class="btn_middle sgn b-kom tipy syslink" title="<?=forkan::txt('show_hide_tafseer')?>">Show/Hide</span>
                    <span id="tfsResizerdown" class="btn_<?=forkan::txt('align2')?> tipy syslink" title="<?=forkan::txt('resize_min')?>">-</span>
                </div>
				<?=forkan::createSuraCBX();?>
				<?=forkan::createPageCBX();?>
				<?=forkan::createJuzCBX();?>
				<?=forkan::createQiraatCBX();?>
				<?=forkan::createTafseerCBX();?>
                
                <div id="sizer" class="">
				    <span id="tsizermax" class="btn_<?=forkan::txt('align')?> tipy syslink" title="<?=forkan::txt('fontplus')?>">A+</span>
				    <span id="tsizermin" class="btn_<?=forkan::txt('align2')?> tipy syslink" title="<?=forkan::txt('fontmoin')?>">a-</span>
			    </div>
				<div class="" style="position:absolute;left:5px;top:2px" ><a href="http://www.itkane.com" target="_blank" class="jnomlogo" style="margin-right:10px;" title="itkane.com"></a></div>
                
            </div>
			<div id="tafseer" class="resizeDiv ui-corner-top">
            
            </div>
		</div>
	
</div>

</div> 

<?php ob_flush();
	echo WPI::BootJS();
?>

<script type="text/javascript"><?php
	echo WPI::getVar('jsh');
	echo WPI::getVar('jsex');
?>
</script>

<!--[if lte IE 6]>
		<script type="text/javascript" src="<?=form::mlink('js', 'scripts'.SH.'iepngfix_tilebg.js')?>"></script>
		<style type="text/css">
			 img, div, input, a, .png, .btn, .sgn, .sgng { behavior: url("<?=KMDA.'iepngfix.htc'?>") }
		</style>

<![endif]-->


</body>
</html>



