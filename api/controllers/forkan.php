<?php

//include our models
include_once 'models/forkan.php';

class Forkan
{
	private $_params;
	
	public function __construct($params)
	{
		$this->_params = $params;
	}
	
	public function readAction()
	{
		//read all the todo items while passing the username and password to authenticate
		$items = ForkanData::getAya($this->_params['ayaID'],7);
		//var_dump($items);
		//return the list
		return  (array)$items;
	}

	
	public function deleteAction()
	{
		//delete a todo item
		//retrieve the todo item first
		$todo = TodoItem::getItem($this->_params['todo_id'], $this->_params['username'], $this->_params['userpass']);
		
		//delete the TODO item while passing the username and password to authenticate
		$todo->delete($this->_params['username'], $this->_params['userpass']);
		
		//return the deleted todo item
		//in array format, for display purposes
		return $todo->toArray();
	}
}

?>