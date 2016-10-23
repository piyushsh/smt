<?php 
define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");
define("PLUGIN_PATH","../plugin/");

$active_menu=2;
ini_set('memory_limit', '-1'); 

include_once(INCLUDES_PATH."basic_config_site.php");
include_once(CONTROLLER_PATH."get_data/survey_operations_read.php");

if(isset($_REQUEST["survey_id"]))
{
	$download_file=new Survey_Data_Read();
	$download_file=$download_file->export_Survey_Report($_REQUEST["survey_id"]);
	//header("Location: ".VIEW_PATH."view_survey_details.php?survey_id=".$_REQUEST["survey_id"]);
	exit;
}
else
{
	header("Location: ".VIEW_PATH."survey_operations.php");
	exit;
}
?>