<?php
class User extends BaseData {

	private $database = false;
	
	function __construct($array = array())
	{
		$this->database = new Database();
		
		parent::__construct($this->database);
		
		//Change here for the table name
		$this->_table = "Users";
		
		//Don't change this one!
		$this->_data = $array;
	}
}
?>