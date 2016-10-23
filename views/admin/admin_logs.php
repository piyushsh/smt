<?php
define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");

$active_menu=2;

include_once(INCLUDES_PATH."basic_config_site_admin.php");
include_once(CONTROLLER_PATH."get_data/admin/admin_user_operations.php");
include_once(CONTROLLER_PATH."get_data/admin/admin_logs_operations.php");

$logs="";
if(isset($_POST["get_logs"]))
{
	$logs=new Logs();
	$logs=$logs->get_Logs($_POST["filter_logs"],$_POST["filter_user"]);
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Survey Management Tool -- Logs</title>
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/bootstrap.css">
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/style.css">
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/jquery_ui_css.css">

<script src="<?php echo ASSETS_PATH;?>script/jquery_1.11.js"></script>
<script src="<?php echo ASSETS_PATH;?>script/jquery_ui_1.10.js"></script>
<script src="<?php echo ASSETS_PATH;?>script/bootstrap.js"></script>

<script src="<?php echo ASSETS_PATH;?>script/admin_config_scripts.js"></script>

<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
<?php include_once(INCLUDES_PATH."header.php");?>
<?php include_once(INCLUDES_PATH."main_menu_admin.php");?>

<script>
$(document).ready(function(){
	var date=/^\d{1,2}\/\d{1,2}\/\d{4}$/;
	$("#date_from").datepicker();
	$("#date_to").datepicker();
	
	$("#get_logs").click(function(){
		if(!date.test($("#date_from").val()) || !date.test($("#date_to").val()))
		{
			alert("Please enter date in correct format i.e. DD/MM/YYYY");
			return false;
		}
		$("form[name='get_logs']").submit();
	});
});
</script>

<div class="main_container">
	<div class="container">
    	<div class="row">
            <div class="col-xs-12">
            	<h5>Logs</h5>
                
                <form action="<?php echo VIEW_PATH."admin/admin_logs.php";?>" method="post" name="get_logs">
                	<input type="hidden" name="get_logs" value="1">
                <div class="row">
                	<div class="col-xs-6">
                            <div class="form_field">
                                <div class="row">
                                    <div class="col-xs-5 label">Filter Logs</div>
                                    <div class="col-xs-7">
                                    			<select name="filter_logs" id="filter_logs">
                                                	<option value="">-- If Needed, Please select --</option>
                                                    <option value="all">All Type of Logs</option>
                                                    <option value="create">Creation Logs</option>
                                                    <option value="update">Updation Logs</option>
                                                    <option value="delete">Deletion Logs</option>
                                                    <option value="error">Error Logs</option>
                                                    <option value="others">Other Logs</option>
                                                </select>
                                    </div>
                                </div>
                            </div>


							<div class="form_field">
                                <div class="row">
                                    <div class="col-xs-5 label">Filter By User</div>
                                    <div class="col-xs-4">
												
                                    			<select name="filter_user" id="filter_user">
                                                	<option value="">-- If Needed, Please select --</option>
													
													<?php
														$users=new Admin_Users();
														$users=$users->get_List_Of_Users();
														while($row=mysql_fetch_array($users))
														{
															echo "<option value='".$row["user_id"]."'>".$row["name"]." (User ID: ".$row["user_id"].")</option>";
														}
													?>
                                                </select>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="form_field">
                                <div class="row">
                                    <div class="col-xs-5 label">From Date</div>
                                    <div class="col-xs-3"><input type="text" name="date_from" id="date_from"></div>
                                </div>
                            </div>
                            
                            <div class="form_field">
                                <div class="row">
                                    <div class="col-xs-5 label">To Date</div>
                                    <div class="col-xs-3"><input type="text" name="date_to" id="date_to"></div>
                                </div>
                            </div>
                            
                            <div class="form_field">
                                <div class="row">
                                	<div class="col-xs-10" align="center">
                                		<input type="button" class="button" value="Get Logs" id="get_logs">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                </form>
                
                <br><br>
                <div class="logs_data">
                	
						<?php 
                            if(isset($_POST["get_logs"]))
                            {
								?>
                                <table class="table">
                                    <tr>
                                        <th>Date</th>
                                        <th>User ID</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                    </tr>
                        <?php
								$flag=0;
                                while($row=mysql_fetch_array($logs))
                                {
									echo "";
                                    if(floatval($row["date"]) >= floatval(strtotime($_POST["date_from"])) && floatval($row["date"]) <= floatval(strtotime($_POST["date_to"])))
                                    {
										$flag=1;
                                        echo "<tr><td>".date("d/m/Y",$row["date"])."</td><td>".$row["user_id"]."</td><td>".ucwords($row["log_type"])."</td><td>".$row["log_message"]."</td></tr>";
                                    }
                                }
								if($flag==0)
								{
									echo "<tr><td colspan='4' align='center'><h2>No Logs present.</h2></td></tr>";
								}
								?>
                                </table>
                                
                                <?php
                                
                            }
                        ?>
                    
                	
                </div>
                
                
                
            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>
</body>
</html>
