



//Get selector id for all browsers
function je(id){
	if(document.getElementById) {
		return document.getElementById(id);
	} else if(document.all) {
		return document.all[id];
	} else {return false;}
}

var compAll = 5;
var compCtr = 0;
function jProgress(t){
	compCtr++;
	if (je('jProgress') != null ) {
	   //var w = je('jProgress').style.width;
	   je('jProgress').style.width  =  ((compCtr*(100 / compAll))*2)+'px';
	   je('jPercent').innerHTML 	=  (compCtr*(100 / compAll));
    }
    
    if(parseInt(compCtr) == parseInt(compAll)){
        JQ.init();
        $('#jPreloaderDiv').remove();
    }
}

function loadcss(filename){

  var fileref=document.createElement("link");
  fileref.setAttribute("rel", "stylesheet");
  fileref.setAttribute("type", "text/css");
  fileref.setAttribute("href", filename);

 if (typeof fileref!="undefined")
  document.getElementsByTagName("head")[0].appendChild(fileref);
}



function loadScript(url, callback){

    var script = document.createElement("script");
    script.type = "text/javascript";

    script.src = url;
    if (script.readyState){  //IE
        script.onreadystatechange = function(){
            if (script.readyState == "loaded" ||
                    script.readyState == "complete"){
                script.onreadystatechange = null;
                if(callback) callback();
            }
        };
    } else {  //Others
        script.onload = function(){
            if(callback) callback();
        };
    }

    document.getElementsByTagName("head")[0].appendChild(script);
}

