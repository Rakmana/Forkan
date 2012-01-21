<?php

include_once(PSYS.'dbase.php');

class ForkanData
{
	public $todo_id;
	public $title;
	public $description;
	public $due_date;
	public $is_done;


/********************************[ Ayas functions ]************************************/
    /**
     * Get aya info as array
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return array [index,sura,aya,text,riwaya]
     */
    public function getAya($ayaID,$riwayaID = '1'){
        //index  sura  aya  text  riwaya  view  
        $y = dbase::jfetch("SELECT q.* FROM quran q WHERE q.index = $ayaID and q.riwaya = $riwayaID");
        
        return $y;
       
    }
    /**
     * Get translated Aya info as array
     * 
     * @param mixed $ayaID
     * @param string $riwayaID
     * @return array [index,sura,aya,text,riwaya]
     */
    public static function getAyaTr($suraID,$ayaID,$transID = '1'){
        //index  sura  aya  text  TransID  view 
        $y = dbase::jfetch("SELECT q.* FROM translations q WHERE q.sura = $suraID AND aya=$ayaID and q.transID = $transID");
        
        return $y;
       
    }    
    /**
     * Get Aya index from SuraID+AyaPos
     * 
     * @param mixed $sura
     * @param mixed $aya
     * @return void
     */
    public static function getAyaIndex($sura,$aya,$riwayaID = '1'){
        $y = dbase::jfetch("SELECT q.index FROM quran q WHERE q.sura = $sura AND q.aya = $aya and q.riwaya = $riwayaID");

        return $y['index'];        
    }
    /**
     * Get SuraID+AyaPos from Aya index
     * 
     * @param mixed $sura
     * @param mixed $aya
     * @return void
     */
    public static function getAyaFromIndex($index,$riwayaID = '1'){
        $y = dbase::jfetch("SELECT q.sura,q.aya FROM quran q WHERE q.index = $index and q.riwaya = $riwayaID");

        return $y;        
    }    
	
	public static function save($username, $userpass)
	{
		$userhash = sha1("{$username}_{$userpass}");
		if( is_dir(DATA_PATH."/{$userhash}") === false ) {
			mkdir(DATA_PATH."/{$userhash}");
		}
		
		//if the $todo_id isn't set yet, it means we need to create a new todo item
		if( is_null($this->todo_id) || !is_numeric($this->todo_id) ) {
			//the todo id is the current time
			$this->todo_id = time();
		}
		
		//get the array version of this todo item
		$todo_item_array = $this->toArray();
		
		//save the serialized array version into a file
		$success = file_put_contents(DATA_PATH."/{$userhash}/{$this->todo_id}.txt", serialize($todo_item_array));
		
		//if saving was not successful, throw an exception
		if( $success === false ) {
			throw new Exception('Failed to save todo item');
		}
		
		//return the array version
		return $todo_item_array;
	}
	
	public function toArray()
	{
		//return an array version of the todo item
		return array(
			'todo_id' => $this->todo_id,
			'title' => $this->title,
			'description' => $this->description,
			'due_date' => $this->due_date,
			'is_done' => $this->is_done
		);
	}
	
	private static function _checkIfUserExists($username, $userpass)
	{
		/*$userhash = sha1("{$username}_{$userpass}");
		if( is_dir(DATA_PATH."/{$userhash}") === false ) {
			throw new Exception('Username  or Password is invalid');
		}*/
		return true;
	}
}