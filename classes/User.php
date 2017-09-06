<?php
/**
 * Basic Simple Module
 * ------------------------------------
 * config.php
 *
 * API configuration file
 * 
 * @author George Carvalho
 */

class User extends BaseData {

	private $database = false;
	private $table = "";
	
	function __construct($array = array())
	{
		$this->database = new Database();
		
		//Please inform your table name here
		$this->table = "user";

		parent::__construct($this->database, $this->table);
		
		//Don't change here!
		$this->_data = $array;
	}
}
?>