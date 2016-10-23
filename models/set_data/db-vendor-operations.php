<?php

include_once(MODEL_PATH."db-config.php");


class DB_Vendor
{
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	function add_Vendor($data)
	{
		$vendor_id=$this->get_New_Vendor_Id();
		$vendor_alpha_id="VEND".$vendor_id;
		
		
		$this->con->query("BEGIN TRANSACTION");
		$this->con->autocommit(FALSE);
		
		$query1=0;

		$query1=$this->con->query("insert into vendor_table values ($vendor_id,'$vendor_alpha_id','".$data->vendor_name."','".$data->vendor_website."','".$data->vendor_contact_no."','".$data->vendor_email."',".$data->vendor_panel_size.",'".$data->vendor_description."','".implode(" ; ",$data->vendor_country)."','$data->redirect_complete','$data->redirect_terminate','$data->redirect_quotafull','".$data->vendor_addition_date."','".$data->vendor_modified_date."',0)");
		
		if($query1)
		{
			$this->con->commit();
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'create','New Vendor(ID: $vendor_id) added by User(ID:".$_SESSION["user_id"].", Name:".$_SESSION["user_name"].").')");
			return true;
		}
		else
		{
			$query_rollback=$this->con->query("delete from vendor_table where vendor_id=$vendor_id");
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'create','Unable to add new Vendor by User(ID:".$_SESSION["user_id"].", Name:".$_SESSION["user_name"]."). Due to some error occured.')");
			return false;
		}
	}
	
	function get_New_Vendor_Id()
	{
		$query=$this->con->query("select max(vendor_id) as max_id from vendor_table");
		$id=$query->fetch_array();
		$id=$id["max_id"];
		return ($id+1);
	}
	
	
	
	
	function modify_Vendor($data,$vendor_id)
	{		
		$this->con->query("BEGIN TRANSACTION");
		$this->con->autocommit(FALSE);
		
		$query1=0;

		$query1=$this->con->query("update vendor_table set vendor_name='".$data->vendor_name."', vendor_website='".$data->vendor_website."', vendor_contact_no='".$data->vendor_contact_no."', vendor_email_id='".$data->vendor_email."', vendor_panel_size=".$data->vendor_panel_size.", vendor_description='".$data->vendor_description."', vendor_country='".implode(" ; ",$data->vendor_country)."', redirect_complete='".$data->redirect_complete."',redirect_terminate='".$data->redirect_terminate."', redirect_quotafull='".$data->redirect_quotafull."', modified_date='".time()."' where vendor_id =".$vendor_id);
		
		if($query1)
		{
			$this->con->commit();
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'update','Vendor(ID: $vendor_id) details updated by User(ID:".$_SESSION["user_id"].", Name:".$_SESSION["user_name"].").')");
			return true;
		}
		else
		{
			$this->con->query("ROLLBACK");
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'update','Vendor(ID: $vendor_id) details cannot be updated by User(ID:".$_SESSION["user_id"].", Name:".$_SESSION["user_name"]."). Due to some error occured.')");
			return false;
		}
	}
	
	//Function to remove vendor
	function db_Remove_Vendor($vendor_id)
	{
		$query=$this->con->query("update vendor_table set removed=1 where vendor_id=$vendor_id");
		if($query)
		{
			return true;
		}
		return false;
	}
	
}

?>