<?php

require_once(MODEL_PATH."db-config.php");

class DB_User_Operations
{
	
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	//Function to change User password
	function db_Change_Password($new_password,$user_id)
	{
		$query=$this->con->query("update user_table set password='$new_password' where user_id=$user_id");
		if($query)
		{
			return true;
		}
		return false;
	}
}



?>