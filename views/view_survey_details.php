<?php
define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");
define("PLUGIN_PATH","../plugin/");

$active_menu=2;

include_once(INCLUDES_PATH."basic_config_site.php");
include_once(CONTROLLER_PATH."get_data/survey_operations_read.php");


?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Survey Management Tool -- Project/Survey Operations -- Viewing a Survey Details</title>
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/bootstrap.css">
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/style.css">

<script src="<?php echo ASSETS_PATH;?>script/jquery_1.11.js"></script>
<script src="<?php echo ASSETS_PATH;?>script/bootstrap.js"></script>

<script src="<?php echo ASSETS_PATH;?>script/config_scripts.js"></script>
<script src="<?php echo ASSETS_PATH;?>script/pop_up_scripts.js"></script>

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
            		<?php
					$survey_details="";
					if(isset($_REQUEST["survey_id"]))
					{
						$survey_details=new Survey_Data_Read();
						
						//Getting Length of Interview for Survey ID passed
						$survey_loi=$survey_details->getLengthOfInterview($_REQUEST["survey_id"]);
						
						//Getting Incidence rate of the survey passed
						$survey_ir=$survey_details->getIncidenceRate($_REQUEST["survey_id"]);
						
						
						$survey_details=$survey_details->get_Survey_Details($_REQUEST["survey_id"]);
					}
					?>
                    <div class="server_msg text-align-left">
                    <?php
					
						if(isset($_REQUEST))
						{
							if(isset($_REQUEST["close_survey_err"]))
							{
								if($_REQUEST["close_survey_err"]=="ERR_TRAFFIC_ALLOWED")
								{
									echo "<p class='error'>Survey cannot get closed as still Traffic is allowed! Please dis-allow the traffic and then close the survey.</p>";
								}
								else if($_REQUEST["close_survey_err"]=="ERR_QUERY_NOT_EXECUTED")
								{
									echo "<p class='error'>Some error occured while closing the survey.</p>";
								}
							}
						}
					
					
					?>
                    </div>
                    <h4>Survey Details</h4>
                    <div class="row">
                    	<div class="col-lg-3">
                        	<h6><span class="show_data_label">Survey ID: </span><span class="show_data"><?php echo $survey_details["survey_detail"]["survey_id"];?></span></h6>
                        </div>
                        <div class="col-lg-3 col-lg-offset-2">
                        	<h6><span class="show_data_label">Name: </span><span class="show_data"><?php echo $survey_details["survey_detail"]["survey_name"];?></span></h6>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                    	<div class="col-lg-3">
                        	<p><span class="show_data_label">Client:</span><span class="show_data"> <?php echo $survey_details["survey_detail"]["client_name"];?></span></p>
                        </div>
                        <div class="col-lg-3 col-lg-offset-2">
                        	<p><span class="show_data_label">Allow Traffic: </span><input type="checkbox" value="allow" name="allow_traffic" <?php if($survey_details["survey_detail"]["allow_traffic"]==1) echo "checked";?>  disabled></p>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-lg-3">
                        	<p><span class="show_data_label">Country:</span><span class="show_data"> <?php echo $survey_details["survey_detail"]["country"];?></span></p>
                        </div>
                        <div class="col-lg-3 col-lg-offset-2">
                        	
                        </div>
                    </div>
                    
                    
                    
                    <p><span class="show_data_label">About: </span><?php echo ucfirst($survey_details["survey_detail"]["survey_description"]);?></p>
                    
                    <div class="row">
                    	<div class="col-lg-3">
                        	<p><span class="show_data_label">Survey Quota:</span><span class="show_data"> <?php echo $survey_details["survey_detail"]["interviewquota"];?></span></p>
                        </div>
                        <div class="col-lg-3 col-lg-offset-2">
                        	<p><span class="show_data_label">Survey max. user click quota: </span><span class="show_data"> <?php echo $survey_details["survey_detail"]["respondentvisitquota"];?></span></p>
                        </div>
                    </div>
                    
                    <div class="row">
                    	<div class="col-lg-3">
                        	<p><span class="show_data_label">Length of Interview (LOI):</span><span class="show_data"> <?php echo $survey_loi." minutes";?></span></p>
                        </div>
                        <div class="col-lg-3 col-lg-offset-2">
                        	<p><span class="show_data_label">Incidence Rate (IR): </span><span class="show_data"> <?php echo $survey_ir;?></span></p>
                        </div>
                    </div>
                    
                    <p><span class="show_data_label">Survey Manager: </span><?php echo ucfirst($survey_details["survey_detail"]["name"]);?></p>
                    <br>
                    <a href="<?php echo VIEW_PATH."modify_survey_details.php?survey_id=".$survey_details["survey_detail"]["survey_id"];?>" class="button">Modify Survey</a>
                    
                    <a href="<?php echo VIEW_PATH."export_survey_report.php?survey_id=".$survey_details["survey_detail"]["survey_id"];?>" class="button" onClick="document.export_survey_report.submit();">Export Survey Report</a> 
                    <br><br>
                    <form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" method="post" name="close_survey_form">
                    	<input type="hidden" value="<?php echo $survey_details["survey_detail"]["survey_id"]; ?>" name="survey_id">
                        <?php
							if($survey_details["survey_detail"]["removed"]==0)
							{
							?>
							<input type="hidden" value="1" name="close_survey">
							<button id="close_survey_but" class="button">Close Survey</button>
						<?php
							}
							else if($survey_details["survey_detail"]["removed"]==1)
							{
							?>
                            <input type="hidden" value="1" name="open_survey">
							<button id="open_survey_but" class="button">Open Survey</button>
                      	<?php
							}
						?>
                    </form>
                    
                    
                    
                    
                    <br>
                    
                    
                    <hr>
                    
                    <?php
						$survey_respondent_status=new Survey_Data_Read();
						
						$vendor_excluded=$survey_respondent_status->get_Vendor_Excluded($survey_details["survey_detail"]["survey_id"]);
						
						$survey_respondent_status=$survey_respondent_status->get_Survey_Status($survey_details["survey_detail"]["survey_id"]);
						
						
					?>
                    
                    <h1>Survey Status</h1>
                    <table class="table">
                    	<tr>
                        	<th>Respondent Status</th><th>No. of Respondents</th>
                        </tr>
                        <tr>
                        	<td>Completes</td><td><?php echo $survey_respondent_status["respondent_counts"]["complete"];?></td>
                        </tr>
                        <tr>
                        	<td>Screened</td><td><?php echo $survey_respondent_status["respondent_counts"]["screened"];?></td>
                        </tr>
                        <tr>
                        	<td>Over Quota</td><td><?php echo $survey_respondent_status["respondent_counts"]["overquota"];?></td>
                        </tr>
                        <tr>
                        	<td>Incomplete</td><td><?php echo $survey_respondent_status["respondent_counts"]["incomplete"];?></td>
                        </tr>
                        <tr>
                        	<th>Total</th><th><?php echo (array_sum($survey_respondent_status["respondent_counts"]));?></th>
                        </tr>
                    </table>
                    
                    <h1>Vendors Involved</h1>
                    <div class="server_msg">
						<?php
                            if(isset($_REQUEST["vendor_exclude_include"]))
                            {
                                switch($_REQUEST["vendor_exclude_include"])
                                {
                                    case "sucess":
                                                    echo "<p class='sucess'>Vendor's links were sucessfully ".$_REQUEST["type"]."d.</p>";
                                                    break;
                                    case "error":
                                                    echo "<p class='error'>Some error occured while vendor links were ".$_REQUEST["type"].". Please try again later.</p>";
                                                    break;
                                    
                                }
                            }
                        ?>
                    </div>
                    <table class="table">
                    	<tr>
                        	<th>Vendor Name</th><th>Vendor ID</th><th>Total Respondents</th><th>Completes</th><th>Terminates</th><th>Quotafull</th><th>Dropouts</th><th>Allowed links</th>
                        </tr>
                        <?php
							foreach($survey_respondent_status["vendor_wise_survey_status"] as $key=>$value)
							{
								echo "<tr><td><a href='".VIEW_PATH."modify_vendor_details.php?vendor_id=".$value["vendor_id"]."'>".$value["vendor_name"]."</a></td><td>".$key."</a></td><td>".$value["total_links"]."</td><td>".$value["complete"]."</td><td>".$value["screened"]."</td><td>".$value["overquota"]."</td><td>".$value["incomplete"]."</td><td>";
								?>
                                	<form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" name="exclude_vendor_<?php echo $value["vendor_id"];?>" method="post">
                                    	<input type="hidden" name="exclude_include_vendor" value="1">
                                    	<input type="hidden" name="vendor_id" value="<?php echo $value["vendor_id"];?>">
                                        <input type="hidden" name="survey_id" value="<?php echo $survey_details["survey_detail"]["survey_id"];?>">
                                        <input type="checkbox" name="include_vendor" id="include_vendor_<?php echo $value["vendor_id"];?>"  <?php if(!in_array($value["vendor_id"],$vendor_excluded)){echo "checked value='1'";} else {echo "value='0'";}?> >
                                    </form>
                                <?php
								echo "</td></tr>";
							}
						?>
                    </table>
                    
                    
                    <br><br>
                    <h1>Redirection Link for Vendor</h1>
                    <Strong><?php echo $survey_respondent_status["redirection_link_vendor"];?></Strong><br>
                    <p class="instruction_note"><strong><i>Note:-</i></strong></p>
                    <p class="instruction_note">Where, <strong><i>identifier=XXXXXXX</i></strong> is given, in place of <strong><i>XXXXXXX</i></strong> identifier value should be inserted by the vendor.</p>
                    <p class="instruction_note">Where, <strong><i>vid=XXXXXXX</i></strong> is given, in place of <strong><i>XXXXXXX</i></strong> vendor ID should be placed.</p>
                    <br>
                    
                    <h1>Redirection Link for Client</h1>
                    <p>For Complete status: <strong><?php echo $survey_respondent_status["redirection_link_survey"]["complete"];?></strong></p>
                    <p>For Screened status: <strong><?php echo $survey_respondent_status["redirection_link_survey"]["screened"];?></strong></p>
                    <p>For Over Quota status: <strong><?php echo $survey_respondent_status["redirection_link_survey"]["overquota"];?></strong></p>
                    <p class="instruction_note"><strong><i>Note:-</i></strong></p>
                    <p class="instruction_note">Where, <strong><i>identifier=XXXXXXX</i></strong> is given, in place of <strong><i>XXXXXXX</i></strong> identifier value should be inserted from the survey.</p>
                    <p class="instruction_note">Where, <strong><i>status=XXXXXX</i></strong> is given, in place of <strong><i>XXXXXXX</i></strong>, survey status <strong><i>('screened','overquota','complete')</i></strong>, should be inserted from the survey.</p>
                    
                    
                    
                
            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>



