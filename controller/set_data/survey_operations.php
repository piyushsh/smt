<?php

use controller\SurveyFilterController;

define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");
define("BASE_PATH","../../");
define("REPOSITORY_PATH","../../Repository/");

if(!defined("EVENT_PATH"))
	define("EVENT_PATH","../../Event/");
if(!defined("VENDOR_PATH"))
	define("VENDOR_PATH","../../vendor/");

session_start();

include_once(CONTROLLER_PATH."SurveyFilterController.php");
include_once(PLUGIN_PATH."PHPExcel-develop/Classes/PHPExcel.php");
include_once(MODEL_PATH."set_data/db-survey-operations.php");
require REPOSITORY_PATH.'/SurveyConfig.php';

class Survey
{
	public $survey_id;
	public $client_name="";
	public $survey_name="";
	public $survey_description="";
	public $survey_creation_date="";
	public $survey_modified_date="";
	public $survey_link_type="";
	public $survey_single_link="";
	public $survey_manager_id="";
	public $survey_created_by_id="";
	public $survey_country="";
	public $survey_allow_traffic=0;
	public $survey_multi_link_file=array();
	public $survey_re_contact_link_file=array();
	public $survey_quota=0;
	public $survey_respondent_click_quota=0;
	
	function set_New_Survey_Fields($survey_data,$file_uploaded) 
	{
		$this->client_name=$survey_data["client_name"];
		$this->survey_name=$survey_data["survey_name"];
		$this->survey_description=$survey_data["survey_description"];
		$this->survey_country=$survey_data["country"];
		
		if(isset($survey_data["allow_traffic"]) && $survey_data["allow_traffic"]=="allow")
		{
			$this->survey_allow_traffic=1;
		}
		else
		{
			$this->survey_allow_traffic=0;
		}
		
		if(isset($survey_data["survey_manager"]))
			$this->survey_manager_id=$survey_data["survey_manager"];
			
		$this->survey_created_by_id=$_SESSION["user_id"];
		
		if(isset($survey_data["survey_type"]))
			$this->survey_link_type=$survey_data["survey_type"];
			
		$this->survey_creation_date=time();
		$this->survey_modified_date=time();
		
		if(isset($survey_data["single_link"]))
			$this->survey_single_link=$survey_data["single_link"];

		if(isset($file_uploaded))
		{
			if($this->survey_link_type=="multi")
			{
				$this->survey_multi_link_file=$file_uploaded["multiple_link"];
			}
			else if($this->survey_link_type == "re_contact")
			{
				$this->survey_re_contact_link_file=$file_uploaded["re_contact_file"];
			}
		}
		
		//Changes Februrary, 2015
		$this->survey_quota=$survey_data["survey_quota"];
		$this->survey_respondent_click_quota=$survey_data["survey_respondent_click_quota"];
    }
	
