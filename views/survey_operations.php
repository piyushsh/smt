<?php
use Repository\SurveyRepository;

define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");
define("PLUGIN_PATH","../plugin/");
define("REPOSITORY_PATH","../Repository/");
define("EVENT_PATH","../Event/");
define("VENDOR_PATH","../vendor/");

$active_menu=2;

include_once(INCLUDES_PATH."basic_config_site.php");
include_once(CONTROLLER_PATH."get_data/survey_operations_read.php");
include_once(CONTROLLER_PATH."get_data/user_operations_read.php");
include_once(REPOSITORY_PATH."SurveyRepository.php");

$form_survey_name="";
$form_client_name="";
$form_survey_description="";
$form_survey_type="";
$form_survey_single_link="";
$form_survey_country="";

if(isset($_SESSION["survey_creation_form_field"]))
{
	$form_survey_name=$_SESSION["survey_creation_form_field"]["survey_name"];
	$form_client_name=$_SESSION["survey_creation_form_field"]["client_name"];
	$form_survey_description=$_SESSION["survey_creation_form_field"]["survey_description"];
	$form_survey_type=$_SESSION["survey_creation_form_field"]["survey_type"];
	$form_survey_single_link=$_SESSION["survey_creation_form_field"]["single_link"];
	$form_survey_country=$_SESSION["survey_creation_form_field"]["country"];
}


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
                    <h5>Recent Surveys <img src="../assets/images/plus_icon.png" id="create_survey_pop_up" title="Add Survey"></h5>
                    
                    
                    <div class="server_msg text-align-left">
                    	<?php
							if(isset($_REQUEST) && isset($_REQUEST["allow_stop_traffic"]))
							{
								if($_REQUEST["allow_stop_traffic"]=="sucess")
								{
									echo "<p class='sucess'>Survey traffic updated.</p>";
								}
								else
								{
									echo "<p class='error'>Some error occurecd while updating traffic for the survey. Please try again.</p>";
								}
							}
							if(isset($_REQUEST) && isset($_REQUEST["close_survey_result"]) && $_REQUEST["close_survey_result"]=="sucess")
							{
								echo "<p class='sucess'>Survey sucessfully closed!</p>";
							}
						?>
                    </div>
                    

                        <?php

                            $page=1;
                            //Page requested
                            if(isset($_REQUEST["page"]) && is_int((int)$_REQUEST["page"]))
                            {
                                $page = $_REQUEST["page"];
                            }

							$survey_read_object=NULL;

							$get_survey=new Survey_Data_Read();
							$survey_read_object=$get_survey;

                            //Total number of surveys
                            $total_surveys=$get_survey->get_Total_Number_Recent_Survey_Data(10,$_SESSION["user_id"]);

                            $total_pages=1;
                            $single_page_limit = $get_survey->getSurveyPresentedPageLimit();

                            if($total_surveys > $single_page_limit)
                            {
                                $total_pages=ceil($total_surveys/$single_page_limit);
                            }

                            //If page parameter passed in the link has high value than allowed number of pages.
                            if($page>$total_pages)
                            {
                                $page=$total_pages;
                            }
							
							$get_survey=$get_survey->get_Recent_Survey_Data(10,$_SESSION["user_id"],$page);

                        ?>

                        <div>
                            <ul class="pagination">
                                <?php
                                if($total_pages>1)
                                {
                                    ?>
                                    <!--li><a href="#">&laquo;</a></li-->
                                    <?php
                                    for($i=1;$i<=$total_pages;$i++)
                                    {
                                        echo "<li ".($i==$page ? "class = 'active'": "")."><a href='".VIEW_PATH."survey_operations.php?page=$i'>$i</a></li>";
                                    }
                                    ?>
                                    <!--li><a href="#">&raquo;</a></li-->
                                <?php
                                }
                                ?>
                            </ul>
                        </div>

                <table class="table apply_pagination" width="100%">
                    <tr>
                        <th>S.No.</th>
                        <th>Survey ID</th>
                        <th>Survey Name</th>
                        <th>Client Name</th>
                        <th>Country</th>
                        <th width="20%">Description</th>
                        <th>Allow Traffic</th>
                        <th>Completes</th>
                        <th>Terminates</th>
                        <th>Quotafull</th>
                        <th>Dropout</th>
                        <th>LOI</th>
                        <th>Incidence Rate</th>
                        <th></th>
                    </tr>

                        <?php
							
							$s_no=0;
							
							while($row=$get_survey->fetch_array())
							{
								$s_no++;
								
								$survey_respondent_status=new Survey_Data_Read();
								$survey_respondent_status=$survey_respondent_status->get_Survey_Status($row["survey_id"]);
								
								//Getting Length of Interview for Survey ID passed
								$survey_loi=$survey_read_object->getLengthOfInterview($row["survey_id"]);
								
								//Getting Incidence rate of the survey passed
								$survey_ir=$survey_read_object->getIncidenceRate($row["survey_id"]);
								
								
								echo "<tr><td>$s_no</td><td>".$row['alpha_survey_id']."</td><td><a href='".VIEW_PATH."view_survey_details.php?survey_id=".$row["survey_id"]."' title='View Details'>".$row["survey_name"]."</a></td><td>".$row["client_name"]."</td><td><strong>".$row["country"]."</strong></td><td>".$row["survey_description"]."</td>";
								
								echo "<td>";
								echo "<form action='".CONTROLLER_PATH."set_data/survey_operations.php' name='change_allow_traffic_".$row["survey_id"]."' method='post'>";
								echo "<input type='hidden' name='survey_id' value='".$row["survey_id"]."'><input type='hidden' name='change_allow_traffic' value='1'>";
								
								if($row["allow_traffic"]==1)
								{
									echo "<input type='checkbox' name='allow_stop_traffic' checked value='allow'>";
								}
								else
								{
									echo "<input type='checkbox' name='allow_stop_traffic' value='allow'>";
								}
								
								echo "</form>";
								
								echo "</td><td>".$survey_respondent_status["respondent_counts"]["complete"]."</td><td>".$survey_respondent_status["respondent_counts"]["screened"]."</td><td>".$survey_respondent_status["respondent_counts"]["overquota"]."</td><td>".$survey_respondent_status["respondent_counts"]["incomplete"]."</td><td>$survey_loi</td><td>$survey_ir</td><td><a href='".VIEW_PATH."view_survey_details.php?survey_id=".$row["survey_id"]."' title='View Details'><img src='".ASSETS_PATH."images/open_file.png' title='View Survey'></a>";
								
								if($row["single_link_url"]==NULL || $row["multi_link_table_name"]==NULL)
								{
									echo "<a href='".VIEW_PATH."modify_survey_set_survey_type.php?survey_id=".$row["survey_id"]."'><img src='".ASSETS_PATH."images/link_icon.png' title='Set Survey Link Type' width='25px'></a>";
								}
								
								echo "</td></tr>";
							}
							if($s_no==0)
							{
								echo "<tr><td colspan='7' align='center'><h6>No Survey added.</h6></td></tr>";
							}
							
						?>
                    </table>
                    
                    
                    <div>
                    	<ul class="pagination">
                        <?php 
						if($total_pages>1)
						{
							?>
                          <!--li><a href="#">&laquo;</a></li-->
                          <?php
						  	for($i=1;$i<=$total_pages;$i++)
							{
								echo "<li ".($i==$page ? "class = 'active'": "")."><a href='".VIEW_PATH."survey_operations.php?page=$i'>$i</a></li>";
							}
						  ?>
                          <!--li><a href="#">&raquo;</a></li-->
						  <?php
						}
						  ?>
                        </ul>
                    </div>
                    
                    <hr>
                    
            	
                
            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>

