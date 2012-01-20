
//loadcss('<?= form::mlink('css', SKN . 'style.css') ?>');        
    
// this for tell js isloggedin or not
var JUserLoggedin = <?= (user::jlogged() ? 'true' : 'false') ?>;

/******************************/
var gMode   = 0; // [quran, trans, search, audio]
var gRiwayaID=1; // [1=hafs, 2=warsh ..7]
var gSuraID = 2; // [1..114]
var gAyaID  = 8; // [1..6236]	
var gAyaPs  = 2; // [1..7]	
var gPageID = 2; // [1..604]	
var gJuzID  = 1; // [1..240]	
var gTfsID  = 1; // [1=muyassar, 2=jlalayn ..7]	
var gTfsType='f';// [f=tafseer, r=translation]		
var gVolume = 90;// [1..150]
var gQiraat = '<?=$_SERVER["DOCUMENT_ROOT"]?>/ajamy/';
//'http://www.everyayah.com/data/Ahmed_ibn_Ali_al-Ajamy_64kbps_QuranExplorer.Com/';//'http://www.everyayah.com/data/Abdurrahmaan_As-Sudais_64kbps/';
/******************************/

var efired,jpressed;
     
        
	loadScript("<?= form::mlink('js', 'scripts/jquery.min.js;scripts/wapl.js', 'cache=1&appr=' . APPC) ?>",function(){
		//--- JQuery loaded...		
		jProgress(30);
		jQuery.ajaxSetup({
            
		  error: function(XMLHttpRequest, textStatus, errorThrown){
            <?php if (konfig::isOn('error_js')) { ?>
			notify('warn','<div style="text-align:left;padding-left:40px;">WAPL::AjaxError :  '+textStatus+' @ '+errorThrown+" :<br /> <small>"+XMLHttpRequest.responseText+'</small></div>');
			//alert('WAPL::AjaxError :  '+textStatus+' @ '+errorThrown+'  !');
			<? } ?>
			rem_loading();

          }
			
        });
		//------
		jetScript("<?= form::mlink('js', 'scripts/forkan.js','cache=0&jpk=0&appr=' . APPC) ?>",function(){jProgress(40);});
		jetScript("<?= form::mlink('js','scripts/tools.js','cache=1&jpk=0&appr=' . APPC) ?>",function(){
			jProgress(50);
		});
		jetScript("<?= form::mlink('js','scripts/sm2.js','cache=1&jpk=0&appr=' . APPC) ?>",function(){
			jProgress(70);
			//soundManager.url = '<?= KURL . 'media/swf/' ?>';
			
			soundManager.onerror = function() {
				//notify('warn','<span style="text-align:left;padding-left:20px;"> WPL: Sound object Error !! </span>');
				// Something went wrong during init - in this example, we *assume* flashblock etc.
				soundManager.flashLoadTimeout = 0; // When restarting, wait indefinitely for flash
				soundManager.onerror = {}; // Prevent an infinite loop, in case it's not flashblock
				soundManager.reboot(); // and, go!
			};    
			jetScript("<?= form::mlink('js','scripts/sm3.js','cache=1&jpk=0&appr=' . APPC) ?>",function(){
				jProgress(90);
				soundManager.reboot();
				//<!-- special IE-only canvas fix -->
				if(($.browser.msie) && (parseInt($.browser.version) < 7)){
					jProgress(99);
					jetScript("<?= form::mlink('js', 'scripts/sm2/excanvas.js') ?>",function(){}); 
				}
			}); 
		});
	
		//***************************************************

		/*/--- js error log
		$(window).error(function(msg, url, line){
			//jQuery.post("js_error_log.php", { msg: msg, url: url, line: line });
			notify('warn','<div style="text-align:left;padding-left:40px;">WJS-Error : '+url+' @ '+line+" :<br /> <small>"+msg+'</small></div>');
		});*/
		
		
		//$('#logoText').FontEffect({outline:true, outlineColor1:"#FFCC00", gradient:true, gradientColor:"#FFFF00", gradientPosition:10, gradientLength:60, gradientSteps:10});
		
		//--- Text resizer
		//$(function() {
		$("#tsizermax")
			.click( function() {ts(1);}).next()
			.click( function() {ts(-1);});
		//});			
		
		
		
		//--- Tabs
		/*$('#container').tabs({		
			fxAutoHeight: true,
			onShow: function() {
				setCookie('wpl_ftb', $('#container').activeTab(), 0, '<?=SH.KBAS.SH?>');
			}
		});
		$('#container').triggerTab(<?=(isset($_COOKIE['wpl_ftb'])? $_COOKIE['wpl_ftb'] : 1)?>);   
		*/
        
        
		//$('#suraCBX').selectmenu({style:'dropdown',maxHeight: 150});		
		
        //--- Sura CBX
        $("#suraCBX").change(function(){
            JQ.gotoSuraPage($(this).val());
            $('#content').focus();
        });
        
        //--- Page CBX
        $("#pageCBX").change(function(){
            JQ.gotoPage($(this).val());
            $('#content').focus();
        });
        
        //--- Juz CBX
        $("#juzCBX").change(function(){
            JQ.gotoJuz($(this).val());
            $('#content').focus();
        });
        //--- Qiraat CBX
        $("#qiraatCBX").change(function(){
            JQ.setQiraat($(this).val());
            $('#content').focus();
        });
        //--- Tafseer CBX
        $("#tafseerCBX").change(function(){
            JQ.setTafseer($(this).val());
            $('#content').focus();
        });
		
		/*//var state = $('#state');
        $('.resizeDiv').resizable({
                handler: '.handler',
                min: { width: 900, height: 43 },
                max: { width: 900, height: 200 },
                
            });

		//--- history
        function load(num) {
            $('#content').load(num +".html");
        }

        $.history.init(function(url) {
                load(url == "" ? "1" : url);
            });

        $('#ajax-links a').live('click', function(e) {
                var url = $(this).attr('href');
                url = url.replace(/^.*#/, '');
                $.history.load(url);
                return false;
            });


			*/       
                

        
        //rem_loading();
		
        //$('#jPreloaderDiv').remove();
	
        <?= WPI::getVar('js') ?>
  
        // this for grap pressed key
        $('body').bind('keypress keyup',function(event) {
	       jpressed = event.which;
            //alert(jpressed);

        });
        
	    $('body').bind('mousedown keydown',function(event) {
		      efired = $(event.target);

	    });
		

		//JQ.init();
		



    });
    