	function validate_Form_Data()
	{
		if(trim($this->client_name)=="")
		{
			return "ERR_CLIENT_NAME_NO_VALUE";
		}
		else if(trim($this->survey_name)=="")
		{
			return "ERR_SURVEY_NAME_NO_VALUE";
		}
		else if(trim($this->survey_description)=="")
		{
			return "ERR_SURVEY_DESCRIPTION_NO_VALUE";
		}
		else if(trim($this->survey_link_type)=="")
		{
			return "ERR_SURVEY_LINK_TYPE_NO_VALUE";
		}
		else if(trim($this->survey_country)=="")
		{
			return "ERR_SURVEY_COUNTRY_NO_VALUE";
		}
		else if(trim($this->survey_link_type)=="single" && (trim($this->survey_single_link)=="" || !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $this->survey_single_link) || strpos($this->survey_single_link,"[IDENTIFIER]")===false))
		{
			return "ERR_SURVEY_SINGLE_LINK_IN_VALID";
		}
		else if(trim($this->survey_link_type)=="multi")
		{
			switch($this->validate_Multi_Links())
			{
				case 'ERR_SURVEY_MULTI_LINK_IN_VALID': 			return "ERR_SURVEY_MULTI_LINK_IN_VALID";
																break;
														
				case 'ERR_SURVEY_MULTI_LINK_FILE_NOT_LOADED': 	return "ERR_SURVEY_MULTI_LINK_FILE_NOT_LOADED";
																break;
			}
		}
		else if(trim($this->survey_link_type)=="re_contact")
		{
			if($error = $this->validate_Re_Contact_File())
			{
				return $error;
			}
		}
		else if(trim($this->survey_quota)=="" || trim($this->survey_respondent_click_quota)=="")
		{
			return "ERR_SURVEY_QUOTA_IN_VALID";
		}
		return true;
	}
	
	function validate_Multi_Links()
	{
		$tmp_name="";
		if(isset($this->survey_multi_link_file))
		{
			$tmp_name=$this->survey_multi_link_file["tmp_name"];
			
			
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objReader->setReadDataOnly(true);
			
			
			$objPHPExcel = $objReader->load($tmp_name);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			for ($row = 1; $row <= $highestRow; ++$row) 
			{
				for ($col = 0; $col < $highestColumnIndex; ++$col) 
				{
					if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",trim($objWorksheet->getCellByColumnAndRow($col, $row)->getValue())) || $this->check_IDENTIFIER_Parameter($objWorksheet->getCellByColumnAndRow($col, $row)->getValue())==0)
					{  
						return "ERR_SURVEY_MULTI_LINK_IN_VALID";
					}
				}
			}
			return false;

		}
		else
		{
			return "ERR_SURVEY_MULTI_LINK_FILE_NOT_LOADED";
		}
	}

	/**
	 *Validating Re-Contact File
     */
	function validate_Re_Contact_File()
	{
		$tmp_name="";
		if(isset($this->survey_re_contact_link_file))
		{
			$tmp_name=$this->survey_re_contact_link_file["tmp_name"];
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($tmp_name);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			for ($row = 1; $row <= $highestRow; ++$row)
			{
				//Checking 1st Column for HASH ID of File uploaded
				if(trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue()) == "")
				{
					return "ERR_SURVEY_RE_CONTACT_HASH_ID_INVALID";
				}
				//Checking 2nd Column for SURVEY LINK of File uploaded
				if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",
						trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue())))
				{
						return "ERR_SURVEY_RE_CONTACT_LINK_INVALID";
				}
			}
			return false;
		}
		else
		{
			return "ERR_SURVEY_RE_CONTACT_FILE_NOT_LOADED";
		}
	}
	
	function check_IDENTIFIER_Parameter($url)
	{
		$vars = array();
		$hash=array();
		$hashes = explode('&',$url.substr($url,strrpos($url,'?') + 1));
		$flag=0;
		for($i = 0; $i < count($hashes); $i++)
		{
			  $hash = explode('=',$hashes[$i]);
			  if($hash[1]=="[IDENTIFIER]")
			  {
				  $flag=1;
				  break;
				}
		  }
		  return $flag;
		
	}
	
	
	
	
	//Function to Modify/Update details of the Survey
	function modify_Survey_Details($survey_id)
	{
		$db_modify_survey=new DB_Survey();
		return $db_modify_survey->db_Modify_Survey_Details($this,$survey_id);
	}
	
	
	//Function to validate Data of modified details
	function validate_Form_Data_Modify_Survey()
	{
		if(trim($this->client_name)=="")
		{
			return "ERR_CLIENT_NAME_NO_VALUE";
		}
		else if(trim($this->survey_name)=="")
		{
			return "ERR_SURVEY_NAME_NO_VALUE";
		}
		else if(trim($this->survey_country)=="")
		{
			return "ERR_SURVEY_COUNTRY_NO_VALUE";
		}
		else if(trim($this->survey_description)=="")
		{
			return "ERR_SURVEY_DESCRIPTION_NO_VALUE";
		}
		else if(trim($this->survey_manager_id)=="")
		{
			return "ERR_SURVEY_MANAGER_NO_VALUE";
		}
		else if(trim($this->survey_link_type)=="single" && (trim($this->survey_single_link)=="" || !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $this->survey_single_link)))
		{
			return "ERR_SURVEY_SINGLE_LINK_IN_VALID";
		}
		else if(trim($this->survey_quota)=="" || trim($this->survey_respondent_click_quota)=="")
		{
			return "ERR_SURVEY_QUOTA_IN_VALID";
		}
		return "NO_ERROR";
	}
	
	
	
	//Function to validate links
	function validate_Form_Data_Set_Survey_Type($survey_data)
	{
		if($survey_data["set_survey_type"]=="")
		{
			return "ERR_NO_TYPE";
		}
		if($survey_data["set_survey_type"]=="single" && ($survey_data["single_link"]=="" || !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $survey_data["single_link"])) )
		{
			return "ERR_LINK_INVALID";
		}
		if($survey_data["set_survey_type"]=="multi")
		{
			if(isset($_FILES["multiple_link"]))
			{
				$this->survey_multi_link_file=$_FILES["multiple_link"];
			}
			else
			{
				return "ERR_SURVEY_MULTI_LINK_FILE_NOT_LOADED";
			}
			
			switch($this->validate_Multi_Links())
			{
				case 'ERR_SURVEY_MULTI_LINK_IN_VALID': 			return "ERR_SURVEY_MULTI_LINK_IN_VALID";
																break;
														
				case 'ERR_SURVEY_MULTI_LINK_FILE_NOT_LOADED': 	return "ERR_SURVEY_MULTI_LINK_FILE_NOT_LOADED";
																break;
			}
		}
		
		return "NO_ERROR";
	}
	
	
	
	
	
	/*Function to Allow or Stop Traffic*/
	function stop_Or_Allow_Traffic($data)
	{
		$allow=0;
		if(isset($data["allow_stop_traffic"]))
		{
			$allow=1;
		}
		$traffic=new DB_Survey();
		return $traffic->db_Stop_Or_Allow_Traffic($data["survey_id"],$allow);
		
	}
	
	
	
	
	/*Add More Multi Survey Links*/
	function add_More_Multi_Survey_Links($form_data)
	{
		$this->survey_multi_link_file=$_FILES["add_links"];
		$validate=$this->validate_Multi_Links();
		if(substr($validate,0,3)=="ERR")
		{
			return $validate;
		}
		$add_links=new DB_Survey();
		return $add_links->db_Add_More_Multi_Survey_Links($form_data,$_FILES["add_links"]);
	}
	
	
	
	/*Function to Modify Multi Survey Links*/
	function modify_Multi_Survey_Links($form_data)
	{
		$this->survey_multi_link_file=$_FILES["modify_links"];
		$validate=$this->validate_Id_And_Links_Multi_Survey_Links();
		if(!is_array($validate) && substr($validate,0,3)=="ERR")
		{
			return $validate;
		}
		$modify_links=new DB_Survey();
		return $modify_links->db_Modify_Multi_Survey_Links($form_data,$validate);
	}
	
	
	/*Function to validate Multi Survey Links*/
	function validate_Id_And_Links_Multi_Survey_Links()
	{
		$tmp_name="";
		$id_links=array();
		if(isset($this->survey_multi_link_file))
		{
			$tmp_name=$this->survey_multi_link_file["tmp_name"];
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($tmp_name);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			
			if($highestColumnIndex>2)
			{
				return "ERR_LINKS_FILE_NOT_IN_FORMAT";
			}

			for ($row = 1; $row <= $highestRow; ++$row) 
			{
				//Link IDs as given in Excel File
				$id_links[$objWorksheet->getCellByColumnAndRow(0, $row)->getValue()]=$objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
				for ($col = 0; $col < $highestColumnIndex; ++$col) 
				{
					if($col==0)
					{
						if(!is_numeric(trim($objWorksheet->getCellByColumnAndRow($col, $row)->getValue())))
						{
							return "ERR_LINK_ID_NOT_NUMERIC";
						}
					}
					if((!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",trim($objWorksheet->getCellByColumnAndRow($col, $row)->getValue())) || $this->check_IDENTIFIER_Parameter($objWorksheet->getCellByColumnAndRow($col, $row)->getValue())==0) && $col==1)
					{  
						return "ERR_SURVEY_MULTI_LINK_IN_VALID";
					}
				}
			}
			return $id_links;
		}
		else
		{
			return "ERR_SURVEY_MULTI_LINK_FILE_NOT_LOADED";
		}
	}

	/*Function to validate Re-Contact Survey Links*/
	function validate_Id_And_HashId_Re_Contact_Survey_Links()
	{
		$tmp_name="";
		$id_links=array();
		if(isset($this->survey_re_contact_link_file))
		{
			$tmp_name=$this->survey_re_contact_link_file["tmp_name"];
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($tmp_name);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

			if($highestColumnIndex>2)
			{
				return "ERR_LINKS_FILE_NOT_IN_FORMAT";
			}

			for ($row = 1; $row <= $highestRow; ++$row)
			{
				//Link IDs as given in Excel File
				$id_links[$objWorksheet->getCellByColumnAndRow(0, $row)->getValue()]=$objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
				if(!is_numeric(trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue())))
				{
					return "ERR_LINK_ID_NOT_NUMERIC";
				}
				if(preg_match("/^\\s*$/",trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue())))
				{
					return "ERR_SURVEY_RE_CONTACT_HASH_ID_EMPTY";
				}
			}
			return $id_links;
		}
		else
		{
			return "ERR_SURVEY_RE_CONTACT_LINK_FILE_NOT_LOADED";
		}
	}

	/*Function to validate Re-Contact Survey Links*/
	function validate_Id_And_Links_Re_Contact_Survey_Links()
	{
		$tmp_name="";
		$id_links=array();
		if(isset($this->survey_re_contact_link_file))
		{
			$tmp_name=$this->survey_re_contact_link_file["tmp_name"];
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($tmp_name);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

			if($highestColumnIndex>2)
			{
				return "ERR_LINKS_FILE_NOT_IN_FORMAT";
			}

			for ($row = 1; $row <= $highestRow; ++$row)
			{
				//Link IDs as given in Excel File
				$id_links[$objWorksheet->getCellByColumnAndRow(0, $row)->getValue()]=$objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
				if(!is_numeric(trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue())))
				{
					return "ERR_LINK_ID_NOT_NUMERIC";
				}
				if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue())))
				{
					return "ERR_SURVEY_RE_CONTACT_LINK_INVALID";
				}
			}
			return $id_links;
		}
		else
		{
			return "ERR_SURVEY_RE_CONTACT_LINK_FILE_NOT_LOADED";
		}
	}
	
	
	/*Function to delete Multi Survey Links*/
	function delete_Multi_Survey_Links($form_data)
	{
		$this->survey_multi_link_file=$_FILES["delete_links"];
		$validate=$this->validate_Id_And_Links_Multi_Survey_Links();
		if(!is_array($validate) && substr($validate,0,3)=="ERR")
		{
			return $validate;
		}
		$delete_links=new DB_Survey();
		return $delete_links->db_Delete_Multi_Survey_Links($form_data,$validate);
		
	}


	//Add More Re-Contact Survey Links
	function add_More_Re_Contact_Survey_Links($form_data){
		$this->survey_re_contact_link_file=$_FILES["re_contact_add_links"];
		$validate=$this->validate_Re_Contact_File();
		if(substr($validate,0,3)=="ERR")
		{
			return $validate;
		}
		$add_links=new DB_Survey();
		return $add_links->db_Add_More_Re_Contact_Survey_Links($form_data,$_FILES["re_contact_add_links"]);
	}

	/*Function to Modify Re-Contact Survey Links*/
	function modify_Re_Contact_Survey_Links($form_data)
	{
		$this->survey_re_contact_link_file=$_FILES["modify_re_contact_links"];
		$validate=$this->validate_Id_And_Links_Re_Contact_Survey_Links();
		if(!is_array($validate) && substr($validate,0,3)=="ERR")
		{
			return $validate;
		}
		$modify_links=new DB_Survey();
		return $modify_links->db_Modify_Re_Contact_Survey_Links($form_data,$validate);
	}

	/*Function to delete Re-Contact Survey Links*/
	function delete_Re_Contact_Survey_Links($form_data)
	{
		$this->survey_re_contact_link_file=$_FILES["delete_re_contact_links"];
		$validate=$this->validate_Id_And_HashId_Re_Contact_Survey_Links();
		if(!is_array($validate) && substr($validate,0,3)=="ERR")
		{
			return $validate;
		}
		$delete_links=new DB_Survey();
		return $delete_links->db_Delete_Re_Contact_Survey_Links($form_data,$validate);

	}
	
	
	
	/*Function to close the survey*/
	function close_Survey($survey_id)
	{
		$close_survey=new DB_Survey();
		return $close_survey->db_Close_Survey($survey_id);
	}
	
	
	
	/*Function to Re-Open the survey*/
	function reopen_Survey($survey_id)
	{
		$open_survey=new DB_Survey();
		return $open_survey->db_Reopen_Survey($survey_id);
	}
	
	
	
	//Function to Exclude of Include a vendor
	function exclude_Include_Vendor($data)
	{
		$exclude_include=new DB_Survey();
		return $exclude_include->db_Exclude_Include_Vendor($data);
	}
	
	
	
}

