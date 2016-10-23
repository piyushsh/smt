<?php
/*define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");*/


include_once(PLUGIN_PATH."PHPExcel-develop/Classes/PHPExcel.php");
include_once(MODEL_PATH."get_data/db-survey-operations-read.php");

class Survey_Data_Read
{

    //Number of Surveys shown on a single Page
    private $survey_presented_limit = 100;

    function get_Recent_Survey_Data($number, $manager_id, $pageRequested)
    {
        $get_survey = new DB_Survey_Read();
        return $get_survey->db_Get_Recent_Survey($number, $manager_id, $pageRequested, $this->survey_presented_limit);
    }


    //Function to get total number of recent surveys of a user
    public function get_Total_Number_Recent_Survey_Data($number, $manager_id)
    {
        $get_survey = new DB_Survey_Read();
        return $get_survey->db_Get_Total_Number_Recent_Survey($number, $manager_id);
    }


    function get_Survey_Data()
    {
        $get_survey = new DB_Survey_Read();
        return $get_survey->db_Get_Survey_Data();
    }

    //Get All the details of a survey
    function get_Survey_Details($survey_id)
    {
        $get_survey_details = new DB_Survey_Read();
        return $get_survey_details->db_Get_Survey_Details($survey_id);
    }


    //Function to get Status of the survey like, no of completes, incompletes, dropouts, total no. of respondent took the survey, etc.
    function get_Survey_Status($survey_id)
    {
        $survey_status = new DB_Survey_Read();
        return $survey_status->get_Survey_Status($survey_id);
    }


    //function to Filter the survey based upon number of factors
    function get_Filter_Surveys($number, $manager_id, $survey_status, $created_by, $pageRequested)
    {
        $get_filter_survey = new DB_Survey_Read();
        return $get_filter_survey->db_Get_Filter_Surveys($number, $manager_id, $survey_status, $created_by, $pageRequested, $this->survey_presented_limit);
    }

