<?php

include_once(MODEL_PATH."db-config.php");


class DB_Survey
{
	public $con;

	function __construct()
	{
		$connection = new DB_Connection();
		$this->con = $connection->con;
	}

	function add_Survey($data)
	{
		$con = $this->con;

		$survey_id = $this->get_New_Survey_Id();

        $data->survey_id = $survey_id;

		$alpha_survey_id = "NXT" . $survey_id;
		$survey_multiple_link_table_name = $survey_id . "_multiplelink";
		$identifier_table_name = $survey_id . "_identifiers";


		$con->query("BEGIN TRANSACTION");
		$con->autocommit(FALSE);

		$query1 = 0;
		$query2 = 0;
		$query3 = 0;
		$query4 = 0;

		if ($data->survey_link_type == "single") {
			$query1 = $con->query("insert into survey_table values ($survey_id,'$alpha_survey_id','$data->client_name',
							'$data->survey_name','$data->survey_country','$data->survey_description',$data->survey_allow_traffic,
							'$data->survey_single_link','N/A',0,'$data->survey_creation_date','$data->survey_modified_date',
							$data->survey_created_by_id,$data->survey_manager_id,0,$data->survey_quota,$data->survey_respondent_click_quota,
							$data->survey_allow_additional_parameter, $data->survey_allow_mask_respondent_identifiers)");
		} else if ($data->survey_link_type == "multi") {
			$query1 = $con->query("insert into survey_table values ($survey_id,'$alpha_survey_id','$data->client_name',
							'$data->survey_name','$data->survey_country','$data->survey_description',$data->survey_allow_traffic,
							'N/A','$survey_multiple_link_table_name',0,'$data->survey_creation_date','$data->survey_modified_date',
							$data->survey_created_by_id,$data->survey_manager_id,0,$data->survey_quota,$data->survey_respondent_click_quota,
							$data->survey_allow_additional_parameter, $data->survey_allow_mask_respondent_identifiers);");

			$query2 = $con->query("create table $survey_multiple_link_table_name (link_id bigint primary key, link mediumtext,used_or_not boolean DEFAULT 0,used_by_identifier text,identifier_vendor varchar(255));");

			$values = $this->get_Query_String_Multi_links($data->survey_multi_link_file, $survey_id);

			$query3 = $con->query("insert into $survey_multiple_link_table_name (link_id,link,used_or_not,used_by_identifier,identifier_vendor) values " . $values);

		} else if ($data->survey_link_type == "re_contact") {
			$query1 = $con->query("insert into survey_table values ($survey_id,'$alpha_survey_id','$data->client_name',
								'$data->survey_name','$data->survey_country','$data->survey_description',$data->survey_allow_traffic,'N/A',
								'N/A',1,'$data->survey_creation_date','$data->survey_modified_date',$data->survey_created_by_id,
								$data->survey_manager_id,0,$data->survey_quota,$data->survey_respondent_click_quota,
								$data->survey_allow_additional_parameter, $data->survey_allow_mask_respondent_identifiers)");

			//Add Query to Add all the links in Re-Contact Table
			$query2 = $this->insert_Re_Contact_Links_In_Table($data->survey_re_contact_link_file, $survey_id);
		}

		if (($data->survey_link_type == "single" && $query1) || ($data->survey_link_type == "multi" && $query1 && $query2 && $query3)
				|| ($data->survey_link_type == "re_contact" && $query1 && $query2)) {
			$con->commit();
			$con->autocommit(TRUE);
			$query_log = $con->query("insert into application_logs values ('" . time() . "'," . $_SESSION["user_id"] . ",'create','New survey created by User(ID:" . $_SESSION["user_id"] . ", Name: " . $_SESSION["user_name"] . "), survey ID:$survey_id.')");
			return true;
		} else {
			$con->query("DROP table $survey_multiple_link_table_name");
			$con->query("DROP table $identifier_table_name");
			$con->query("delete from survey_table where survey_id=$survey_id");
			$con->autocommit(TRUE);


			$query_log = $con->query("insert into application_logs values ('" . time() . "'," . $_SESSION["user_id"] . ",'create','New survey cannot be created by User(ID:" . $_SESSION["user_id"] . ", Name: " . $_SESSION["user_name"] . "). Due to some error occured.')");
			return false;
		}
	}

