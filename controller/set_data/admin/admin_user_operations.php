<?php
session_start();
define("CONTROLLER_PATH","../../../controller/");
define("MODEL_PATH","../../../models/");
define("VIEW_PATH","../../../views/");
define("ASSETS_PATH","../../../assets/");
define("INCLUDES_PATH","../../../includes/");


include_once(MODEL_PATH."set_data/admin/db-admin-user-operations.php");

class Admin_User
{
	//Function to change/modify the Password of a user
	function modify_User_Password($user_id,$password)
	{
		$password=$this->encrypt_decrypt("encrypt",$password);
		$change_pass=new Admin_DB_User();
		return $change_pass->db_Modify_User_Password($user_id,$password);
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
	
	
	//Function to deactivate the user account and store the corresponding reason for it.
	function delete_User_Account($user_id,$reason)
	{
		$delete_user=new Admin_DB_User();
		return $delete_user->db_Delete_User_Account($user_id,$reason);
	}
	
	
	
	//Function to recover User
	function recover_User_Account($user_id)
	{
		$recover_user=new Admin_DB_User();
		return $recover_user->db_Recover_User_Account($user_id);
	}
	
	
	
	//Function to modify User
	function modify_User_Details($user_detail)
	{
		$modify_user=new Admin_DB_User();
		return $modify_user->db_Modify_User_Details($user_detail);
	}
}


if(isset($_POST) && isset($_POST["change_password"]) )
{
	if($_POST["new_password"]!=$_POST["confirm_password"])
	{
		header("Location: ".VIEW_PATH."admin/admin_change_password.php?user_id=".$_POST["user_id"]."&change_pass_error=pass_confirm");
		exit;
	}
	
	$change_pass=new Admin_User();
	$change_pass=$change_pass->modify_User_Password($_POST["user_id"],$_POST["new_password"]);
	if($change_pass)
	{
		header("Location: ".VIEW_PATH."admin/admin_change_password.php?user_id=".$_POST["user_id"]."&change_pass_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."admin/admin_change_password.php?user_id=".$_POST["user_id"]."&change_pass_result=error");
		exit;
	}
}


if(isset($_POST) && isset($_POST["change_admin_password"]) )
{
	if($_POST["new_password"]!=$_POST["confirm_password"])
	{
		header("Location: ".VIEW_PATH."admin/change_own_password.php?user_id=".$_POST["user_id"]."&change_pass_error=pass_confirm");
		exit;
	}
	
	$change_pass=new Admin_User();
	$change_pass=$change_pass->modify_User_Password($_POST["user_id"],$_POST["new_password"]);
	if($change_pass)
	{
		header("Location: ".VIEW_PATH."admin/change_own_password.php?user_id=".$_POST["user_id"]."&change_pass_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."admin/change_own_password.php?user_id=".$_POST["user_id"]."&change_pass_result=error");
		exit;
	}
}


if(isset($_POST) && isset($_POST["delete_user_account"]) )
{	
	$delete_account=new Admin_User();
	$delete_account=$delete_account->delete_User_Account($_POST["user_id"],$_POST["reason"]);
	
	if($delete_account)
	{
		header("Location: ".VIEW_PATH."admin/admin_user_account_delete.php?user_id=".$_POST["user_id"]."&user_account_remove_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."admin/admin_user_account_delete.php?user_id=".$_POST["user_id"]."&user_account_remove_result=error");
		exit;
	}
}




if(isset($_POST) && isset($_POST["recover_user"]))
{
	$recover_user=new Admin_User();
	$recover_user=$recover_user->recover_User_Account($_POST["user_id"]);
	if($recover_user)
	{
		header("Location: ".VIEW_PATH."admin/admin_recover_user.php?user_account_recover_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."admin/admin_recover_user.php?user_account_recover_result=error");
		exit;
	}
}

if(isset($_POST) && isset($_POST["modify_user_details"]))
{
	$modify_user=new Admin_User();
	$modify_user=$modify_user->modify_User_Details($_POST);
	if($modify_user)
	{
		header("Location: ".VIEW_PATH."admin/admin_edit_user_details.php?user_id=".$_POST["user_id"]."&user_edit_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."admin/admin_edit_user_details.php?user_id=".$_POST["user_id"]."&user_edit_result=error");
		exit;
	}
}

?>