if(isset($_POST) && isset($_POST["create_survey"]))
{
	$create_survey=new Survey();
	$create_survey->set_New_Survey_Fields($_POST,$_FILES);
	$validated=$create_survey->validate_Form_Data();
	
	$_SESSION["survey_creation_form_field"]=array(
												"client_name"=>$create_survey->client_name,
												"survey_name"=>$create_survey->survey_name,
												"survey_description"=>$create_survey->survey_description,
												"survey_type"=>$create_survey->survey_link_type,
												"single_link"=>$create_survey->survey_single_link,
												"country"=>$create_survey->survey_country,
												"allow_traffic"=>$create_survey->survey_allow_traffic,
												"survey_quota"=>$create_survey->survey_quota,
												"survey_respondent_click_quota"=>$create_survey->survey_respondent_click_quota												
											);
											

	if(substr($validated,0,3)!="ERR")
	{
		$db_create_survey=new DB_Survey();
		if($db_create_survey->add_Survey($create_survey))
		{
			unset($_SESSION["survey_creation_form_field"]);
			//Recently created Survey
			$_SESSION["recently_created_survey"]=$create_survey->survey_id;

			//Adding Survey Filters in the survey.
			$surveyFilters = new SurveyFilterController($create_survey->survey_id, isset($_POST["country_filter"])
				, isset($_POST["duplicate_ip"]));
			$resultCountryFilter = $surveyFilters->storeCountryFilter($_POST);
			$resultDuplicateIP = $surveyFilters->storeDuplicateIPFilter($_POST);

			//If any error while saving country IP filter
			if($resultCountryFilter["country_filter_applied"] && !$resultCountryFilter["result"])
			{
				header("Location: ".VIEW_PATH."survey_operations.php?survey_create_result=sucess&country_ip_filter_error="
					.$resultCountryFilter["validation_error"]);
				exit;
			}
			//If any error occurred while saving Duplicate IP filter
			else if($resultDuplicateIP["duplicate_ip_filter_applied"] && !$resultDuplicateIP["result"])
			{
				header("Location: ".VIEW_PATH."survey_operations.php?survey_create_result=sucess&duplicate_ip_filter_error="
					.$resultDuplicateIP["validation_error"]);
				exit;
			}

			header("Location: ".VIEW_PATH."survey_operations.php?survey_create_result=sucess");
			exit;
		}
		else
		{
			header("Location: ".VIEW_PATH."survey_operations.php?survey_create_result=error");
			exit;
		}
	}
	else
	{
		header("Location: ".VIEW_PATH."survey_operations.php?survey_create_err=".$validated);
		exit;
	}
	
}


