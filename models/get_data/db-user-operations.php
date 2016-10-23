<?php

include_once(MODEL_PATH."db-config.php");


class DB_Users
{
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	//Function to get the list of All Managers
	function db_Get_All_Users_Managers()
	{
		$query=$this->con->query("select * from user_table where account_type='user'");
		return $query;
	}
	
}

?>