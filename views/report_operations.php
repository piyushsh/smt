<?php
define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");
define("PLUGIN_PATH","../plugin/");

$active_menu=5;

include_once(INCLUDES_PATH."basic_config_site.php");
include_once(CONTROLLER_PATH."get_data/survey_operations_read.php");


?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Survey Management Tool -- Reporting</title>
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/bootstrap.css">
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/style.css">

<script src="<?php echo ASSETS_PATH;?>script/jquery_1.11.js"></script>
<script src="<?php echo ASSETS_PATH;?>script/bootstrap.js"></script>

<script src="<?php echo ASSETS_PATH;?>script/config_scripts.js"></script>

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
                        <h5>Generate Survey Reports</h5>
                        
                        <form action="<?php echo CONTROLLER_PATH."get_data/reporting_operations.php";?>" method="post" name="get_report">
                        
                        <div class="validations text-align-left">                    	
                            <p class="error" id="err_survey">Please select a survey!</p>
                            <p class="error" id="err_report_type">Please select a Report type!</p>
                    	</div>
                        
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form_field">
                                    <div class="label">Select Survey <span class="mandatory">*</span></div>
                                    <div class="input_field">
                                        <select name="select_survey" id="select_survey">
                                            <option value="">-- Please Select a Survey --</option>
                                            <?php
											$survey_data=new Survey_Data_Read();
											$survey_data=$survey_data->get_Survey_Data();
											while($row=$survey_data->fetch_array())
											{
												echo "<option value='".$row["survey_id"]."'>".substr(ucwords($row["survey_name"]),0,50)." -- (Survey ID: ".$row["survey_id"].")"."</option>";
											}
											?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form_field">
                                    <!--div class="label">Report<span class="mandatory">*</span></div-->
                                    <div class="input_field">
                                    			<!--input type="hidden" name="report_type" value="survey_report_1"-->
                                                <input type="radio" name="report_type" value="survey_report_1"><span class="label">Survey Report</span><br>
                                                <input type="radio" name="report_type" value="survey_report_2"><span class="label">Detailed Survey Report (Vendor Wise)</span><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form_field">
                                    <input type="button" class="button" value="Download Report" id="download_report">
                                </div>
                            </div>
                        </div>
                        
                        </form>
                
                
                
            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>
</body>
</html>
