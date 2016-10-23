<?php
session_start();

error_reporting(E_ALL ^ E_WARNING);

if(!isset($_SESSION["user_id"]))
{
	header("Location: ".VIEW_PATH."index.php");
	exit;
}
if(!isset($_SESSION["user_type"]) || $_SESSION["user_type"]!="admin")
{
	header("Location: ".VIEW_PATH."index.php");
	exit;
}
?>