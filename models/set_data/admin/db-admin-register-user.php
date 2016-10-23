<?php

include_once(MODEL_PATH."db-config.php");


class DB_Register_User
{
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	function validate_Data($data)
	{
		$query=$this->con->query("select * from user_table");
		while($row=$query->fetch_array())
		{
			if($data["username"]==$row["username"])
			{
				return "username_exist";
			}
			if($data["email"]==$row["email_id"])
			{
				return "email_exist";
			}
		}
		return "no_error";
	}
	
	function get_New_User_Id()
	{
		$query=$this->con->query("select max(user_id) as max_id from user_table");
		$id=$query->fetch_array();
		$id=$id["max_id"];
		return ($id+1);
	}
	
	function create_User($data)
	{
		$user_id=$this->get_New_User_Id();
		$this->con->query("BEGIN TRANSACTION");
		$this->con->autocommit(FALSE);
		
		$query=$this->con->query("insert into user_table values ($user_id,'".$data["username"]."','".$data["password"]."','".$data["name"]."','".$data["email"]."','".$data["contact"]."',0,'".$data["creation_date"]."','".$data["modified_date"]."','".$data["account_type"]."',0,null)");
				
		if($query)
		{
			
			$this->con->commit();
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'create','A new user has been created with ID:$user_id')");
			return true;
		}
		else
		{
			$this->con->query("ROLLBACK");
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'error','New user cannot be created with ID:$user_id, because of some error!')");
			return false;
		}
		
	}
}

?>