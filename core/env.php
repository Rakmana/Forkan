<?php
/**
 * Environment looker: Trait request & make response
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: env.php,v2.0 10:09 09/03/2010 NP++ Exp $
*/




class env{
	 /**
	* check, whether client supports compressed data
	*
	* @access	private
	* @return	boolean
	*/
	public static function encoding()	{
		if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
			return false;
		}

		$encoding = false;

		if (false !== strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
			$encoding = 'gzip';
		}

		if (false !== strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip')) {
			$encoding = 'x-gzip';
		}

		return $encoding;
	}
	public static function compress($data){
		$encoding = env::encoding();

		if (!$encoding)
			return $data;

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent())
			return $data;

		if (connection_status() !== 0)
			return $data;

		@header('Content-Encoding: '.$encoding);
		@header('X-Content-Encoded-By: '.WPL_ASM." ".WPL_VERSION);
		$compressed = gzencode($data, 4);
			return $compressed;

	}
    public static function getXMLresponse(){
        
        //4ajax profiler
        $time = (konfig::isOn('showProfiler'))? ' it="'.kjxProfiler().'"' : '';
        $notifications = (registry::get('notifications') != '')? '<![CDATA['.registry::get('notifications').']]>' : '';
        $contents = (registry::get('contents') != '')? registry::get('contents') : '';
        
        $cjs = registry::get('css');
        $cjs.= registry::get('js');
        $cjs.= registry::get('aljs');
        
        //organize in xml format
        $response = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $response.= '	<wres'.$time.'>'."\n";
        $response.= '	<wntf>'.$notifications.'</wntf>'."\n";
        $response.= '	<wctn>'.$contents.'</wctn>'."\n";
        if($cjs != ''){
            $response.= '	<wcjs><![CDATA['.$cjs.']]></wcjs>'."\n";
        }
        if(jawab::getAjxTitle() != ''){
            $response.= '	<doctitle>'.jawab::getAjxTitle().'</doctitle>'."\n";
        }
        //$response.= '	<wpfr>'</wpfr>'."\n";
        $response.= '</wres>';
        return $response;
    }
