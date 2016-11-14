<?php

include_once(MODEL_PATH."db-config.php");


class DB_Survey_Read
{
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}

    function db_Get_Recent_Survey($number,$manager_id,$pageRequested,$survey_presented_limit)
	{
		//return mysql_query("select * from survey_table where survey_manager=".$_SESSION["user_id"]." order by survey_id DESC limit 0,".$number);
        $record_lower_limit = (($pageRequested-1)*$survey_presented_limit);
        $record_upper_limit = $survey_presented_limit;
		return $this->con->query("select * from survey_table survey inner join user_table user on survey.survey_manager=".$manager_id." AND survey.survey_manager=user.user_id AND survey.removed=0 limit $record_lower_limit,$record_upper_limit");
	}

    //Function to get total number of surveys
    function db_Get_Total_Number_Recent_Survey($number,$manager_id)
    {
        $query = $this->con->query("select count(*) as total_surveys from survey_table survey where survey.survey_manager=".$manager_id." AND survey.removed=0");
        $row = $query->fetch_array();
        return $row["total_surveys"];
    }



	function db_Get_Survey_Data()
	{
		return $this->con->query("select * from survey_table where removed=0");
	}
	
	//Getting all the details of a survey
	function db_Get_Survey_Details($survey_id)
	{
		$result_array=array();
		$query1=$this->con->query("select * from survey_table survey inner join user_table user on survey.survey_id=$survey_id AND survey.survey_manager=user.user_id");
		$query1=$query1->fetch_array();
		
		
		$query2=$this->con->query("select count(identifier) as total_identifier from survey_identifiers where survey_id=$survey_id");
		$query2=$query2->fetch_array();
		
		$result_array=array(
						"survey_detail"=>$query1,
						"identifier_count"=>$query2["total_identifier"]
					);
				
		return $result_array;
	}
	
	
	
	//Function to get Status of the survey like, no of completes, incompletes, dropouts, total no. of respondent took the survey, etc.
	function get_Survey_Status($survey_id)
	{
		$status=array();
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

		$redirection_link["complete"]="http://".$_SERVER['HTTP_HOST']."/".str_replace("\\","/",substr(getcwd(), strlen($_SERVER['DOCUMENT_ROOT'])))."/redirection_operations.php?survey_id=".$survey_id."&identifier=XXXXXXX&redirected_from=survey&status=complete";
		$redirection_link["screened"]="http://".$_SERVER['HTTP_HOST']."/".str_replace("\\","/",substr(getcwd(), strlen($_SERVER['DOCUMENT_ROOT'])))."/redirection_operations.php?survey_id=".$survey_id."&identifier=XXXXXXX&redirected_from=survey&status=screened";
		$redirection_link["overquota"]="http://".$_SERVER['HTTP_HOST']."/".str_replace("\\","/",substr(getcwd(), strlen($_SERVER['DOCUMENT_ROOT'])))."/redirection_operations.php?survey_id=".$survey_id."&identifier=XXXXXXX&redirected_from=survey&status=overquota";
		
		$status["redirection_link_survey"]=$redirection_link;
		$status["redirection_link_vendor"]="http://".$_SERVER['HTTP_HOST']."/"
            .str_replace("\\","/",substr(getcwd(), strlen($_SERVER['DOCUMENT_ROOT'])))."/redirection_operations.php?survey_id="
            .$survey_id."&identifier=XXXXXXX&vid=XXXXXXX&redirected_from=respondent&additional_param=XXX;YYY";
		
		
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
	
	
	//function to Filter the survey based upon number of factors
	function db_Get_Filter_Surveys($number,$manager_id,$survey_status,$created_by,$pageRequested,$survey_presented_limit)
	{
		$condition_string="";
		if($manager_id>0)
		{
			$condition_string.="s.survey_manager=$manager_id AND ";
		}
		if($manager_id==0 || $manager_id=='')
		{
			$condition_string.="s.survey_manager >= 0 AND ";
		}
		if($created_by>0)
		{
			$condition_string.="s.created_by=$created_by AND ";
		}
		if($survey_status>0)
		{
			$condition_string.="s.removed=$survey_status AND ";
		}
		else if($survey_status=="" || $survey_status==0)
		{
			$condition_string.="s.removed=0 AND ";
		}
		
		$condition_string=substr($condition_string,0,-4);
        $record_lower_limit = (($pageRequested-1)*$survey_presented_limit);
        $record_upper_limit = $survey_presented_limit;

		$query=$this->con->query("select * from survey_table s inner join user_table user on ".$condition_string." AND s.survey_manager=user.user_id limit $record_lower_limit,$record_upper_limit");
		return $query;
	}


    //Function to get total number of surveys (filtered at dashboard)
    function db_Get_Total_Number_Filter_Surveys($number,$manager_id,$survey_status,$created_by)
    {
        $condition_string="";
        if($manager_id>0)
        {
            $condition_string.="s.survey_manager=$manager_id AND ";
        }
        if($manager_id==0 || $manager_id=='')
        {
            $condition_string.="s.survey_manager >= 0 AND ";
        }
        if($created_by>0)
        {
            $condition_string.="s.created_by=$created_by AND ";
        }
        if($survey_status>0)
        {
            $condition_string.="s.removed=$survey_status AND ";
        }
        else if($survey_status=="" || $survey_status==0)
        {
            $condition_string.="s.removed=0 AND ";
        }

        $condition_string=substr($condition_string,0,-4);
        $query=$this->con->query("select count(*) as total_surveys from survey_table s where ".$condition_string);
        $row = $query->fetch_array();
        return $row["total_surveys"];
    }
	
	
	//Function to Export Survey report
	function db_Export_Survey_Report($survey_id)
	{
		$query1=$this->con->query("select * from survey_table where survey_id=$survey_id");
		$query2=$this->con->query("select * from survey_identifiers where survey_id=$survey_id");
		$data["survey_info"]=$query1->fetch_array();
		$data["survey_respondent_info"]=$query2;
		return $data;
	}
	
	
	
	//Function to Download Multi Survey Links
	function db_Download_Multi_Survey_Links($survey_id)
	{
		$query=$this->con->query("select * from $survey_id"."_multiplelink");
		return $query;
	}

    //Function to Download Re-Contact Survey Links
    function db_Download_Re_Contact_Survey_Links($survey_id)
    {
        $query=$this->con->query("select * from re_contact_survey_links where survey_id = $survey_id");
        return $query;
    }
	
	
	//Function to get Vendor Excluded from the survey
	function db_Get_Vendor_Excluded($survey_id)
	{
		$vendor_excluded=array();
		$query=$this->con->query("select * from survey_exclude_vendor where survey_id=$survey_id");
		while($row=$query->fetch_array())
		{
			array_push($vendor_excluded,$row["vendor_id"]);
		}
		return $vendor_excluded;
	}
	
}

?>