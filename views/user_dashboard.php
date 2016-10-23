<?php
define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");
define("PLUGIN_PATH","../plugin/");

$active_menu=1;

include_once(INCLUDES_PATH."basic_config_site.php");
include_once(CONTROLLER_PATH."get_data/survey_operations_read.php");
include_once(CONTROLLER_PATH."get_data/user_operations_read.php");

$manager_id=$_SESSION["user_id"];
$survey_status='';

if($_POST && isset($_POST["filter_survey"]))
{
	$manager_id=$_POST["manager_name_filter"];
	$survey_status=$_POST["survey_status"];
}
else if(isset($_REQUEST["filter_applied"]) && trim(strtolower($_REQUEST["filter_applied"])) == "yes" )
{
    $manager_id= isset($_REQUEST["manager_name_filter"]) ? $_REQUEST["manager_name_filter"] : $_SESSION["user_id"];
    $survey_status = isset($_REQUEST["survey_status"]) ? $_REQUEST["survey_status"] : "";
}



?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Survey Management Tool -- User Dashboard</title>
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/bootstrap.css">
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/style.css">

<script src="<?php echo ASSETS_PATH;?>script/jquery_1.11.js"></script>
<script src="<?php echo ASSETS_PATH;?>script/bootstrap.js"></script>

<script src="<?php echo ASSETS_PATH;?>script/config_scripts.js"></script>
<script src="<?php echo ASSETS_PATH;?>script/paging_script.js"></script>

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
            	<h5>Survey Status</h5>
                <div class="row">
                	<form action="user_dashboard.php" method="post" name="filter_survey_form">
                    	<input type="hidden" name="filter_survey" value="1">
                        <div class="col-xs-1"><strong>Filter BY: </strong> </div>
                        <div class="col-xs-2" align="right">Manager's Name: </div>
                        <div class="col-xs-2">
                                        <select name="manager_name_filter" id="manager_name_filter">
                                                <option value="">-- Please Select --</option>
                                                <option value="0">All Managers</option>
                                                <?php 
                                                    $manager_list=new Users();
                                                    $manager_list=$manager_list->get_All_Users_Managers();
                                                    while($row=$manager_list->fetch_array())
                                                    {
                                                        echo "<option value='".$row["user_id"]."'>".ucfirst($row["name"])."</option>";
                                                    }
                                                ?>
                                        </select>
                        </div>
                        <div class="col-xs-2" align="right">Survey Status:</div>
                        <div class="col-xs-2">
                                        <select name="survey_status" id="survey_status">
                                                <option value="">-- Please Select --</option>
                                                <option value="0">Open</option>
                                                <option value="1">Closed</option>
                                        </select>
                        </div>
                        
                        <div class="col-xs-2"><input type="button" class="button" value="Go" onclick="filter_Survey_Form();"></div>
                    </form>
                </div>
                <br>
                
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
							
							if(isset($_REQUEST) && isset($_REQUEST["open_survey_result"]))
							{
								if($_REQUEST["open_survey_result"]=="sucess")
								{
									echo "<p class='sucess'>Survey opened sucessfully.</p>";
								}
								if(isset($_REQUEST["open_survey_err"]) && $_REQUEST["open_survey_err"]=="ERR_QUERY_NOT_EXECUTED")
								{
									echo "<p class='error'>Some error occurecd while re-opening the survey. Please try again.</p>";
								}
							}

						?>
                    </div>
                

                        <?php
						
							$form_survey_name="";
							$form_client_name="";
							$form_survey_description="";
							$form_survey_type="";
							$form_survey_single_link="";
							
							if(isset($_SESSION["survey_creation_form_field"]))
							{
								$form_survey_name=$_SESSION["survey_creation_form_field"]["survey_name"];
								$form_client_name=$_SESSION["survey_creation_form_field"]["client_name"];
								$form_survey_description=$_SESSION["survey_creation_form_field"]["survey_description"];
								$form_survey_type=$_SESSION["survey_creation_form_field"]["survey_type"];
								$form_survey_single_link=$_SESSION["survey_creation_form_field"]["single_link"];
							}


                            $page=1;
                            //Page requested
                            if(isset($_REQUEST["page"]) && is_int((int)$_REQUEST["page"]))
                            {
                                $page = $_REQUEST["page"];
                            }
							
							$survey_read_object=NULL;
							
							$get_survey=new Survey_Data_Read();

                            //Total number of surveys
                            $total_surveys=$get_survey->get_Total_Filter_Surveys(10,$manager_id,$survey_status,-1);

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

							
							$survey_read_object=$get_survey;

							$get_survey=$get_survey->get_Filter_Surveys(10,$manager_id,$survey_status,-1,$page);
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
                                    echo "<li ".($i==$page ? "class = active": "")."><a href='".VIEW_PATH."user_dashboard.php?page=$i&filter_applied=yes&manager_name_filter=$manager_id&survey_status=$survey_status'>$i</a></li>";
                                }
                                ?>
                                <!--li><a href="#">&raquo;</a></li-->
                            <?php
                            }
                            ?>
                        </ul>
                    </div>

                <table class="table apply_pagination">
                    <tr>
                        <th>S.No.</th>
                        <th>Survey ID</th>
                        <th>Survey Name</th>
                        <th>Client Name</th>
                        <th>Country</th>
                        <th width="15%">Description</th>
                        <th>Allow Traffic</th>
                        <th>Survey Manager</th>
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
								
								
								echo "<tr><td>$s_no</td><td>".$row["alpha_survey_id"]."</td><td><a href='".VIEW_PATH."view_survey_details.php?survey_id=".$row["survey_id"]."' title='View Details'>".$row["survey_name"]."</a></td><td>".$row["client_name"]."</td><td><strong>".$row["country"]."</strong></td><td>".$row["survey_description"]."</td>";
								echo "<td>";
								echo "<form action='".CONTROLLER_PATH."set_data/survey_operations.php' name='change_allow_traffic_".$row["survey_id"]."' method='post'>";
								echo "<input type='hidden' name='survey_id' value='".$row["survey_id"]."'><input type='hidden' name='change_allow_traffic_dashboard' value='1'>";
								
								if($row["allow_traffic"]==1)
								{
									echo "<input type='checkbox' name='allow_stop_traffic' checked value='allow'>";
								}
								else
								{
									echo "<input type='checkbox' name='allow_stop_traffic' value='allow'>";
								}
								
								echo "</form>";
								
								echo "</td><td>".ucfirst($row["name"])."</td><td>".$survey_respondent_status["respondent_counts"]["complete"]."</td><td>".$survey_respondent_status["respondent_counts"]["screened"]."</td><td>".$survey_respondent_status["respondent_counts"]["overquota"]."</td><td>".$survey_respondent_status["respondent_counts"]["incomplete"]."</td><td>$survey_loi</td><td>$survey_ir</td><td><a href='".VIEW_PATH."view_survey_details.php?survey_id=".$row["survey_id"]."' title='View Details'><img src='".ASSETS_PATH."images/open_file.png' title='View Survey'></a>";
								
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
                                echo "<li ".($i==$page ? "class = active": "")."><a href='".VIEW_PATH."user_dashboard.php?page=$i&filter_applied=yes&manager_name_filter=$manager_id&survey_status=$survey_status'>$i</a></li>";
                            }
                            ?>
                            <!--li><a href="#">&raquo;</a></li-->
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                
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
</body>
</html>
