<?php
session_start();
if(isset($_SESSION))
{
	session_destroy();
}
define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Survey Management Tool</title>
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

<div class="main_container">
	<div class="container">
    	<div class="row">
            <div class="col-xs-offset-4 col-xs-4">
            	
                <div class="server_msg">
                	<?php
                    if(isset($_REQUEST["user_reg"]) && $_REQUEST["user_reg"]=="sucess")
                    {
					?>
                    	<p class="sucess">You have been registered.</p>
					<?php
					}
					else if(isset($_REQUEST["user_reg"]) && $_REQUEST["user_reg"]=="failed")
					{
						?>
                        <p class="error">Some error occured while registering you! Please try again.</p>
                        <?php
					}
					else if(isset($_REQUEST["reg_err"]))
					{
						switch($_REQUEST["reg_err"])
						{
							case 'username_exist': 
													echo "<p class='error'>Username alredy exist. Please select some other username.</p>";
													break;
							case 'email_exist':
													echo "<p class='error'>This email address already exist. Please register with some other email address.</p>";
													break;
										
						}
					}
					else if(isset($_REQUEST["user_login_err"]))
					{
						switch($_REQUEST["user_login_err"])
						{
							case 'pass_incorrect':
													echo "<p class='error'>Username/Password is incorrect!</p>";
													break;
							case 'user_not_found':
													echo "<p class='error'>You are not registered! Please register first.</p>";
													break;
						}
					}
                    ?>
                </div>
				
                
                
                <div class="login_div">
                
                    <h2 align="center">LOGIN</h2>
                    <div class="validations">
                    	<p class="error"><?php echo "Username/Password is incorrect";?></p>
                        <p class="error" id="err_username">Please enter your username!</p>
                        <p class="error" id="err_password">Please enter your password!</p>
                    </div>
                    <form action="<?php echo CONTROLLER_PATH."get_data/login_user.php";?>" method="post" name="login">
                        <div class="form_field">
                            <div class="row">
                                <div class="col-xs-5 label">Username</div>
                                <div class="col-xs-7"><input type="text" name="username" id="username"></div>
                            </div>
                        </div>
                        <div class="form_field">
                            <div class="row">
                                <div class="col-xs-5 label">Password</div>
                                <div class="col-xs-7"><input type="password" name="password" id="password"></div>
                            </div>
                        </div>
                        <div align="center" class="form_field">                	
                            <div class="col-xs-12"><!--a href="#">Forgot Password?</a> <!--a href="#" id="register_user">Register</a--></div>
                            <div class="col-xs-12 form_field"><input type="button" value="LOGIN" id="login"></div>
                        </div>
                    </form>
                    
                </div>
                
                <!--div class="register_div">
                
                    <h2 align="center">SIGN UP</h2>
                    <div class="validations">
                    	<p class="error"><?php echo "Validations Error";?></p>
                        <p class="error" id="err_name">Please provide your Full Name!</p>
                        <p class="error" id="err_email">Enter a valid email address!</p>
                        <p class="error" id="err_contact_no">Enter a valid contact number!</p>
                        <p class="error" id="err_username">Please provide a Username! Username should have 6 - 12 characters.</p>
                        <p class="error" id="err_password">Please provide a Password! Password should be atleast of 6 characters.</p>
                        <p class="error" id="err_confirm_pass">Confirm your password correctly!</p>
                    </div>
                    <form action="<?php echo CONTROLLER_PATH."set_data/register_user.php";?>" method="post" name="sign_up">
                        <div class="form_field">
                            <div class="row">
                                <div class="col-xs-5 label">Full Name</div>
                                <div class="col-xs-7"><input type="text" name="full_name" id="full_name"></div>
                            </div>
                        </div>
                        <div class="form_field">
                            <div class="row">
                                <div class="col-xs-5 label">Email ID</div>
                                <div class="col-xs-7"><input type="text" name="email" id="email"></div>
                            </div>
                        </div>
                        
                        <div class="form_field">
                            <div class="row">
                                <div class="col-xs-5 label">Contact</div>
                                <div class="col-xs-7"><input type="text" name="contact_no" id="contact_no"></div>
                            </div>
                        </div>
                        
                        <div class="form_field">
                            <div class="row">
                                <div class="col-xs-5 label">Username</div>
                                <div class="col-xs-7"><input type="text" name="sign_in_username" id="sign_in_username"></div>
                            </div>
                        </div>
                        <div class="form_field">
                            <div class="row">
                                <div class="col-xs-5 label">Password</div>
                                <div class="col-xs-7"><input type="password" name="sign_in_password" id="sign_in_password"></div>
                            </div>
                        </div>
                        <div class="form_field">
                            <div class="row">
                                <div class="col-xs-5 label">Confirm Password</div>
                                <div class="col-xs-7"><input type="password" name="sign_in_confirm_pass" id="sign_in_confirm_pass"></div>
                            </div>
                        </div>
                        
                        <div align="center" class="form_field">                	
                            <div class="col-xs-12 form_field"><input type="button" value="SIGN IN" id="sign_in"> <a href="#" id="user_login" class="button">LOGIN</a></div>
                        </div>
                    </form>
                    
                </div-->
            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>
</body>
</html>
