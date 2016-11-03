<?php
define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");
define("PLUGIN_PATH","../plugin/");
define("EVENT_PATH","../Event/");
define("VENDOR_PATH","../vendor/");
define("REPOSITORY_PATH","../Repository/");

$active_menu=2;

include_once(CONTROLLER_PATH."set_data/redirection_operations.php");
include_once(CONTROLLER_PATH."get_data/survey_operations_read.php");
require REPOSITORY_PATH."SurveyConfig.php";

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


</head>

<body>

<?php
$error='NO_ERROR';
if(isset($_REQUEST) && isset($_REQUEST["redirected_from"]) && isset($_REQUEST["identifier"]) && isset($_REQUEST["survey_id"]))
{
	if($_REQUEST["redirected_from"]=="respondent")
	{
		if(isset($_REQUEST["vid"]))
		{
			$redirect_respondent=new Link_Redirections($_REQUEST);
			$redirect_respondent=$redirect_respondent->redirect_Respondent_To_Survey();
			if($redirect_respondent=="")
			{
				$redirect_respondent="#";
			}
		}	
		else
		{
			echo "<h1>Vendor ID is not present. Please provide vendor ID.</h1>";
			exit;
		}	
	}
	else if($_REQUEST["redirected_from"]=="survey")
	{
		if(isset($_REQUEST["status"]))
		{
			$redirect_vendor=new Link_Redirections($_REQUEST);
			$redirect_vendor=$redirect_vendor->redirect_Survey_To_Vendor();	
		}
		else
		{
			echo "<h1>Unable to update the status of the survey. Status field is not pass by the survey</h1>";
			exit;
			//!!!!!  Need to inser the code to generate a warning for the admin
		}		
	}
}
else
{
	echo "Identifier is not Found!";
}
?>


<div class="fade_background"></div>
<div class="loading_pop_up">
	<div class="row">
    	<div class="col-xs-12">
        	<div class='server_msg'>
				<?php
					if($_REQUEST["redirected_from"]=='respondent')
					{
						if(substr($redirect_respondent,0,3)=="ERR")
						{
							switch($redirect_respondent)
							{
								case 'ERR_SURVEY_NOT_OPEN':		echo "<p class='error'>The survey is not open right now. You can try again later.</p>";
																break;
								case 'ERR_IDENTIFIER_NOT_FOUND':		echo "<p class='error'>The respondent is not found in the database.</p>";
																break;
								case 'ERR_VENDOR_NOT_EXIST':	echo "<p class='error'>Vendor is not registered or vendor links are not allowed for this survey!</p>";
																break;
								case 'ERR_SURVEY_QUOTA_OVER':	echo "<p class='error'>Sorry, survey quota is full!</p>";
																break;
								case 'ERR_SURVEY_RESPONDENT_CLICK_QUOTA_OVER':	echo "<p class='error'>Sorry, survey's maximum respondent visit quota is full!</p>";
																break;
								case 'ERR_RESPONDENT_ALREADY_COMPLETED_SURVEY': echo "<p class='error'>User has already COMPLETED the survey!</p>";
																break;
								case 'ERR_SURVEY_FILTER_COUNTRY_IP':
									echo "<p class='error'>This survey is not allowed for your country!</p>";
									break;

								case 'ERR_SURVEY_FILTER_DUPLICATE_IP':
									echo "<p class='error'>You cannot continue the survey from your IP address, as limit has reached to take survey from your IP address!</p>";
									break;
							}
						}
						else if($redirect_respondent == "#")
						{
							echo "<p class='error'>Sorry, but no Survey redirection Link found!</p>";
						}
						else
						{
							echo '<div align="center"><img src="'.ASSETS_PATH.'images/loading.gif" width="100"></div><br>';
							?>
								<div class="row">
									<div class="col-xs-12">
										<p align="center">Please do not refersh the page.</p>
										<p align="center">You are getting redirected to the survey.</p>
									</div>
								</div>
								<script>
									$(document).ready(function() {
										window.location='<?php echo $redirect_respondent;?>';
                                    });
									
								</script>
							<?php
						}
					}
					else if($_REQUEST["redirected_from"]=='survey')
					{
						if(substr($redirect_vendor,0,3)=="ERR")
						{
							switch($redirect_vendor)
							{
								case 'ERR_IDENTIFIER_NOT_FOUND':		echo "<p class='error'>The respondent is not found in the database. So, status cannot be updated</p>";
																		break;
								case 'ERR_INVALID_STATUS':				echo "<p class='error'>Survey status is not correct!</p>";
																		break;
							}
						}
						else
						{
							echo "<h1>Thank You for taking the survey!</h1>";
							echo "<h3 class='show'>Your status updated!</h3>";
							echo "<h3 class='hide'>Please wait, while we update your status!</h3>";
							echo '<div align="center" class="hide"><img src="'.ASSETS_PATH.'images/loading.gif" width="100"></div><br>';
							?>
								
								<script>
									
									$(document).ready(function() {
                                        $(".hide").hide();
										window.location='<?php echo $redirect_vendor;?>';
                                    });
								</script>
							<?php
						}
					}
                ?>
             </div>
			
        </div>
    </div>
    <br>
    
</div>


</body>
</html>
