<?php
/**
 * WAPL Media File Loader Class
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . itkane.com
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: mediax.php,v2.0 19:06 12/03/2011 NP++ Exp $
 */


/**
 * MEDIAX
 * WAPL Media File Loader Class
 * Require Registry.cls + Debugger.cls + konfig.cls + dbase.cls + *CssMin.cls + *Env.cls
 *
 * @package  WAPL
 */

class mediax
{
    var $description = 'System Media Manager Class';


    /**
     * mediax::init()
     * 
     * @return
     */
    function init()
    {
        registry::set('css', ''); //header css
        registry::set('aljs', ''); //auto loaded javascript code
        registry::set('jsh', ''); //header javascript code
        registry::set('js', ''); // body javascript code
        //loader::import(PSYS.'cpacker.php')	;//css compressor
    }

    /**
     * mediax::load()
     * 
     * @param mixed $f
     * @return
     */
    function load($f)
    {
        $loaded = "";
        if (is_array($f)) {
            foreach ($f as $file) {
                $loaded .= mediax::load($file);
            }
        } else {
            $j = str_replace(',', SH, $f);
            //check file existence
            if (file_exists($j)) {
                /*ob_start();
                loader::import($j);
                $ret = ob_get_contents();
                ob_end_clean();*/
                $ret = loader::getMedia($j);

                $loaded .= $ret;//"/*[ File : " . basename($j) . " ]==============================*/" .
                    
            } else {
                debugger::warn('File Not Found: ' . $j, false);
                $loaded .= "/*[ File not found: " . ($j) . " ]=================*/";
            }
            /*/////////////////////*/
            //btrack('Media rendered '.basename($j));
        }

        return $loaded;
    }
    /**
     * mediax::index()
     * 
     * @return
     */
    function index()
    {

        if (!empty($_GET['wimg']))
            mediax::loadimg();
        if (!empty($_GET['dbimg']))
            mediax::loaddbimg();
        if (!empty($_GET['wcss']))
            mediax::loadcss();
        if (!empty($_GET['wjs']))
            mediax::loadjs();
        if (!empty($_GET['wswf']))
            mediax::loadswf();
    }
    /**
     * mediax::loadjs()
     * 
     * @return
     */
    function loadjs()
    {
        $js = isset($_GET['wjs']) ? $_GET['wjs'] : '';

        $files = explode(';', $js);
        registry::add('js', mediax::load($files));
        mediax::show_js();
    }
    /**
     * mediax::loadcss()
     * 
     * @return
     */
    function loadcss()
    {
        $css = isset($_GET['wcss']) ? $_GET['wcss'] : '';

        $files = explode(';', $css);
        registry::add('css', mediax::load($files));
        mediax::show_css();
    }
    /**
     * mediax::loadimg()
     * 
     * @return
     */
    function loadimg()
    {

        $f = $_GET['wimg'];
        $exts = explode('.', $f);
        $ext = $exts[(count($exts) - 1)];

        $j = str_replace(',', SH, $f);
        if (is_readable($j)) {
            $img = @file_get_contents($j);
        } else {
            $img = "IMG Not Found!";
            debugger::warn($img);
            exit;
        }

        @header("ETag: WPL-" . md5($j));
        header("content-type: image/$ext");
        header("X-Powered-By: ".WPL_ASM." ".WPL_VERSION);
        header("Expires: 15 Apr 2025 20:00:00 GMT");
        header("Cache-Control: public");


        tools::show($img);


        /*/////////////////////
        btrack('IMG Loaded');*/
        exit;
    }
    /**
     * mediax::loaddbimg()
     * 
     * @return
     */
    function loaddbimg()
    {
        $f = ($_GET['dbimg']);
        $mat = str_replace(',', SH, $f);

        $result = dbase::query('SELECT PHOTO_EMPLOYER FROM grh_employee WHERE MATRICULE = ' .
            $mat . ';');

        if ($result) {
            $rsl = mysql_fetch_array($result);
            //tools::dump($rsl[0]);
            //$data = base64_decode($rsl[0]);

            $im = @imagecreatefromstring($rsl[0]);
            if ($im !== false) {
                header('Content-Type: image/png');
                header("ETag: WPL-" . md5($f));
                header("X-Powered-By: ".WPL_ASM." ".WPL_VERSION);
                header("Expires: 15 Apr 2025 20:00:00 GMT");
                header("Cache-Control: public");
                @imagepng($im);
            } else
                echo 'Invalid Format...! ';

        } else {
            $img = "DBIMG Not Found!";
            debugger::warn($img);
            exit;
        }


        //	tools::show($img);


        /*/////////////////////
        btrack('IMG Loaded');*/
        exit;
    }
    /**
     * mediax::loadswf()
     * 
     * @return
     */
    function loadswf()
    {

        $f = $_GET['wswf'];
        $exts = explode('.', $f);
        $ext = $exts[(count($exts) - 1)];

        $j = str_replace(',', SH, $f);
        if (is_readable($j)) {
            $swf = @file_get_contents($j);
        } else {
            $swf = "SWF Not Found!";
            debugger::warn($swf);
            exit;
        }

        @header("Content-Type: application/x-shockwave-flash");
        @header("ETag: WPL-" . md5($j));
        @header("X-Powered-By: ".WPL_ASM." ".WPL_VERSION);
        @header("Expires: 15 Apr 2025 20:00:00 GMT");
        @header("Cache-Control: public");
        @header("Pragma: public");


        tools::show($swf);
        //readfile($f);

        /*/////////////////////
        btrack('IMG Loaded');*/
        exit;
    }
    /**
     * mediax::show_js()
     * 
     * @return
     */
    function show_js()
    {

        $cache = !empty($_GET['cache']) ? true : false;

        @header("content-type: text/javascript;  charset=UTF-8");
        @header("X-Powered-By: ".WPL_ASM." ".WPL_VERSION);
        @header("Vary: Accept-Encoding");
        if ($cache = true) {
            @header("Expires: 15 Apr 2015 20:00:00 GMT");
            @header("ETag: WPL-" . md5($_GET['js']));
            @header("Cache-Control: public");
            @header("Pragma: public");
        }
        $output0 = registry::get('js', true);
        //php javascript packer
        if (!empty($_GET['jpk'])) {
            $packer = new jpacker($output0, 'Normal', true, true);
            $output = $packer->pack();
        } else
            $output = @JSMin::minify($output0);
        //$output = $output0;

        // Don't compress something if the server is going todo it anyway. Waste of time.
        if (konfig::isOn('gzip') && !ini_get('zlib.output_compression') && ini_get('output_handler') !=
            'ob_gzhandler') {
            $output = env::compress($output);

            @header('Content-Encoding: gzip');
            @header('X-Content-Encoded-By: '.WPL_ASM." ".WPL_VERSION);
        }

        tools::show($output);


    }
    /**
     * mediax::show_css()
     * 
     * @return
     */
    function show_css()
    {
        $cache = !empty($_GET['cache']) ? true : false;

        @header("content-type: text/css;  charset=UTF-8");
        @header("X-Powered-By: ".WPL_ASM." ".WPL_VERSION);
        @header("Vary: Accept-Encoding");
        if ($cache = true) {
            @header("Expires: 15 Apr 2015 20:00:00 GMT");
            @header("ETag: WPL-" . md5($_GET['css']));
            @header("Cache-Control: public");
            @header("Pragma: public");
        }
        $output = registry::get('css', true);

        $output = CssMin::minify($output);

        if (konfig::isOn('gzip') && !ini_get('zlib.output_compression') && ini_get('output_handler') !=
            'ob_gzhandler') {
            $output = env::compress($output);

            @header('Content-Encoding: gzip');
            @header('X-Content-Encoded-By: Wapl v1.0');
        }
        tools::show($output);

    }
    /**
     * mediax::minify()
     * 
     * @param mixed $code
     * @return
     */
    function minify(&$code)
    {
        $code = preg_replace('/([\t]+)/i', "", $code); //white tab
        //$code = preg_replace('/([\s]+)/i',"",$code);//white space
        $code = str_replace("\r\n", "\n", $code);
        //$code = str_replace("\r", "\n", $code);
        $code = str_replace("/*", "#S#", $code);
        $code = str_replace("*/", "#E#", $code);
        $code = preg_replace('/#S#([^\t]+)#E#/siU', '', $code); //Js Css code comment /*bla bla*/
        //$code = preg_replace('@([^:])//([^\n]+)\n@',"\\1 //XXX \n",$code);//Js Css code comment //bla bla
        $code = preg_replace('/([\n\n]+)/i', "\n", $code); //enter line
        //$code = preg_replace('/([\n]+)/i'," ",$code);//empty line

        return $code;
    }

