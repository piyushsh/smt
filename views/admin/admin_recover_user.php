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



?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Survey Management Tool -- Admin Recove User</title>
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
                
                <div class="server_msg">
                	<?php 
						if(isset($_REQUEST) && isset($_REQUEST["user_account_recover_result"]))
						{
							if($_REQUEST["user_account_recover_result"]=="sucess")
							{
								echo "<p class='sucess'>User recovered sucessfully.</p>";
							}
							else if($_REQUEST["user_account_recover_result"]=="error")
							{
								echo "<p class='error'>Some error occured while recovering the user. Please try again later.</p>";
							}
						}
					?>
                </div>
                
                <table class="table">
                	<th>S. No.</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Operations</th>
                    <?php
						$users=new Admin_Users();
						$users=$users->get_List_Of_Removed_Users();
						
						$s_no=1;
						
						while($row=$users->fetch_array())
						{
							echo "<tr><td>$s_no</td><td>".$row["user_id"]."</td><td>".$row["name"]."</td><td>".$row["email_id"]."</td><td>".$row["contact_no"]."</td><td>";
							?>
                            <form action="<?php echo CONTROLLER_PATH."set_data/admin/admin_user_operations.php"; ?>" method="post" name="recover_user_form_<?php echo $row["user_id"];?>">
                            	<input type="hidden" name="recover_user" value="1">
                                <input type="hidden" name="user_id" value="<?php echo $row["user_id"];?>">
                            
                            <?php
                            echo "<img src='".ASSETS_PATH."images/recover_icon.jpg' title='Recover User' class='recover_user_but'></form></td></tr>";
							$s_no++;
						}
					?>
                </table>
                
                
                <hr>
                
                
                <div class="server_msg">
                		
                        
                        
                </div>
                
                <div class="row">
                	<div class="col-xs-6">
                                
                    </div>
                </div>
                
                
                
                
            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>
</body>
</html>
