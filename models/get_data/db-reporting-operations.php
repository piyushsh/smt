<?php

include_once(MODEL_PATH."db-config.php");


class Db_Reports
{
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	
	
	//Function to create Ist sheet of survey Report vendor wise
	function db_Get_Survey_Status_Report($survey_id)
	{
		$status=array();
		
		$survey_info=array();
		$query1=$this->con->query("select * from survey_table survey inner join user_table user on survey.survey_id=$survey_id AND survey.survey_manager=user.user_id");
		$row=$query1->fetch_array();
		
		$survey_info["alpha_id"]=$row["alpha_survey_id"];
		$survey_info["survey_name"]=$row["survey_name"];
		$survey_info["country"]=$row["country"];
		$survey_info["survey_description"]=$row["survey_description"];
		$survey_info["survey_manager"]=$row["name"];
		
		$status["survey_info"]=$survey_info;
		
		$respondent_count=array();
		$query=$this->con->query("SELECT respondent_status,count(respondent_status) as total_count FROM survey_identifiers where survey_id=$survey_id group by respondent_status");
		while($row=$query->fetch_array())
		{
			if($row["respondent_status"]=="not started")
			{
				$respondent_count["not_started"]=$row["total_count"];
			}
			else if($row["respondent_status"]=="incomplete")
			{
				$respondent_count["incomplete"]=$row["total_count"];
			}
			else if($row["respondent_status"]=="screened")
			{
				$respondent_count["screened"]=$row["total_count"];
			}
			else if($row["respondent_status"]=="overquota")
			{
				$respondent_count["overquota"]=$row["total_count"];
			}
			else if($row["respondent_status"]=="complete")
			{
				$respondent_count["complete"]=$row["total_count"];
			}			
		}
		if(!isset($respondent_count["not_started"]))
		{
			$respondent_count["not_started"]=0;
		}
		if(!isset($respondent_count["incomplete"]))
		{
			$respondent_count["incomplete"]=0;
		}
		if(!isset($respondent_count["screened"]))
		{
			$respondent_count["screened"]=0;
		}
		if(!isset($respondent_count["complete"]))
		{
			$respondent_count["complete"]=0;
		}
		if(!isset($respondent_count["overquota"]))
		{
			$respondent_count["overquota"]=0;
		}
		
		$status["respondent_counts"]=$respondent_count;
		
		$vendor_wise_survey_status=array();
		
		$query=$this->con->query("select si.vendor_linked,vt.vendor_id,vt.vendor_name from survey_identifiers si inner join vendor_table vt on si.survey_id=$survey_id and si.vendor_linked = vt.alpha_vendor_id group by vendor_linked");
		while($row=$query->fetch_array())
		{
			$vendor_wise_survey_status[$row["vendor_linked"]]["vendor_id"]=$row["vendor_id"];
			$vendor_wise_survey_status[$row["vendor_linked"]]["vendor_name"]=$row["vendor_name"];
			$vendor_wise_survey_status[$row["vendor_linked"]]["incomplete"]=0;
			$vendor_wise_survey_status[$row["vendor_linked"]]["screened"]=0;
			$vendor_wise_survey_status[$row["vendor_linked"]]["overquota"]=0;
			$vendor_wise_survey_status[$row["vendor_linked"]]["complete"]=0;
			
			$total_respondents=0;
			
			$query1=$this->con->query("select respondent_status,count(respondent_status) as total_count FROM survey_identifiers where vendor_linked='".$row["vendor_linked"]."' and survey_id=$survey_id group by respondent_status");
			while($result=$query1->fetch_array())
			{
				$total_respondents+=$result["total_count"];
				if($result["respondent_status"]=="incomplete")
					$vendor_wise_survey_status[$row["vendor_linked"]]["incomplete"]=$result["total_count"];
				
				if($result["respondent_status"]=="screened")
					$vendor_wise_survey_status[$row["vendor_linked"]]["screened"]=$result["total_count"];
					
				if($result["respondent_status"]=="overquota")
					$vendor_wise_survey_status[$row["vendor_linked"]]["overquota"]=$result["total_count"];
					
				if($result["respondent_status"]=="complete")
					$vendor_wise_survey_status[$row["vendor_linked"]]["complete"]=$result["total_count"];
			}
			$vendor_wise_survey_status[$row["vendor_linked"]]["total_links"]=$total_respondents;
		}
		
		$status["vendor_wise_survey_status"]=$vendor_wise_survey_status;
		
		return $status;
	}
	
	
	//Function to generate survey report vendor wise ---- Full Survey Report (Vendor wise)
	function db_Generate_Survey_Report_Vendor_Wise($survey_id)
	{
		$identifier_table_name="survey_identifiers";
		
		$query1=$this->con->query("select * from survey_identifiers sid inner join vendor_table vt on sid.vendor_linked=vt.alpha_vendor_id and sid.survey_id=$survey_id");
		
		$count_vendor=1;
		$vendor_ids_ar=array();
		$data=array();
		$index=0;
		while($row=$query1->fetch_array())
		{
			if(!in_array($row["alpha_vendor_id"],$vendor_ids_ar))
			{
				$vendor_ids_ar[$index]=$row["alpha_vendor_id"];
				$count_vendor=$row["alpha_vendor_id"];
				$data[$count_vendor]["name"]=$row["vendor_name"];
				$data[$count_vendor]["vendor_id"]=$row["alpha_vendor_id"];
				$data[$count_vendor]["respondent_details"]["completes"]=0;
				$data[$count_vendor]["respondent_details"]["teminates"]=0;
				$data[$count_vendor]["respondent_details"]["overquotas"]=0;
				$data[$count_vendor]["respondent_details"]["incompletes"]=0;
				$index++;
				
				$query2=$this->con->query("select * from survey_identifiers where survey_id=$survey_id and vendor_linked = '".$row["alpha_vendor_id"]."'");
				$data[$count_vendor]["identifiers_array"]=$query2;
				
			}
			
			if($row["respondent_status"]=="complete")
			{
				$data[$row["vendor_linked"]]["respondent_details"]["completes"]++;
			}
			else if($row["respondent_status"]=="screened")
			{
				$data[$row["vendor_linked"]]["respondent_details"]["teminates"]++;
			}
			else if($row["respondent_status"]=="overquota")
			{
				$data[$row["vendor_linked"]]["respondent_details"]["overquotas"]++;
			}
			else if($row["respondent_status"]=="incomplete")
			{
				$data[$row["vendor_linked"]]["respondent_details"]["incompletes"]++;
			}
		}
		
		return $data;
	}
	
}

?>