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

class Database {
	
	private $db = false;
	private $result = array("Error" => "", "Result" => "");
	
	/**
	* Create database connection
	*/
	function __construct()
	{
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$mysqli->connect_error)
		{
			$this->db = $mysqli;
			$this->db->set_charset("utf8");
		}
	}
	
	/**
	* Query unique data
	* @param string $sql query string
	* @return array
	*/
	public function Query($sql)
	{
		$this->result = array("Error" => "", "Result" => "");
		
		if (strlen($sql) > 4 && $this->db)
		{
			$query = $this->db->query($sql);
			
			if (!$query) $this->result["Error"] = $this->db->error == null ? $this->db->errno : $this->db->error;
			else {
				$str = explode(" ", $sql);
				
				if (strtolower($str[0]) == "insert") $this->result["Result"] = $this->db->insert_id;
				else if (strtolower($str[0]) == "update" || strtolower($str[0]) == "delete")
				{
					$this->result["Result"] = true;
					$this->result["AffectedRows"] = $this->db->affected_rows;
				}
				else {
					if ($query->num_rows > 0)
					{
						$this->result["Result"] = $query->fetch_assoc();
					}
				}
			}
		}
		else{
			$this->result["Error"] = "Invalid sql statment!";
			$query->free_result();
		} 
		
		return $this->result;
	}
	
	/**
	* Query multiple data
	* @param string $sql query string
	* @return array
	*/
	public function QueryArray($sql)
	{
		$this->result = array("Error" => "", "Result" => "");
		
		$query = $this->db->query($sql);
		
		if (!$query) $this->result["Error"] = $this->db->error == null ? $this->db->errno : $this->db->error;
		else {
			
			while($row = $query->fetch_assoc())
			{
				$this->result["Result"][] = $row;
			}
			
			//$this->result["Result"] = $query->fetch_array();
			$this->result["Count"] = $query->num_rows;
			$query->free_result();
		}
		
		return $this->result;
	}
	
	/**
	* Escape string
	* @param string $string to be escaped
	* @return string
	*/
	public function EscapeString($string)
	{
		return $this->db->real_escape_string($string);
	}
	
	/**
	* Get last error occurred
	* @return string
	*/
	public function LastError()
	{
		return $this->db->error;
	}
	
	public function BeginTrans()
	{
		//$this->db->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		$this->db->autocommit(FALSE);
	}
	
	public function SaveChanges()
	{
		$this->db->commit();
	}
}
?>