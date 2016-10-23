
<div class="menu_container">
	<div class="container">
    	<div class="row">
        	<ul>
            	<li <?php if($active_menu==1) echo "class='active'";?>><a href="<?php echo VIEW_PATH."user_dashboard.php";?>">Dashboard</a></li>
                <li <?php if($active_menu==2) echo "class='active'";?>><a href="<?php echo VIEW_PATH."survey_operations.php";?>">Survey</a>
                	<!--ul class="sub_menu">
                    	<li><a href="">TEst</a></li>
                        <li><a href="">TEst</a></li>
                    </ul-->
                </li>
                <li <?php if($active_menu==3) echo "class='active'";?>><a href="<?php echo VIEW_PATH."vendor_operations.php";?>">Vendor</a></li>
                	<!--ul class="sub_menu">
                    	<li><a href="">TEst</a></li>
                        <li><a href="">TEst</a></li>
                    </ul-->
                </li>
                <li <?php if($active_menu==5) echo "class='active'";?>><a href="<?php echo VIEW_PATH."report_operations.php";?>">Report Generation</a></li>
                <li <?php if($active_menu==6) echo "class='active'";?>><a href="<?php echo VIEW_PATH."change_password.php";?>">Change Password</a></li>
                
                <li <?php if($active_menu==9) echo "class='active'";?>><a href="<?php echo VIEW_PATH."logout.php";?>">Logout</a></li>
            </ul>
        </div>
    </div>
</div>