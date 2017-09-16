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

class BaseData {
	
	/** @var array allocate data in memory to optimization */
	protected $_data = array();
	
	/** @var object|null database instance */
	private $database = null;

	/** @var string table name */
	private $table = "";

	/** @var string table primary key */
	private $primary_key = "";
	
	/**
	* Passing the database instance and table name, with this we can work on the table as well
	* 
	* @param object $database instance of the database
	* @param string $table string with the table name
	*/
	function __construct($database, $table)
	{
		$this->database = $database;
		$this->table = $table;

		if (isset($this->table) && isset($this->database))
		{
			$sql = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'";

			$query = $this->database->QueryArray($sql);

			if ($query["Result"] != "")
			{
				$q = $query["Result"][0];

				$this->primary_key = $q["Column_name"];
			}
		}
	}
	

	/**
	* Load data of the table
	* @param string $primary ID or anything to identify the row
	* @return array
	*/
	public function Load($primary)
	{
		if (isset($this->table) && isset($this->database))
		{
			$query = $this->database->Query("SELECT * FROM ".$this->table." WHERE ".$this->primary_key."='".$primary."'");

			if ($query["Result"] != "")
			{
				$this->_data = $query["Result"];
				return true;
			}
		}

		return false;
	}

	/**
	* Load data of the table by column and value
	* @param array $args columns and values respectively to SELECT condition
	* @return array
	*/
	public function LoadBy($args, $condition = "=")
	{
		if (isset($this->table) && isset($this->database) && is_array($args))
		{

			$sql = "SELECT * FROM ".$this->table." ";
			$where = "";

			foreach($args as $key => $value)
			{
				if ($where == "") $where = "WHERE ";
				else $where .= " AND ";

				$where .= "$key $condition '$value'";
			}

			$sql .= $where;

			$query = $this->database->Query("SELECT * FROM ".$this->table." $where");

			if ($query["Result"] != "")
			{
				$this->_data = $query["Result"];
				return true;
			}
		}

		return false;
	}

	/**
	* Register data to table
	* @param array $array as it says it's an array with columns name as key and value as... value :P
	* @return mixed
	*/
	public function Register($array)
	{
		if (isset($this->table) && isset($this->database))
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
			
			$sql = "INSERT INTO ".$this->table." $columns VALUES $vals";
			
			$result = $this->database->Query($sql);
			
			if ($result["Error"] == "")
			{
				$query = $this->database->Query("SELECT * FROM ".$this->table." WHERE ".$this->primary_key."='".$result["Result"]."'");
				
				$this->_data = $query["Result"];
				
				return $result["Result"];
			}
			else return $result["Error"];
		}
	}
	
	/**
	* Update data to table
	* @param array $except array with columns to be not updated
	* @return bool
	*/
	public function Update($except = array())
	{
		$sql = "UPDATE ".$this->table." SET ";
		$first_row = true;

		foreach($this->_data as $key => $value)
		{
			if ($key != $this->primary_key && !in_array($key, $except) && (is_string($value) || is_int($value)))
			{
				if ($first_row)
				{
					$first_row = false;
					$sql .= $key.'="'.$value.'"';
				}
				else $sql .= ", ".$key.'="'.$value.'"';
			}
		}

		$sql .= " WHERE ".$this->primary_key."='".$this->_data[$this->primary_key]."'";

		$result = $this->database->Query($sql);

		if ($result["Error"] == "") return $result["Result"];
		else return $result["Error"];
	}

	/*------------------------------------------------------------------------------------------------------------------*/
	
	/**
	* Change data from in memory data
	* @param string $key column name
	* @param string $val value
	*/
	public function Change($key, $val)
	{
		$this->_data[$key] = $val;
	}
	
	/**
	* Get data from in memory data loaded
	* @param string $key column name
	* @return array
	*/
	public function Get($key)
	{
		return $this->_data[$key];
	}

	/**
	* Get primary key from the table
	* @return string
	*/
	public function GetPrimary()
	{
		return isset($this->_data[$this->primary_key]) ? $this->_data[$this->primary_key] : null;
	}
}
?>