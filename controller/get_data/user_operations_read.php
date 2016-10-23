<?php
/*define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");*/

include_once(MODEL_PATH."get_data/db-user-operations.php");

class Users
{	

	//Function to get the list of All Managers
	function get_All_Users_Managers()
	{
		$manager_list=new DB_Users();
		return $manager_list->db_Get_All_Users_Managers();
	}
	
	
	
}





?>
