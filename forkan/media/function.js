/**
 * 
 * @package  WAPL
 * @link http://www.itkane.com/
 * @author     Khedrane Atallah <jnom23@gmail.com>
 * @copyright  (C) 2003 - 2011 Khedrane Atallah
 * @license    http://gnu.org/copyleft/gpl.html GNU GPL
 * @version 1.0

*/

var JQ = {};

JQ = {
    UChars:{
        HAMZA:'\u0621',
        ALEF_WITH_MADDA_ABOVE:'\u0622',
        ALEF_WITH_HAMZA_ABOVE:'\u0623',
        WAW_WITH_HAMZA_ABOVE:'\u0624',
        ALEF_WITH_HAMZA_BELOW:'\u0625',
        YEH_WITH_HAMZA:'\u0626',
        ALEF:'\u0627',BEH:'\u0628',MARBUTA:'\u0629',TEH:'\u062A',THEH:'\u062B',
        JEMM:'\u062C',HAH:'\u062D',KHAH:'\u062E',DAL:'\u062F',THAL:'\u0630',
        REH:'\u0631',ZAIN:'\u0632',SEEN:'\u0633',SHEEN:'\u0634',SAD:'\u0635',DAD:'\u0636',
        TAH:'\u0637',ZAH:'\u0638',AIN:'\u0639',GHAIN:'\u063A',TATWEEL:'\u0640',
        FEH:'\u0641',QAF:'\u0642',KAF:'\u0643',LAM:'\u0644',MEEM:'\u0645',NOON:'\u0646',
        HEH:'\u0647',WAW:'\u0648',ALEF_MAKSURA:'\u0649',YEH:'\u064A',FATHATAN:'\u064B',
        DAMMATAN:'\u064C',KASRATAN:'\u064D',FATHA:'\u064E',DAMMA:'\u064F',KASRA:'\u0650',
        SHADDA:'\u0651',SUKUN:'\u0652',MADDA:'\u0653',HAMZA_ABOVE:'\u0654',
        HAMZA_BELOW:'\u0655',SMALL_ALEF:'\u065F',SUPERSCRIPT_ALEF:'\u0670',
        ALEF_WASLA:'\u0671',HIGH_SALA:'\u06D6',HIGH_GHALA:'\u06D7',
        HIGH_MEEM_INITIAL_FORM:'\u06D8',HIGH_LA:'\u06D9',HIGH_JEMM:'\u06DA',
        HIGH_THREE_DOT:'\u06DB',HIGH_SEEN:'\u06DC',RUB_EL_HIZB:'\u06DE',
        HIGH_ROUNDED_ZERO:'\u06DF',HIGH_UPRIGHT_ZERO:'\u06E0',HIGH_DOTLESS_KHAH:'\u06E1',
        HIGH_MEEM:'\u06E2',LOW_SEEN:'\u06E3',SMALL_WAW:'\u06E5',SMALL_YEH:'\u06E6',
        HIGH_YEH:'\u06E7',HIGH_NOON:'\u06E8',SAJDAH:'\u06E9',LOW_STOP:'\u06EA',
        HIGH_STOP:'\u06EB',HIGH_STOP_FILLED:'\u06EC',LOW_MEEM:'\u06ED',
        HAMZA_ABOVE_ALEF:'\u0675',DOTLESS_BEH:'\u066E',ZWNJ:'\u200C',NBSP:'\u00A0',
        NNBSP:'\u202F',FARSI_YEH:'\u06CC',FARSI_KEHEH:'\u06A9',HEH_DOACHASHMEE:'\u06BE',
        SWASH_KAF:'\u06AA',YEH_BARREE:'\u06D2'},
    UGroups:{
        LETTER:"[$HAMZA-$YEH]",HARAKA:"[$FATHATAN-$MADDA$SUPERSCRIPT_ALEF]",
        HARAKAT:"[$FATHATAN-$MADDA$SUPERSCRIPT_ALEF$TATWEEL]",
        SPACE:"[\\s$HIGH_SALA-$LOW_MEEM]*\\s",HAMZA_SHAPE:"[$HAMZA_ABOVE$HAMZA$ALEF_WITH_HAMZA_ABOVE-$YEH_WITH_HAMZA]",
        LETTER_HARAKA:"[$HAMZA-$ALEF_WASLA]"},
    
    TextTools:{
        matchingRules:[["$HAMZA_SHAPE","$HAMZA_SHAPE"],["$ALEF_MAKSURA","YY"],["$ALEF","[$ALEF$ALEF_MAKSURA$ALEF_WITH_MADDA_ABOVE$ALEF_WITH_HAMZA_ABOVE$ALEF_WITH_HAMZA_BELOW$ALEF_WASLA]"],["[$TEH$MARBUTA]","[$TEH$MARBUTA]"],["$HEH","[$HEH$MARBUTA]"],["$WAW","[$WAW$WAW_WITH_HAMZA_ABOVE$SMALL_WAW]"],["$YEH","[$YEH$ALEF_MAKSURA$YEH_WITH_HAMZA$SMALL_YEH]"],["YY","[$ALEF_MAKSURA$YEH$ALEF]"],[" ","$SPACE"]],
        wildcardRegs:[["\\.","P"],["\\*","S"],["[?؟]","Q"],["S+","S"]],
        wildcards:[["S","$LETTER_HARAKA*"],["Q","$LETTER"],["P","$LETTER"]],
        preProcess:[["[$FARSI_YEH$YEH_BARREE]","$YEH"],["[$FARSI_KEHEH$SWASH_KAF]","$KAF"],["$HEH_DOACHASHMEE","$HEH"],["$NOON$SUKUN","$NOON"],["([$KASRA$KASRATAN])($SHADDA)","$2$1"]],
        init:function(){for(var i in UGroups)UGroups[i]=this.regTrans(UGroups[i]);},
        fixText:function(text,args){args=args||{};if(args.showSigns){text=this.pregReplace(' ([$HIGH_SALA-$HIGH_SEEN])','<span class="sign">&nbsp;$1</span>',text);text=this.pregReplace('($SAJDAH)',args.ignoreInternalSigns?'':'<span class="internal-sign">$1</span>',text);text=this.pregReplace('$RUB_EL_HIZB',args.ignoreInternalSigns?'':'<span class="icon juz-sign"></span>',text);}
else
text=this.pregReplace('[$HIGH_SALA-$RUB_EL_HIZB$SAJDAH]','',text);if(isFF4)
text=this.pregReplace('($REH$HARAKA*$END)','$1$ZWNJ',text);if(!args.showSmallAlef)
text=this.pregReplace('$SUPERSCRIPT_ALEF','',text);if(args.font=='me_quran'){text=this.pregReplace('([$HAMZA$DAL-$ZAIN$WAW][$SHADDA$FATHA]*)($SUPERSCRIPT_ALEF)','$1$ZWNJ$2',text);text=this.pregReplace('($LAM$HARAKA*)$TATWEEL$HAMZA_ABOVE($HARAKA*$ALEF)','$1$HAMZA$2',text);}
else{text=this.pregReplace('($SHADDA)([$KASRA$KASRATAN])','$2$1',text);text=this.pregReplace('($LAM$HARAKA*$LAM$HARAKA*)($HEH)','$1$TATWEEL$2',text);}
text=this.removeExtraMeems(text);text=this.pregReplace('$ALEF$MADDA','$ALEF_WITH_MADDA_ABOVE',text);return text;},fixTransText:function(text,args){text=text.replace(/\]\]/g,'$').replace(/ *\[\[[^$]*\$/g,'');return text;},removeExtraMeems:function(text){text=this.pregReplace('([$FATHATAN$DAMMATAN])$LOW_MEEM','$1',text);text=this.pregReplace('($KASRATAN)$HIGH_MEEM','$1',text);return text;},highlight:function(pattern,str){pattern=new RegExp('('+pattern+')','g');str=str.replace(pattern,'◄$1►');str=str.replace(/◄\s/g,' ◄').replace(/\s►/g,'► ');str=str.replace(/([^\s]*)◄/g,'◄$1').replace(/►([^\s]*)/g,'$1►');while(/◄[^\s]*◄/.test(str))
str=str.replace(/(◄[^\s]*)◄/g,'$1').replace(/►([^\s]*►)/g,'$1');str=str.replace(/◄/g,'<span class="highlight">').replace(/►/g,'</span>');return str;},enrichPattern:function(pattern,ignoreHaraka){if(ignoreHaraka)
pattern=this.pregReplace("$HARAKA",'',pattern);pattern=this.pregReplace('$TATWEEL','',pattern);pattern=pattern.replace(/\-/g,'!');pattern=this.regTrans(pattern);pattern=this.handleSpaces(pattern);pattern=this.applyRules(this.preProcess,pattern);pattern=this.applyRules(this.wildcardRegs,pattern);pattern=this.pregReplace("(.)","$1$HARAKAT*",pattern);pattern=this.applyRules(this.matchingRules,pattern);pattern=this.applyRules(this.wildcards,pattern);return pattern;},handleSpaces:function(pattern){var prev='';if(pattern=='')return pattern;pattern=pattern.replace(/\s+/g,' ');while(pattern!=prev){prev=pattern;pattern=pattern.replace(/^(([^"]*"[^"]*")*)([^"\s]*) /g,'$1$3+');}
pattern=pattern.replace(/_/g,' ');pattern=pattern.replace(/"/g,' ');pattern=pattern.replace(/^[+|]+/g,'').replace(/[+|!]+$/g,'');pattern=pattern.replace(/\+*([+|!])\+*/g,'$1');return pattern;},isValidReg:function(pattern){try{new RegExp(pattern,'g');}
catch(e){return false;}
return true;},regTrans:function(str){return str.replace(/\$([A-Z_]+)/g,function(s,i,ofs,all){return UGroups[i]||UChars[i]||'';});},
    pregReplace:function(fromExp,toExp,str){fromExp=new RegExp(this.regTrans(fromExp),'g');toExp=this.regTrans(toExp);return str.replace(fromExp,toExp);},applyRules:function(rules,str){for(var i in rules)
str=this.pregReplace(rules[i][0],rules[i][1],str);return str;},end:0},    
	config : {
		autoloadTafseer   : true,           // Autoload tafseer text on Aya click [true , false]
		TafseerIsShown    : true,           // Show tafseer text on Aya, if hidden dont load text
        ayaHTClass        : '.ytxt',    // Aya Text Html classname  
        ayaHTID           : '#y',          // Aya Text Html identity  
		v           : '1.0'
	},
	init : function(){
	    $().ready(function() {
       	
           
        
        //--- set the embded html data
        $.metadata.setType("attr", "data");    
           	
		
        
        
		$('#searchBTN').click(function(){
			JQ.searchPage($('#searchEdit').val());
		});
        
		//--- 
        $('#btnToggleTafseer').click(function(){
            
            if(JQ.config.TafseerIsShown != false){
                JQ.config.TafseerIsShown = false;
                $('#overAya').animate({height:'29px'},700);
            }else{
                JQ.config.TafseerIsShown = true;
                $('#overAya').animate({height:'80px'},700);
                
            }
        });
        function tfsResizer(inc){
            var nh,oyh = $('#overAya').css('height');
                oyh = oyh.substr(0,oyh.length-2);
                nh = parseInt(oyh - (inc * 40 * (-1)));
            if(oyh>=400 && inc == 1){return false;}
            if(oyh<=100 && inc == -1){return false;}
            
			$('#overAya').animate({height:nh+'px'},500);
            $('#tafseer').animate({height:(nh-27)+'px'},500);
            
        };
                 
        $('#tfsResizerup').click(function(){
            tfsResizer(1);
        });      
        $('#tfsResizerdown').click(function(){
            tfsResizer(-1);
        }); 
		
        $('#volumeup').click(function(){
            JQ.setVolume(parseInt(gVolume+5));
        });      
        $('#volumedown').click(function(){
            JQ.setVolume(parseInt(gVolume-5));
        });

		
		
		//--- hotkeys
        jQuery(document).bind('keydown', 'pageup',function (evt){evt.preventDefault();JQ.getPrevPage();});
        jQuery(document).bind('keydown', 'pagedown',function (evt){evt.preventDefault();JQ.getNextPage();});
		
        jQuery(document).bind('keydown', 'down',function (evt){evt.preventDefault();JQ.gotoNextAya();});
        jQuery(document).bind('keydown', 'left',function (evt){evt.preventDefault();JQ.getNextPage(); });
        
        jQuery(document).bind('keydown', 'up',function (evt){evt.preventDefault();JQ.gotoPrevAya(); });
        jQuery(document).bind('keydown', 'right',function (evt){evt.preventDefault();JQ.getPrevPage(); });
        jQuery(document).bind('keydown', 'space',function (evt){
            evt.preventDefault();
			
			threeSixtyPlayer.config.playNext = true;
			threeSixtyPlayer.config.iniVolume = gVolume;
			
			threeSixtyPlayer.handleClick({target:je('soundlink'),button:1});     

        });
        /*
        jQuery(document).bind('keydown', 'esc',function (evt){jQuery('#_esc').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'tab',function (evt){jQuery('#_tab').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'space',function (evt){jQuery('#_space').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'return',function (evt){jQuery('#_return').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'backspace',function (evt){jQuery('#_backspace').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'scroll',function (evt){jQuery('#_scroll').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'capslock',function (evt){jQuery('#_capslock').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'numlock',function (evt){jQuery('#_numlock').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'pause',function (evt){jQuery('#_pause').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'insert',function (evt){jQuery('#_insert').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'home',function (evt){jQuery('#_home').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'del',function (evt){jQuery('#_del').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'end',function (evt){jQuery('#_end').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'pageup',function (evt){jQuery('#_pageup').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'pagedown',function (evt){jQuery('#_pagedown').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'left',function (evt){jQuery('#_left').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'up',function (evt){jQuery('#_up').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'right',function (evt){jQuery('#_right').addClass('dirty'); return false; });
        jQuery(document).bind('keydown', 'down',function (evt){jQuery('#_down').addClass('dirty'); return false; });
		
		jQuery(document).bind('keydown', 'f1',function (evt){jQuery('#_f1').addClass('dirty'); return false; });
*/
		//jinit();
		
	
	/**************************************************************************************************/       

        JQ.setQiraat($('#qiraatCBX').val());  
        JQ.setTafseer($('#tafseerCBX').val());
        JQ.setVolume(gVolume);
		
	    var startp = ($.cookie('WFPID') != null )? $.cookie('WFPID') : 50 ;
		var jhash = window.location.hash;
      
        jhash = jhash.substr(1);
        /*if(jhash.split(':').length > 1){
           var jps = jhash.split(':')[0];
           var jpy = jhash.split(':')[1]; 
           
           JQ.gotoAyaPage(jps,jpy)  ;
        }
        else{*/
            JQ.gotoPage(startp);
        //}
	})
    },
    setVolume : function(val){
		if(val<0 || val > 150){return false};
		
		gVolume = val;
		threeSixtyPlayer.config.iniVolume = gVolume;
		
		if(threeSixtyPlayer.lastSound !=null){
			threeSixtyPlayer.lastSound.setVolume(gVolume);
		};
		
		$('#volumeSpan').empty().append(' '+gVolume+'%');
		
	/*	
		var jSound = threeSixtyPlayer.getSoundByURL($('#jPlayr').attr('href'));
          //var o = threeSixtyPlayer.getTheDamnLink(e);
          
          if(jSound){
            // already exists
            if (jSound === threeSixtyPlayer.lastSound) {
                // and was playing (or paused)
                jSound.togglePause();
            } else {
                // different sound
                jSound.togglePause(); // start playing current
                threeSixtyPlayer.stopSound(threeSixtyPlayer.lastSound);
            }
          }
          else{
            notify('info','Start Playing with Mouse click, then you can use "Space" hotkey !!  ');
          }  
	*/
			
	},
    arabicNumberName : function(num){
        var unary=Array('الاول','الثاني','الثالث','الرابع','الخامس','السادس','السابع','الثامن','التاسع','العاشر');
        var decimal=Array('عشر','العشرون','الثلاثون','الأربعون','الخمسون','الستون','السبعون','الثمانون','التسعون');
        var handred=Array('مائة','مائتان','ثلاتمائة','أربعمائة','خمسمائة','ستمائة','سبعمائة','ثمانمائة','تسعمائة');
        
        if(num<1||num>=240)return'';
        if(num<=10)return unary[num-1];
        var digit=(num>=100)? (num%100)%10 : num%10;
        var handr=num%100 ;
        var dec=(num>=100)? parseInt((num%100)/10) : parseInt(num/10);
        var hnd=parseInt(num/100);
        unary[0]='الحادي';
        var res=(digit>0?unary[digit-1]+(dec>0?' و':' '):'')
                +(dec>0?decimal[dec-1] : '')+(hnd>0 && handr>0?' و':'')+(hnd>0? handred[hnd-1]: '');
        return res;
    },
	
	//---- get aya id from html    
	getAyaID : function (hid){
		return hid.substr(1);
	},
	
	//---- Make ayaMp3 File name: Qiraat / sura[3]+aya[3].mp3
	getAyaFName : function (raya,sura)
	{
		var folder = gQiraat;
		var surastring;
		var ayastring;
		if(sura<10){surastring="00"+sura;}else if(sura<100){surastring="0"+sura;}else{surastring=sura;}
		if(raya<10){ayastring="00"+raya;}else if(raya<100){ayastring="0"+raya;}else{ayastring=raya;}
		//alert("sura"+surastring+"aya"+ayastring);
		return folder+surastring+""+ayastring+".mp3";
	},
    
    getAyaIndex: function(sid,yid){
        var ndx ,start , count; 
        // [start, ayas, order, rukus, name, tname, ename, type]
    
                
                start = QuranData.Sura[sid][0];
                count = QuranData.Sura[sid][1];
                
                if(yid <= count){
                    ndx = start+yid;
                }
                else return false;
        
        
        return ndx; 
    },

	Aya_hightlighter : function (obj){
			
        gAyaID  = obj.metadata().yndx;     //gAyaID  = JQ.getAyaID($(this).attr('id'));
        gAyaPs  = obj.metadata().aya; 
		gSuraID = obj.metadata().sura;//		gSuraID = $(this).attr('title');
		gJuzID  = JQ.getAyaJuz(gSuraID,gAyaPs);//obj.metadata().juz;
        
        JQ.updateSuraVal(gSuraID);
        JQ.updateJuzVal(gJuzID);
		
        if(JQ.config.autoloadTafseer != false) {
            JQ.getTafseer(gSuraID,gAyaPs,gTfsID,gTfsType);
		}
        //--- update player mp3 link			
		JQ.updatePlayer(gSuraID,gAyaPs);
		
			
		
		$('.ytxt').removeClass('ytxt_c');//css({'background-color' : 'transparent','color':'#0A3'});
		//$('.ayaText').removeFE(true);
		
		
		$('.yctr').removeClass('yctr_c');
		obj.addClass('ytxt_c');
		//$(this).FontEffect({shadow:true, shadowColor:"#ccf", shadowOffsetTop:4, shadowOffsetLeft:4, shadowBlur:2, shadowOpacity:0.05});
		
		$('#yctr-'+gAyaID).addClass('yctr_c');
		
		window.location.hash = gSuraID+':'+gAyaPs;
        
		//$('.ui360').remove();
		//$('#counterAya-'+gAyaID).prepend('<div id="jPlayr" class="ui360" style="display:inline-block;z-index:1010"><a href="'+getAyaFName($(this).attr('rel'),gSuraID)+'" id="jPlayr"></a></div>');
        //threeSixtyPlayer.init();
	},	
	getAyaJuz : function (sura,aya)	{

        var i, sjuz , leng = (QuranData.Juz.length-1); 
       // [sura, aya] 
    
        for(i=1; i<leng; i++ ){
           if(QuranData.Juz[i][0] == sura) {
                // if begin from page head or not
                if(QuranData.Juz[i][1] > aya){sjuz = i-1;break;}
                else if((QuranData.Juz[i+1][0] == sura) && (QuranData.Juz[i+1][1] < aya)){continue;}
                else{sjuz = i;break;};
                 
           }
           if((QuranData.Juz[i][0] < sura) && (sura < QuranData.Juz[i+1][0])){
                sjuz = i; 
                break; 
           }
        };
        
        return sjuz; 
			
	},	
    /**************************** Player functions ***************************/
    
	updatePlayer : function (sura,aya)	{
	   $('#jPlayr').attr({'href':JQ.getAyaFName(aya,sura)});
		$('.sm2_link').attr({'href':JQ.getAyaFName(aya,sura)});
			
	},
	
    /**************************** Tafseer functions ***************************/
    
	Tloading : 0,
	
	getTafseer : function ($suraID ,$ayaID,$tafseerID,$tafseerType){
		
        if( !$suraID){$suraID = gSuraID};
		if( !$ayaID){$ayaID = gAyaPs};
		if( !$tafseerID){$tafseerID = gTfsID};
		if( !$tafseerType){$tafseerType = gTfsType};
        
        
		if(JQ.Tloading == $ayaID ){ return false;  }
		
		
        jParam = {
			'typ' : $tafseerType,
			'yid' : $ayaID,
			'sid' : $suraID,
			'tid' : $tafseerID 
		}
		
        //--- if already getit
		if($('#tafseer').children().is('#'+$tafseerType+'-'+$tafseerID+'-'+gAyaID)){
			return false;
		}
		else{
			
			JQ.Tloading = $ayaID;  
			jLoad('<?=form::clink("kjax", "forkan", "getTafseerHTML")?>',$('#tafseer'),jParam,"GET",function(){
			 JQ.Tloading = 0;  
			});
		}
	},
	
    /**************************** Page functions ***************************/
	
    
	//--- update suraCBX
    updateSuraVal: function($sid){
	   gSuraID = $sid;
       $('#suraCBX').val(gSuraID);
       $('#suraSpan').text('<?=forkan::txt('sura')?> '+ QuranData.Sura[$sid][4]);
       jTitle('<?=forkan::txt('sura')?> '+ QuranData.Sura[$sid][4]);
	},
    
	//--- update juzCBX
    updateJuzVal: function($jid){
	   gJuzID = $jid;
       $('#juzCBX').val(gJuzID);
       $('#juzSpan').text('<?=forkan::txt('juz')?> '+ JQ.arabicNumberName($jid));
	},
    
	//--- update PageCBX
    updatePageVal: function($pid){
	   gPageID = $pid;
	   $('#pageCBX').val(gPageID);
       $('#pageSpan').text($pid);
	},
	//--- update QiraatCBX
    updateQiraatVal: function($qid){
	   gQiraatID = $qid;
	   $('#qiraatCBX').val(gQiraatID);
	},
    
    setQiraat : function($qid){
       //threeSixtyPlayer.refresh(); 
	   gQiraat = $qid;
       //var jSound = threeSixtyPlayer.getSoundByURL($('#jPlayr').attr('href'));
       // if sound played so stop it
       /*if(threeSixtyPlayer._360data.className == threeSixtyPlayer.css.sPlaying){
            threeSixtyPlayer.stopSound(threeSixtyPlayer.lastSound);
       } */
       JQ.updatePlayer(gSuraID,gAyaPs);
       
    },
    
    setTafseer : function($tid){ 
	   var $c = $tid.split('.',2);
       
       gTfsID   = $c[1];
       gTfsType = $c[0];
       
       if($('#tafseer').children().is('#'+gTfsType+'-'+gTfsID+'-'+gAyaID) || 
          ($('#tafseer').children().text() == '')){
			return false;
	   }
       else{
            JQ.getTafseer();
       }
       
    },    
    
	searchPage : function ($txt,$page){
		
		jParam = {
			'stxt' : $txt,
            'rid' : gRiwayaID ,
            'pid' : $page 
		}
        
        jLoad('<?=form::clink("kjax", "forkan", "search")?>',$('#content'),jParam,"GET",function(){
			$('#content').css({width:'auto',height:'auto',margin:'auto'});
		});
	},
        
	getPage : function ($pageID,callback,refresh){
		
		jParam = {
			'pid' : $pageID,
            'rid' : gRiwayaID 
		}
	
		// if the same page do callback and dont load it again
        if((gPageID == $pageID) && !refresh){
            if(callback){callback();}
            return ;
		};
        
        jLoad('<?=form::clink("kjax", "forkan", "getPageHTML")?>',$('#content'),jParam,"GET",function(){
			
            gAyaID  = $(JQ.config.ayaHTClass).first().metadata().yndx;  
            gAyaPs  = $(JQ.config.ayaHTClass).first().metadata().aya;  
		    gSuraID = $(JQ.config.ayaHTClass).first().metadata().sura;
		    gJuzID  = $(JQ.config.ayaHTClass).first().metadata().juz;
                      
            gPageID = $pageID;

            if(gPageID < 3){
                $('#content').css({width:'550px',height:'300px',margin:'auto'});
            }
            else{
                $('#content').css({width:'auto',height:'auto',margin:'auto'});
            }            

            JQ.updateSuraVal(gSuraID);
            JQ.updatePageVal($pageID);
            JQ.updateJuzVal(gJuzID);
            
            $.cookie('WFPID',$pageID);
			window.location.hash = gSuraID+':'+gAyaPs;  
			
            //$('.yctr').html($(this).text().replace(')','\uFD3E'));
            //JQ.gotoAya(gAyaID);
            JQ.Aya_hightlighter($(JQ.config.ayaHTClass).first());
			
            
           
            if( callback ){	callback();}
            
            

			
		});
	},
	
	//--- Go To Defined Page
	gotoPage : function (pid,callback){
	
		if((pid < 1) || (pid > 604)){
			notify('warn','Page index between 1 - 604 : '+pid+' !! ');
			pid = 50;
		};
		
			JQ.getPage(pid,callback);
			return true;
	},
    
    //--- Go To Sura Page
    gotoSuraPage: function(sid){
        var i, spage , leng = (QuranData.Page.length-1); 
        
	// [sura, aya] 
    
        for(i=1; i<QuranData.Page.length; i++ ){
           if(QuranData.Page[i][0] == sid) {
                // if begin from page head or not
                if(QuranData.Page[i][1] == 1){spage = i;}
                else{spage = i-1;}
                
                break; 
           }
           if((QuranData.Page[i][0] < sid) && (sid < QuranData.Page[i+1][0])){
                spage = i; 
                break; 
           }
        };
        JQ.gotoPage(spage,function(){JQ.gotoAya(QuranData.Sura[sid][0]+1);});
    },
    
    //--- Go To Sura Page
    gotoAyaPage: function(sid,yid){
        var i, spage , leng = (QuranData.Page.length); 
        
	// [sura, aya] 
    
        for(i=1; i<leng; i++ ){
           if(QuranData.Page[i][0] == sid) {
                // if begin from page head or not
                if(QuranData.Page[i][1] > yid){
                    spage = i-1;break;
                }
                else if(QuranData.Page[i+1][1] > yid){
                    spage = i;break;
                }
                 
           }
        };
        JQ.gotoPage(spage/*,function(){
            //JQ.gotoAya(JQ.getAyaIndex(sid,yid));
			JQ.Aya_hightlighter($(JQ.config.ayaHTID+JQ.getAyaIndex(sid,yid)));
        }*/);
    },
	
	//--- Go To next Page
	getNextPage : function (callback){
	var $pid = (gPageID < 604)? parseInt(gPageID)-(1-2) : 604; // this for fix str to int bug
		JQ.getPage($pid,callback);
	},
	
	//--- Go To Prev Page
	getPrevPage : function (callback){
	var $pid = (gPageID > 1)? parseInt(gPageID)-1 : 1;
		JQ.getPage($pid,callback);
	},
	
    /**************************** Juz functions ***************************/
  
    
    //--- Go To Juz Page
    gotoJuz: function(sid){
        var i, jpage ,
            jsura = QuranData.Juz[sid][0],
            jaya  = QuranData.Juz[sid][1];
        
        for(i=1; i<QuranData.Page.length; i++ ){
           if(QuranData.Page[i][0] == jsura) {
                // if begin from page head or not
                if(QuranData.Page[i][1] == jaya){
                    jpage = i;
                    break;
                } 
                //--- if aya between sura page
                else if(QuranData.Page[i+1][0] == jsura){
                    if(QuranData.Page[i+1][1] > jaya){
                        jpage = i;
                        break;
                    }    
                }
                //--- if aya in last sura page
                else if(QuranData.Page[i+1][0] != jsura){
                        jpage = i;
                        break;
                       
                }
           };
        };
        JQ.gotoPage(jpage);
    },
	
	//--- Go To next Juz
	getNextJuz : function (){
	var $pid = (gJuzID < 30)? parseInt(gJuzID)-(1-2) : 30; // this for fix str to int bug
		JQ.getJuz($pid);
	},
	
	//--- Go To Prev Juz
	getPrevJuz : function (){
	var $pid = (gJuzID > 1)? parseInt(gJuzID)-1 : 1;
		JQ.getJuz($pid);
	},
	
    
    
    
    
    /**************************** Aya functions ***************************/
    
	//--- Goto Defined Aya
	gotoAya: function (yid,callback){
	   var fst = $(JQ.config.ayaHTClass).first().metadata().yndx;
	   var lst = $(JQ.config.ayaHTClass).last().metadata().yndx;
       
       // if not exist & minix goto 1st
	   if(fst > yid){//$(this.config.ayaHTID+yid).is(this.config.ayaHTClass)
	       //JQ.Aya_hightlighter($(JQ.config.ayaHTClass).first());
           JQ.getPrevPage(callback);
       }
       // if not exist & maxix goto last
       else if(yid > lst){
	       //JQ.Aya_hightlighter($(JQ.config.ayaHTClass).last());
           JQ.getNextPage(callback);
       }
       // if exist so hightlight
       else{
	       JQ.Aya_hightlighter($(JQ.config.ayaHTID+yid));
		   if(callback){callback();}
       };
		
	},    
	//--- Goto Defined Aya Pos
	gotoAyaPs: function (sid,yps){
	   var fst = $(JQ.config.ayaHTClass).first().metadata().yndx;
	   var lst = $(JQ.config.ayaHTClass).last().metadata().yndx;
       
       // if not exist & minix goto 1st
	   if(fst > yid){//$(this.config.ayaHTID+yid).is(this.config.ayaHTClass)
	       //JQ.Aya_hightlighter($(JQ.config.ayaHTClass).first());
           JQ.getPrevPage();
       }
       // if not exist & maxix goto last
       else if(yid > lst){
	       //JQ.Aya_hightlighter($(JQ.config.ayaHTClass).last());
           JQ.getNextPage();
       }
       // if exist so hightlight
       else{
	       JQ.Aya_hightlighter($(JQ.config.ayaHTID+yid));
       };
		
	},
	
	//--- Goto next Aya
	gotoNextAya: function (callback){
	   var yid = gAyaID -(1-2);// this for fix str to int bug
	   JQ.gotoAya(yid,callback);
		
	},
	//--- Goto Prev Aya
	gotoPrevAya: function (callback){
	   var yid = gAyaID -1;// this for fix str to int bug
	   JQ.gotoAya(yid,callback);
		
	}

};

	
/*****************************[ Quran Data]*********************************/


// Quran Metadata (ver 1.0) 
// Copyright (C) 2008-2009 Tanzil.info
// License: Creative Commons Attribution 3.0


var QuranData = {};

//------------------ Sura Data ---------------------

QuranData.Sura = [
	// [start, ayas, order, rukus, name, tname, ename, type]
    <?=forkan::metaSuraXml2Js();?>
/*	[],
	[0, 7, 5, 1, 'الفاتحة', "Al-Faatiha", 'The Opening', 'Meccan'],
	[7, 286, 87, 40, 'البقرة', "Al-Baqara", 'The Cow', 'Medinan'],
	[293, 200, 89, 20, 'آل عمران', "Aal-i-Imraan", 'The Family of Imraan', 'Medinan'],
	[493, 176, 92, 24, 'النساء', "An-Nisaa", 'The Women', 'Medinan'],
	[669, 120, 112, 16, 'المائدة', "Al-Maaida", 'The Table', 'Medinan'],
	[789, 165, 55, 20, 'الأنعام', "Al-An'aam", 'The Cattle', 'Meccan'],
	[954, 206, 39, 24, 'الأعراف', "Al-A'raaf", 'The Heights', 'Meccan'],
	[1160, 75, 88, 10, 'الأنفال', "Al-Anfaal", 'The Spoils of War', 'Medinan'],
	[1235, 129, 113, 16, 'التوبة', "At-Tawba", 'The Repentance', 'Medinan'],
	[1364, 109, 51, 11, 'يونس', "Yunus", 'Jonas', 'Meccan'],
	[1473, 123, 52, 10, 'هود', "Hud", 'Hud', 'Meccan'],
	[1596, 111, 53, 12, 'يوسف', "Yusuf", 'Joseph', 'Meccan'],
	[1707, 43, 96, 6, 'الرعد', "Ar-Ra'd", 'The Thunder', 'Medinan'],
	[1750, 52, 72, 7, 'ابراهيم', "Ibrahim", 'Abraham', 'Meccan'],
	[1802, 99, 54, 6, 'الحجر', "Al-Hijr", 'The Rock', 'Meccan'],
	[1901, 128, 70, 16, 'النحل', "An-Nahl", 'The Bee', 'Meccan'],
	[2029, 111, 50, 12, 'الإسراء', "Al-Israa", 'The Night Journey', 'Meccan'],
	[2140, 110, 69, 12, 'الكهف', "Al-Kahf", 'The Cave', 'Meccan'],
	[2250, 98, 44, 6, 'مريم', "Maryam", 'Mary', 'Meccan'],
	[2348, 135, 45, 8, 'طه', "Taa-Haa", 'Taa-Haa', 'Meccan'],
	[2483, 112, 73, 7, 'الأنبياء', "Al-Anbiyaa", 'The Prophets', 'Meccan'],
	[2595, 78, 103, 10, 'الحج', "Al-Hajj", 'The Pilgrimage', 'Medinan'],
	[2673, 118, 74, 6, 'المؤمنون', "Al-Muminoon", 'The Believers', 'Meccan'],
	[2791, 64, 102, 9, 'النور', "An-Noor", 'The Light', 'Medinan'],
	[2855, 77, 42, 6, 'الفرقان', "Al-Furqaan", 'The Criterion', 'Meccan'],
	[2932, 227, 47, 11, 'الشعراء', "Ash-Shu'araa", 'The Poets', 'Meccan'],
	[3159, 93, 48, 7, 'النمل', "An-Naml", 'The Ant', 'Meccan'],
	[3252, 88, 49, 8, 'القصص', "Al-Qasas", 'The Stories', 'Meccan'],
	[3340, 69, 85, 7, 'العنكبوت', "Al-Ankaboot", 'The Spider', 'Meccan'],
	[3409, 60, 84, 6, 'الروم', "Ar-Room", 'The Romans', 'Meccan'],
	[3469, 34, 57, 3, 'لقمان', "Luqman", 'Luqman', 'Meccan'],
	[3503, 30, 75, 3, 'السجدة', "As-Sajda", 'The Prostration', 'Meccan'],
	[3533, 73, 90, 9, 'الأحزاب', "Al-Ahzaab", 'The Clans', 'Medinan'],
	[3606, 54, 58, 6, 'سبإ', "Saba", 'Sheba', 'Meccan'],
	[3660, 45, 43, 5, 'فاطر', "Faatir", 'The Originator', 'Meccan'],
	[3705, 83, 41, 5, 'يس', "Yaseen", 'Yaseen', 'Meccan'],
	[3788, 182, 56, 5, 'الصافات', "As-Saaffaat", 'Those drawn up in Ranks', 'Meccan'],
	[3970, 88, 38, 5, 'ص', "Saad", 'The letter Saad', 'Meccan'],
	[4058, 75, 59, 8, 'الزمر', "Az-Zumar", 'The Groups', 'Meccan'],
	[4133, 85, 60, 9, 'غافر', "Al-Ghaafir", 'The Forgiver', 'Meccan'],
	[4218, 54, 61, 6, 'فصلت', "Fussilat", 'Explained in detail', 'Meccan'],
	[4272, 53, 62, 5, 'الشورى', "Ash-Shura", 'Consultation', 'Meccan'],
	[4325, 89, 63, 7, 'الزخرف', "Az-Zukhruf", 'Ornaments of gold', 'Meccan'],
	[4414, 59, 64, 3, 'الدخان', "Ad-Dukhaan", 'The Smoke', 'Meccan'],
	[4473, 37, 65, 4, 'الجاثية', "Al-Jaathiya", 'Crouching', 'Meccan'],
	[4510, 35, 66, 4, 'الأحقاف', "Al-Ahqaf", 'The Dunes', 'Meccan'],
	[4545, 38, 95, 4, 'محمد', "Muhammad", 'Muhammad', 'Medinan'],
	[4583, 29, 111, 4, 'الفتح', "Al-Fath", 'The Victory', 'Medinan'],
	[4612, 18, 106, 2, 'الحجرات', "Al-Hujuraat", 'The Inner Apartments', 'Medinan'],
	[4630, 45, 34, 3, 'ق', "Qaaf", 'The letter Qaaf', 'Meccan'],
	[4675, 60, 67, 3, 'الذاريات', "Adh-Dhaariyat", 'The Winnowing Winds', 'Meccan'],
	[4735, 49, 76, 2, 'الطور', "At-Tur", 'The Mount', 'Meccan'],
	[4784, 62, 23, 3, 'النجم', "An-Najm", 'The Star', 'Meccan'],
	[4846, 55, 37, 3, 'القمر', "Al-Qamar", 'The Moon', 'Meccan'],
	[4901, 78, 97, 3, 'الرحمن', "Ar-Rahmaan", 'The Beneficent', 'Medinan'],
	[4979, 96, 46, 3, 'الواقعة', "Al-Waaqia", 'The Inevitable', 'Meccan'],
	[5075, 29, 94, 4, 'الحديد', "Al-Hadid", 'The Iron', 'Medinan'],
	[5104, 22, 105, 3, 'المجادلة', "Al-Mujaadila", 'The Pleading Woman', 'Medinan'],
	[5126, 24, 101, 3, 'الحشر', "Al-Hashr", 'The Exile', 'Medinan'],
	[5150, 13, 91, 2, 'الممتحنة', "Al-Mumtahana", 'She that is to be examined', 'Medinan'],
	[5163, 14, 109, 2, 'الصف', "As-Saff", 'The Ranks', 'Medinan'],
	[5177, 11, 110, 2, 'الجمعة', "Al-Jumu'a", 'Friday', 'Medinan'],
	[5188, 11, 104, 2, 'المنافقون', "Al-Munaafiqoon", 'The Hypocrites', 'Medinan'],
	[5199, 18, 108, 2, 'التغابن', "At-Taghaabun", 'Mutual Disillusion', 'Medinan'],
	[5217, 12, 99, 2, 'الطلاق', "At-Talaaq", 'Divorce', 'Medinan'],
	[5229, 12, 107, 2, 'التحريم', "At-Tahrim", 'The Prohibition', 'Medinan'],
	[5241, 30, 77, 2, 'الملك', "Al-Mulk", 'The Sovereignty', 'Meccan'],
	[5271, 52, 2, 2, 'القلم', "Al-Qalam", 'The Pen', 'Meccan'],
	[5323, 52, 78, 2, 'الحاقة', "Al-Haaqqa", 'The Reality', 'Meccan'],
	[5375, 44, 79, 2, 'المعارج', "Al-Ma'aarij", 'The Ascending Stairways', 'Meccan'],
	[5419, 28, 71, 2, 'نوح', "Nooh", 'Noah', 'Meccan'],
	[5447, 28, 40, 2, 'الجن', "Al-Jinn", 'The Jinn', 'Meccan'],
	[5475, 20, 3, 2, 'المزمل', "Al-Muzzammil", 'The Enshrouded One', 'Meccan'],
	[5495, 56, 4, 2, 'المدثر', "Al-Muddaththir", 'The Cloaked One', 'Meccan'],
	[5551, 40, 31, 2, 'القيامة', "Al-Qiyaama", 'The Resurrection', 'Meccan'],
	[5591, 31, 98, 2, 'الانسان', "Al-Insaan", 'Man', 'Medinan'],
	[5622, 50, 33, 2, 'المرسلات', "Al-Mursalaat", 'The Emissaries', 'Meccan'],
	[5672, 40, 80, 2, 'النبإ', "An-Naba", 'The Announcement', 'Meccan'],
	[5712, 46, 81, 2, 'النازعات', "An-Naazi'aat", 'Those who drag forth', 'Meccan'],
	[5758, 42, 24, 1, 'عبس', "Abasa", 'He frowned', 'Meccan'],
	[5800, 29, 7, 1, 'التكوير', "At-Takwir", 'The Overthrowing', 'Meccan'],
	[5829, 19, 82, 1, 'الإنفطار', "Al-Infitaar", 'The Cleaving', 'Meccan'],
	[5848, 36, 86, 1, 'المطففين', "Al-Mutaffifin", 'Defrauding', 'Meccan'],
	[5884, 25, 83, 1, 'الإنشقاق', "Al-Inshiqaaq", 'The Splitting Open', 'Meccan'],
	[5909, 22, 27, 1, 'البروج', "Al-Burooj", 'The Constellations', 'Meccan'],
	[5931, 17, 36, 1, 'الطارق', "At-Taariq", 'The Morning Star', 'Meccan'],
	[5948, 19, 8, 1, 'الأعلى', "Al-A'laa", 'The Most High', 'Meccan'],
	[5967, 26, 68, 1, 'الغاشية', "Al-Ghaashiya", 'The Overwhelming', 'Meccan'],
	[5993, 30, 10, 1, 'الفجر', "Al-Fajr", 'The Dawn', 'Meccan'],
	[6023, 20, 35, 1, 'البلد', "Al-Balad", 'The City', 'Meccan'],
	[6043, 15, 26, 1, 'الشمس', "Ash-Shams", 'The Sun', 'Meccan'],
	[6058, 21, 9, 1, 'الليل', "Al-Lail", 'The Night', 'Meccan'],
	[6079, 11, 11, 1, 'الضحى', "Ad-Dhuhaa", 'The Morning Hours', 'Meccan'],
	[6090, 8, 12, 1, 'الشرح', "Ash-Sharh", 'The Consolation', 'Meccan'],
	[6098, 8, 28, 1, 'التين', "At-Tin", 'The Fig', 'Meccan'],
	[6106, 19, 1, 1, 'العلق', "Al-Alaq", 'The Clot', 'Meccan'],
	[6125, 5, 25, 1, 'القدر', "Al-Qadr", 'The Power, Fate', 'Meccan'],
	[6130, 8, 100, 1, 'البينة', "Al-Bayyina", 'The Evidence', 'Medinan'],
	[6138, 8, 93, 1, 'الزلزلة', "Az-Zalzala", 'The Earthquake', 'Medinan'],
	[6146, 11, 14, 1, 'العاديات', "Al-Aadiyaat", 'The Chargers', 'Meccan'],
	[6157, 11, 30, 1, 'القارعة', "Al-Qaari'a", 'The Calamity', 'Meccan'],
	[6168, 8, 16, 1, 'التكاثر', "At-Takaathur", 'Competition', 'Meccan'],
	[6176, 3, 13, 1, 'العصر', "Al-Asr", 'The Declining Day, Epoch', 'Meccan'],
	[6179, 9, 32, 1, 'الهمزة', "Al-Humaza", 'The Traducer', 'Meccan'],
	[6188, 5, 19, 1, 'الفيل', "Al-Fil", 'The Elephant', 'Meccan'],
	[6193, 4, 29, 1, 'قريش', "Quraish", 'Quraysh', 'Meccan'],
	[6197, 7, 17, 1, 'الماعون', "Al-Maa'un", 'Almsgiving', 'Meccan'],
	[6204, 3, 15, 1, 'الكوثر', "Al-Kawthar", 'Abundance', 'Meccan'],
	[6207, 6, 18, 1, 'الكافرون', "Al-Kaafiroon", 'The Disbelievers', 'Meccan'],
	[6213, 3, 114, 1, 'النصر', "An-Nasr", 'Divine Support', 'Medinan'],
	[6216, 5, 6, 1, 'المسد', "Al-Masad", 'The Palm Fibre', 'Meccan'],
	[6221, 4, 22, 1, 'الإخلاص', "Al-Ikhlaas", 'Sincerity', 'Meccan'],
	[6225, 5, 20, 1, 'الفلق', "Al-Falaq", 'The Dawn', 'Meccan'],
	[6230, 6, 21, 1, 'الناس', "An-Naas", 'Mankind', 'Meccan'],
	[6236, 1]*/
];


//------------------ Juz Data ---------------------

QuranData.Juz = [
	// [sura, aya]
	[],	
	[1, 1], 	[2, 142], 	[2, 253], 	[3, 93], 	[4, 24],
	[4, 148], 	[5, 82], 	[6, 111], 	[7, 88], 	[8, 41],
	[9, 93], 	[11, 6], 	[12, 53], 	[15, 1], 	[17, 1],
	[18, 75], 	[21, 1], 	[23, 1], 	[25, 21], 	[27, 56],
	[29, 46], 	[33, 31], 	[36, 28], 	[39, 32], 	[41, 47],
	[46, 1], 	[51, 31], 	[58, 1], 	[67, 1], 	[78, 1],
	[115, 1] 
];

//------------------ Hizb Data ---------------------

QuranData.HizbQaurter = [
	// [sura, aya]
	[],	
	[1, 1], 	[2, 26], 	[2, 44], 	[2, 60],
	[2, 75], 	[2, 92], 	[2, 106], 	[2, 124],
	[2, 142], 	[2, 158], 	[2, 177], 	[2, 189],
	[2, 203], 	[2, 219], 	[2, 233], 	[2, 243],
	[2, 253], 	[2, 263], 	[2, 272], 	[2, 283],
	[3, 15], 	[3, 33], 	[3, 52], 	[3, 75],
	[3, 93], 	[3, 113], 	[3, 133], 	[3, 153],
	[3, 171], 	[3, 186], 	[4, 1], 	[4, 12],
	[4, 24], 	[4, 36], 	[4, 58], 	[4, 74],
	[4, 88], 	[4, 100], 	[4, 114], 	[4, 135],
	[4, 148], 	[4, 163], 	[5, 1], 	[5, 12],
	[5, 27], 	[5, 41], 	[5, 51], 	[5, 67],
	[5, 82], 	[5, 97], 	[5, 109], 	[6, 13],
	[6, 36], 	[6, 59], 	[6, 74], 	[6, 95],
	[6, 111], 	[6, 127], 	[6, 141], 	[6, 151],
	[7, 1], 	[7, 31], 	[7, 47], 	[7, 65],
	[7, 88], 	[7, 117], 	[7, 142], 	[7, 156],
	[7, 171], 	[7, 189], 	[8, 1], 	[8, 22],
	[8, 41], 	[8, 61], 	[9, 1], 	[9, 19],
	[9, 34], 	[9, 46], 	[9, 60], 	[9, 75],
	[9, 93], 	[9, 111], 	[9, 122], 	[10, 11],
	[10, 26], 	[10, 53], 	[10, 71], 	[10, 90],
	[11, 6], 	[11, 24], 	[11, 41], 	[11, 61],
	[11, 84], 	[11, 108], 	[12, 7], 	[12, 30],
	[12, 53], 	[12, 77], 	[12, 101], 	[13, 5],
	[13, 19], 	[13, 35], 	[14, 10], 	[14, 28],
	[15, 1], 	[15, 50], 	[16, 1], 	[16, 30],
	[16, 51], 	[16, 75], 	[16, 90], 	[16, 111],
	[17, 1], 	[17, 23], 	[17, 50], 	[17, 70],
	[17, 99], 	[18, 17], 	[18, 32], 	[18, 51],
	[18, 75], 	[18, 99], 	[19, 22], 	[19, 59],
	[20, 1], 	[20, 55], 	[20, 83], 	[20, 111],
	[21, 1], 	[21, 29], 	[21, 51], 	[21, 83],
	[22, 1], 	[22, 19], 	[22, 38], 	[22, 60],
	[23, 1], 	[23, 36], 	[23, 75], 	[24, 1],
	[24, 21], 	[24, 35], 	[24, 53], 	[25, 1],
	[25, 21], 	[25, 53], 	[26, 1], 	[26, 52],
	[26, 111], 	[26, 181], 	[27, 1], 	[27, 27],
	[27, 56], 	[27, 82], 	[28, 12], 	[28, 29],
	[28, 51], 	[28, 76], 	[29, 1], 	[29, 26],
	[29, 46], 	[30, 1], 	[30, 31], 	[30, 54],
	[31, 22], 	[32, 11], 	[33, 1], 	[33, 18],
	[33, 31], 	[33, 51], 	[33, 60], 	[34, 10],
	[34, 24], 	[34, 46], 	[35, 15], 	[35, 41],
	[36, 28], 	[36, 60], 	[37, 22], 	[37, 83],
	[37, 145], 	[38, 21], 	[38, 52], 	[39, 8],
	[39, 32], 	[39, 53], 	[40, 1], 	[40, 21],
	[40, 41], 	[40, 66], 	[41, 9], 	[41, 25],
	[41, 47], 	[42, 13], 	[42, 27], 	[42, 51],
	[43, 24], 	[43, 57], 	[44, 17], 	[45, 12],
	[46, 1], 	[46, 21], 	[47, 10], 	[47, 33],
	[48, 18], 	[49, 1], 	[49, 14], 	[50, 27],
	[51, 31], 	[52, 24], 	[53, 26], 	[54, 9],
	[55, 1], 	[56, 1], 	[56, 75], 	[57, 16],
	[58, 1], 	[58, 14], 	[59, 11], 	[60, 7],
	[62, 1], 	[63, 4], 	[65, 1], 	[66, 1],
	[67, 1], 	[68, 1], 	[69, 1], 	[70, 19],
	[72, 1], 	[73, 20], 	[75, 1], 	[76, 19],
	[78, 1], 	[80, 1], 	[82, 1], 	[84, 1],
	[87, 1], 	[90, 1], 	[94, 1], 	[100, 9],
	[115, 1] 
];

//------------------ Manzil Data ---------------------

QuranData.Manzil = [
	// [sura, aya]
	[],	
	[1, 1], 	[5, 1], 	[10, 1], 	[17, 1],
	[26, 1], 	[37, 1], 	[50, 1]
];


//------------------ Ruku Data ---------------------


//------------------ Page Data ---------------------

QuranData.Page = [
	// [sura, aya]
	[],	
	[1, 1], 	[2, 1], 	[2, 6], 	[2, 17], 	[2, 25],
	[2, 30], 	[2, 38], 	[2, 49], 	[2, 58], 	[2, 62],
	[2, 70], 	[2, 77], 	[2, 84], 	[2, 89], 	[2, 94],
	[2, 102], 	[2, 106], 	[2, 113], 	[2, 120], 	[2, 127],
	[2, 135], 	[2, 142], 	[2, 146], 	[2, 154], 	[2, 164],
	[2, 170], 	[2, 177], 	[2, 182], 	[2, 187], 	[2, 191],
	[2, 197], 	[2, 203], 	[2, 211], 	[2, 216], 	[2, 220],
	[2, 225], 	[2, 231], 	[2, 234], 	[2, 238], 	[2, 246],
	[2, 249], 	[2, 253], 	[2, 257], 	[2, 260], 	[2, 265],
	[2, 270], 	[2, 275], 	[2, 282], 	[2, 283], 	[3, 1],
	[3, 10], 	[3, 16], 	[3, 23], 	[3, 30], 	[3, 38],
	[3, 46], 	[3, 53], 	[3, 62], 	[3, 71], 	[3, 78],
	[3, 84], 	[3, 92], 	[3, 101], 	[3, 109], 	[3, 116],
	[3, 122], 	[3, 133], 	[3, 141], 	[3, 149], 	[3, 154],
	[3, 158], 	[3, 166], 	[3, 174], 	[3, 181], 	[3, 187],
	[3, 195], 	[4, 1], 	[4, 7], 	[4, 12], 	[4, 15],
	[4, 20], 	[4, 24], 	[4, 27], 	[4, 34], 	[4, 38],
	[4, 45], 	[4, 52], 	[4, 60], 	[4, 66], 	[4, 75],
	[4, 80], 	[4, 87], 	[4, 92], 	[4, 95], 	[4, 102],
	[4, 106], 	[4, 114], 	[4, 122], 	[4, 128], 	[4, 135],
	[4, 141], 	[4, 148], 	[4, 155], 	[4, 163], 	[4, 171],
	[4, 176], 	[5, 3], 	[5, 6], 	[5, 10], 	[5, 14],
	[5, 18], 	[5, 24], 	[5, 32], 	[5, 37], 	[5, 42],
	[5, 46], 	[5, 51], 	[5, 58], 	[5, 65], 	[5, 71],
	[5, 77], 	[5, 83], 	[5, 90], 	[5, 96], 	[5, 104],
	[5, 109], 	[5, 114], 	[6, 1], 	[6, 9], 	[6, 19],
	[6, 28], 	[6, 36], 	[6, 45], 	[6, 53], 	[6, 60],
	[6, 69], 	[6, 74], 	[6, 82], 	[6, 91], 	[6, 95],
	[6, 102], 	[6, 111], 	[6, 119], 	[6, 125], 	[6, 132],
	[6, 138], 	[6, 143], 	[6, 147], 	[6, 152], 	[6, 158],
	[7, 1], 	[7, 12], 	[7, 23], 	[7, 31], 	[7, 38],
	[7, 44], 	[7, 52], 	[7, 58], 	[7, 68], 	[7, 74],
	[7, 82], 	[7, 88], 	[7, 96], 	[7, 105], 	[7, 121],
	[7, 131], 	[7, 138], 	[7, 144], 	[7, 150], 	[7, 156],
	[7, 160], 	[7, 164], 	[7, 171], 	[7, 179], 	[7, 188],
	[7, 196], 	[8, 1], 	[8, 9], 	[8, 17], 	[8, 26],
	[8, 34], 	[8, 41], 	[8, 46], 	[8, 53], 	[8, 62],
	[8, 70], 	[9, 1], 	[9, 7], 	[9, 14], 	[9, 21],
	[9, 27], 	[9, 32], 	[9, 37], 	[9, 41], 	[9, 48],
	[9, 55], 	[9, 62], 	[9, 69], 	[9, 73], 	[9, 80],
	[9, 87], 	[9, 94], 	[9, 100], 	[9, 107], 	[9, 112],
	[9, 118], 	[9, 123], 	[10, 1], 	[10, 7], 	[10, 15],
	[10, 21], 	[10, 26], 	[10, 34], 	[10, 43], 	[10, 54],
	[10, 62], 	[10, 71], 	[10, 79], 	[10, 89], 	[10, 98],
	[10, 107], 	[11, 6], 	[11, 13], 	[11, 20], 	[11, 29],
	[11, 38], 	[11, 46], 	[11, 54], 	[11, 63], 	[11, 72],
	[11, 82], 	[11, 89], 	[11, 98], 	[11, 109], 	[11, 118],
	[12, 5], 	[12, 15], 	[12, 23], 	[12, 31], 	[12, 38],
	[12, 44], 	[12, 53], 	[12, 64], 	[12, 70], 	[12, 79],
	[12, 87], 	[12, 96], 	[12, 104], 	[13, 1], 	[13, 6],
	[13, 14], 	[13, 19], 	[13, 29], 	[13, 35], 	[13, 43],
	[14, 6], 	[14, 11], 	[14, 19], 	[14, 25], 	[14, 34],
	[14, 43], 	[15, 1], 	[15, 16], 	[15, 32], 	[15, 52],
	[15, 71], 	[15, 91], 	[16, 7], 	[16, 15], 	[16, 27],
	[16, 35], 	[16, 43], 	[16, 55], 	[16, 65], 	[16, 73],
	[16, 80], 	[16, 88], 	[16, 94], 	[16, 103], 	[16, 111],
	[16, 119], 	[17, 1], 	[17, 8], 	[17, 18], 	[17, 28],
	[17, 39], 	[17, 50], 	[17, 59], 	[17, 67], 	[17, 76],
	[17, 87], 	[17, 97], 	[17, 105], 	[18, 5], 	[18, 16],
	[18, 21], 	[18, 28], 	[18, 35], 	[18, 46], 	[18, 54],
	[18, 62], 	[18, 75], 	[18, 84], 	[18, 98], 	[19, 1],
	[19, 12], 	[19, 26], 	[19, 39], 	[19, 52], 	[19, 65],
	[19, 77], 	[19, 96], 	[20, 13], 	[20, 38], 	[20, 52],
	[20, 65], 	[20, 77], 	[20, 88], 	[20, 99], 	[20, 114],
	[20, 126], 	[21, 1], 	[21, 11], 	[21, 25], 	[21, 36],
	[21, 45], 	[21, 58], 	[21, 73], 	[21, 82], 	[21, 91],
	[21, 102], 	[22, 1], 	[22, 6], 	[22, 16], 	[22, 24],
	[22, 31], 	[22, 39], 	[22, 47], 	[22, 56], 	[22, 65],
	[22, 73], 	[23, 1], 	[23, 18], 	[23, 28], 	[23, 43],
	[23, 60], 	[23, 75], 	[23, 90], 	[23, 105], 	[24, 1],
	[24, 11], 	[24, 21], 	[24, 28], 	[24, 32], 	[24, 37],
	[24, 44], 	[24, 54], 	[24, 59], 	[24, 62], 	[25, 3],
	[25, 12], 	[25, 21], 	[25, 33], 	[25, 44], 	[25, 56],
	[25, 68], 	[26, 1], 	[26, 20], 	[26, 40], 	[26, 61],
	[26, 84], 	[26, 112], 	[26, 137], 	[26, 160], 	[26, 184],
	[26, 207], 	[27, 1], 	[27, 14], 	[27, 23], 	[27, 36],
	[27, 45], 	[27, 56], 	[27, 64], 	[27, 77], 	[27, 89],
	[28, 6], 	[28, 14], 	[28, 22], 	[28, 29], 	[28, 36],
	[28, 44], 	[28, 51], 	[28, 60], 	[28, 71], 	[28, 78],
	[28, 85], 	[29, 7], 	[29, 15], 	[29, 24], 	[29, 31],
	[29, 39], 	[29, 46], 	[29, 53], 	[29, 64], 	[30, 6],
	[30, 16], 	[30, 25], 	[30, 33], 	[30, 42], 	[30, 51],
	[31, 1], 	[31, 12], 	[31, 20], 	[31, 29], 	[32, 1],
	[32, 12], 	[32, 21], 	[33, 1], 	[33, 7], 	[33, 16],
	[33, 23], 	[33, 31], 	[33, 36], 	[33, 44], 	[33, 51],
	[33, 55], 	[33, 63], 	[34, 1], 	[34, 8], 	[34, 15],
	[34, 23], 	[34, 32], 	[34, 40], 	[34, 49], 	[35, 4],
	[35, 12], 	[35, 19], 	[35, 31], 	[35, 39], 	[35, 45],
	[36, 13], 	[36, 28], 	[36, 41], 	[36, 55], 	[36, 71],
	[37, 1], 	[37, 25], 	[37, 52], 	[37, 77], 	[37, 103],
	[37, 127], 	[37, 154], 	[38, 1], 	[38, 17], 	[38, 27],
	[38, 43], 	[38, 62], 	[38, 84], 	[39, 6], 	[39, 11],
	[39, 22], 	[39, 32], 	[39, 41], 	[39, 48], 	[39, 57],
	[39, 68], 	[39, 75], 	[40, 8], 	[40, 17], 	[40, 26],
	[40, 34], 	[40, 41], 	[40, 50], 	[40, 59], 	[40, 67],
	[40, 78], 	[41, 1], 	[41, 12], 	[41, 21], 	[41, 30],
	[41, 39], 	[41, 47], 	[42, 1], 	[42, 11], 	[42, 16],
	[42, 23], 	[42, 32], 	[42, 45], 	[42, 52], 	[43, 11],
	[43, 23], 	[43, 34], 	[43, 48], 	[43, 61], 	[43, 74],
	[44, 1], 	[44, 19], 	[44, 40], 	[45, 1], 	[45, 14],
	[45, 23], 	[45, 33], 	[46, 6], 	[46, 15], 	[46, 21],
	[46, 29], 	[47, 1], 	[47, 12], 	[47, 20], 	[47, 30],
	[48, 1], 	[48, 10], 	[48, 16], 	[48, 24], 	[48, 29],
	[49, 5], 	[49, 12], 	[50, 1], 	[50, 16], 	[50, 36],
	[51, 7], 	[51, 31], 	[51, 52], 	[52, 15], 	[52, 32],
	[53, 1], 	[53, 27], 	[53, 45], 	[54, 7], 	[54, 28],
	[54, 50], 	[55, 17], 	[55, 41], 	[55, 68], 	[56, 17],
	[56, 51], 	[56, 77], 	[57, 4], 	[57, 12], 	[57, 19],
	[57, 25], 	[58, 1], 	[58, 7], 	[58, 12], 	[58, 22],
	[59, 4], 	[59, 10], 	[59, 17], 	[60, 1], 	[60, 6],
	[60, 12], 	[61, 6], 	[62, 1], 	[62, 9], 	[63, 5],
	[64, 1], 	[64, 10], 	[65, 1], 	[65, 6], 	[66, 1],
	[66, 8], 	[67, 1], 	[67, 13], 	[67, 27], 	[68, 16],
	[68, 43], 	[69, 9], 	[69, 35], 	[70, 11], 	[70, 40],
	[71, 11], 	[72, 1], 	[72, 14], 	[73, 1], 	[73, 20],
	[74, 18], 	[74, 48], 	[75, 20], 	[76, 6], 	[76, 26],
	[77, 20], 	[78, 1], 	[78, 31], 	[79, 16], 	[80, 1],
	[81, 1], 	[82, 1], 	[83, 7], 	[83, 35], 	[85, 1],
	[86, 1], 	[87, 16], 	[89, 1], 	[89, 24], 	[91, 1],
	[92, 15], 	[95, 1], 	[97, 1], 	[98, 8], 	[100, 10],
	[103, 1], 	[106, 1], 	[109, 1], 	[112, 1], 	[115, 1]
];


//------------------ Sajda Data ---------------------

QuranData.Sajda = [
	// [sura, aya, type]
	[],
	[7, 206, 'recommended'],
	[13, 15, 'recommended'],
	[16, 50, 'recommended'],
	[17, 109, 'recommended'],
	[19, 58, 'recommended'],
	[22, 18, 'recommended'],
	[22, 77, 'recommended'],
	[25, 60, 'recommended'],
	[27, 26, 'recommended'],
	[32, 15, 'obligatory'],
	[38, 24, 'recommended'],
	[41, 38, 'obligatory'],
	[53, 62, 'obligatory'],
	[84, 21, 'recommended'],
	[96, 19, 'obligatory'],
];





/*****************************************************************************/