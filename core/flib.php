<?php
/**
 * WAPL File Library 
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . n-Teek
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: flib.php,v2.0 06:53 09/04/2011 NP++ Exp $
*/



/**
* FLIB
* WAPL File & Folder Managment class 
* Require ...
*
* @package  WAPL
*/
class flib{
	var $description = 'System Files & Folders Functions Class';
	/**
	* Copy File with overwrite option. 
	*
	* @access public
	* @param string		$source		Source File Path
	* @param string		$destination Target File Path
	* @param bool		$overwrite If target exist decide action
	*/
	public static function copy($source,$destination,$overwrite=false){
		if( $overwrite && flib::exists($destination) )
			return false;
		return copy($source,$destination);
	}
	/**
	* Move File with overwrite option. 
	*
	* @access public
	* @param string		$source		Source File Path
	* @param string		$destination Target File Path
	* @param bool		$overwrite If target exist decide action
	*/
public static function move($source,$destination,$overwrite=false){
		//Possible to use rename()
		if( flib::copy($source,$destination,$overwrite) && flib::exists($destination) ){
			flib::delete($source);
			return true;
		} else {
			return false;
		}
	}
public static function delete($file,$recursive=false){
		$file = str_replace('\\','/',$file); //for win32, occasional problems deleteing files otherwise
		if( flib::is_file($file) )
			return @unlink($file);
		if( !$recursive && flib::is_dir($file) )
			return @rmdir($file);
		$filelist = flib::dirlist($file);
		if( ! $filelist )
			return true; //No files exist, Say we've deleted them
		$retval = true;
		foreach($filelist as $filename=>$fileinfo){
			if( ! flib::delete($file.'/'.$filename,$recursive) )
				$retval = false;
		}
		if( ! @rmdir($file) )
			return false;
		return $retval;
	}
public static function exists($file){
		return @file_exists($file);
	}
public static function is_file($file){
		return @is_file($file);
	}
public static function is_dir($path){
		return @is_dir($path);
	}
public static function is_readable($file){
			return @is_readable($file);
	}
public static function is_writable($file){
		return @is_writable($file);
	}
public static function infomp3($file){
	/*Array
	(
	   [copyright] => Dirty Mac
	   [originalArtist] => Dirty Mac
	   [composer] => Marcus G?tze
	   [artist] => Dirty Mac
	   [title] => Little Big Man
	   [album] => Demo-Tape
	   [track] => 5/12
	   [genre] => (17)Rock
	   [year] => 2001
	)*/
	$i = function_exists(id3_get_tag)? @id3_get_tag($file, ID3_V2_3 ) : "ID3 Not Enabled";
	$i ='<b>{_title}: </b>'.$i[title].'<br />
			<b>{_artist}: </b>'.$i[artist].'<br />
			<b>{_album}: </b>'.$i[album].'<br />
			<b>{_track}: </b>'.$i[track].'<br />
			<b>{_genre}: </b>'.$i[genre].'<br />';
	$i = KTemplate::replace($i);
return $i;
}
public static function infoimg($file){
	/*($width, $height, $type, $attr)*/
	$file = flib::tohtml($file);
	list($w, $h, $t, $p) = @getimagesize($file) or die('Cannot Find Informations');

	//write infos
	$ret['width'] = $w;
	$ret['height'] = $h;
	$ret['type'] =image_type_to_mime_type(exif_imagetype($file));

return $ret;
}
public static function size($size){
	if ($size >= 1073741824){ $size = round($size / 1073741824 * 100) / 100 .' '. txt('gb'); }
	elseif ($size >= 1048576){ $size = round($size / 1048576 * 100) / 100 .' '. txt('mb'); }
	elseif ($size >= 1024){	$size = round($size / 1024 * 100) / 100 .' '. txt('kb'); }
	elseif ($size > 0){ $size = $size .' '. txt('byte'); }
	elseif ($size <= 0){ $size = txt('empty'); }
	else   {$size = txt('unknown') ; }
 return $size;
}
public static function dsize($idir){
	if (strrchr($idir, "/")!='/') $idir .= '/';
	$size = 0;
  $handle=opendir($idir);
  while ($rep = readdir($handle)) {
  	if ($rep != "." && $rep != "..") {
		if (flib::is_dir($idir.$rep)){
			$folder[]=$rep;//we dont need it
			$size += flib::dsize($idir.$rep);}
		else{
			$size += filesize($idir.$rep);
		}
	}
	
}
closedir($handle);
 return $size;
}
public static function fsize($fname){//return file size in B, KB , MB or GB
	$size = filesize($fname);
	return flib::size($size);
}
public static function fileslist ($currentdir, $startdir=NULL,$ext=NULL) {
  global $pathchar;
  //chdir ($currentdir);
  // remember where we started from
  if (!$startdir)
  {
      $startdir = $currentdir;
  }
  $d = @opendir($currentdir);
  $files = array();
  if(!$d)
    return $files;
  //list the files in the dir
  while (false !== ($file = readdir($d)))
  {
      if ($file != ".." && $file != ".")
      {
          if (flib::is_dir($currentdir.DS.$file))
          {
              // If $file is a directory take a look inside
              $a = flib::fileslist ($currentdir.DS.$file, $startdir,$ext);
              if(is_array($a))
                $files = array_merge($files,$a);
          }
          else
          {
              if($ext!=NULL)
              {
                if(is_array($ext)){
                    
                $fext = flib::extension($file);
                if (in_array($fext ,$ext))
                    $files[] = $currentdir.DS.$file;
                
                }
                else{
                  $extstr = stristr($file,".".$ext);
                  if(strlen($extstr))
                      $files[] = $currentdir.DS.$file;
                };
              }
              else
                $files[] = $currentdir.DS.$file;
          }
      }
  }
  closedir ($d);
  return $files;
}
public static function dirslist ($currentdir, $startdir=NULL) {
  global $pathchar;
  //chdir ($currentdir);
  // remember where we started from
  if (!$startdir)
  {
      $startdir = $currentdir;
  }
  $d = @opendir($currentdir);
  $dirs = array();
  if(!$d)
    return $dirs;
  //list the dirs in the dir
  while (false !== ($dir = readdir($d)))
  {
      if ($dir != ".." && $dir != ".")
      {
          if (flib::is_dir($currentdir.DS.$dir))
          {
              $dirs[] = $dir;
          }
          else
          {
          }
      }
  }
  closedir ($d);
  return $dirs;
}
public static function extension($file) {
	$file_type = explode(".", $file);
	$ext = mb_strtolower($file_type[(count($file_type)-1)]);
return $ext;
}
public static function get_contents($file){
	//return @file_get_contents($file);
	$fp =  fopen($file, 'r');
	$ret = fread($fp, filesize($file));
	fclose($fp);
	return $ret;
}
public static function get_contents_array($file){
		return @file($file);
	}
public static function put_contents($file,$contents,$type=''){
		$fp=@fopen($file,'w'.$type);
		if (!$fp)
			return false;
		@fwrite($fp,$contents);
		@fclose($fp);
		return true;
	}
public static function getchmod($file){
		return @fileperms($file);
	}
public static function getnumchmodfromh($mode) {
		$realmode = "";
		$legal =  array("","w","r","x","-");
		$attarray = preg_split("//",$mode);
		for($i=0;$i<count($attarray);$i++){
		   if($key = array_search($attarray[$i],$legal)){
			   $realmode .= $legal[$key];
		   }
		}
		$mode = str_pad($realmode,9,'-');
		$trans = array('-'=>'0','r'=>'4','w'=>'2','x'=>'1');
		$mode = strtr($mode,$trans);
		$newmode = '';
		$newmode .= $mode[0]+$mode[1]+$mode[2];
		$newmode .= $mode[3]+$mode[4]+$mode[5];
		$newmode .= $mode[6]+$mode[7]+$mode[8];
		return $newmode;
	}
}
?>