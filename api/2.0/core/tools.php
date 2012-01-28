<?php

 Class tools{

public static function init(){}

/**
* Time : now function
*
*@return time in string form
* @access public
*/
public static function now($format='d-m-Y H:i:s',$second=false){
	$mt = microtime();
	list($usec1, $sec) = explode(' ', $mt);
	$usec = round($usec1,3);
	list($zro, $us) = explode('.', $usec);
	$time = (!$second)? date($format) : date($format).':'.$us;
	return $time;
}
public static function code2utf($num){
 if($num<128) 
   return chr($num);
 if($num<1024) 
   return chr(($num>>6)+192).chr(($num&63)+128);
 if($num<32768) 
   return chr(($num>>12)+224).chr((($num>>6)&63)+128)
         .chr(($num&63)+128);
 if($num<2097152) 
   return chr(($num>>18)+240).chr((($num>>12)&63)+128)
         .chr((($num>>6)&63)+128).chr(($num&63)+128);
 return '';
}
public static function unescape($strIn, $iconv_to = 'UTF-8') {
 $strOut = '';
 $iPos = 0;
 $len = strlen ($strIn);
 while ($iPos < $len) {
   $charAt = substr ($strIn, $iPos, 1);
   if ($charAt == '%') {
     $iPos++;
     $charAt = substr ($strIn, $iPos, 1);
     if ($charAt == 'u') {
       // Unicode character
       $iPos++;
       $unicodeHexVal = substr ($strIn, $iPos, 4);
       $unicode = hexdec ($unicodeHexVal);
       $strOut .= tools::code2utf($unicode);
       $iPos += 4;
     }
     else {
       // Escaped ascii character
       $hexVal = substr ($strIn, $iPos, 2);
       if (hexdec($hexVal) > 127) {
         // Convert to Unicode 
         $strOut .= tools::code2utf(hexdec ($hexVal));
       }
       else {
         $strOut .= chr (hexdec ($hexVal));
       }
       $iPos += 2;
     }
   }
   else {
     $strOut .= $charAt;
     $iPos++;
   }
 }
 if ($iconv_to != "UTF-8") {
   $strOut = iconv("UTF-8", $iconv_to, $strOut);
 }   
 return $strOut;
}

public static function jEscape($value){
if($value=='NULL'){$value="";}
return mysqli_real_escape_string(dbase::getID(),trim($value));
}
//get the GET var and format it
public static function Jet($name,$qt="'",$null='NULL'){
if(isset($_REQUEST[$name])){
	$value = ($_REQUEST[$name]=='')? $null : $qt.mysqli_real_escape_string(dbase::getID(),trim($_REQUEST[$name])).$qt;
	return $value;
}
else return $null;
}
/**
* echo function with ob_handler
*
* @access public
*/
public static function show($html){
	ob_start();
	print $html;
	ob_end_flush();
}
/**
* Var_dump function
*
* @access public
*/
public static function dump($var){
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}
/**
* Format an attribute string to insert in a tag.
*
* @param $attributes
*   An associative array of HTML attributes.
* @return
*   An HTML string ready for insertion in a tag.
* @access public
*/
public static function arry2attr($attributes = array()) {
	if (is_array($attributes)) {
		$t = '';
		foreach ($attributes as $key => $value) {
			$t .= " $key=".'"'. KToolz::toascii($value) .'"';
		}
		return $t;
	}
}
/**
* formate string (key = "val") as array
*
* @access public
*/
public static function attr2arry($string) {
	preg_match_all('/
\s*(\w+)              # key                               \\1
\s*=\s*               # =
(\'|")?               # values may be included in \' or " \\2
(.*?)                 # value                             \\3
(?(2) \\2)            # matching \' or " if needed        \\4
\s*(?:
	(?=\w+\s*=) | \s*$  # followed by another key= or the end of the string
)
/x', $string, $matches, PREG_SET_ORDER);
	$attributes = array();
	foreach ($matches as $val)
	{
		$attributes[$val[1]] = $val[3];
	}
	return $attributes;
}

/**
* extract the real remote ip
*/
public static function realip(){	
   
   if (isSet($_SERVER)) {
    if (isSet($_SERVER["HTTP_X_FORWARDED_FOR"])) {
     $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (isSet($_SERVER["HTTP_CLIENT_IP"])) {
     $realip = $_SERVER["HTTP_CLIENT_IP"];
    } else {
     $realip = $_SERVER["REMOTE_ADDR"];
    }

   } else {
    if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
     $realip = getenv( 'HTTP_X_FORWARDED_FOR' );
    } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
     $realip = getenv( 'HTTP_CLIENT_IP' );
    } else {
     $realip = getenv( 'REMOTE_ADDR' );
    }
   }
   return $realip;
}

public static function jCrypt($text,$a=69){

    $text1 = $text;
    for( $pos = 0;$pos < strlen($text1);$pos++){
       $text1{$pos} = chr(ord($text1{$pos}) + $a);
	}
     // crypte := text1;
     return $text1;
}

public static function jDecrypt($text){
	$text1 = $text;
    for( $p=0;$p < strlen($text);$p++){
       $text1{$p} = chr(ord($text1{$p}) + 69);
	}
     // crypte := text1;
     return utf8_encode($text1);
}

/**
* parse ini file as array
*
* @access public
* @param string	$iniFile
* @param bool	$has_sections
* @return array	
*/
public static function parse_ini($iniFile,$section=true){
	$mfile = file_get_contents($iniFile);
	return @parse_ini_file($iniFile,$section);
}
/**
* write to ini file
*
* @access public
* @param array 	$assoc_arr
* @param string	$path
* @param bool	$has_sections
* @return bool	true if saved
*/
public static function write_ini(&$assoc_arr, $path, $has_sections=true) {
	$content = "";
	if(is_array($assoc_arr)){
		if ($has_sections) {
			foreach ($assoc_arr as $key=>$elem) {
				$content .= "[".$key."]\n";
				foreach ($elem as $key2=>$elem2) {
					$content .= $key2." = \"".$elem2."\"\n";
				}
			}
		}
		else {
			foreach ($assoc_arr as $key=>$elem) {
				$content .= $key." = \"".$elem."\"\n";
			}
		}
		$content = preg_replace('/^
(\s*\w+\s*=\s*)        # Part \1 - the key and initial whitespace.
(                      # Part \2 - the value to be quoted
(?:(?!\s;)[^"\r\n])  # Anything but \r, ", \s;, \n
		*?            # As little as possible of that, minimise whitespace.
)
(                      # Part \3 - everything after the value
\s*                  # Optional whitespace.
(?:\s;.*)?           # Optional comment preceded by a space
)
$/mx', '\1"\2"\3', $content);
	}
	if (!@file_put_contents($path, $content)) {
		return false;
	}
	return true;
}/**
CHECK IF EMAIL ADDRESS IS VALID OR NOT
*/
public static function check_email($email){
	$email_regexp = "^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,4}\$";
	return eregi($email_regexp, $email);
}
/**
*Check if is mod_rewrite enabled
*
* @access public
*/
public static function is_mod_rewrite() {//@WILL: add to checker.class
	$got_rewrite = apache_mod_loaded('mod_rewrite', true);
	return $got_rewrite;
}
public static function appendchlog(){
	/*/////////////////////*/
	$ret = '<center><span class="syslink tipy" title="Show/hide Changelog" onclick="showhide(\'chlog\')">[Changelog]</span><center>
		<div class="console" id="chlog" style="display:none;"><pre col="50">';
		$ret .= flib::get_contents(KSYS.'history.htr');
	$ret .= '</pre></div>';
	
	return $ret;
			
}
	/**
	* Implementation of PHP's native utf8_encode for people without XML support
	* This function exploits some nice things that ISO-8859-1 and UTF-8 have in common
	*
	* @param string $str ISO-8859-1 encoded data
	* @return string UTF-8 encoded data
	*/
	public static function utf8_encode($str)
	{
		$out = '';
		for ($i = 0, $len = strlen($str); $i < $len; $i++)
		{
			$letter = $str[$i];
			$num = ord($letter);
			if ($num < 0x80)
			{
				$out .= $letter;
			}
			else if ($num < 0xC0)
			{
				$out .= "\xC2" . $letter;
			}
			else
			{
				$out .= "\xC3" . chr($num - 64);
			}
		}
		return $out;
	}

	/**
	* Implementation of PHP's native utf8_decode for people without XML support
	*
	* @param string $str UTF-8 encoded data
	* @return string ISO-8859-1 encoded data
	*/
	public static function utf8_decode($str)
	{
		$pos = 0;
		$len = strlen($str);
		$ret = '';

		while ($pos < $len)
		{
			$ord = ord($str[$pos]) & 0xF0;
			if ($ord === 0xC0 || $ord === 0xD0)
			{
				$charval = ((ord($str[$pos]) & 0x1F) << 6) | (ord($str[$pos + 1]) & 0x3F);
				$pos += 2;
				$ret .= (($charval < 256) ? chr($charval) : '?');
			}
			else if ($ord === 0xE0)
			{
				$ret .= '?';
				$pos += 3;
			}
			else if ($ord === 0xF0)
			{
				$ret .= '?';
				$pos += 4;
			}
			else
			{
				$ret .= $str[$pos];
				++$pos;
			}
		}
		return $ret;
	}

	/**
	* UTF-8 aware alternative to strtolower
	* Make a string lowercase
	* Note: The concept of a characters "case" only exists is some alphabets
	* such as Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does
	* not exist in the Chinese alphabet, for example. See Unicode Standard
	* Annex #21: Case Mappings
	*
	* @param string
	* @return string string in lowercase
	*/
	public static function utf8_strtolower($string)
	{
		static $utf8_upper_to_lower = array(
			"\xC3\x80" => "\xC3\xA0", "\xC3\x81" => "\xC3\xA1",
			"\xC3\x82" => "\xC3\xA2", "\xC3\x83" => "\xC3\xA3", "\xC3\x84" => "\xC3\xA4", "\xC3\x85" => "\xC3\xA5",
			"\xC3\x86" => "\xC3\xA6", "\xC3\x87" => "\xC3\xA7", "\xC3\x88" => "\xC3\xA8", "\xC3\x89" => "\xC3\xA9",
			"\xC3\x8A" => "\xC3\xAA", "\xC3\x8B" => "\xC3\xAB", "\xC3\x8C" => "\xC3\xAC", "\xC3\x8D" => "\xC3\xAD",
			"\xC3\x8E" => "\xC3\xAE", "\xC3\x8F" => "\xC3\xAF", "\xC3\x90" => "\xC3\xB0", "\xC3\x91" => "\xC3\xB1",
			"\xC3\x92" => "\xC3\xB2", "\xC3\x93" => "\xC3\xB3", "\xC3\x94" => "\xC3\xB4", "\xC3\x95" => "\xC3\xB5",
			"\xC3\x96" => "\xC3\xB6", "\xC3\x98" => "\xC3\xB8", "\xC3\x99" => "\xC3\xB9", "\xC3\x9A" => "\xC3\xBA",
			"\xC3\x9B" => "\xC3\xBB", "\xC3\x9C" => "\xC3\xBC", "\xC3\x9D" => "\xC3\xBD", "\xC3\x9E" => "\xC3\xBE",
			"\xC4\x80" => "\xC4\x81", "\xC4\x82" => "\xC4\x83", "\xC4\x84" => "\xC4\x85", "\xC4\x86" => "\xC4\x87",
			"\xC4\x88" => "\xC4\x89", "\xC4\x8A" => "\xC4\x8B", "\xC4\x8C" => "\xC4\x8D", "\xC4\x8E" => "\xC4\x8F",
			"\xC4\x90" => "\xC4\x91", "\xC4\x92" => "\xC4\x93", "\xC4\x96" => "\xC4\x97", "\xC4\x98" => "\xC4\x99",
			"\xC4\x9A" => "\xC4\x9B", "\xC4\x9C" => "\xC4\x9D", "\xC4\x9E" => "\xC4\x9F", "\xC4\xA0" => "\xC4\xA1",
			"\xC4\xA2" => "\xC4\xA3", "\xC4\xA4" => "\xC4\xA5", "\xC4\xA6" => "\xC4\xA7", "\xC4\xA8" => "\xC4\xA9",
			"\xC4\xAA" => "\xC4\xAB", "\xC4\xAE" => "\xC4\xAF", "\xC4\xB4" => "\xC4\xB5", "\xC4\xB6" => "\xC4\xB7",
			"\xC4\xB9" => "\xC4\xBA", "\xC4\xBB" => "\xC4\xBC", "\xC4\xBD" => "\xC4\xBE", "\xC5\x81" => "\xC5\x82",
			"\xC5\x83" => "\xC5\x84", "\xC5\x85" => "\xC5\x86", "\xC5\x87" => "\xC5\x88", "\xC5\x8A" => "\xC5\x8B",
			"\xC5\x8C" => "\xC5\x8D", "\xC5\x90" => "\xC5\x91", "\xC5\x94" => "\xC5\x95", "\xC5\x96" => "\xC5\x97",
			"\xC5\x98" => "\xC5\x99", "\xC5\x9A" => "\xC5\x9B", "\xC5\x9C" => "\xC5\x9D", "\xC5\x9E" => "\xC5\x9F",
			"\xC5\xA0" => "\xC5\xA1", "\xC5\xA2" => "\xC5\xA3", "\xC5\xA4" => "\xC5\xA5", "\xC5\xA6" => "\xC5\xA7",
			"\xC5\xA8" => "\xC5\xA9", "\xC5\xAA" => "\xC5\xAB", "\xC5\xAC" => "\xC5\xAD", "\xC5\xAE" => "\xC5\xAF",
			"\xC5\xB0" => "\xC5\xB1", "\xC5\xB2" => "\xC5\xB3", "\xC5\xB4" => "\xC5\xB5", "\xC5\xB6" => "\xC5\xB7",
			"\xC5\xB8" => "\xC3\xBF", "\xC5\xB9" => "\xC5\xBA", "\xC5\xBB" => "\xC5\xBC", "\xC5\xBD" => "\xC5\xBE",
			"\xC6\xA0" => "\xC6\xA1", "\xC6\xAF" => "\xC6\xB0", "\xC8\x98" => "\xC8\x99", "\xC8\x9A" => "\xC8\x9B",
			"\xCE\x86" => "\xCE\xAC", "\xCE\x88" => "\xCE\xAD", "\xCE\x89" => "\xCE\xAE", "\xCE\x8A" => "\xCE\xAF",
			"\xCE\x8C" => "\xCF\x8C", "\xCE\x8E" => "\xCF\x8D", "\xCE\x8F" => "\xCF\x8E", "\xCE\x91" => "\xCE\xB1",
			"\xCE\x92" => "\xCE\xB2", "\xCE\x93" => "\xCE\xB3", "\xCE\x94" => "\xCE\xB4", "\xCE\x95" => "\xCE\xB5",
			"\xCE\x96" => "\xCE\xB6", "\xCE\x97" => "\xCE\xB7", "\xCE\x98" => "\xCE\xB8", "\xCE\x99" => "\xCE\xB9",
			"\xCE\x9A" => "\xCE\xBA", "\xCE\x9B" => "\xCE\xBB", "\xCE\x9C" => "\xCE\xBC", "\xCE\x9D" => "\xCE\xBD",
			"\xCE\x9E" => "\xCE\xBE", "\xCE\x9F" => "\xCE\xBF", "\xCE\xA0" => "\xCF\x80", "\xCE\xA1" => "\xCF\x81",
			"\xCE\xA3" => "\xCF\x83", "\xCE\xA4" => "\xCF\x84", "\xCE\xA5" => "\xCF\x85", "\xCE\xA6" => "\xCF\x86",
			"\xCE\xA7" => "\xCF\x87", "\xCE\xA8" => "\xCF\x88", "\xCE\xA9" => "\xCF\x89", "\xCE\xAA" => "\xCF\x8A",
			"\xCE\xAB" => "\xCF\x8B", "\xD0\x81" => "\xD1\x91", "\xD0\x82" => "\xD1\x92", "\xD0\x83" => "\xD1\x93",
			"\xD0\x84" => "\xD1\x94", "\xD0\x85" => "\xD1\x95", "\xD0\x86" => "\xD1\x96", "\xD0\x87" => "\xD1\x97",
			"\xD0\x88" => "\xD1\x98", "\xD0\x89" => "\xD1\x99", "\xD0\x8A" => "\xD1\x9A", "\xD0\x8B" => "\xD1\x9B",
			"\xD0\x8C" => "\xD1\x9C", "\xD0\x8E" => "\xD1\x9E", "\xD0\x8F" => "\xD1\x9F", "\xD0\x90" => "\xD0\xB0",
			"\xD0\x91" => "\xD0\xB1", "\xD0\x92" => "\xD0\xB2", "\xD0\x93" => "\xD0\xB3", "\xD0\x94" => "\xD0\xB4",
			"\xD0\x95" => "\xD0\xB5", "\xD0\x96" => "\xD0\xB6", "\xD0\x97" => "\xD0\xB7", "\xD0\x98" => "\xD0\xB8",
			"\xD0\x99" => "\xD0\xB9", "\xD0\x9A" => "\xD0\xBA", "\xD0\x9B" => "\xD0\xBB", "\xD0\x9C" => "\xD0\xBC",
			"\xD0\x9D" => "\xD0\xBD", "\xD0\x9E" => "\xD0\xBE", "\xD0\x9F" => "\xD0\xBF", "\xD0\xA0" => "\xD1\x80",
			"\xD0\xA1" => "\xD1\x81", "\xD0\xA2" => "\xD1\x82", "\xD0\xA3" => "\xD1\x83", "\xD0\xA4" => "\xD1\x84",
			"\xD0\xA5" => "\xD1\x85", "\xD0\xA6" => "\xD1\x86", "\xD0\xA7" => "\xD1\x87", "\xD0\xA8" => "\xD1\x88",
			"\xD0\xA9" => "\xD1\x89", "\xD0\xAA" => "\xD1\x8A", "\xD0\xAB" => "\xD1\x8B", "\xD0\xAC" => "\xD1\x8C",
			"\xD0\xAD" => "\xD1\x8D", "\xD0\xAE" => "\xD1\x8E", "\xD0\xAF" => "\xD1\x8F", "\xD2\x90" => "\xD2\x91",
			"\xE1\xB8\x82" => "\xE1\xB8\x83", "\xE1\xB8\x8A" => "\xE1\xB8\x8B", "\xE1\xB8\x9E" => "\xE1\xB8\x9F", "\xE1\xB9\x80" => "\xE1\xB9\x81",
			"\xE1\xB9\x96" => "\xE1\xB9\x97", "\xE1\xB9\xA0" => "\xE1\xB9\xA1", "\xE1\xB9\xAA" => "\xE1\xB9\xAB", "\xE1\xBA\x80" => "\xE1\xBA\x81",
			"\xE1\xBA\x82" => "\xE1\xBA\x83", "\xE1\xBA\x84" => "\xE1\xBA\x85", "\xE1\xBB\xB2" => "\xE1\xBB\xB3"
		);

		return strtr(strtolower($string), $utf8_upper_to_lower);
	}

	/**
	* UTF-8 aware alternative to strtoupper
	* Make a string uppercase
	* Note: The concept of a characters "case" only exists is some alphabets
	* such as Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does
	* not exist in the Chinese alphabet, for example. See Unicode Standard
	* Annex #21: Case Mappings
	*
	* @param string
	* @return string string in uppercase
	*/
	public static function utf8_strtoupper($string)
	{
		static $utf8_lower_to_upper = array(
			"\xC3\xA0" => "\xC3\x80", "\xC3\xA1" => "\xC3\x81",
			"\xC3\xA2" => "\xC3\x82", "\xC3\xA3" => "\xC3\x83", "\xC3\xA4" => "\xC3\x84", "\xC3\xA5" => "\xC3\x85",
			"\xC3\xA6" => "\xC3\x86", "\xC3\xA7" => "\xC3\x87", "\xC3\xA8" => "\xC3\x88", "\xC3\xA9" => "\xC3\x89",
			"\xC3\xAA" => "\xC3\x8A", "\xC3\xAB" => "\xC3\x8B", "\xC3\xAC" => "\xC3\x8C", "\xC3\xAD" => "\xC3\x8D",
			"\xC3\xAE" => "\xC3\x8E", "\xC3\xAF" => "\xC3\x8F", "\xC3\xB0" => "\xC3\x90", "\xC3\xB1" => "\xC3\x91",
			"\xC3\xB2" => "\xC3\x92", "\xC3\xB3" => "\xC3\x93", "\xC3\xB4" => "\xC3\x94", "\xC3\xB5" => "\xC3\x95",
			"\xC3\xB6" => "\xC3\x96", "\xC3\xB8" => "\xC3\x98", "\xC3\xB9" => "\xC3\x99", "\xC3\xBA" => "\xC3\x9A",
			"\xC3\xBB" => "\xC3\x9B", "\xC3\xBC" => "\xC3\x9C", "\xC3\xBD" => "\xC3\x9D", "\xC3\xBE" => "\xC3\x9E",
			"\xC3\xBF" => "\xC5\xB8", "\xC4\x81" => "\xC4\x80", "\xC4\x83" => "\xC4\x82", "\xC4\x85" => "\xC4\x84",
			"\xC4\x87" => "\xC4\x86", "\xC4\x89" => "\xC4\x88", "\xC4\x8B" => "\xC4\x8A", "\xC4\x8D" => "\xC4\x8C",
			"\xC4\x8F" => "\xC4\x8E", "\xC4\x91" => "\xC4\x90", "\xC4\x93" => "\xC4\x92", "\xC4\x97" => "\xC4\x96",
			"\xC4\x99" => "\xC4\x98", "\xC4\x9B" => "\xC4\x9A", "\xC4\x9D" => "\xC4\x9C", "\xC4\x9F" => "\xC4\x9E",
			"\xC4\xA1" => "\xC4\xA0", "\xC4\xA3" => "\xC4\xA2", "\xC4\xA5" => "\xC4\xA4", "\xC4\xA7" => "\xC4\xA6",
			"\xC4\xA9" => "\xC4\xA8", "\xC4\xAB" => "\xC4\xAA", "\xC4\xAF" => "\xC4\xAE", "\xC4\xB5" => "\xC4\xB4",
			"\xC4\xB7" => "\xC4\xB6", "\xC4\xBA" => "\xC4\xB9", "\xC4\xBC" => "\xC4\xBB", "\xC4\xBE" => "\xC4\xBD",
			"\xC5\x82" => "\xC5\x81", "\xC5\x84" => "\xC5\x83", "\xC5\x86" => "\xC5\x85", "\xC5\x88" => "\xC5\x87",
			"\xC5\x8B" => "\xC5\x8A", "\xC5\x8D" => "\xC5\x8C", "\xC5\x91" => "\xC5\x90", "\xC5\x95" => "\xC5\x94",
			"\xC5\x97" => "\xC5\x96", "\xC5\x99" => "\xC5\x98", "\xC5\x9B" => "\xC5\x9A", "\xC5\x9D" => "\xC5\x9C",
			"\xC5\x9F" => "\xC5\x9E", "\xC5\xA1" => "\xC5\xA0", "\xC5\xA3" => "\xC5\xA2", "\xC5\xA5" => "\xC5\xA4",
			"\xC5\xA7" => "\xC5\xA6", "\xC5\xA9" => "\xC5\xA8", "\xC5\xAB" => "\xC5\xAA", "\xC5\xAD" => "\xC5\xAC",
			"\xC5\xAF" => "\xC5\xAE", "\xC5\xB1" => "\xC5\xB0", "\xC5\xB3" => "\xC5\xB2", "\xC5\xB5" => "\xC5\xB4",
			"\xC5\xB7" => "\xC5\xB6", "\xC5\xBA" => "\xC5\xB9", "\xC5\xBC" => "\xC5\xBB", "\xC5\xBE" => "\xC5\xBD",
			"\xC6\xA1" => "\xC6\xA0", "\xC6\xB0" => "\xC6\xAF", "\xC8\x99" => "\xC8\x98", "\xC8\x9B" => "\xC8\x9A",
			"\xCE\xAC" => "\xCE\x86", "\xCE\xAD" => "\xCE\x88", "\xCE\xAE" => "\xCE\x89", "\xCE\xAF" => "\xCE\x8A",
			"\xCE\xB1" => "\xCE\x91", "\xCE\xB2" => "\xCE\x92", "\xCE\xB3" => "\xCE\x93", "\xCE\xB4" => "\xCE\x94",
			"\xCE\xB5" => "\xCE\x95", "\xCE\xB6" => "\xCE\x96", "\xCE\xB7" => "\xCE\x97", "\xCE\xB8" => "\xCE\x98",
			"\xCE\xB9" => "\xCE\x99", "\xCE\xBA" => "\xCE\x9A", "\xCE\xBB" => "\xCE\x9B", "\xCE\xBC" => "\xCE\x9C",
			"\xCE\xBD" => "\xCE\x9D", "\xCE\xBE" => "\xCE\x9E", "\xCE\xBF" => "\xCE\x9F", "\xCF\x80" => "\xCE\xA0",
			"\xCF\x81" => "\xCE\xA1", "\xCF\x83" => "\xCE\xA3", "\xCF\x84" => "\xCE\xA4", "\xCF\x85" => "\xCE\xA5",
			"\xCF\x86" => "\xCE\xA6", "\xCF\x87" => "\xCE\xA7", "\xCF\x88" => "\xCE\xA8", "\xCF\x89" => "\xCE\xA9",
			"\xCF\x8A" => "\xCE\xAA", "\xCF\x8B" => "\xCE\xAB", "\xCF\x8C" => "\xCE\x8C", "\xCF\x8D" => "\xCE\x8E",
			"\xCF\x8E" => "\xCE\x8F", "\xD0\xB0" => "\xD0\x90", "\xD0\xB1" => "\xD0\x91", "\xD0\xB2" => "\xD0\x92",
			"\xD0\xB3" => "\xD0\x93", "\xD0\xB4" => "\xD0\x94", "\xD0\xB5" => "\xD0\x95", "\xD0\xB6" => "\xD0\x96",
			"\xD0\xB7" => "\xD0\x97", "\xD0\xB8" => "\xD0\x98", "\xD0\xB9" => "\xD0\x99", "\xD0\xBA" => "\xD0\x9A",
			"\xD0\xBB" => "\xD0\x9B", "\xD0\xBC" => "\xD0\x9C", "\xD0\xBD" => "\xD0\x9D", "\xD0\xBE" => "\xD0\x9E",
			"\xD0\xBF" => "\xD0\x9F", "\xD1\x80" => "\xD0\xA0", "\xD1\x81" => "\xD0\xA1", "\xD1\x82" => "\xD0\xA2",
			"\xD1\x83" => "\xD0\xA3", "\xD1\x84" => "\xD0\xA4", "\xD1\x85" => "\xD0\xA5", "\xD1\x86" => "\xD0\xA6",
			"\xD1\x87" => "\xD0\xA7", "\xD1\x88" => "\xD0\xA8", "\xD1\x89" => "\xD0\xA9", "\xD1\x8A" => "\xD0\xAA",
			"\xD1\x8B" => "\xD0\xAB", "\xD1\x8C" => "\xD0\xAC", "\xD1\x8D" => "\xD0\xAD", "\xD1\x8E" => "\xD0\xAE",
			"\xD1\x8F" => "\xD0\xAF", "\xD1\x91" => "\xD0\x81", "\xD1\x92" => "\xD0\x82", "\xD1\x93" => "\xD0\x83",
			"\xD1\x94" => "\xD0\x84", "\xD1\x95" => "\xD0\x85", "\xD1\x96" => "\xD0\x86", "\xD1\x97" => "\xD0\x87",
			"\xD1\x98" => "\xD0\x88", "\xD1\x99" => "\xD0\x89", "\xD1\x9A" => "\xD0\x8A", "\xD1\x9B" => "\xD0\x8B",
			"\xD1\x9C" => "\xD0\x8C", "\xD1\x9E" => "\xD0\x8E", "\xD1\x9F" => "\xD0\x8F", "\xD2\x91" => "\xD2\x90",
			"\xE1\xB8\x83" => "\xE1\xB8\x82", "\xE1\xB8\x8B" => "\xE1\xB8\x8A", "\xE1\xB8\x9F" => "\xE1\xB8\x9E", "\xE1\xB9\x81" => "\xE1\xB9\x80",
			"\xE1\xB9\x97" => "\xE1\xB9\x96", "\xE1\xB9\xA1" => "\xE1\xB9\xA0", "\xE1\xB9\xAB" => "\xE1\xB9\xAA", "\xE1\xBA\x81" => "\xE1\xBA\x80",
			"\xE1\xBA\x83" => "\xE1\xBA\x82", "\xE1\xBA\x85" => "\xE1\xBA\x84", "\xE1\xBB\xB3" => "\xE1\xBB\xB2"
		);

		return strtr(strtoupper($string), $utf8_lower_to_upper);
	}

}

?>