//Below code is for modifying the Survey Details
else if(isset($_POST) && (isset($_POST["modify_survey"]) && isset($_POST["survey_id"]) && $_POST["modify_survey"]==1))
{
	$survey_data=array(
					"client_name"=>$_POST["m_client_name"],
					"survey_name"=>$_POST["m_survey_name"],
					"survey_country"=>$_POST["m_country"],
					"survey_description"=>$_POST["m_survey_description"],
					"survey_type"=>$_POST["survey_type"],
					"single_link"=>$_POST["m_single_link"],
					"survey_manager"=>$_POST["m_survey_manager"],
					"survey_quota"=>$_POST["survey_quota"],
					"survey_respondent_click_quota"=>$_POST["survey_respondent_click_quota"]
				);
	
	$modify_survey=new Survey();
	
	$modify_survey->client_name=$survey_data["client_name"];
	$modify_survey->survey_name=$survey_data["survey_name"];
	$modify_survey->survey_country=$survey_data["survey_country"];
	$modify_survey->survey_description=$survey_data["survey_description"];
	$modify_survey->survey_link_type=$survey_data["survey_type"];
	$modify_survey->survey_single_link=$survey_data["single_link"];
	$modify_survey->survey_quota=$survey_data["survey_quota"];
	$modify_survey->survey_respondent_click_quota=$survey_data["survey_respondent_click_quota"];
	
	
	
	
	if(isset($_POST["m_allow_traffic"]) && $_POST["m_allow_traffic"]=="allow")
	{
		$modify_survey->survey_allow_traffic=1;
	}
	else
	{
		$modify_survey->survey_allow_traffic=0;
	}

	
	if(isset($_POST["m_single_link"]) && $_POST["m_single_link"]!='N/A')
	{
		$modify_survey->survey_single_link=$_POST["m_single_link"];
	}
	else
	{
		$modify_survey->survey_single_link='N/A';
	}
	$modify_survey->survey_manager_id=$survey_data["survey_manager"];
	
	$validated=$modify_survey->validate_Form_Data_Modify_Survey();
	if($validated=="NO_ERROR")
	{
		$modify_survey=$modify_survey->modify_Survey_Details($_POST["survey_id"]);
		if($modify_survey && substr($modify_survey,0,3)!="ERR")
		{
			header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&survey_modify_result=sucess");
			exit;
		}
		else
		{
			header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&survey_modify_result=$modify_survey");
			exit;
		}
	}
	else
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&survey_modify_err=".$validated);
		exit;
	}
}






