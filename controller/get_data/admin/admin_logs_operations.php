<?php
/*define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");*/



include_once(MODEL_PATH."get_data/admin/db-logs-operations.php");

class Logs
{	
	function get_Logs($filter_type,$filter_user)
	{
		$logs=new Db_Logs();
		return $logs->db_Get_Logs($filter_type,$filter_user);
	}	
}





?>