    /**
     * Replace
     * replace variables and constants in the template html
     *
     * @param 	$code	template html
     * @return  $code	validated html
     */
    /**
     * mediax::replace()
     * 
     * @param mixed $code
     * @return
     */
    function replace(&$code)
    {

        $code = preg_replace('/__([^\_]+)__/e', 'constant("\\1")', $code); //constants
        $code = preg_replace('/#_([^\#]+)#/e', 'txt("\\1")', $code); //language
        $code = preg_replace('/##([^\#]+)#/e', 'registry::get("\\1")', $code); //registry
        $code = preg_replace('/_#([^\#]+)#/e', 'konfig::get("\\1")', $code); //konfig
        $code = preg_replace('/@@([^\|]+)\|([^\|]+)?\|([^\@]+)?@/e', 'form::mlink("\\1","\\2","\\3")',
            $code); //create media  Links
        $code = preg_replace('/_@([^\|]+)\|([^\|]+)\|([^\|]+)\|([^\@]+)?@/e',
            'form::clink("\\1","\\2","\\3","\\4")', $code); //create Links

        return $code;
    }

    /**
     * getJS
     * Print All needed js code into template html
     *
     * @return  string	Print Output html
     */
    /**
     * mediax::BootJS()
     * 
     * @return
     */
    function BootJS()
    {
        $output = loader::getMedia('scripts/boot.js');
        $packer = new jpacker($output, 'Normal', true, true);
        return '<script type="text/javascript" src="' . form::mlink('js',
            'scripts/loader.js', 'jpk=1&amp;cache=1') . '"></script>
        <script type="text/javascript">' . $packer->pack() . '</script>';
    }
	function loadTinyMSE(){
	registry::add('jsex', '<script src="'.KPLG.'tiny_mce/jquery.tinymce.js" type="text/javascript"></script>');
    $ret = '<!-- Load TinyMCE -->
	$().ready(function() {
		$(\'textarea.tinymce\').tinymce({
			// Location of TinyMCE script
			script_url : "'.KPLG.'tiny_mce/tiny_mce.js",

			// General options
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pasteword,|,search|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,code,|,insertdate,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			//content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			//template_external_list_url : "lists/template_list.js",
			//external_link_list_url : "lists/link_list.js",
			//external_image_list_url : "lists/image_list.js",
			//media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
            <!-- /TinyMCE -->';
	registry::add('jsh',$ret);
	}
}
?>