<div class="fade_background"></div>
<div class="pop_up">
	<div>
        	<img src="<?php echo ASSETS_PATH.'images/loading.gif';?>" width="100">
   	</div>
</div>

<div class="pop_up_container">
	<div class="close">X</div>
	<div class="pop_up_content" id="pop_up_create_survey_form">
    	<h5>Create New Survey</h5>
                    <br>
                    <div class="server_msg text-align-left">
						<?php
                        if(isset($_REQUEST))
                        {
                            if(isset($_REQUEST["survey_create_result"]) && $_REQUEST["survey_create_result"]=="sucess")
                            {
                                echo "<p class='sucess'>New survey has been created sucessfully.</p>";
                            }
							else if(isset($_REQUEST["survey_create_result"]) && $_REQUEST["survey_create_result"]=="error")
                            {
                                echo "<p class='error'>Some error occurecd while create a new survey. Please try again.</p>";
                            }
							
							if(isset($_REQUEST["survey_create_err"]))
							{
								switch($_REQUEST["survey_create_err"])
								{
									case 'ERR_CLIENT_NAME_NO_VALUE':
												echo "<p class='error'>Client name cannot be blank! Please enter a client name.</p>";
												break;
									case 'ERR_SURVEY_NAME_NO_VALUE':
												echo "<p class='error'>Survey name cannot be blank! Please enter a survey name.</p>";
												break;
												
									case 'ERR_SURVEY_DESCRIPTION_NO_VALUE':
												echo "<p class='error'>Please provide some survey description.</p>";
												break;
									case 'ERR_SURVEY_LINK_TYPE_NO_VALUE':
												echo "<p class='error'>Survey link type should be specified.</p>";
												break;
									case 'ERR_SURVEY_COUNTRY_NO_VALUE':
												echo "<p class='error'>Country is not specified!</p>";
												break;
									case 'ERR_SURVEY_SINGLE_LINK_IN_VALID':
												echo "<p class='error'>Single link provided, is not a valid link address. Please provide the correct link address.</p>";
												break;
									case 'ERR_SURVEY_MULTI_LINK_IN_VALID':
												echo "<p class='error'>Multi links file uploaded, have a link(s) with invalid link addresses. Please provide the correct link address and upload again.</p>";
												break;
									case 'ERR_SURVEY_MULTI_LINK_FILE_NOT_LOADED':
												echo "<p class='error'>Multi links file is not uploaded properly. Please try again.</p>";
												break;
									case 'ERR_SURVEY_QUOTA_IN_VALID':
												echo "<p class='error'>Survey quotas are not properly provided. Please correct it and try again.</p>";
												break;
                                    //Error for Re-Contact Link
                                    case 'ERR_SURVEY_RE_CONTACT_HASH_ID_INVALID':
                                                echo "<p class='error'>Re-Contact File uploaded by you has one or more INVALID Hash IDs. Please correct it and try again.</p>";
                                                break;
                                    case 'ERR_SURVEY_RE_CONTACT_LINK_INVALID':
                                            echo "<p class='error'>Re-Contact File uploaded by you has one or more INVALID Recontact Links. Please correct it and try again.</p>";
                                            break;
                                    case 'ERR_SURVEY_RE_CONTACT_FILE_NOT_LOADED':
                                            echo "<p class='error'>Re-Contact File was not uploaded properly. Please try again.</p>";
                                            break;
								}
							}
							
                        }
                        ?>
                    </div>
                    <form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" method="post" name="create_survey" enctype="multipart/form-data">
                    <input type="hidden" name="create_survey" value="1">
                    <input type="hidden" name="survey_manager" value="<?php echo $_SESSION["user_id"];?>">
                    
                    <div class="validations text-align-left">                    	
                        <p class="error" id="err_client">Please provide the Client Name!</p>
                        <p class="error" id="err_survey">Please provide the Survey Name!</p>
                        <p class="error" id="err_survey_description">Provide some survey description!</p>
                        <p class="error" id="err_survey_manager">Select a Survey Manager!</p>
                        <p class="error" id="err_survey_link_type">Survey link type is not specified!</p>
                        <p class="error" id="err_survey_country">Country is not specified!</p>
                        <p class="error" id="err_survey_link">Please provide the survey link(s)!</p>
                        <p class="error" id="err_survey_link_format">Please provide the survey link in correct format!</p>
                        <p class="error" id="err_survey_quota">Please provide the survey quota in numeric format!</p>
                        <p class="error" id="err_survey_click_quota">Please provide the survey's respondent click quota in numeric format!</p>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form_field">
                                <div class="label">Client Name <span class="mandatory">*</span></div>
                                <div class="input_field"><input type="text" name="client_name" id="client_name" value="<?php echo $form_client_name;?>"></div>
                            </div>
                        </div>
                        
                        <div class="col-xs-4 col-xs-offset-1">
                            <div class="form_field">
                                <div class="label">Survey Name <span class="mandatory">*</span></div>
                                <div class="input_field"><input type="text" name="survey_name" id="survey_name" value="<?php echo $form_survey_name;?>"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form_field">
                                <div class="label">Country<span class="mandatory">*</span></div>
                                <div class="input_field"><input type="text" name="country" id="country" value="<?php echo $form_survey_country;?>"></div>
                            </div>
                        </div>
                        
                        <div class="col-xs-4 col-xs-offset-1">
                            <div class="form_field">
                                <div class="label">Survey Description<span class="mandatory">*</span></div>
                                <div class="input_field"><textarea name="survey_description" id="survey_description"><?php echo $form_survey_description;?></textarea></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form_field">
                                <div class="label">Allow Traffic<span class="mandatory">*</span></div>
                                <div class="input_field">
                                	&nbsp;&nbsp;<input type="checkbox" name="allow_traffic" id="allow_traffic" value="allow">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xs-4 col-xs-offset-1">
                            <div class="form_field">
                                <div class="label">Allow Additional Parameter (from Vendor to Survey)<span class="mandatory">*</span></div>
                                <div class="input_field">
                                    &nbsp;&nbsp;<input type="checkbox" name="allow_additional_param" id="allow_additional_param" value="allow">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form_field">
                                <div class="label">Survey Link Type<span class="mandatory">*</span></div>
                                <div class="input_field">
                                        <input type="radio" name="survey_type" value="single" <?php if($form_survey_type=='single'){echo "checked='checked'";}?>> <span>Single</span><br>
                                        <input type="radio" name="survey_type" value="multi" <?php if($form_survey_type=='multi'){echo "checked='checked'";}?>> <span>Multiple</span><br>
                                        <input type="radio" name="survey_type" value="re_contact" <?php if($form_survey_type=='re_contact'){echo "checked='checked'";}?>> <span>Re-Contact Links</span> <br>
                                        <span class="instruction_note">Re-Contact Links cannot be modified, once survey is created.</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xs-4 col-xs-offset-1">
                            <div class="form_field">
                                <div id="link_1">
                                    <div class="label">Single Link<span class="mandatory">*</span></div>
                                    <input type="text" name="single_link" id="single_link" value="<?php if($form_survey_type=='single'){echo $form_survey_single_link;}?>"><br>
                                    <span class="instruction_note">Link eg. https://www.google.co.in?identifier=<Strong>[IDENTIFIER]</Strong></span>
                                    
                                </div>
                                
                                <div id="link_2">
                                    <div class="label">Upload Multiple Links File<span class="mandatory">*</span></div>
                                    <input type="file" name="multiple_link" id="multiple_link">
                                </div>

                                <div id="link_3">
                                    <div class="label">Upload Re-Contact Link File<span class="mandatory">*</span></div>
                                    <input type="file" name="re_contact_file" id="re_contact_file">
                                    <span class="instruction_note">Re-Contact File you are uploading should have two columns i.e. Hash ID and Unique User Link</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form_field">
                                <div class="label">Survey Quota<span class="mandatory">*</span></div>
                                <div class="input_field">
                                	<input type="text" name="survey_quota" id="survey_quota" value="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xs-5 col-xs-offset-1">
                            <div class="form_field">
                                <div class="label">Survey Respondent Click Quota<span class="mandatory">*</span></div>
                                <div class="input_field">
                                	<input type="text" name="survey_respondent_click_quota" id="survey_respondent_click_quota" value="0">
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-12">
                            <h5 role="button" data-toggle="collapse" href="#survey_filters" aria-expanded="false" aria-controls="survey_filters">Survey Filters <span class="glyphicon glyphicon-chevron-down"></span></h5>
                            <div class="collapse" id="survey_filters">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="form_field">
                                            <div class="input_field">
                                                <div class="label">Add Country Filter</div>
                                                <input type="checkbox" name="country_filter" id="country_filter" value="yes">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="form_field">
                                            <div class="label">Select Countries (from which Respondents are acceptable)</div>
                                            <div class="input_field">
                                                <select multiple name="country_ip_filter_countries[]" id="country_ip_filter_countries" disabled>
                                                    <?php
                                                    $countryList = SurveyRepository::getCountryIPFilterOptions();

                                                    foreach($countryList as $countryISOCode => $country)
                                                    {
                                                        ?>
                                                        <option value="<?php echo $countryISOCode?>"><?php echo $country?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <!-- UI Element for Duplicate IP Filter -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form_field">
                                            <div class="input_field">
                                                <div class="label">Duplicate IP Filter</div>
                                                <input type="checkbox" name="duplicate_ip" id="duplicate_ip" value="yes">
                                            </div>
                                        </div>
                                        <div class="form_field">
                                            <div class="input_field">
                                                <div class="label">Limit of Duplicate IPs</div>
                                                <input type="number" name="duplicate_ip_limit" id="duplicate_ip_limit" value="0" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <br>
                    <input type="button" value="Create Survey" id="create_survey" class="button">
                    
                    
                    </form>
                    
                    
                    <hr>
    </div>
</div>


<?php
if(isset($_REQUEST["survey_create_result"]) || isset($_REQUEST["survey_create_err"]))
{
	?>
    <script>
		$(document).ready(function(){
			$(".fade_background,.pop_up_container,.pop_up_container #pop_up_create_survey_form").fadeIn(200);
		});
	</script>
    <?php
}
?>

</body>
</html>
