<?php

include_once(MODEL_PATH."db-config.php");


class DB_Link_Redirection
{
	
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	//Function to check the status of the survey, validate Identifier and set the status of the link and identifier, , when respondent visits our application
	//Getting the redirection link for respondent
	function db_Redirect_Respondent_To_Survey($data)
	{	
		$identifier_table="survey_identifiers";
		
		$survey_status=$this->con->query("select * from survey_table where survey_id='".$data->survey_id."'");
		$survey_status=$survey_status->fetch_array();
		
		$survey_link_status=$this->get_Survey_Link_Counts($data->survey_id);
		
		if($survey_status["allow_traffic"]=="1")
		{
			
		}
		else
		{
			return "ERR_SURVEY_NOT_OPEN";
		}
		
		if(!$this->check_Vendor_Exists($data->vendor_id,$data->survey_id))
		{
			return "ERR_VENDOR_NOT_EXIST";
		}
		
		if($survey_link_status["complete"]>=$survey_status["interviewquota"] && $survey_status["interviewquota"]!=0)
		{
			return "ERR_SURVEY_QUOTA_OVER";
		}
		if(($survey_link_status["complete"]+$survey_link_status["incomplete"]+$survey_link_status["screened"]+$survey_link_status["overquota"])>=$survey_status["respondentvisitquota"] && $survey_status["respondentvisitquota"]!=0)
		{
			return "ERR_SURVEY_RESPONDENT_CLICK_QUOTA_OVER";
		}
		
		
		
		
		$data->hash_identifier=$data->create_Hash_Identifier($data->identifier,$data->vendor_id);
		
	
		$this->con->query("BEGIN TRANSACTION");
		$this->con->autocommit(FALSE);
		
		$query1=0;
		$query2=0;
		$query3=0;
		$query4=0;
		$query5=0;
		$flag=0; // Respondent never took the survey link
		$error='';
		$redirection_link='';
		
		$query1=$this->con->query("select * from $identifier_table where identifier='$data->identifier' and vendor_linked='$data->vendor_id' and survey_id=$data->survey_id");
		if($query1->num_rows<=0)
		{
			$query2=$this->con->query("insert into $identifier_table values ($data->survey_id,'$data->vendor_id','$data->identifier','$data->hash_identifier','incomplete','$data->ip_address','".date("d-m-Y H:i:s")."','')");
		}
		else if($query1->num_rows>0)
		{
			$query2=true;
			$row=$query1->fetch_array();
			if($row["respondent_status"]=="incomplete")
			{
				$flag=1; // Respondent Previously took the survey
			}
			else
			{
				$this->con->query("ROLLBACK");
				$this->con->autocommit(TRUE);
				$error="ERR_RESPONDENT_TOOK_SURVEY";
				$flag=-1;
				return "ERR_RESPONDENT_ALREADY_COMPLETED_SURVEY";
			}
		}		
		
		
		if($flag==1)
		{
			$query3=$this->con->query("select * from survey_table where survey_id='".$data->survey_id."'");
			$row=$query3->fetch_array();
			//Single Survey Link
			if($row["multi_link_table_name"]=='N/A' && $row["single_link_url"]!='N/A')
			{
				$redirection_link=str_replace("[IDENTIFIER]" ,$data->hash_identifier,$row["single_link_url"]);
				$query4=true;
				$query5=true;
			}
			//Multiple Survey Link
			else if($row["single_link_url"]=='N/A' && $row["multi_link_table_name"]!='N/A')
			{
				$query4=$this->con->query("select * from ".$data->survey_id."_multiplelink where used_by_identifier='".$data->identifier."'");
				$query5=true;
				while($row=$query4->fetch_array())
				{
					$redirection_link=str_replace("[IDENTIFIER]" ,$data->hash_identifier,$row["link"]);
					
					break;
				}
			}
			//Re-Contact Survey Link
			else if($row["re_contact_links"]==1)
			{
				$query4=$this->con->query("select * from re_contact_survey_links where survey_id = ".$data->survey_id.
					" AND user_hash_id = '".$data->hash_identifier."'");
				$query5=true;
				$row=$query4->fetch_array();
				$redirection_link=$row["survey_link"];
			}
		}
		else if($flag==0)
		{
			$query3=$this->con->query("select * from survey_table where survey_id='".$data->survey_id."'");
			$row=$query3->fetch_array();

			//Single Link Survey
			if($row["multi_link_table_name"]=='N/A' && $row["single_link_url"]!='N/A')
			{
				$redirection_link=str_replace("[IDENTIFIER]" ,$data->hash_identifier,$row["single_link_url"]);
				$query4=true;
				$query5=true;
			}
			//Multiple Link Survey
			else if($row["single_link_url"]=='N/A' && $row["multi_link_table_name"]!='N/A')
			{
				$query4=$this->con->query("select * from ".$data->survey_id."_multiplelink where used_or_not=0");
				while($row=$query4->fetch_array())
				{
					$redirection_link=str_replace("[IDENTIFIER]" ,$data->hash_identifier,$row["link"]);
					$query5=$this->con->query("update ".$data->survey_id."_multiplelink set used_or_not=1, used_by_identifier='".$data->identifier."', identifier_vendor='".$data->vendor_id."' where link_id='".$row["link_id"]."'");
					break;
				}
			}
			//Re-Contact Survey Link
			else if($row["re_contact_links"]==1)
			{
				$query4=$this->con->query("select * from re_contact_survey_links where survey_id = ".$data->survey_id.
					" AND user_hash_id = '".$data->hash_identifier."'");
				$query5=true;
				$row=$query4->fetch_array();
				$redirection_link=$row["survey_link"];
			}
		}
		
		
		//var_dump($query1);var_dump($query2);var_dump($query3);var_dump($query4);var_dump($query5);
		
		if($redirection_link!='' && $query1 && $query2 && $query3 && $query4 && $query5)
		{
			$this->con->commit();
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',0,'update','Operation: Redirection (Traverse -> Survey). Repondent(ID: ".$data->identifier.") has been redirected to the survey (ID:".$data->survey_id."). Status marked as Incomplete.')");
			return $redirection_link;
		}
		else
		{
			$this->con->query("ROLLBACK");
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',0,'update','Operation: Redirection (Traverse -> Survey). Repondent(ID: ".$data->identifier.") is unable to redirected to the survey (ID:".$data->survey_id."). Due to some error.')");
			return $error;
		}
	}
	
	
	
