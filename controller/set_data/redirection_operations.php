<?php
/*define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");*/


include_once(MODEL_PATH."set_data/db-redirection-operations.php");

class Link_Redirections
{
	public $from="";
	public $identifier="";
	public $survey_id="";
	public $survey_status="";
	public $vendor_id="";
	public $hash_identifier="";
	public $ip_address="";
	
	function __construct($redirection_data) 
	{
		$this->from=$redirection_data["redirected_from"];
		$this->identifier=$redirection_data["identifier"];
		$this->survey_id=$redirection_data["survey_id"];
		if($this->from=="survey" && isset($redirection_data["status"]))
		{
			$this->survey_status=$redirection_data["status"];
		}
		if(isset($redirection_data["vid"]))
		{
			$this->vendor_id=$redirection_data["vid"];
		}
		$this->ip_address=$this->get_Client_IP();
    }
	
	//Function to set Status of respondent to Incomplete and get the redirection link to the survey
	function redirect_Respondent_To_Survey()
	{
		$redirection_link=new DB_Link_Redirection();
		$redirection_link=$redirection_link->db_Redirect_Respondent_To_Survey($this);
		return $redirection_link;
	}
	
	
	//Function to set Status of respondent to Screener or Complete, when redirected from survey. And further redirect it to Vendor
	function redirect_Survey_To_Vendor()
	{
		$redirect_vendor=new DB_Link_Redirection();
		$redirect_vendor=$redirect_vendor->db_Redirect_Survey_To_Vendor($this);
		return $redirect_vendor;
	}
	
	
	//Function to create Hash Identifier
	function create_Hash_Identifier($data,$key)
	{
		$algo="md5";
		return hash_hmac($algo,$data,$key);
	}
	
	
	function get_Client_IP()
	 {
		  $ipaddress = '';
		  if (getenv('HTTP_CLIENT_IP'))
			  $ipaddress = getenv('HTTP_CLIENT_IP');
		  else if(getenv('HTTP_X_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		  else if(getenv('HTTP_X_FORWARDED'))
			  $ipaddress = getenv('HTTP_X_FORWARDED');
		  else if(getenv('HTTP_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_FORWARDED_FOR');
		  else if(getenv('HTTP_FORWARDED'))
			  $ipaddress = getenv('HTTP_FORWARDED');
		  else if(getenv('REMOTE_ADDR'))
			  $ipaddress = getenv('REMOTE_ADDR');
		  else
			  $ipaddress = 'UNKNOWN';
	
		  return $ipaddress;
	 }
	
	
	
	
}





?>
