<?php
session_start();

//error_reporting(E_ALL ^ E_WARNING);
error_reporting(0);


if(!isset($_SESSION["user_id"]))
{
	header("Location: ".VIEW_PATH."index.php");
	exit;
}
if(!isset($_SESSION["user_type"]) || $_SESSION["user_type"]!="user")
{
	header("Location: ".VIEW_PATH."index.php");
	exit;
}
?>