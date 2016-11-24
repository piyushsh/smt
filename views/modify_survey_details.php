<?php

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
include_once(CONTROLLER_PATH."SurveyFilterController.php");
include_once(REPOSITORY_PATH."SurveyRepository.php");
include_once(REPOSITORY_PATH."SurveyConfig.php");

use controller\SurveyFilterController;
use Repository\SurveyRepository;

$form_survey_name="";
$form_client_name="";
$form_survey_description="";
$form_survey_type="";
$form_survey_single_link="";

$survey_id=0;


if(isset($_REQUEST["survey_id"]))
{
	$survey_id=$_REQUEST["survey_id"];
}
else
{
	header("Location: ".VIEW_PATH."index.php");
	exit;
}

$survey_details=new Survey_Data_Read();
$survey_details=$survey_details->get_Survey_Details($survey_id);

$survey_filter_info = new SurveyFilterController($survey_id);
$survey_filter_info = $survey_filter_info->getSurveyFilterInfo();

$countryFilterInfo = $survey_filter_info["countryIPFilter"];
$duplicateIPFilterInfo = $survey_filter_info["duplicateIPFilter"];


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
<script src="<?php echo ASSETS_PATH;?>script/survey_modify_links_script.js"></script>
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
                    
                    
                    
                    <h5>Modify/Update Survey</h5>
                    <br>
                    <div class="server_msg text-align-left">
						<?php
                        if(isset($_REQUEST))
                        {
                            if(isset($_REQUEST["survey_modify_result"]) && $_REQUEST["survey_modify_result"]=="sucess")
                            {
                                echo "<p class='sucess'>The survey has been modified sucessfully.</p>";
                            }
							else if(isset($_REQUEST["survey_modify_result"]) && $_REQUEST["survey_modify_result"]!="")
                            {
								switch($_REQUEST["survey_modify_result"])
								{
									case 'ERR_SURVEY_STATUS_INVOICE':
													echo "<p class='error'>Survey Status is 'Invoice', so cannot be modified!</p>";
													break;
									default:
													echo "<p class='error'>Some error occurecd while modifying the survey. Please try again.</p>";
								}
                                
                            }
							
							if(isset($_REQUEST["survey_modify_err"]))
							{
								switch($_REQUEST["survey_modify_err"])
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
									
									case 'ERR_SURVEY_SINGLE_LINK_IN_VALID':
												echo "<p class='error'>Single link provided, is not a valid link address. Please provide the correct link address.</p>";
												break;
									
									case 'ERR_SURVEY_MANAGER_NO_VALUE':
												echo "<p class='error'>Survey manager is not selected, please select a survey manager.</p>";
												break;
								}
							}
							
                        }
                        ?>
                    </div>
                    
                                <form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" method="post" name="modify_survey">
                                <input type="hidden" name="modify_survey" value="1">
                                <input type="hidden" name="survey_id" value="<?php echo $survey_details["survey_detail"]["survey_id"];?>">
                                <?php
                                    if($survey_details["survey_detail"]["single_link_url"]!='N/A' && $survey_details["survey_detail"]["single_link_url"]!=NULL)
                                    {
                                        echo "<input type='hidden' name='survey_type' value='single'>";
                                    }
                                    else if($survey_details["survey_detail"]["multi_link_table_name"]!='N/A' && $survey_details["survey_detail"]["multi_link_table_name"]!=NULL)
                                    {
                                        echo "<input type='hidden' name='survey_type' value='multi'>";
                                    }
                                    else if($survey_details["survey_detail"]["re_contact_links"]==1)
                                    {
                                        echo "<input type='hidden' name='survey_type' value='re_contact'>";
                                    }
                                    else if($survey_details["survey_detail"]["single_link_url"]==NULL && $survey_details["survey_detail"]["multi_link_table_name"]==NULL)
                                    {
                                        echo "<input type='hidden' name='survey_type' value='no_link'>";
                                    }
                                ?>
                                

                                    <div class="validations text-align-left">
                                    <p class="error" id="err_client">Please provide the Client Name!</p>
                                    <p class="error" id="err_survey">Please provide the Survey Name!</p>
                                    <p class="error" id="err_country">Please provide the country!</p>
                                    <p class="error" id="err_survey_description">Provide some survey description!</p>
                                    <p class="error" id="err_survey_link_type">Survey link type is not specified!</p>
                                    <p class="error" id="err_survey_link">Please provide the survey link(s)!</p>
                                    <p class="error" id="err_survey_link_format">Please provide the survey link in correct format!</p>
                                    <p class="error" id="err_survey_link_format_MEMBER_ID">Please provide the survey link with MEMBER_ID parameter, as masking of member ID option is selected.</p>
                                    <p class="error" id="err_survey_quota">Please provide the survey quota in numeric format!</p>
                        			<p class="error" id="err_survey_click_quota">
                                        Please provide the survey's respondent click quota in numeric format!</p>
                                    <p class="error" id="err_survey_country_filter">
                                        Please select the countries, if "Add Country Filter" option is checked.</p>
                                    <p class="error" id="err_survey_duplicate_ip_filter">
                                        Please provide a integer value, limit of duplicate IPs accepted, if "Duplicate IP Filter" is checked.</p>
                                </div>

                                    <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form_field">
                                            <div class="label">Client Name <span class="mandatory">*</span></div>
                                            <div class="input_field"><input type="text" name="m_client_name" id="m_client_name" value="<?php echo $survey_details["survey_detail"]["client_name"];?>"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-4 col-xs-offset-1">
                                        <div class="form_field">
                                            <div class="label">Survey Name <span class="mandatory">*</span></div>
                                            <div class="input_field"><input type="text" name="m_survey_name" id="m_survey_name" value="<?php echo $survey_details["survey_detail"]["survey_name"];?>"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                    <div class="row">
                                    <div class="col-xs-4">
                                    	<div class="form_field">
                                            <div class="label">Country<span class="mandatory">*</span></div>
                                            <div class="input_field"><input type="text" name="m_country" id="m_country" value="<?php echo $survey_details["survey_detail"]["country"];?>"></div>
                                        </div>                                        
                                    </div>
                                    
                                    <div class="col-xs-4 col-xs-offset-1">
                                        <div class="form_field">
                                            <div class="label">Survey Description<span class="mandatory">*</span></div>
                                            <div class="input_field"><textarea name="m_survey_description" id="m_survey_description"><?php echo $survey_details["survey_detail"]["survey_description"];?></textarea></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form_field">
                                            <div class="label">Allow Traffic<span class="mandatory">*</span></div>
                                            <div class="input_field">
                                                &nbsp;&nbsp;<input type="checkbox" name="m_allow_traffic" id="m_allow_traffic" value="allow" <?php if($survey_details["survey_detail"]["allow_traffic"]!=0) echo "checked";?> >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-4 col-xs-offset-1">
                                        <div class="form_field">
                                            <div class="form_field">
                                                <div class="label">Allow Additional Parameter (from Vendor to Survey)<span class="mandatory">*</span></div>
                                                <div class="input_field">
                                                    &nbsp;&nbsp;<input type="checkbox" name="m_allow_additional_param" id="m_allow_additional_param" value="allow"
                                                            <?php
                                                            if ($survey_details["survey_detail"]["append_additional_param"]==1)
                                                                echo "checked";
                                                            ?>
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form_field">
                                            <div class="label">Include Masked Respondent Identifier / Pass Member ID with Survey Link</div>
                                            <div class="input_field">
                                                &nbsp;&nbsp;<input type="checkbox" name="m_mask_respondent_identifier" id="m_mask_respondent_identifier"
                                                                   value="allow"
                                                    <?php
                                                    if ($survey_details["survey_detail"]["mask_identifier"]==1)
                                                        echo "checked";
                                                    ?>
                                                    >
                                                <p class="instruction_note">Link eg. --- http://www.surveylink.com?identifier=<Strong>[IDENTIFIER]</Strong>&member_id=<strong>[MEMBER_ID]</strong></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-4 col-xs-offset-1">

                                    </div>
                                </div>

								    <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form_field">
                                            <div class="label">Survey Manager<span class="mandatory">*</span></div>
                                            <div class="input_field">
                                                        <select name="m_survey_manager" id="m_survey_manager">
															<option value="">-- Please select --</option>
															<?php 
																$manager_list=new Users();
																$manager_list=$manager_list->get_All_Users_Managers();
																while($row=$manager_list->fetch_array())
																{
																	echo "<option value='".$row["user_id"]."'>".ucfirst($row["name"])."</option>";
																}
															?>
														</select>

														<script>
															$(document).ready(function(){
																$("#m_survey_manager").val('<?php echo $survey_details["survey_detail"]["survey_manager"];?>');
															});
														</script>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-4 col-xs-offset-1">
                                        <div class="form_field">
                                            
                                        </div>
                                    </div>
                                </div>

                                    <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form_field">
                                            <div class="label">Survey Link Type<span class="mandatory">*</span></div>
                                            <div class="input_field">
                                                    <input type="radio" name="m_survey_type" value="single" disabled <?php if($survey_details["survey_detail"]["single_link_url"]!='N/A' && $survey_details["survey_detail"]["single_link_url"]!=NULL){echo "checked='checked'";}?>> <span>Single</span><br>
                                                    <input type="radio" name="m_survey_type" value="multi" disabled <?php if($survey_details["survey_detail"]["multi_link_table_name"]!='N/A' && $survey_details["survey_detail"]["multi_link_table_name"]!=NULL){echo "checked='checked'";}?>> <span>Multiple</span><br/>
                                                    <input type="radio" name="m_survey_type" value="re_contact" disabled <?php if($survey_details["survey_detail"]["re_contact_links"]==1 && $survey_details["survey_detail"]["re_contact_links"]!=NULL){echo "checked='checked'";}?>> <span>Re-Contact Links</span>
                                                    <input type="hidden" name="m_survey_type_hidden" id="m_survey_type_hidden" value=
                                                    	<?php 
															if($survey_details["survey_detail"]["single_link_url"]!='N/A' && $survey_details["survey_detail"]["single_link_url"]!=NULL)
															{echo "'single'";}
															else if($survey_details["survey_detail"]["multi_link_table_name"]!='N/A' && $survey_details["survey_detail"]["multi_link_table_name"]!=NULL)
															{echo "'multi'";}
                                                            else if($survey_details["survey_detail"]["re_contact_links"]==1 && $survey_details["survey_detail"]["re_contact_links"]!=NULL)
                                                            {echo "'re_contact'";}
														?>
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-4 col-xs-offset-1">
                                        <div class="form_field">
                                            <?php 
                                                if($survey_details["survey_detail"]["single_link_url"]!=NULL && $survey_details["survey_detail"]["single_link_url"]!='N/A')
                                                {
                                                    ?>
                                            <div>
                                                <div class="label">Single Link<span class="mandatory">*</span></div>
                                                <input type="text" name="m_single_link" id="m_single_link" value="<?php echo $survey_details["survey_detail"]["single_link_url"];?>" <?php if($survey_details["survey_detail"]["single_link_url"]=='N/A') echo "readonly";?>><br>
                                                <span class="instruction_note">Link eg. https://www.google.co.in?id=<strong>[IDENTIFIER]</strong></span>
                                                
                                            </div>
                                            <?php
												}
											?>
                                            
                                            
                                        </div>
                                    </div>
                                </div>

                                    <!-- Survey Quotas Modify fields -->
                                    <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form_field">
                                            <div class="label">Survey Quota<span class="mandatory">*</span></div>
                                            <div class="input_field">
                                                <input type="text" name="survey_quota" id="survey_quota" value="<?php  echo $survey_details["survey_detail"]["interviewquota"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-5 col-xs-offset-1">
                                        <div class="form_field">
                                            <div class="label">Survey Respondent Click Quota<span class="mandatory">*</span></div>
                                            <div class="input_field">
                                                <input type="text" name="survey_respondent_click_quota" id="survey_respondent_click_quota" value="<?php  echo $survey_details["survey_detail"]["respondentvisitquota"]; ?>">
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                    <!-- Survey Filters -->
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h5 role="button" data-toggle="collapse" href="#survey_filters" aria-expanded="false"
                                                aria-controls="survey_filters">Survey Filters
                                                <span class="glyphicon glyphicon-chevron-down"></span></h5>
                                            <?php

                                            /*
                                             * Displaying Errors for Filters applied in the survey
                                             */
                                            //Errors for Country Filter
                                            if(isset($_REQUEST["country_ip_filter_error"]))
                                            {
                                                switch($_REQUEST["country_ip_filter_error"])
                                                {
                                                    case "ERR_COUNTRY_ISO_CODE_NOT_FOUND":
                                                        echo "<p class='error'>While applying Country Filter, you didn't selected valid country option. Please try again with correct option.</p>";
                                                        break;

                                                    case "ERR_COUNTRY_FILTER_DATABASE_OPERATION":
                                                        echo "<p class='error'>Some error occured at database end, while saving the country filter. Please try again, if problem persists contact system administrator.</p>";
                                                        break;

                                                    case "ERR_COUNTRY_FILTER_DATABASE_WHILE_REMOVING":
                                                        echo "<p class='error'>Some error occured at database end, while removing the country filter. Please try again, if problem persists contact system administrator.</p>";
                                                        break;

                                                }
                                            }

                                            //Errors for Duplicate IP Filter
                                            if(isset($_REQUEST["duplicate_ip_filter_error"]))
                                            {
                                                switch($_REQUEST["duplicate_ip_filter_error"])
                                                {
                                                    case "ERR_DUPLICATE_IP_LIMIT_INTEGER":
                                                        echo "<p class='error'>For Duplicate IP Filter, you didn't entered \"Duplicat IP Limit\" as numeric value. Please try again by providing numeric value.</p>";
                                                        break;

                                                    case "ERR_DUPLICATE_IP_FILTER_DATABASE_OPERATION":
                                                        echo "<p class='error'>Some error occured at database end, while saving the duplicate IP filter. Please try again.</p>";
                                                        break;

                                                    case "ERR_DUPLICATE_IP_FILTER_DATABASE_WHILE_REMOVING":
                                                        echo "<p class='error'>Some error occured at database end, while removing the country filter. Please try again, if problem persists contact system administrator.</p>";
                                                        break;

                                                }
                                            }

                                            ?>

                                            <div class="collapse" id="survey_filters">
                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <div class="form_field">
                                                            <div class="input_field">
                                                                <div class="label">Add Country Filter</div>
                                                                <input type="checkbox" name="country_filter" id="country_filter"
                                                                       value="yes"
                                                                    <?php
                                                                    if($countryFilterInfo["applied"])
                                                                    {
                                                                        echo "checked";
                                                                    }
                                                                    ?>
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-7">
                                                        <div class="form_field">
                                                            <div class="label">Select Countries (from which Respondents are acceptable)</div>
                                                            <div class="input_field">
                                                                <select multiple name="country_ip_filter_countries[]"
                                                                        id="country_ip_filter_countries"
                                                                        <?php echo !$countryFilterInfo["applied"] ? "disabled" : ""; ?>>
                                                                    <?php
                                                                    $countryList = SurveyRepository::getCountryIPFilterOptions();

                                                                    foreach($countryList as $countryISOCode => $country)
                                                                    {
                                                                        $selected="";
                                                                        if(in_array($countryISOCode,$countryFilterInfo["countriesSelected"]))
                                                                        {
                                                                            $selected="selected='selected'";
                                                                        }
                                                                        ?>
                                                                            <option <?php echo "value='$countryISOCode' $selected";?>">
                                                                                <?php echo $country?></option>
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
                                                                <input type="checkbox" name="duplicate_ip"
                                                                       id="duplicate_ip" value="yes"
                                                                    <?php
                                                                    if($duplicateIPFilterInfo["applied"])
                                                                    {
                                                                        echo "checked";
                                                                    }
                                                                    ?>
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="form_field">
                                                            <div class="input_field">
                                                                <div class="label">Limit of Duplicate IPs</div>
                                                                <input type="number" name="duplicate_ip_limit"
                                                                       id="duplicate_ip_limit"
                                                                       value="<?php echo $duplicateIPFilterInfo["duplicateIPLimit"];?>"
                                                                        <?php
                                                                        if(!$duplicateIPFilterInfo["applied"])
                                                                        {
                                                                            echo "disabled";
                                                                        }
                                                                        ?>
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                
                                    <br>
                                    <input type="button" value="Modify Survey" id="modify_survey" class="button">
                                
                                    <a href="<?php echo VIEW_PATH."view_survey_details.php?survey_id=".$survey_id;?>"
                                       class="button">Back to Survey</a>
                    
                                </form>
                         
                    
                    
                    <hr>
                    
                    <?php
						if($survey_details["survey_detail"]["single_link_url"]=="N/A"
                            && $survey_details["survey_detail"]["multi_link_table_name"]!='N/A')
						{
							?>
							<h6>Multi Links Functions:-</h6>
                            <br>
                            <div class="server_msg text-align-left">
                            	<?php
									
									if(isset($_REQUEST) && isset($_REQUEST["add_links_result"]))
									{
										if($_REQUEST["add_links_result"]=="sucess")
										{
											echo "<p class='sucess'>Links have been updated Sucessfully</p>";
										}
										else if($_REQUEST["add_links_result"]=="error")
										{
											echo "<p class='error'>Some error occured while updating the links. Please try again later.</p>";
										}
										echo "<br>";
									}
									else if(isset($_REQUEST) && isset($_REQUEST["add_links_err"]))
									{
										switch($_REQUEST["add_links_err"])
										{
											case 'ERR_SURVEY_MULTI_LINK_IN_VALID':		echo "<p class='error'>Links format is invalid. Please update the link and try again.</p>";
																						break;
														
											case 'ERR_SURVEY_MULTI_LINK_FILE_NOT_LOADED':echo "<p class='error'>File not loaded. Please try again.</p>";
																						break;
											
										}
										echo "<br>";
									}
									
									
									
									if(isset($_REQUEST) && isset($_REQUEST["multi_links_update_result"]))
									{
										if($_REQUEST["multi_links_update_result"]=="sucess")
										{
											echo "<p class='sucess'>Links have been updated Sucessfully</p>";
										}
										else if($_REQUEST["multi_links_update_result"]=="error")
										{
											echo "<p class='error'>Some error occured while updating the links. Please try again later.</p>";
										}
										echo "<br>";
									}
									else if(isset($_REQUEST) && isset($_REQUEST["multi_links_update_err"]))
									{
										switch($_REQUEST["multi_links_update_err"])
										{
											case 'ERR_LINKS_FILE_NOT_IN_FORMAT':	echo "<p class='error'>Links file is not in proper format. Please update the file and try again.</p>";
																					break;
														
											case 'ERR_LINK_ID_NOT_NUMERIC':			echo "<p class='error'>Link Id provided is not numeric. Please update and try again.</p>";
																					break;
																					
											case 'ERR_SURVEY_MULTI_LINK_IN_VALID':	echo "<p class='error'>Link provided is invalid. Please update the links and try again.</p>";
																					break;
																					
											case 'ERR_LINK_USED_CANNOT_UPDATE':		echo "<p class='error'>Link already cannot be updated. Please remove used links and try again.</p>";
																					break;
											
										}
										echo "<br>";
									}
									
									
									
									if(isset($_REQUEST) && isset($_REQUEST["multi_links_delete_result"]))
									{
										if($_REQUEST["multi_links_delete_result"]=="sucess")
										{
											echo "<p class='sucess'>Links have been deleted Sucessfully</p>";
										}
										else if($_REQUEST["multi_links_delete_result"]=="error")
										{
											echo "<p class='error'>Some error occured while deleting the links. Please try again later.</p>";
										}
										echo "<br>";
									}
									else if(isset($_REQUEST) && isset($_REQUEST["multi_links_delete_err"]))
									{
										switch($_REQUEST["multi_links_delete_err"])
										{
											case 'ERR_LINKS_FILE_NOT_IN_FORMAT':	echo "<p class='error'>Links file is not in proper format. Please update the file and try again.</p>";
																					break;
														
											case 'ERR_LINK_ID_NOT_NUMERIC':			echo "<p class='error'>Link Id provided is not numeric. Please update and try again.</p>";
																					break;
																					
											case 'ERR_SURVEY_MULTI_LINK_IN_VALID':	echo "<p class='error'>Link provided is invalid. Please update the links and try again.</p>";
																					break;
																					
											case 'ERR_LINK_USED_CANNOT_UPDATE':		echo "<p class='error'>Link already cannot be updated. Please remove used links and try again.</p>";
																					break;
											
										}
										echo "<br>";
									}
									
									
									
								?>
                            	
                            </div>

                            <!-- Multiple Link Survey -->
                            <div class="survey_multi_link_ops">
                                <a href="<?php echo VIEW_PATH."download_survey_multi_links.php?survey_id=$survey_id"; ?>" class="button" >Download Links</a>
                                <span class="button" data-showdiv="#add_links_form_container">Add More Links</span>
                                <span class="button" data-showdiv="#modfiy_links_form_container">Modify Links</span>
                                <span class="button" data-showdiv="#delete_links_form_container">Delete Links</span>
                           	</div>
                            
                            <br><br><br>
                            <div id="add_links_form_container" class="show_unshow">
                            	
                            	<strong>Add Links</strong><br>
                                
                            	<form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" method="post" enctype="multipart/form-data" name="add_multi_survey_links">
                                	<input name="add_multi_survey_links_form" type="hidden" value="1">
                                    <input type="hidden" name="survey_id"  value="<?php echo $survey_id;?>">
                                	<div class="row">
                                    	<div class="col-xs-4">
                                        	<div class="form_field">
                                            	<div class="label">Upload File<span class="mandatory">*</span></div>
                                                <div class="input_field">
                                                	<input type="file" name="add_links" id="add_links">
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <input type="button" value="Add Links" id="add_links_but">
                                </form>
                            </div>
                            <div id="modfiy_links_form_container" class="show_unshow">
                            	
                            	<strong>Modify Links</strong><br>
                                <span class="instruction_note">Note:- Include only those links which you want to <strong>Update</strong> and which are <strong>Not Used</strong> till now by any respondent.</span>
                            	<form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" method="post" enctype="multipart/form-data" name="modify_multi_survey_links">
                                	<input name="modify_multi_survey_links_form" type="hidden" value="1">
                                    <input type="hidden" name="survey_id"  value="<?php echo $survey_id;?>">
                                	<div class="row">
                                    	<div class="col-xs-4">
                                        	<div class="form_field">
                                            	<div class="label">Upload File<span class="mandatory">*</span></div>
                                                <div class="input_field">
                                                	<input type="file" name="modify_links" id="modify_links">
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <input type="button" value="Modify Links" id="modify_links_but">
                                </form>
                            </div>
                            
                            <div id="delete_links_form_container" class="show_unshow">
                            	<strong>Delete Links</strong>
                            	<form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" method="post" enctype="multipart/form-data" name="delete_multi_survey_links">
                                	<input name="delete_multi_survey_links_form" type="hidden" value="1">
                                    <input type="hidden" name="survey_id" value="<?php echo $survey_id;?>">
                                	<div class="row">
                                    	<div class="col-xs-4">
                                        	<div class="form_field">
                                            	<div class="label">Upload File<span class="mandatory">*</span></div>
                                                <div class="input_field">
                                                	<input type="file" name="delete_links" id="delete_links">
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <input type="button" value="Delete Links" id="delete_links_but">
                                </form>
                            </div>
                            
							<?php
						}

                    if($survey_details["survey_detail"]["single_link_url"]=="N/A"
                        && $survey_details["survey_detail"]["multi_link_table_name"]=='N/A'
                        && $survey_details["survey_detail"]["re_contact_links"]==1) {
                        ?>

                        <h6>Re-Contact Links Functions:-</h6>
                        <br>
                        <div class="server_msg text-align-left">
                            <?php

                            if(isset($_REQUEST) && isset($_REQUEST["add_links_result"]))
                            {
                                if($_REQUEST["add_links_result"]=="sucess")
                                {
                                    echo "<p class='sucess'>Links have been updated Successfully</p>";
                                }
                                else if($_REQUEST["add_links_result"]=="error")
                                {
                                    echo "<p class='error'>Some error occurred while updating the links. Please try again later.</p>";
                                }
                                echo "<br>";
                            }
                            else if(isset($_REQUEST) && isset($_REQUEST["add_links_err"]))
                            {
                                switch($_REQUEST["add_links_err"])
                                {
                                    case 'ERR_SURVEY_RE_CONTACT_HASH_ID_INVALID' : echo "<p class='error'>Hash ID is invalid. Please update and try again.</p>";
                                        break;

                                    case 'ERR_SURVEY_RE_CONTACT_LINK_INVALID':		echo "<p class='error'>Links format is invalid. Please update the link and try again.</p>";
                                        break;

                                    case 'ERR_SURVEY_RE_CONTACT_FILE_NOT_LOADED':echo "<p class='error'>File not loaded. Please try again.</p>";
                                        break;

                                }
                                echo "<br>";
                            }



                            if(isset($_REQUEST) && isset($_REQUEST["re_contact_links_update_result"]))
                            {
                                if($_REQUEST["re_contact_links_update_result"]=="sucess")
                                {
                                    echo "<p class='sucess'>Links have been updated Sucessfully</p>";
                                }
                                else if($_REQUEST["re_contact_links_update_result"]=="error")
                                {
                                    echo "<p class='error'>Some error occured while updating the links. Please try again later.</p>";
                                }
                                echo "<br>";
                            }
                            else if(isset($_REQUEST) && isset($_REQUEST["re_contact_links_update_err"]))
                            {
                                switch($_REQUEST["re_contact_links_update_err"])
                                {
                                    case 'ERR_LINKS_FILE_NOT_IN_FORMAT':	echo "<p class='error'>Links file is not in proper format. Please update the file and try again.</p>";
                                        break;

                                    case 'ERR_LINK_ID_NOT_NUMERIC':			echo "<p class='error'>Link Id provided is not numeric or is null. Please update and try again.</p>";
                                        break;

                                    case 'ERR_SURVEY_RE_CONTACT_LINK_INVALID':	echo "<p class='error'>Re-Contact Link provided is invalid. Please update the links and try again.</p>";
                                        break;

                                    case 'ERR_SURVEY_RE_CONTACT_LINK_FILE_NOT_LOADED':	echo "<p class='error'>There was some issue with file uploading, please try again.</p>";
                                        break;

                                }
                                echo "<br>";
                            }



                            if(isset($_REQUEST) && isset($_REQUEST["re_contact_links_delete_result"]))
                            {
                                if($_REQUEST["re_contact_links_delete_result"]=="sucess")
                                {
                                    echo "<p class='sucess'>Re-Contact Links have been deleted Sucessfully</p>";
                                }
                                else if($_REQUEST["re_contact_links_delete_result"]=="error")
                                {
                                    echo "<p class='error'>Some error occured while deleting the Re-Contact links. Please try again later.</p>";
                                }
                                echo "<br>";
                            }
                            else if(isset($_REQUEST) && isset($_REQUEST["re_contact_links_delete_err"]))
                            {
                                switch($_REQUEST["re_contact_links_delete_err"])
                                {
                                    case 'ERR_LINKS_FILE_NOT_IN_FORMAT':	echo "<p class='error'>Re-Contact Links file is not in proper format. Please update the file and try again.</p>";
                                        break;

                                    case 'ERR_LINK_ID_NOT_NUMERIC':			echo "<p class='error'>Re-Contact Link Id provided is not numeric or is null. Please update and try again.</p>";
                                        break;

                                    case 'ERR_SURVEY_RE_CONTACT_LINK_FILE_NOT_LOADED':  echo "<p class='error'>File was not loaded properly. Please try again.</p>";
                                        break;

                                    case 'ERR_SURVEY_RE_CONTACT_HASH_ID_EMPTY' : echo "<p class='error'>Re-Contact Link HASH Id provided is null. Please update and try again.</p>";
                                        break;
                                }
                                echo "<br>";
                            }



                            ?>

                        </div>

                        <!-- Re-Contact Link Survey -->
                        <div class="survey_re_contact_link_ops">
                            <a href="<?php echo VIEW_PATH."download_survey_re_contact_links.php?survey_id=$survey_id"; ?>" class="button" >Download Links</a>
                            <span class="button" data-showdiv="#add_links_form_container_re_contact">Add More Links</span>
                            <span class="button" data-showdiv="#modfiy_links_form_container_re_contact">Modify Links</span>
                            <span class="button" data-showdiv="#delete_links_form_container_re_contact">Delete Links</span>
                        </div>

                        <br><br><br>
                        <div id="add_links_form_container_re_contact" class="show_unshow">

                            <strong>Add Links</strong><br>

                            <form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" method="post"
                                  enctype="multipart/form-data" name="add_re_contact_survey_links">
                                <input name="add_re_contact_survey_links_form" type="hidden" value="1">
                                <input type="hidden" name="survey_id"  value="<?php echo $survey_id;?>">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form_field">
                                            <div class="label">Upload File<span class="mandatory">*</span></div>
                                            <div class="input_field">
                                                <input type="file" name="re_contact_add_links" id="re_contact_add_links">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="button" value="Add Re-Contact Links" id="re_contact_add_links_but">
                            </form>
                        </div>
                        <div id="modfiy_links_form_container_re_contact" class="show_unshow">
                            <strong>Modify Re-Contact Links</strong><br>
                            <span class="instruction_note">Note:- <strong>File Format :</strong> Columns - Record/Database IDs & Updated Links.</span>
                            <form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" method="post"
                                  enctype="multipart/form-data" name="modify_re_contact_survey_links">
                                <input name="modify_re_contact_survey_links_form" type="hidden" value="1">
                                <input type="hidden" name="survey_id"  value="<?php echo $survey_id;?>">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form_field">
                                            <div class="label">Upload File<span class="mandatory">*</span></div>
                                            <div class="input_field">
                                                <input type="file" name="modify_re_contact_links" id="modify_re_contact_links">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="button" value="Modify Re-Contact Links" id="modify_re_contact_links_but">
                            </form>
                        </div>

                        <div id="delete_links_form_container_re_contact" class="show_unshow">
                            <strong>Delete Links</strong><br>
                            <span class="instruction_note">Note:- <strong>File Format :</strong> Columns - Record/Database IDs & HashID.
                            <form action="<?php echo CONTROLLER_PATH."set_data/survey_operations.php";?>" method="post"
                                  enctype="multipart/form-data" name="delete_re_contact_survey_links">
                                <input name="delete_re_contact_survey_links_form" type="hidden" value="1">
                                <input type="hidden" name="survey_id" value="<?php echo $survey_id;?>">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form_field">
                                            <div class="label">Upload Re-Contact File<span class="mandatory">*</span></div>
                                            <div class="input_field">
                                                <input type="file" name="delete_re_contact_links" id="delete_re_contact_links">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="button" value="Delete Re-Contact Links" id="delete_re_contact_links_but">
                            </form>
                        </div>

                    <?php
                    }
					?>

            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>
</body>
</html>
