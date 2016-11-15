<?php
define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");
define("PLUGIN_PATH","../plugin/");

$active_menu=7;

include_once(INCLUDES_PATH."basic_config_site.php");



?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Survey Management Tool -- Change Password</title>
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
            			<div class="server_msg">
                        	<?php
								if(isset($_REQUEST["change_pass_result"]))
								{
									if($_REQUEST["change_pass_result"]=="sucess")
									{
										echo "<p class='sucess'>Your password has been changed sucessfully.</p>";
									}
									else if($_REQUEST["change_pass_result"]=="error")
									{
										echo "<p class='error'>Some error occured, while changing the password. Please try again.</p>";
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
                        
                        <div class="row">
                        <div class="col-xs-6">
                            <div class="validations">
                                <p class="error" id="err_new_password">Please enter atleast 6 characters for your new password!</p>
                                <p class="error" id="err_confirm_pass">Confirm your password correctly!</p>
                            </div>
                        
                            <form action="<?php echo CONTROLLER_PATH."set_data/user_operations.php";?>" method="post" name="change_password_form">
                                <input type="hidden" name="change_password" value="1">
                                <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"];?>">
                                <div class="row">
                                    <div class="col-xs-4"><label>New Password:</label></div>
                                    <div class="col-xs-8"><input type="password" name="new_password" id="new_password"></div>                    
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xs-4"><label>Confirm Password:</label></div>
                                    <div class="col-xs-8"><input type="password" name="confirm_password" id="confirm_password"></div>                    
                                </div>
                                <br>
                                <div align="center"><input type="button" class="button" value="Modify Password" id="change_password_but"></div>
                            </form>
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
