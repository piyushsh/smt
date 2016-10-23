<?php
define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");

$active_menu=1;

include_once(INCLUDES_PATH."basic_config_site_admin.php");
include_once(CONTROLLER_PATH."get_data/admin/admin_user_operations.php");


$user_data=new Admin_Users();

if(!isset($_REQUEST["user_id"]))
{
	header("Location: ".VIEW_PATH."");
	exit;
}
else if(isset($_REQUEST["user_id"]))
{
	$user_data=$user_data->get_User_Details($_REQUEST["user_id"]);
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Survey Management Tool -- Edit User Details</title>
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/bootstrap.css">
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/style.css">

<script src="<?php echo ASSETS_PATH;?>script/jquery_1.11.js"></script>
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

<div class="main_container">
	<div class="container">
    	<div class="row">
            <div class="col-xs-12">
            	<h5>Modify User Details</h5>
                
                <br>
                
                <div class="row">
                	<div class="col-xs-6">
                    
                    	<div class="server_msg">
                        	<?php
								if(isset($_REQUEST["user_edit_result"]))
								{
									if($_REQUEST["user_edit_result"]=="sucess")
									{
										echo "<p class='sucess'>User details updated sucessfully.</p>";
									}
									else if($_REQUEST["user_edit_result"]=="error")
									{
										echo "<p class='error'>Some error occured, while updating the user details. Please try again.</p>";
									}
								}
								if(isset($_REQUEST["change_pass_error"]))
								{
									switch($_REQUEST["change_pass_error"])
									{
										case 'pass_confirm':
																	echo "<p class='error'>Please confirm your password correctly!</p>";
																	break;
									}
								}
							?>
                        </div>
                        
                        <div class="validations">
                        	<p class="error" id="err_name">Please enter a Name of the user!</p>
                            <p class="error" id="err_contact">Please enter contact number of the user!</p>
                        </div>
                    
                        <form action="<?php echo CONTROLLER_PATH."set_data/admin/admin_user_operations.php";?>" method="post" name="modify_user_details_form">
                        	<input type="hidden" name="modify_user_details" value="1">
                            <input type="hidden" name="user_id" value="<?php echo $user_data["user_id"];?>">
                            <div class="row">
                                <div class="col-xs-4"><label>Name:</label></div>
                                <div class="col-xs-8"><input type="text" name="name" id="name" value="<?php echo $user_data["name"];?>"></div>                    
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4"><label>Email:</label></div>
                                <div class="col-xs-8"><input type="text" name="email" id="email" value="<?php echo $user_data["email_id"];?>" disabled></div>                    
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4"><label>Contact:</label></div>
                                <div class="col-xs-8"><input type="text" name="contact" id="contact" value="<?php echo $user_data["contact_no"];?>"></div>                    
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4"><label>Username:</label></div>
                                <div class="col-xs-8"><input type="text" name="email" id="email" value="<?php echo $user_data["username"];?>" disabled></div>                    
                            </div>
                            <br>
                            <div align="center"><input type="button" class="button" value="Modify User Details" id="modify_user_detail"></div>
                        </form>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>
</body>
</html>