    //Function to get Total number of surveys based upon number of factors as defined in parameters
    function get_Total_Filter_Surveys($number, $manager_id, $survey_status, $created_by)
    {
        $get_filter_survey = new DB_Survey_Read();
        return $get_filter_survey->db_Get_Total_Number_Filter_Surveys($number, $manager_id, $survey_status, $created_by);
    }
	
	
	
	
	//Function to export survey report
	function export_Survey_Report($survey_id)
	{
		$survey_report=new DB_Survey_Read();
		$survey_report=$survey_report->db_Export_Survey_Report($survey_id);
		
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator($survey_report["survey_info"]["survey_id"]."_".$survey_report["survey_info"]["survey_name"]."_Report")
									 ->setLastModifiedBy($survey_report["survey_info"]["survey_name"]."_Report")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Sheet containing survey report")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Survey Report");
		
		
		$count=2;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1","Survey Name:".$survey_report["survey_info"]["survey_name"]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2","Date:".date("d-m-Y H:i:s"));
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A4","Vendor ID")->setCellValue("B4","Identifier")->setCellValue("C4","Hash Identifier")->setCellValue("D4","Status")->setCellValue("E4","Start Date")->setCellValue("F4","End Date")->setCellValue("G4","LOI (Minutes)")->setCellValue("H4","IP Address");
		
		$count=5;
		while($row=$survey_report["survey_respondent_info"]->fetch_array())
		{
			$cell_value_A="A".$count;
			$cell_value_B="B".$count;
			$cell_value_C="C".$count;
			$cell_value_D="D".$count;
			$cell_value_E="E".$count;
			$cell_value_F="F".$count;
			$cell_value_G="G".$count;
			$cell_value_H="H".$count;
			
			$loi=0;
			
			if($row["survey_end_date"]!="" && $row["survey_start_date"]!="")
			{
				$loi=number_format((strtotime($row["survey_end_date"])-strtotime($row["survey_start_date"]))/60);
			}
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_value_A,$row["vendor_linked"])->setCellValue($cell_value_B,ucfirst($row["identifier"]))->setCellValue($cell_value_C,$row["hash_identifier"])->setCellValue($cell_value_D,$row["respondent_status"])->setCellValue($cell_value_E,$row["survey_start_date"])->setCellValue($cell_value_F,$row["survey_end_date"])->setCellValue($cell_value_G,$loi)->setCellValue($cell_value_H,$row["ip_addr"]);
			$count++;
		}
		
		$objPHPExcel->getActiveSheet()->setTitle('Survey Report');
			
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$survey_report["survey_info"]["survey_id"].'_'.$survey_report["survey_info"]["survey_name"].'_Report.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save($survey_id.'_identifier_and_links.xlsx');
		$objWriter->save('php://output');
	}
	
	
	
	//Download Multi Survey links
	function download_Multi_Survey_Links($survey_id)
	{
		$multi_links=new DB_Survey_Read();
		$multi_links_result=$multi_links->db_Download_Multi_Survey_Links($survey_id);
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator($survey_id."_Multi_Links")
									 ->setLastModifiedBy($survey_id."_Multi_Links")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Sheet containing identifiers and links of the survey")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Multi Survey Link");
		
		
		$count=2;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1","Link ID")->setCellValue("B1","Survey Link")->setCellValue("C1","Used or Not")->setCellValue("D1","Used Identifier")->setCellValue("E1","Vendor");
		while($row=$multi_links_result->fetch_array())
		{
			$cell_value_A="A".$count;
			$cell_value_B="B".$count;
			$cell_value_C="C".$count;
			$cell_value_D="D".$count;
			$cell_value_E="E".$count;
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_value_A,$row["link_id"])
				->setCellValue($cell_value_B,ucfirst($row["link"]))
				->setCellValue($cell_value_C,$row["used_or_not"])
				->setCellValue($cell_value_D,$row["used_by_identifier"])
				->setCellValue($cell_value_E,$row["identifier_vendor"]);
			$count++;
		}
		
		$objPHPExcel->getActiveSheet()->setTitle('Multi Survey Link');
			
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$survey_id.'_Multi_Links.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save($survey_id.'_identifier_and_links.xlsx');
		$objWriter->save('php://output');
	}

	//Download Re-Contact Survey links
	function download_Re_Contact_Survey_Links($survey_id)
	{
		$re_Contact_links=new DB_Survey_Read();
		$re_Contact_links_result=$re_Contact_links->db_Download_Re_Contact_Survey_Links($survey_id);
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator($survey_id."_Re_Contact_Links")
			->setLastModifiedBy($survey_id."_Multi_Links")
			->setTitle("Office 2007 XLSX Test Document")
			->setSubject("Office 2007 XLSX Test Document")
			->setDescription("Sheet containing identifiers and links of the survey")
			->setKeywords("office 2007 openxml php")
			->setCategory("Multi Survey Link");


		$count=2;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1","Link ID")->setCellValue("B1","Hash Identifier")
			->setCellValue("C1","Survey Link");
		while($row=$re_Contact_links_result->fetch_array())
		{
			$cell_value_A="A".$count;
			$cell_value_B="B".$count;
			$cell_value_C="C".$count;

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_value_A,$row["id"])
				->setCellValue($cell_value_B,$row["user_hash_id"])
				->setCellValue($cell_value_C,$row["survey_link"]);
			$count++;
		}

		$objPHPExcel->getActiveSheet()->setTitle('Re-Contact Survey Link');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$survey_id.'_Re_Contact_Links.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
	
	
	
	//Function to calculate Length of Interview
	function getLengthOfInterview($survey_id)
	{
		$survey_report=new DB_Survey_Read();

		$survey_report=$survey_report->db_Export_Survey_Report($survey_id);
		
		$loi=0;
		$count=0;
		$array_loi_values=array();
		while($row=$survey_report["survey_respondent_info"]->fetch_array())
		{
			$temp_loi=0;
			
			if($row["survey_end_date"]!="" && $row["survey_start_date"]!="" && $row["respondent_status"]=="complete")
			{
				$temp_loi=number_format((strtotime($row["survey_end_date"])-strtotime($row["survey_start_date"]))/60);
				array_push($array_loi_values,$temp_loi);
			}
			$loi+=$temp_loi;
		}

		if(asort($array_loi_values) && count($array_loi_values)>0)
		{
			$count=count($array_loi_values);
			if($count%2==0)
			{
				$loi=number_format(($array_loi_values[intval($count/2)-1]+$array_loi_values[intval($count/2)])/2);
			}
			else
			{
				$loi=number_format($array_loi_values[intval($count/2)]);
			}
		}
		return $loi;
	}
	
	//Function to calculate Incidence Rate
	function getIncidenceRate($survey_id)
	{
		$survey_status=$this->get_Survey_Status($survey_id);
		$total_completes=$survey_status["respondent_counts"]["complete"];
		$total_terminates=$survey_status["respondent_counts"]["screened"];
		
		$incidence=0;

        if(($total_completes+$total_terminates)>0)
        {
            $incidence=number_format(($total_completes/($total_completes+$total_terminates))*100,2);
        }
		return $incidence."%";
	}
	
	
	
	//Function to get Vendor Excluded from the survey
	function get_Vendor_Excluded($survey_id)
	{
		$vendor_excluded=new DB_Survey_Read();
		return $vendor_excluded->db_Get_Vendor_Excluded($survey_id);
	}




    public function getSurveyPresentedPageLimit()
    {
        return $this->survey_presented_limit;
    }
	
	
}





?>