if(isset($_POST) && isset($_POST["change_allow_traffic"]))
{
	$traffic=new Survey();
	$traffic=$traffic->stop_Or_Allow_Traffic($_POST);
	if($traffic)
	{
		header("Location: ".VIEW_PATH."survey_operations.php?allow_stop_traffic=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."survey_operations.php?allow_stop_traffic=error");
		exit;
	}
}

if(isset($_POST) && isset($_POST["change_allow_traffic_dashboard"]))
{
	$traffic=new Survey();
	$traffic=$traffic->stop_Or_Allow_Traffic($_POST);
	if($traffic)
	{
		header("Location: ".VIEW_PATH."user_dashboard.php?allow_stop_traffic=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."user_dashboard.php?allow_stop_traffic=error");
		exit;
	}
}


if(isset($_POST) && isset($_POST["add_multi_survey_links_form"]))
{
	$add_more_links=new Survey();
	$add_more_links=$add_more_links->add_More_Multi_Survey_Links($_POST);
	if($add_more_links && substr($add_more_links,0,3)!="ERR")
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&add_links_result=sucess");
		exit;
	}
	else if(substr($add_more_links,0,3)=="ERR")
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&add_links_err=$add_more_links");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&add_links_result=error");
		exit;
	}
}

if(isset($_POST) && isset($_POST["modify_multi_survey_links_form"]))
{
	$modify_links=new Survey();
	$modify_links=$modify_links->modify_Multi_Survey_Links($_POST);

	if($modify_links && substr($modify_links,0,3)!="ERR" && !is_array($modify_links))
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&multi_links_update_result=sucess");
		exit;
	}
	else if(is_array($modify_links))
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&multi_links_update_err=".$modify_links[0]."&link_id=".$modify_links[1]);
		exit;
	}
	else if(substr($modify_links,0,3)=="ERR")
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&multi_links_update_err=".$modify_links);
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&multi_links_update_result=error");
		exit;
	}
}

