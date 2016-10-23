<?php

define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");

require_once(MODEL_PATH."set_data/db-user-operations.php");

class User_Operations
{
	//Function to change User password
	function change_Password($new_password,$user_id)
	{
		$password=$this->encrypt_decrypt("encrypt",$new_password);
		$change_pass=new DB_User_Operations();
		return $change_pass->db_Change_Password($password,$user_id);
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
}



if(isset($_POST) && isset($_POST["change_password"]))
{
	$change_pass=new User_Operations();
	$change_pass=$change_pass->change_Password($_POST["new_password"],$_POST["user_id"]);
	
	if($_POST["new_password"]!=$_POST["confirm_password"])
	{
		header("Location: ".VIEW_PATH."change_password.php?change_pass_error=pass_confirm");
		exit;
	}
	
	if($change_pass)
	{
		header("Location: ".VIEW_PATH."change_password.php?change_pass_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."change_password.php?change_pass_result=error");
		exit;
	}
}

?>