//});

function jinit(){
 $().ready(function() {

	//--- set the embded html data
	$.metadata.setType("attr", "data"); 
    
    /**************************************************************************************************/
	
	//data="{sura: '$y['sura']',aya:'$y['aya']', yndx: '$y['index']'}"
	//$(".ayaText").metadata().item_id == 1; $("#one").metadata().item_label == "Label"
		

	//$('.suraName').FontEffect({outline:true, outlineColor1:"#0AE", gradient:true, gradientColor:"#00FFFF", gradientPosition:10, gradientLength:60, gradientSteps:10});
	
	
	$('.ytxt').hover(function(){
		$(this).addClass('yHover');
	},function(){
		$(this).removeClass('yHover');
	});
	
    $('.ytxt').click(function() {
		JQ.Aya_hightlighter($(this));
   	});
        /*$('.ayaText').FontEffect({
            shadow:true
        });*/
        

    /**************************************************************************************************/
   
	
        $('.tipy').tipsy({fade: false,gravity:  $.fn.tipsy.autoNS,fallback: 'WAPL v1.0 tooltip' });
        $('input[title],textarea[title]').inputHint();
        
        jQuery.each(jQuery.browser, function(i) {
        if(!$.browser.msie){

            $('.box').corner("keep");
            $('.notify').corner("keep");
            $('.formfooter').corner("bottom");	
            $('fieldset').corner("keep");
        	jvalidate();
	
        }
        else if(($.browser.msie) && (parseInt($.browser.version) < 7)){
            //ie_png.fix('.png,.btn,.sgn,.sgng');
        }
    });	
	
	//$('.scroll').scrollbar();//jScrollPane({animateTo:true, animateInterval:50, animateStep:5});
	
    //threeSixtyPlayer.init();
    //$(".wysiwyg").cleditor();


    <?= WPI::getVar('aljs') ?>
      });
};

function ts( inc ) {
    //TODO: add cookies 4 fontsize
    var olds = $('.ytxt').css('font-size');
        olds = olds.substr(0,olds.length-2);
    var news = parseInt(olds-(-1*(olds * 0.3 * inc)));
    $('.ytxt').css('font-size',news+'px');
    //alert(news);
};