if(isset($_POST) && isset($_POST["delete_multi_survey_links_form"]))
{
	$delete_links=new Survey();
	$delete_links=$delete_links->delete_Multi_Survey_Links($_POST);
	
	if($delete_links && substr($delete_links,0,3)!="ERR" && !is_array($delete_links))
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&multi_links_delete_result=sucess");
		exit;
	}
	else if(is_array($delete_links))
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&multi_links_delete_err=".$delete_links[0]."&link_id=".$delete_links[1]);
		exit;
	}
	else if(substr($delete_links,0,3)=="ERR")
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&multi_links_delete_err=".$delete_links);
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&multi_links_delete_result=error");
		exit;
	}
}

//Modify Re-Contact Survey Links
if(isset($_POST) && isset($_POST["add_re_contact_survey_links_form"]))
{
	$add_more_links = new Survey();
	$add_more_links = $add_more_links->add_More_Re_Contact_Survey_Links($_POST);

	if($add_more_links && substr($add_more_links,0,3)!="ERR")
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&add_links_result=sucess");
		exit;
	}
	else if(substr($add_more_links,0,3)=="ERR")
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&add_links_err=$add_more_links");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&add_links_result=error");
		exit;
	}
}

if(isset($_POST) && isset($_POST["modify_re_contact_survey_links_form"]))
{
	$modify_links=new Survey();
	$modify_links=$modify_links->modify_Re_Contact_Survey_Links($_POST);

	if($modify_links && substr($modify_links,0,3)!="ERR" && !is_array($modify_links))
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&re_contact_links_update_result=sucess");
		exit;
	}
	else if(is_array($modify_links))
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&re_contact_links_update_err=".$modify_links[0]."&link_id=".$modify_links[1]);
		exit;
	}
	else if(substr($modify_links,0,3)=="ERR")
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&re_contact_links_update_err=".$modify_links);
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&re_contact_links_update_result=error");
		exit;
	}
}

