<?php
/*define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");*/



include_once(MODEL_PATH."get_data/admin/db-admin-user-operations.php");

class Admin_Users
{	
	function get_List_Of_Users()
	{
		$users=new Admin_DB_Users();
		return $users->db_Get_List_Of_Users();
	}
	
	
	function get_User_Details($user_id)
	{
		$user_data=new Admin_DB_Users();
		return $user_data->db_Get_User_Details($user_id);
	}
	
	
	function get_List_Of_Removed_Users()
	{
		$users=new Admin_DB_Users();
		return $users->db_Get_List_Of_Removed_Users();
	}
	
	
}





?>
