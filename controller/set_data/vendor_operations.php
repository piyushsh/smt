<?php
define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");

session_start();


include_once(MODEL_PATH."set_data/db-vendor-operations.php");

class Vendor
{
	public $vendor_name="";
	public $vendor_website="";
	public $vendor_description="";
	public $vendor_contact_no="";
	public $vendor_email="";
	public $vendor_addition_date="";
	public $vendor_modified_date="";
	public $vendor_panel_size="";
	public $redirect_complete="";
	public $redirect_terminate="";
	public $redirect_quotafull="";
	public $vendor_country=array();
	
	function initiallize_Vendor_Data($vendor_data) 
	{
		$this->vendor_name=$vendor_data["vendor_name"];
		$this->vendor_website=$vendor_data["vendor_site"];
		$this->vendor_description=$vendor_data["vendor_description"];
		$this->vendor_contact_no=$vendor_data["vendor_contact_no"];
		$this->vendor_email=$vendor_data["vendor_email"];
		$this->vendor_addition_date=time();
		$this->vendor_modified_date=time();
		$this->vendor_panel_size=$vendor_data["vendor_panel_size"];
		$this->redirect_complete=$vendor_data["redirect_complete"];
		$this->redirect_terminate=$vendor_data["redirect_terminate"];
		$this->redirect_quotafull=$vendor_data["redirect_quotafull"];
		$count=0;
		for($i=0;$i<$vendor_data["country_selected_no"];$i++)
		{
			$country_id="country_".($i+1);
			$country_panel_count="country_size_".($i+1);
			$this->vendor_country[$i]=$vendor_data[$country_id]." # ".$vendor_data[$country_panel_count];
		}
		$_SESSION["vendor_creation_form_details"]=array(
													"vendor_name"=>$vendor_data["vendor_name"],
													"vendor_website"=>$vendor_data["vendor_site"],
													"vendor_description"=>$vendor_data["vendor_description"],
													"vendor_contact_no"=>$vendor_data["vendor_contact_no"],
													"vendor_email"=>$vendor_data["vendor_email"],
													"vendor_panel_size"=>$vendor_data["vendor_panel_size"],
													"redirect_complete"=>$vendor_data["redirect_complete"],
													"redirect_terminate"=>$vendor_data["redirect_terminate"],
													"redirect_quotafull"=>$vendor_data["redirect_quotafull"]
												);
    }
	
	function validate_Form_Data()
	{
		if(trim($this->vendor_name)=="")
		{
			return "ERR_VENDOR_NAME_NO_VALUE";
		}
		else if(trim($this->vendor_website)=="" || !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$this->vendor_website))
		{
			return "ERR_VENDOR_WEBSITE_IN_VALID";
		}
		else if(trim($this->vendor_contact_no)=="")
		{
			return "ERR_VENDOR_CONTACT_NO_VALUE";
		}
		else if(trim($this->vendor_email)=="" || !preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$this->vendor_email))
		{
			return "ERR_VENDOR_EMAIL_IN_VALID";
		}
		else if(trim($this->vendor_description)=="")
		{
			return "ERR_VENDOR_DESCRIPTION_NO_VALUE";
		}
		else if(trim($this->vendor_panel_size)=="")
		{
			return "ERR_VENDOR_PANEL_SIZE_NO_VALUE";
		}
		else if(count($this->vendor_country)==0)
		{
			return "ERR_VENDOR_COUNTRY_NO_VALUE";
		}
		else if(trim($this->vendor_website)=="" || !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$this->redirect_complete))
		{
			return "ERR_VENDOR_REDIRECT_COMPLETE_VALID";
		}
		else if(trim($this->vendor_website)=="" || !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$this->redirect_terminate))
		{
			return "ERR_VENDOR_REDIRECT_TERMINATE_VALID";
		}
		else if(trim($this->vendor_website)=="" || !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$this->redirect_quotafull))
		{
			return "ERR_VENDOR_REDIRECT_QUOTAFULL_VALID";
		}
		return "No_ERROR";
	}
	
	
	
	
	/*Function to remove vendor*/
	function remove_Vendor($vendor_id)
	{
		$remove_vendor=new DB_Vendor();
		return $remove_vendor->db_Remove_Vendor($vendor_id);
	}
	
	
}

if(isset($_POST) && isset($_POST["add_vendor"]))
{
	$add_vendor=new Vendor();
	$add_vendor->initiallize_Vendor_Data($_POST);
	
	$validated=$add_vendor->validate_Form_Data();
	
	if($validated=="No_ERROR")
	{
		$db_add_vendor=new DB_Vendor();
		
		if($db_add_vendor->add_Vendor($add_vendor))
		{
			unset($_SESSION["vendor_creation_form_details"]);
			header("Location: ".VIEW_PATH."vendor_operations.php?vendor_add_result=sucess");
			exit;
		}
		else
		{
			header("Location: ".VIEW_PATH."vendor_operations.php?vendor_add_result=error");
			exit;
		}
	}
	else
	{
		header("Location: ".VIEW_PATH."vendor_operations.php?survey_create_err=".$validated);
		exit;
	}
	
}


if(isset($_POST) && isset($_POST["modify_vendor"]))
{
	$modify_vendor=new Vendor($_POST);
	$modify_vendor->initiallize_Vendor_Data($_POST);
	$validated=$modify_vendor->validate_Form_Data();
	
	if($validated=="No_ERROR")
	{
		$db_modify_vendor=new DB_Vendor();
		
		if($db_modify_vendor->modify_Vendor($modify_vendor,$_POST["vendor_id"]))
		{
			unset($_SESSION["vendor_creation_form_details"]);
			header("Location: ".VIEW_PATH."modify_vendor_details.php?vendor_id=".$_POST["vendor_id"]."&vendor_modify_result=sucess");
			exit;
		}
		else
		{
			header("Location: ".VIEW_PATH."modify_vendor_details.php?vendor_id=".$_POST["vendor_id"]."&vendor_modify_result=error");
			exit;
		}
	}
	else
	{
		header("Location: ".VIEW_PATH."modify_vendor_details.php?vendor_id=".$_POST["vendor_id"]."&vendor_modify_err=".$validated);
		exit;
	}
	
}



if(isset($_POST) && isset($_POST["remove_vendor"]))
{
	$remove_vendor=new Vendor();
	$remove_vendor=$remove_vendor->remove_Vendor($_POST["vendor_id"]);
	if($remove_vendor && substr($remove_vendor,0,3)!="ERR")
	{
		header("Location: ".VIEW_PATH."vendor_operations.php?vendor_removed_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."vendor_operations.php?vendor_removed_result=error");
		exit;
	}
}



?>