<div class="fade_background">
</div>

<div class="pop_up_container">
	<div class="close">X</div>
	<div class="pop_up_content" id="raise_invoice_pop_up">
    	<h5 align="center">Raise Invoice</h5> 
        <br>
        	<div class="row">
            	<div class="col-xs-6 col-xs-offset-3">
						<?php
                            if($survey_details["survey_detail"]["survey_status"]=="closed")
                            {
                                ?>
                                <form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" name="update_survey_staus" method="post" enctype="multipart/form-data">
                                <?php
                                echo '<input type="hidden" value="'.$survey_details["survey_detail"]["survey_id"].'" name="survey_id">';
                                echo '<input type="hidden" value="1" name="raise_invoice">';
								echo '<input type="hidden" value="'.$_SESSION["user_id"].'" name="user_id">';
                                ?>
                                <div class="row form_field">
                                        <div class="col-xs-6"><label>Upload File:</label></div>
                                        <div class="col-xs-6"><input type="file" name="raise_invoice_file" id="raise_invoice_file"></div>
                                </div>
                                
                                <div class="row form_field">
                                	<div class="col-xs-6"><label>Additional Comments:</label></div>
                                    <div class="col-xs-6"><textarea rows="5" name="raise_invoice_comments" id="raise_invoice_comments"></textarea></div>
                                </div>
                                <div class="row form_field" align="center">
                                	<div class="col-xs-12">
										<?php
                                        echo '<input type="button" class="button" value="Raise Invoice" id="raise_invoice">';
                                        ?>
                                    </div>
                                </div>
                                
                                </form>
                                <?php
                            }
                        ?>
                </div>
           </div>
    </div>
</div>


</body>
</html>
