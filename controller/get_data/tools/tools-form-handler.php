<?php
/**
 * Created by PhpStorm.
 * User: Piyush_Sharma5
 * Date: 11/15/2016
 * Time: 6:14 PM
 */

//Constants
if(!defined("CONTROLLER_PATH"))
    define("CONTROLLER_PATH","../../../controller/");

if(!defined("MODEL_PATH"))
    define("MODEL_PATH","../../../models/");

if(!defined("REPOSITORY_PATH"))
    define("REPOSITORY_PATH","../../../Repository/");

if(!defined("INCLUDES_PATH"))
    define("INCLUDES_PATH","../../../includes/");

if(!defined("EVENT_PATH"))
    define("EVENT_PATH","../../../Event/");

if(!defined("VENDOR_PATH"))
    define("VENDOR_PATH","../../../vendor/");

if(!defined("VIEW_PATH"))
    define("VIEW_PATH","../../../views/");

if(!defined("PLUGIN_PATH"))
    define("PLUGIN_PATH","../../../plugin/");

//Includes
include(CONTROLLER_PATH."get_data/tools/MapIdentifierToSurveyId.php");

//Uses
use tools\MapIdentifierToSurveyId;

$lastVisitedPage = "";

if(isset($_POST) && isset($_POST["tool_operation_type"]))
{
    switch($_POST["tool_operation_type"])
    {
        case "map_identifier_to_surveyIds" :
            $lastVisitedPage = "map-identifier-to-surveyId";
            $mapIdentifierToSurveyId = new MapIdentifierToSurveyId($_FILES["hash_identifier_file"]);
            if(count($mapIdentifierToSurveyId->errors) > 0)
            {
                header("Location: ".VIEW_PATH."tools/$lastVisitedPage.php?error=".array_pop($mapIdentifierToSurveyId->errors));
                exit;
            }
            break;
    }
}

header("Location: ".VIEW_PATH."tools/$lastVisitedPage.php");
exit;
?>