if(isset($_POST) && isset($_POST["delete_re_contact_survey_links_form"]))
{
	$delete_links=new Survey();
	$delete_links=$delete_links->delete_Re_Contact_Survey_Links($_POST);

	if($delete_links && substr($delete_links,0,3)!="ERR" && !is_array($delete_links))
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&re_contact_links_delete_result=sucess");
		exit;
	}
	else if(is_array($delete_links))
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&re_contact_links_delete_err=".$delete_links[0]."&link_id=".$delete_links[1]);
		exit;
	}
	else if(substr($delete_links,0,3)=="ERR")
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&re_contact_links_delete_err=".$delete_links);
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."modify_survey_details.php?survey_id=".$_POST["survey_id"]."&re_contact_links_delete_result=error");
		exit;
	}
}

if(isset($_POST) && isset($_POST["close_survey"]))
{
	$close_survey=new Survey();
	$close_survey=$close_survey->close_Survey($_POST["survey_id"]);
	
	if($close_survey && substr($close_survey,0,3)!="ERR")
	{
		header("Location: ".VIEW_PATH."survey_operations.php?close_survey_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."view_survey_details.php?close_survey_err=$close_survey&survey_id=".$_POST["survey_id"]);
		exit;
	}
}

if(isset($_POST) && isset($_POST["open_survey"]))
{
	$open_survey=new Survey();
	$open_survey=$open_survey->reopen_Survey($_POST["survey_id"]);
	
	if($open_survey && substr($open_survey,0,3)!="ERR")
	{
		header("Location: ".VIEW_PATH."user_dashboard.php?open_survey_result=sucess");
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."user_dashboard.php?open_survey_err=$open_survey");
		exit;
	}
}



//Disallow or allow a vendor to send links to a survey

if(isset($_POST) && isset($_POST["exclude_include_vendor"]))
{
	$exclude_include_vendor=new Survey();
	$exclude_include_vendor=$exclude_include_vendor->exclude_Include_Vendor($_POST);
	$type=$_POST["include_vendor"];
	if($type==1)
	{
		$type="include";
	}
	else if($type==0)
	{
		$type="exclude";
	}
	if($exclude_include_vendor)
	{
		header("Location: ".VIEW_PATH."view_survey_details.php?vendor_exclude_include=sucess&type=$type&survey_id=".$_POST["survey_id"]);
		exit;
	}
	else
	{
		header("Location: ".VIEW_PATH."view_survey_details.php?vendor_exclude_include=error&type=$type&survey_id=".$_POST["survey_id"]);
		exit;
	}
}


?>
