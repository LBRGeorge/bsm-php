<?php
class BaseData {
	
	protected $_table = "";
	protected $_data = array();
	
	private $database = null;
	
	function __construct($database)
	{
		$this->database = $database;
	}
	
	public function Register($array)
	{
		if (isset($this->_table) && isset($this->database))
		{
			$columns = "";
			$vals = "";
			
			foreach($array as $key => $value)
			{
				$str = $this->database->EscapeString($value);
				
				if ($columns == "") $columns = "($key";
				else $columns .= ", $key";
				
				if ($vals == "") $vals = "('$str'";
				else $vals .= ", '$str'";
			}
			
			$columns .= ")";
			$vals .= ")";
			
			$sql = "INSERT INTO ".$this->_table." $columns VALUES $vals";
			
			$result = $this->database->Query($sql);
			
			if ($result["Error"] == "")
			{
				$query = $this->database->Query("SELECT * FROM ".$this->_table." WHERE id='".$result["Result"]."'");
				
				$this->_data = $query["Result"];
				
				return $result["Result"];
			}
			else return $result["Error"];
		}
	}
	
	public function Update($except = array())
	{
		$sql = "UPDATE ".$this->_table." SET ";
		$first_row = true;

		foreach($this->_data as $key => $value)
		{
			if ($key != "id" && !in_array($key, $except) && (is_string($value) || is_int($value)))
			{
				if ($first_row)
				{
					$first_row = false;
					$sql .= $key.'="'.$value.'"';
				}
				else $sql .= ", ".$key.'="'.$value.'"';
			}
		}

		$sql .= " WHERE id='".$this->_data["id"]."'";

		$result = $this->database->Query($sql);

		if ($result["Error"] == "") return $result["Result"];
		else return $result["Error"];
	}
	
	public function Change($key, $val)
	{
		$this->_data[$key] = $val;
	}
	
	public function Get($key)
	{
		return $this->_data[$key];
	}
}
?>