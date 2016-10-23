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



$name='';
$email='';
$contact='';
$username='';
if(isset($_SESSION["admin_create_user"]))
{
	$name=$_SESSION["admin_create_user"]["name"];
	$email=$_SESSION["admin_create_user"]["email"];
	$contact=$_SESSION["admin_create_user"]["contact"];
	$username=$_SESSION["admin_create_user"]["username"];
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
            	<h5>Users</h5>
                
                <br>
                
                <table class="table">
                	<th>S. No.</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Operations</th>
                    <?php
						$users=new Admin_Users();
						$users=$users->get_List_Of_Users();
						
						$s_no=1;
						
						while($row=$users->fetch_array())
						{
							echo "<tr><td>$s_no</td><td>".$row["user_id"]."</td><td>".$row["name"]."</td><td>".$row["email_id"]."</td><td>".$row["contact_no"]."</td><td><a href='".VIEW_PATH."admin/admin_change_password.php?user_id=".$row["user_id"]."'><img src='".ASSETS_PATH."images/pass_keys.png' title='Modify/Change Password'></a> <a href='".VIEW_PATH."admin/admin_user_account_delete.php?user_id=".$row["user_id"]."'><img src='".ASSETS_PATH."images/bin.png' title='Delete User'></a> <a href='".VIEW_PATH."admin/admin_edit_user_details.php?user_id=".$row["user_id"]."'><img src='".ASSETS_PATH."images/edit_icon.png' title='Edit User Detail'></a></td></tr>";
							$s_no++;
						}
					?>
                </table>
                
                
                <hr>
                
                <h5>Create New User</h5>
                
                
                <div class="server_msg">
                		<?php
							if(isset($_REQUEST["create_user_result"]) && $_REQUEST["create_user_result"]=="sucess")
							{
							?>
								<p class="sucess">New User created.</p>
							<?php
							}
							else if(isset($_REQUEST["create_user_result"]) && $_REQUEST["create_user_result"]=="failed")
							{
								?>
								<p class="error">Some error occured while registering you! Please try again.</p>
								<?php
							}
							else if(isset($_REQUEST["create_user_err"]))
							{
								switch($_REQUEST["create_user_err"])
								{
									case 'username_exist': 
															echo "<p class='error'>Username alredy exist. Please select some other username.</p>";
															break;
									case 'email_exist':
															echo "<p class='error'>This email address already exist. Please register with some other email address.</p>";
															break;
												
								}
							}
							
							?>
                        
                        
                </div>
                
                <div class="row">
                	<div class="col-xs-6">
                                <div class="validations">
                                    <p class="error"><?php echo "Validations Error";?></p>
                                    <p class="error" id="err_name">Please provide your Full Name!</p>
                                    <p class="error" id="err_email">Enter a valid email address!</p>
                                    <p class="error" id="err_contact_no">Enter a valid contact number!</p>
                                    <p class="error" id="err_username">Please provide a Username! Username should have 6 - 12 characters.</p>
                                    <p class="error" id="err_password">Please provide a Password! Password should be atleast of 6 characters.</p>
                                    <p class="error" id="err_confirm_pass">Confirm your password correctly!</p>
                                </div>
                                <form action="<?php echo CONTROLLER_PATH."set_data/admin/admin_register_user.php";?>" method="post" name="create_user">
                                    <div class="form_field">
                                        <div class="row">
                                            <div class="col-xs-5 label">Full Name</div>
                                            <div class="col-xs-7"><input type="text" name="full_name" id="full_name" value='<?php echo $name;?>'></div>
                                        </div>
                                    </div>
                                    <div class="form_field">
                                        <div class="row">
                                            <div class="col-xs-5 label">Email ID</div>
                                            <div class="col-xs-7"><input type="text" name="email" id="email" value='<?php echo $email;?>'></div>
                                        </div>
                                    </div>
                                    
                                    <div class="form_field">
                                        <div class="row">
                                            <div class="col-xs-5 label">Contact</div>
                                            <div class="col-xs-7"><input type="text" name="contact_no" id="contact_no" value='<?php echo $contact;?>'></div>
                                        </div>
                                    </div>
                                    
                                    <div class="form_field">
                                        <div class="row">
                                            <div class="col-xs-5 label">Username</div>
                                            <div class="col-xs-7"><input type="text" name="sign_in_username" id="sign_in_username" value='<?php echo $username;?>'> </div>
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
                                        <div class="col-xs-12 form_field"><input type="button" value="Create User" id="create_user"></div>
                                    </div>
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
