<?php

include_once(MODEL_PATH."db-config.php");


class Admin_DB_Users
{
	
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	
	function db_Get_List_Of_Users()
	{
		$query=$this->con->query("select * from user_table where account_type='user' AND removed_account=0");
		return $query;
	}
	
	function db_Get_User_Details($user_id)
	{
		$query=$this->con->query("select * from user_table where account_type='user' AND user_id=$user_id");
		$row=$query->fetch_array();
		return $row;
	}
	
	
	function db_Get_List_Of_Removed_Users()
	{
		$query=$this->con->query("select * from user_table where account_type='user' AND removed_account=1");
		return $query;
	}
	
}

?>