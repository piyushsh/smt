
<div class="menu_container">
	<div class="container">
    	<div class="row">
        	<ul>
            	<li <?php if($active_menu==1) echo "class='active'";?>><a href="<?php echo VIEW_PATH."admin/admin_dashboard.php";?>">Users</a></li>
                <li <?php if($active_menu==2) echo "class='active'";?>><a href="<?php echo VIEW_PATH."admin/admin_recover_user.php";?>">Recover User</a></li>
                <!--li <?php if($active_menu==3) echo "class='active'";?>><a href="<?php echo VIEW_PATH."vendor_operations.php";?>">Delete User</a></li-->
                <!--li <?php if($active_menu==2) echo "class='active'";?>><a href="<?php echo VIEW_PATH."admin/admin_logs.php";?>">Logs</a></li-->
                <li <?php if($active_menu==3) echo "class='active'";?>><a href="<?php echo VIEW_PATH."admin/change_own_password.php";?>">Change Password</a></li>
                <li <?php if($active_menu==9) echo "class='active'";?>><a href="<?php echo VIEW_PATH."logout.php";?>">Logout</a></li>
            </ul>
        </div>
    </div>
</div>