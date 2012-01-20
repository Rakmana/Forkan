<?php


$GLOBALS['BT'] = '';

function btrack($msg="TRACKER",$show=false){

  static $start_time = NULL;
  static $start_time0 = null;
  
  ob_start();
  
  $call_info = @array_shift( debug_backtrace() );
  $code_line = $call_info['line'];
  $file = @basename(array_pop( explode('/', $call_info['file'])));
  if( $start_time === NULL ){
      echo "TRACKER ".$file."> initialize\n";
      $start_time = time() + microtime();
      $start_time0 = $start_time;
      $GLOBALS['start_time0'] = $start_time0;
	  
	$output = ob_get_contents();
	ob_end_clean();
	$GLOBALS['BT'] .= $output;
      return 0;
  }
 if($show != false){
	printf("TCK: %.4f\ - %d KB - %s - %s - @%d\n", (time() + microtime() - $start_time0), ceil( memory_get_usage()/1024), "All Done.", $file, $code_line);
  
	$output = ob_get_contents();
	ob_end_clean();
	$GLOBALS['BT'] .= $output;
		
	return $GLOBALS['BT'];
}else{
	printf("TCK: %.4f\ - %d KB - %s - %s - @%d\n", (time() + microtime() - $start_time), ceil( memory_get_usage()/1024), $msg, $file, $code_line);
	$start_time = time() + microtime();
  
	$output = ob_get_contents();
	ob_end_clean();
	$GLOBALS['BT'] .= $output;
}
}

function kjxProfiler(){
  static $start_time0;
  
	ob_start();
  	printf("TCK: %.4f\ - %d KB - %s", (time()+microtime()-$GLOBALS['start_time0']), ceil( memory_get_usage()/1024), "KJX");

	$output = ob_get_contents();
	ob_end_clean();
	
return $output;
}

function appendProfiler(){
	/*/////////////////////*/
	echo '<span class="btn b-time tipy" title="Show/hide Profiler" onclick="showhide(\'consoleDIV\');"></span>
		<div class="console" id="consoleDIV" style="display:none"><pre id="console">'.btrack('End',true).'<hr /></pre></div>';
			
}

?>