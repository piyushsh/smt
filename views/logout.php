<?php
define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");

session_start();
session_destroy();
header("Location: ".VIEW_PATH."index.php");
?>