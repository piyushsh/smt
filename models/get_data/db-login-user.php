<?php

include_once(MODEL_PATH."db-config.php");


class DB_Login_User
{	
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	function login_User($data)
	{				
		$query=$this->con->query("select * from user_table where removed_account=0 AND username = '".$data["username"]."'");

        if($query->num_rows > 0)
        {
            $row = $query->fetch_array();
            if($data["password"] == ($this->encrypt_decrypt("decrypt",$row["password"])))
            {
                $this->set_SESSION_Fields($row);
                return "sucess";
            }
        }
        else
        {
            return "user_not_found";
        }

        //Old Code
//        {
//            while($row=$query->fetch_array())
//		{
//			if($data["username"]==$row["username"])
//			{
//				if($data["password"]==($this->encrypt_decrypt("decrypt",$row["password"])))
//				{
//					$this->set_SESSION_Fields($row);
//					return "sucess";
//				}
//				return "pass_incorrect";
//			}
//		}
//
//		return "user_not_found";
//        }

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
	function set_SESSION_Fields($row)
	{
		$_SESSION["user_id"]=$row["user_id"];
		$_SESSION["user_name"]=$row["name"];
		$_SESSION["user_type"]=$row["account_type"];
	}
}

?>