<?php
session_start();
define("CONTROLLER_PATH","../../../controller/");
define("MODEL_PATH","../../../models/");
define("VIEW_PATH","../../../views/");
define("ASSETS_PATH","../../../assets/");
define("INCLUDES_PATH","../../../includes/");


include_once(MODEL_PATH."set_data/admin/db-admin-register-user.php");

class User_Registration
{
	public $name="";
	public $email="";
	public $contact="";
	public $creation_date;
	public $modified_date;
	private $username="";
	private $password="";
	private $confirm_pass="";
	private $account_type="user";
	
	function __construct($reg_fields) 
	{
		$this->name=$reg_fields["full_name"];
		$this->email=$reg_fields["email"];
		$this->contact=$reg_fields["contact_no"];
		$this->username=$reg_fields["sign_in_username"];
		$this->password=$this->encrypt_decrypt("encrypt",$reg_fields["sign_in_password"]);
		$this->confirm_pass=$reg_fields["sign_in_confirm_pass"];
		$this->account_type="user";
		$this->creation_date=time();
		$this->modified_date=time();
    }
	
	function encrypt_decrypt($operation,$data,$key="nexton")
	{
		$key=hash('md5',$key,TRUE);
		$iv=mcrypt_create_iv(32);
		if($operation=="encrypt")
		{
			return 	base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$data, MCRYPT_MODE_ECB, $iv));
		}
		else if($operation=="decrypt")
		{
			return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, $iv));
		}
	}
	
	function get_Data()
	{
		return array(
				"name"=>$this->name,
				"email"=>$this->email,
				"contact"=>$this->contact,
				"username"=>$this->username,
				"password"=>$this->password,
				"account_type"=>$this->account_type,
				"creation_date"=>$this->creation_date,
				"modified_date"=>$this->modified_date);
	}
}



if(isset($_POST))
{
	
	if($_POST["sign_in_password"]!=$_POST["sign_in_confirm_pass"])
	{
		header("Location: ".VIEW_PATH."admin/admin_dashboard.php?create_user_err=confirm_pass");
		exit;
	}
	
	$new_user=new User_Registration($_POST);
	$user_data=$new_user->get_Data();
	
	if(!isset($_SESSION["admin_create_user"]))
	{
		$_SESSION["admin_create_user"]=$user_data;
	}
	
	
	$create_user=new DB_Register_User();
	
	$validate_check=$create_user->validate_Data($user_data);	
	if($validate_check != "no_error")
	{
		header("Location: ".VIEW_PATH."admin/admin_dashboard.php?create_user_err=".$validate_check);
		exit;
	}
	
	if($create_user->create_User($user_data))
	{
		unset($_SESSION["admin_create_user"]);
		header("Location: ".VIEW_PATH."admin/admin_dashboard.php?create_user_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."admin/admin_dashboard.php?create_user_result=failed");
		exit;
	}
	
	
	
	
}
else
{
	header("Location: ".VIEW_PATH."index.php");
	exit;
}
?>