	//Function to update the status of the survey, validate Identifier and set the status of the link and identifier, when redirected from survey to the application
	//Getting the redirection link to redirect to vendor
	function db_Redirect_Survey_To_Vendor($data)
	{	
		$identifier_table="survey_identifiers";
		
		if($data->survey_status!="complete" && $data->survey_status!="screened" && $data->survey_status!="overquota")
		{
			return "ERR_INVALID_STATUS";
		}
	
		$this->con->query("BEGIN TRANSACTION");
		$this->con->autocommit(FALSE);
		
		$query1=0;
		$query2=0;
		$query3=0;
		$query4=0;
		$query5=0;
		$vendor_id='';
		$flag=0;
		$error='';
		$redirection_link='';
		$respondent_status="";
		$identifier_to_vendor="";
		
		$query1=$this->con->query("select * from $identifier_table where hash_identifier='".$data->identifier."' and survey_id=$data->survey_id");
		while($row=$query1->fetch_array())
		{
			if($row["hash_identifier"]==$data->identifier && $row["respondent_status"]=="incomplete")
			{
				$query2=$this->con->query("update $identifier_table set respondent_status='".$data->survey_status."',survey_end_date='".date("d-m-Y H:i:s")."' where hash_identifier='".$data->identifier."' and survey_id=$data->survey_id");
				
				$flag=1;//Specifiying that respondent status is updated
				$vendor_id=$row['vendor_linked'];
				$respondent_status=$data->survey_status;
				
				$identifier_to_vendor=$row["identifier"];
				break;				
			}
			else if($row["hash_identifier"]==$data->identifier && ($row["respondent_status"]=="screened" || $row["respondent_status"]=="complete" || $row["respondent_status"]=="overquota"))
			{
				$flag=2;//Specifiying that respondent status is updated already
				$query2=true;
				$vendor_id=$row['vendor_linked'];
				$respondent_status=$row["respondent_status"];
				$identifier_to_vendor=$row["identifier"];
			}
		}
		if($flag==1 || $flag==2)
		{
			$query3=$this->con->query("select * from vendor_table where alpha_vendor_id='".$vendor_id."'");
			$row=$query3->fetch_array();
			
			if($row["vendor_id"] > 0 && $respondent_status=="complete")
			{
				$redirection_link=str_replace("[IDENTIFIER]" ,$identifier_to_vendor,$row["redirect_complete"]);
			}
			else if($row["vendor_id"] > 0 && $respondent_status=="screened")
			{
				$redirection_link=str_replace("[IDENTIFIER]" ,$identifier_to_vendor,$row["redirect_terminate"]);
			}
			else if($row["vendor_id"] > 0 && $respondent_status=="overquota")
			{
				$redirection_link=str_replace("IDENTIFIER" ,$identifier_to_vendor,$row["redirect_quotafull"]);
			}
			else if($row["vendor_id"] < 0)
			{
				$redirection_link='#';
			}
		}
		else if($flag==0)
		{
			$error="ERR_IDENTIFIER_NOT_FOUND";
		}
		

		if($redirection_link!='' && $query1 && $query2 && $query3)
		{
			$this->con->commit();
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',0,'update','Operation: Redirection (Survey -> Traverse -> Vendor). Repondent(ID:".$data->identifier.") status updated as ".$data->survey_status." of survey (ID:".$data->survey_id."). And redirected to vendor.')");
			return $redirection_link;
		}
		else
		{
			$this->con->query("ROLLBACK");
			$this->con->autocommit(TRUE);
			$query_log=$this->con->query("insert into application_logs values ('".time()."',0,'update','Operation: Redirection (Survey -> Traverse -> Vendor). Repondent(ID:".$data->identifier.") status is unable to update as ".$data->survey_status." of survey (ID:".$data->survey_id."). Due to some error occured.')");
			return $error;
		}
	}
	
	
	
	
	//Function to check Vendor exits or not
	function check_Vendor_Exists($vendor_alpha_id,$survey_id)
	{
		$query=$this->con->query("select * from vendor_table where alpha_vendor_id='$vendor_alpha_id' AND removed=0 AND vendor_id not in (select vendor_id from survey_exclude_vendor where survey_id=$survey_id)");
		if($query->num_rows>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	
	//Function to get survey link status counts
	function get_Survey_Link_Counts($survey_id)
	{
		$survey_link_status=array("complete"=>0,"screened"=>0,"incomplete"=>0,"overquota"=>0);
		$query=$this->con->query("SELECT COUNT( identifier ) AS count, respondent_status FROM  survey_identifiers WHERE survey_id=$survey_id GROUP BY respondent_status");
		if($query->num_rows>0)
		{
			while($row=$query->fetch_array())
			{
				if($row["respondent_status"]=="complete")
				{
					$survey_link_status["complete"]=$row["count"];
				}
				else if($row["respondent_status"]=="overquota")
				{
					$survey_link_status["overquota"]=$row["count"];
				}
				else if($row["respondent_status"]=="incomplete")
				{
					$survey_link_status["incomplete"]=$row["count"];
				}
				else if($row["respondent_status"]=="screened")
				{
					$survey_link_status["screened"]=$row["count"];
				}
			}
		}
		return $survey_link_status;
	}
	
	
}

?>