	function get_New_Survey_Id()
	{
		$query2 = $this->con->query("select max(survey_id) as max_id from survey_table");
		$id = $query2->fetch_array();
		$id = $id["max_id"];
		return ($id + 1);
	}

	function get_Query_String_Multi_links($file, $survey_id)
	{
		$query_str = "";
		$link_id = $this->get_Max_Link_ID_Multi_Survey($survey_id);

		$tmp_name = $file["tmp_name"];

		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);


		$objPHPExcel = $objReader->load($tmp_name);
		$objWorksheet = $objPHPExcel->getActiveSheet();

		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();

		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		for ($row = 1; $row <= $highestRow; ++$row) {
			for ($col = 0; $col < $highestColumnIndex; ++$col) {
				$query_str .= "('$link_id','" . $objWorksheet->getCellByColumnAndRow($col, $row)->getValue() . "',0,'','')";
				$link_id++;
			}
			$query_str .= ",";
		}
		$query_str = substr($query_str, 0, -1);
		return $query_str;
	}

	/**
	 * @param $file, survey_id
	 * @return boolean
	 * @throws PHPExcel_Exception
	 * @throws PHPExcel_Reader_Exception
     */
	function insert_Re_Contact_Links_In_Table($file, $survey_id)
	{
		$query = null;
		$tmp_name = $file["tmp_name"];
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($tmp_name);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();

		for ($row = 1; $row <= $highestRow; ++$row) {
			//Inserting 1st Column for HASH ID & 2nd Column for Link in the table
			$query = $this->con->query("insert into re_contact_survey_links values ('',$survey_id,'".
				$objWorksheet->getCellByColumnAndRow(0,$row)."','".$objWorksheet->getCellByColumnAndRow(1,$row)."')");
			if(!$query)
				return $query;
		}
		return $query;
	}
	
	
	//Function to get Max Link ID of Multi Survey
	function get_Max_Link_ID_Multi_Survey($survey_id)
	{
		$query=$this->con->query("select max(link_id) as max_id from ".$survey_id."_multiplelink");
		$row=$query->fetch_array();
		return $row["max_id"]+1;
	}

	//Function to store Modified/Updated details of the Survey
	function db_Modify_Survey_Details($data,$survey_id)
	{
		$query_check=$this->con->query("select * from survey_table where survey_id=$survey_id");
		$row=$query_check->fetch_array();
		
		
		if($data->survey_single_link!='N/A')
		{
			$query=$this->con->query("update survey_table set client_name='".$data->client_name."',survey_name='"
                .$data->survey_name."',country='".$data->survey_country."', survey_description='".$data->survey_description
                ."',allow_traffic=$data->survey_allow_traffic, survey_manager=".$data->survey_manager_id.",single_link_url='"
                .$data->survey_single_link."',modified_date='".time()."',interviewquota=$data->survey_quota, append_additional_param ="
                .$data->survey_allow_additional_parameter.",respondentvisitquota=$data->survey_respondent_click_quota".
                ", mask_identifier=$data->survey_allow_mask_respondent_identifiers where survey_id=".$survey_id);
		}
		else if($data->survey_link_type == "multi")
		{
			$query=$this->con->query("update survey_table set client_name='".$data->client_name."',survey_name='"
                .$data->survey_name."',country='".$data->survey_country."',survey_description='".$data->survey_description
                ."',allow_traffic=$data->survey_allow_traffic, survey_manager=".$data->survey_manager_id
                .",single_link_url='N/A', modified_date='".time()."',interviewquota=$data->survey_quota, append_additional_param = "
                .$data->survey_allow_additional_parameter.", respondentvisitquota=$data->survey_respondent_click_quota".
                ", mask_identifier=$data->survey_allow_mask_respondent_identifiers where survey_id=".$survey_id);
		}
        else if($data->survey_link_type == "re_contact")
        {
            $query=$this->con->query("update survey_table set client_name='".$data->client_name."',survey_name='"
                .$data->survey_name."',country='".$data->survey_country."',survey_description='".$data->survey_description
                ."',allow_traffic=$data->survey_allow_traffic, survey_manager=".$data->survey_manager_id
                .",single_link_url='N/A', modified_date='".time()."',interviewquota=$data->survey_quota, append_additional_param = "
                .$data->survey_allow_additional_parameter.", respondentvisitquota=$data->survey_respondent_click_quota".
                ", mask_identifier=$data->survey_allow_mask_respondent_identifiers where survey_id=".$survey_id);
        }
		if($this->con->affected_rows>0)
		{
			$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'update','Survey(ID:".$survey_id.") details has been updated by User(ID:".$_SESSION["user_id"].", Name:".$_SESSION["user_name"].").')");
			return true;
		}
		$query_log=$this->con->query("insert into application_logs values ('".time()."',".$_SESSION["user_id"].",'update','Survey(ID:".$survey_id.") details cannot be updated by User(ID:".$_SESSION["user_id"].", Name:".$_SESSION["user_name"]."). Due to some error occured.')");
		return false;
	}
	
	
	
	
	/*Function to Allow or Stop the traffic*/
	function db_Stop_Or_Allow_Traffic($survey_id,$allow)
	{
		$query=$this->con->query("update survey_table set allow_traffic=$allow where survey_id=$survey_id");

		if($this->con->affected_rows>0)
		{
			return true;
		}
		return false;
	}
	
	
	
	
	/*Add more multi survey links*/
	function db_Add_More_Multi_Survey_Links($form_data,$file)
	{
		$survey_multiple_link_table_name=$form_data["survey_id"]."_multiplelink";;
		$values=$this->get_Query_String_Multi_links($file,$form_data["survey_id"]);
			
		$query3=$this->con->query("insert into $survey_multiple_link_table_name (link_id,link,used_or_not,used_by_identifier,identifier_vendor) values ".$values);
		if($query3)
		{
			return true;
		}
		return false;
	}
	
	
	/*Function to modify Multi links of the survey*/
	function db_Modify_Multi_Survey_Links($form_data,$id_link_array)
	{
		$check_used=$this->db_Link_Used($form_data["survey_id"],$id_link_array);
		if(is_array($check_used) && substr($check_used[0],0,3)=="ERR")
		{
			return array("ERR_LINK_USED_CANNOT_UPDATE",$check_used[1]);
		}
		
		foreach($id_link_array as $key=>$value)
		{
			$query=$this->con->query("update ".$form_data["survey_id"]."_multiplelink set link='$value' where link_id=$key");
			if(!$query)
			{
				return false;
			}
		}
		return true;
		
	}
	
	
	/*Function to check if any link used or not*/
	function db_Link_Used($survey_id,$id_link_array)
	{
		$query=$this->con->query("select link_id,used_or_not from $survey_id"."_multiplelink");
		
		$array_keys=array_keys($id_link_array);
		
		while($row=$query->fetch_array())
		{
			if($row["used_or_not"]==1 && in_array($row["link_id"],$array_keys))
			{
				return array("ERR_LINK_USED",$row['link_id']);
			}	
		}
		return true;
	}
	
	
	/*Function to delete links from Multi Survey*/
	function db_Delete_Multi_Survey_Links($form_data,$id_link_array)
	{
		$check_used=$this->db_Link_Used($form_data["survey_id"],$id_link_array);
		if(is_array($check_used) && substr($check_used[0],0,3)=="ERR")
		{
			return array("ERR_LINK_USED_CANNOT_UPDATE",$check_used[1]);
		}
		
		foreach($id_link_array as $key=>$value)
		{
			$query=$this->con->query("delete from ".$form_data["survey_id"]."_multiplelink where link_id=$key");
			if(!$query)
			{
				return false;
			}
		}
		return true;
	}

    //Re-Contact Survey Link Operations
	/*Add more Re-Contact survey links*/
    function db_Add_More_Re_Contact_Survey_Links($form_data,$file)
    {
        $this->con->query("BEGIN TRANSACTION");
        $this->con->autocommit(FALSE);

        $query1 = $this->insert_Re_Contact_Links_In_Table($file,$form_data["survey_id"]);

        if ($query1) {
            $this->con->commit();
            $this->con->autocommit(TRUE);
            return true;
        } else {
            $this->con->rollback();
            $this->con->autocommit(TRUE);
            return false;
        }
    }

    /*Function to modify Re-Contact links of the survey*/
    function db_Modify_Re_Contact_Survey_Links($form_data,$id_link_array)
    {
        $this->con->query("BEGIN TRANSACTION");
        $this->con->autocommit(FALSE);
        $query = true;
        foreach($id_link_array as $key=>$value)
        {
            $exeQuery=$this->con->query("update re_contact_survey_links set survey_link='$value' where id=$key AND survey_id=".$form_data["survey_id"]);
            $query = $query && $exeQuery;
        }
        if ($query) {
            $this->con->commit();
            $this->con->autocommit(TRUE);
            return true;
        } else {
            $this->con->rollback();
            $this->con->autocommit(TRUE);
            return false;
        }
    }

    /*Function to delete links from Re-Contact Survey*/
    function db_Delete_Re_Contact_Survey_Links($form_data,$id_link_array)
    {
        $this->con->query("BEGIN TRANSACTION");
        $this->con->autocommit(FALSE);
        $query = true;
        foreach($id_link_array as $key=>$value)
        {
            $exeQuery=$this->con->query("delete from re_contact_survey_links where id = ".$key." AND survey_id=".$form_data["survey_id"].
                " AND user_hash_id='".$value."'");
            $query = $query && $exeQuery;
        }
        if ($query) {
            $this->con->commit();
            $this->con->autocommit(TRUE);
            return true;
        } else {
            $this->con->rollback();
            $this->con->autocommit(TRUE);
            return false;
        }
    }
	
	
	//Function to close the survey
	function db_Close_Survey($survey_id)
	{
		$query=$this->con->query("select * from survey_table where survey_id=$survey_id");
		$row=$query->fetch_array();
		if($row["allow_traffic"]==1)
		{
			return "ERR_TRAFFIC_ALLOWED";
		}
		$query=$this->con->query("update survey_table set removed=1 where survey_id=$survey_id");
		if($query)
		{
			return true;
		}
		return "ERR_QUERY_NOT_EXECUTED";
	}
	
	
	/*Function to Re-Open the survey*/
	function db_Reopen_Survey($survey_id)
	{
		$query=$this->con->query("update survey_table set removed=0 where survey_id=$survey_id");
		if($query)
		{
			return true;
		}
		return "ERR_QUERY_NOT_EXECUTED";
	}
	
	
	
	
	//Function to Exclude of Include a vendor
	function db_Exclude_Include_Vendor($data)
	{
		$query="";
		if($data["include_vendor"]==0)
		{
			$query=$this->con->query("insert into survey_exclude_vendor values (".$data["survey_id"].",".$data["vendor_id"].",'".time()."')");
		}
		else if($data["include_vendor"]==1)
		{
			$query=$this->con->query("delete from survey_exclude_vendor where survey_id=".$data["survey_id"]." AND vendor_id=".$data["vendor_id"]);
		}
			if($query)
		{
			return true;
		}
		return false;
	}
	
}

?>