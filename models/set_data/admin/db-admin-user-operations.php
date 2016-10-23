<?php

include_once(MODEL_PATH."db-config.php");


class Admin_DB_User
{
	
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	//Function to change/modify the Password of a user
	function db_Modify_User_Password($user_id,$password)
	{
		$query=$this->con->query("update user_table set password='$password' where user_id=$user_id");
		if($this->con->affected_rows>0)
		{
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'update','User password updated, with user ID:$user_id')");
			return true;
		}
		$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'error','User password unable to update due to some error, with user ID:$user_id')");
		return false;
	}
	
	//Function to deactivate the user account and store the corresponding reason for it.
	function db_Delete_User_Account($user_id,$reason)
	{
		$this->con->query("BEGIN TRANSACTION");
		$this->con->autocommit(FALSE);
		
		$query=$this->con->query("update user_table set removed_account=1, delete_reason='$reason' where user_id=$user_id");
		
		if($query)
		{
			$this->con->commit();
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'delete','User account removed with User ID: $user_id')");
			return true;
		}
		else
		{
			$this->con->query("ROLLBACK");
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'delete','Unable to remove the user account due to some error with User ID: $user_id')");
			return false;
		}
	}
	
	
	
	//Function to Recover User Account
	function db_Recover_User_Account($user_id)
	{
		$query=$this->con->query("update user_table set removed_account=0, delete_reason='' where user_id=$user_id");
		if($query)
		{
			return true;
		}
		return false;
	}
	
	
	
	//Function to modify user detials
	function db_Modify_User_Details($user_detail)
	{
		$query=$this->con->query("update user_table set name='".$user_detail["name"]."', contact_no='".$user_detail["contact"]."' where user_id=".$user_detail["user_id"]);
		if($query)
		{
			return true;
		}
		return false;
	}

}

?>