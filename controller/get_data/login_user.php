<?php
session_start();
ini_set("session.gc_maxlifetime",14400);


define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");


include_once(MODEL_PATH."get_data/db-login-user.php");

class User_Login
{
	private $username="";
	private $password="";
	
	function __construct($reg_fields) 
	{
		$this->username=$reg_fields["username"];
		$this->password=$reg_fields["password"];
    }
	
	
	function get_Data()
	{
		return array(
				"username"=>$this->username,
				"password"=>$this->password);
	}
}



if(isset($_POST) && isset($_POST["username"]) && isset($_POST["password"]))
{
	
	$login_user=new User_Login($_POST);
	$user_data=$login_user->get_Data();
	
	$user_logged_in=new DB_Login_User();
	$user_logged_status=$user_logged_in->login_User($user_data);
	
	if($user_logged_status=="sucess")
	{
		if($_SESSION["user_type"]=="user")
		{
			header("Location: ".VIEW_PATH."user_dashboard.php?page=1");
			exit;
		}
		else if($_SESSION["user_type"]=="admin")
		{
			header("Location: ".VIEW_PATH."admin/admin_dashboard.php");
			exit;
		}
		
	}
	else
	{
		header("Location: ".VIEW_PATH."index.php?user_login_err=".$user_logged_status);
		exit;
	}
	
	
	
	
}
else
{
	header("Location: ".VIEW_PATH."index.php");
	exit;
}
?>