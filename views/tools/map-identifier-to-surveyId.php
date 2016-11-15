<?php
/**
 * Created by PhpStorm.
 * User: Piyush_Sharma5
 * Date: 11/15/2016
 * Time: 5:22 PM
 */

define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");
define("REPOSITORY_PATH","../../Repository/");
define("EVENT_PATH","../../Event/");
define("VENDOR_PATH","../../vendor/");

$active_menu=6;

include_once(INCLUDES_PATH."basic_config_site.php");
include_once(CONTROLLER_PATH."get_data/survey_operations_read.php");
include_once(CONTROLLER_PATH."get_data/user_operations_read.php");
include_once(REPOSITORY_PATH."SurveyRepository.php");


?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Survey Management Tool -- Project/Survey Operations</title>
    <link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/style.css">

    <script src="<?php echo ASSETS_PATH;?>script/jquery_1.11.js"></script>
    <script src="<?php echo ASSETS_PATH;?>script/bootstrap.js"></script>

    <script src="<?php echo ASSETS_PATH;?>script/config_scripts.js"></script>
    <script src="<?php echo ASSETS_PATH;?>script/pop_up_scripts.js"></script>
    <script src="<?php echo ASSETS_PATH;?>script/paging_script.js"></script>
    <script src="<?php echo ASSETS_PATH;?>script/survey_page_animation_scripts.js"></script>
    <script src="<?php echo ASSETS_PATH;?>script/tools/tools_form_validation.js"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include_once(INCLUDES_PATH."header.php");?>
<?php include_once(INCLUDES_PATH."main_menu.php");?>

<div class="main_container">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h5>Map Hash Identifiers to Survey ID</h5>
                <form action="<?php echo CONTROLLER_PATH."get_data/tools/tools-form-handler.php";?>" name="map_identifier_to_surveyId_form"
                      enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="tool_operation_type" value="map_identifier_to_surveyIds" >
                    <div class="validations text-align-left">
                        <p class="error" id="err_hash_identifier_file">Please provide a Hash Identifier File to upload!</p>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form_field">
                                <div class="label">Upload File <span class="mandatory">*</span>
                                    <p class="instruction_note text-align-left">Format - 1<sup>st</sup> Column - Hash Identifier</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input_field">
                                <input type="file" name="hash_identifier_file" id="hash_identifier_file">
                            </div>
                        </div>
                    </div>

                    <button type="button" class="button" onclick="validate_map_hash_identifier_form()">Map Hash Identifier to Survey IDs</button>
                </form>
                <hr>



            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>

</body>
</html>
