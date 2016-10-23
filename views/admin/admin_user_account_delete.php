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
<title>Survey Management Tool -- Admin Dashboard</title>
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
            	<h5>Modify/Change Password</h5>
                
                <div class="row">
                	<div class="col-xs-2"><label>Name:</label></div>
                    <div class="col-xs-10"><?php echo $user_data["name"];?></div>                    
                </div>
                
                
                <div class="row">
                	<div class="col-xs-2"><label>User Name:</label></div>
                    <div class="col-xs-10"><?php echo $user_data["username"];?></div>                    
                </div>
                
                <div class="row">
                	<div class="col-xs-2"><label>Email:</label></div>
                    <div class="col-xs-10"><?php echo $user_data["email_id"];?></div>                    
                </div>
                
                <div class="row">
                	<div class="col-xs-2"><label>Contact Number:</label></div>
                    <div class="col-xs-10"><?php echo $user_data["contact_no"];?></div>                    
                </div>
                
                <br>
                
                <div class="row">
                	<div class="col-xs-6">
                    
                    	<div class="server_msg">
                        	<?php
								if(isset($_REQUEST["user_account_remove_result"]))
								{
									if($_REQUEST["user_account_remove_result"]=="sucess")
									{
										echo "<p class='sucess'>Account has been removed successfully.</p>";
									}
									else if($_REQUEST["user_account_remove_result"]=="error")
									{
										echo "<p class='error'>Some error occured, while removing the account. Please try again.</p>";
									}
								}
								
							?>
                        </div>
                        
                        <div class="validations">
                            <p class="error" id="err_delete_reason">Please provide the reason for deleting the account!</p>
                        </div>
                    
                        <form action="<?php echo CONTROLLER_PATH."set_data/admin/admin_user_operations.php";?>" method="post" name="delete_user_account_form">
                        	<input type="hidden" name="delete_user_account" value="1">
                            <input type="hidden" name="user_id" value="<?php echo $user_data["user_id"];?>">
                            
                            <div class="row">
                                <div class="col-xs-4"><label>Reason for deleting the Account:</label></div>
                                <div class="col-xs-8"><textarea name="reason" id="reason" rows="5"></textarea></div>                    
                            </div>
                            <br>
                            <div align="center"><input type="button" class="button" value="Delete User Account" id="delete_user_account_but"></div>
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