/**
* response function with ob_handler
*
* @access public
*/
public static Function response($html,$type="html",$cache=false){

	$encoding = env::encoding();

	//reset output if already started
	
	if($ob = ob_get_status()){
		$out = ob_get_contents();
		ob_end_clean();
	}	
		
	@header("content-type: text/$type;  charset=UTF-8");
	@header("X-Powered-By: ".WPL_ASM." ".WPL_VERSION);
	if($cache != false){
		//@header("ETag: WPL" . microtime()); // image
		@header("Expires: 15 Apr 2015 20:00:00 GMT");
		@header("Cache-Control: public");
		@header("Pragma: public");
	}
	if(($encoding == 'gzip' || $encoding == 'x-gzip')&& konfig::isOn('gzip') && !ini_get('zlib.output_compression') && ini_get('output_handler')!='ob_gzhandler'){
		@header('Content-Encoding: '.$encoding);
		@header('X-Content-Encoded-By: '.WPL_ASM." ".WPL_VERSION);
		ob_start("ob_gzhandler");
		//$html = env::compress($html);
	}
	else
		ob_start();
		
	echo ($html);
    
	ob_end_flush();
}

	
	/**
	 * XSS Clean
	 *
	 * Sanitizes data so that Cross Site Scripting Hacks can be
	 * prevented.  This function does a fair amount of work but
	 * it is extremely thorough, designed to prevent even the
	 * most obscure XSS attempts.  Nothing is ever 100% foolproof,
	 * of course, but I haven't been able to get anything passed
	 * the filter.
	 *
	 * Note: This function should only be used to deal with data
	 * upon submission.  It's not something that should
	 * be used for general runtime processing.
	 *
	 * This function was based in part on some code and ideas I
	 * got from Bitflux: http://blog.bitflux.ch/wiki/XSS_Prevention
	 *
	 * To help develop this script I used this great list of
	 * vulnerabilities along with a few other hacks I've
	 * harvested from examining vulnerabilities in other programs:
	 * http://ha.ckers.org/xss.html
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public static function xss_clean($str, $charset = 'UTF-8')
	{	
		/*
		 * Remove Null Characters
		 *
		 * This prevents sandwiching null characters
		 * between ascii characters, like Java\0script.
		 *
		 */
		$str = preg_replace('/\0+/', '', $str);
		$str = preg_replace('/(\\\\0)+/', '', $str);

		/*
		 * Validate standard character entities
		 *
		 * Add a semicolon if missing.  We do this to enable
		 * the conversion of entities to ASCII later.
		 *
		 */
		$str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"\\1;",$str);
		
		/*
		 * Validate UTF16 two byte encoding (x00)
		 *
		 * Just as above, adds a semicolon if missing.
		 *
		 */
		$str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"\\1\\2;",$str);

		/*
		 * URL Decode
		 *
		 * Just in case stuff like this is submitted:
		 *
		 * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
		 *
		 * Note: Normally urldecode() would be easier but it removes plus signs
		 *
		 */	
		$str = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $str);
		$str = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $str);		
				
		/*
		 * Convert character entities to ASCII
		 *
		 * This permits our tests below to work reliably.
		 * We only convert entities that are within tags since
		 * these are the ones that will pose security problems.
		 *
		 */
		/*if (preg_match_all("/<(.+?)>/si", $str, $matches))
		{		
			for ($i = 0; $i < count($matches['0']); $i++)
			{
				$str = str_replace($matches['1'][$i],
									$this->_html_entity_decode($matches['1'][$i], $charset),
									$str);
			}
		}*/
		
		/*
		 * Not Allowed Under Any Conditions
		 */	
		$bad = array(
						'document.cookie'	=> '[removed]',
						'document.write'	=> '[removed]',
						'window.location'	=> '[removed]',
						"javascript\s*:"	=> '[removed]',
						"Redirect\s+302"	=> '[removed]',
						'<!--'				=> '&lt;!--',
						'-->'				=> '--&gt;'
					);
	
		foreach ($bad as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);   
		}
	
		/*
		 * Convert all tabs to spaces
		 *
		 * This prevents strings like this: ja	vascript
		 * Note: we deal with spaces between characters later.
		 *
		 */		
		$str = preg_replace("#\t+#", " ", $str);
	
		/*
		 * Makes PHP tags safe
		 *
		 *  Note: XML tags are inadvertently replaced too:
		 *
		 *	<?xml
		 *
		 * But it doesn't seem to pose a problem.
		 *
		 */		
		$str = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
	
		/*
		 * Compact any exploded words
		 *
		 * This corrects words like:  j a v a s c r i p t
		 * These words are compacted back to their correct state.
		 *
		 */		
		$words = array('javascript', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
		foreach ($words as $word)
		{
			$temp = '';
			for ($i = 0; $i < strlen($word); $i++)
			{
				$temp .= substr($word, $i, 1)."\s*";
			}
			
			$temp = substr($temp, 0, -3);
			$str = preg_replace('#'.$temp.'#s', $word, $str);
			$str = preg_replace('#'.ucfirst($temp).'#s', ucfirst($word), $str);
		}
	
		/*
		 * Remove disallowed Javascript in links or img tags
		 */		
		 $str = preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $str);
		 $str = preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $str);
		 $str = preg_replace("#<(script|xss).*?\>#si", "", $str);

		/*
		 * Remove JavaScript Event Handlers
		 *
		 * Note: This code is a little blunt.  It removes
		 * the event handler and anything up to the closing >,
		 * but it's unlikely to be a problem.
		 *
		 */		
		 $str = preg_replace('#(<[^>]+.*?)(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize)[^>]*>#iU',"\\1>",$str);
	
		/*
		 * Sanitize naughty HTML elements
		 *
		 * If a tag containing any of the words in the list
		 * below is found, the tag gets converted to entities.
		 *
		 * So this: <blink>
		 * Becomes: &lt;blink&gt;
		 *
		 */		
		$str = preg_replace('#<(/*\s*)(alert|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss)([^>]*)>#is', "&lt;\\1\\2\\3&gt;", $str);
		
		/*
		 * Sanitize naughty scripting elements
		 *
		 * Similar to above, only instead of looking for
		 * tags it looks for PHP and JavaScript commands
		 * that are disallowed.  Rather than removing the
		 * code, it simply converts the parenthesis to entities
		 * rendering the code un-executable.
		 *
		 * For example:	eval('some code')
		 * Becomes:		eval&#40;'some code'&#41;
		 *
		 */
		$str = preg_replace('#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
						
		/*
		 * Final clean up
		 *
		 * This adds a bit of extra precaution in case
		 * something got through the above filters
		 *
		 */	
		$bad = array(
						'document.cookie'	=> '[removed]',
						'document.write'	=> '[removed]',
						'window.location'	=> '[removed]',
						"javascript\s*:"	=> '[removed]',
						"Redirect\s+302"	=> '[removed]',
						'<!--'				=> '&lt;!--',
						'-->'				=> '--&gt;'
					);
	
		foreach ($bad as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}
		
						
		//log_message('debug', "XSS Filtering completed");
		return $str;
	}
	
}
?>