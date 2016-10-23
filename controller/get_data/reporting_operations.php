<?php
define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");
define("BASE_PATH","../../");

session_start();

include_once(PLUGIN_PATH."PHPExcel-develop/Classes/PHPExcel.php");
include_once(CONTROLLER_PATH."get_data/survey_operations_read.php");
include_once(MODEL_PATH."get_data/db-reporting-operations.php");

class Reports
{
	//Function to check which report need to be generated
	function generate_Report($survey_id,$report_type)
	{
		switch($report_type)
		{
			case 'survey_report_1':	$this->generate_Survey_Report($survey_id);
									break;
			case 'survey_report_2':	$this->generate_Survey_Report_Vendor_Wise($survey_id);
									break;
			default:
						return "ERR_NO_REPORT_SELECTED";
						break;
		}
	}
	
	
	//Function to generate survey report ---- Survey Report
	function generate_Survey_Report($survey_id)
	{
		$survey_data=new Survey_Data_Read();
		$survey_data=$survey_data->export_Survey_Report($survey_id);
		
	}
	
	
	//Function to generate survey report vendor wise ---- Full Survey Report (Vendor wise)
	function generate_Survey_Report_Vendor_Wise($survey_id)
	{
		$survey_report=new Db_Reports();
		$survey_report=$survey_report->db_Get_Survey_Status_Report($survey_id);
		
		$vendor_data=new Db_Reports();
		$vendor_data=$vendor_data->db_Generate_Survey_Report_Vendor_Wise($survey_id);
		
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator(time()."_Survey_Report_".$survey_id)
									 ->setLastModifiedBy(time()."_Survey_Report_".$survey_id)
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Sheet containing Survey Report")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Survey Report");
		
		

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A5",$survey_report["survey_info"]["survey_name"]." (Survey ID: ".$survey_report["survey_info"]["alpha_id"].")");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A6",$survey_report["survey_info"]["survey_description"]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A7","Country:".$survey_report["survey_info"]["country"]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A8","Survey Manager : ".ucwords($survey_report["survey_info"]["survey_manager"]));
		
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A12","Respondent Status");

		
		
		
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$objDrawing->setPath(ASSETS_PATH.'images/logo_report.png');
		$objDrawing->setHeight(50);
		$objDrawing->setOffsetX(5);
		$objDrawing->setOffsetY(5);
		
		
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		
		
		$styleArray1 = array(
			'font' => array(
				'bold' => true,
			)
		);
		
		$styleArray2 = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => '00000000'),
				),
			),
		);
		
		
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A5')->applyFromArray($styleArray1);

		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A12:B12')->applyFromArray($styleArray1);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A12:B17')->applyFromArray($styleArray2);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(50);
		
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A13","Completes")->setCellValue("B13",$survey_report["respondent_counts"]["complete"]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A14","Terminates")->setCellValue("B14",$survey_report["respondent_counts"]["screened"]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A15","Over Quota")->setCellValue("B15",$survey_report["respondent_counts"]["overquota"]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A16","Incompletes")->setCellValue("B16",$survey_report["respondent_counts"]["incomplete"]);
		$total=$survey_report["respondent_counts"]["complete"]+$survey_report["respondent_counts"]["screened"]+$survey_report["respondent_counts"]["overquota"]+$survey_report["respondent_counts"]["incomplete"];
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A17","Total")->setCellValue("B17",$total);
		
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A17:B17')->applyFromArray($styleArray1);
		
		
		
		$objPHPExcel->getActiveSheet()->setTitle('Survey Report');
		
		
		
		/*Code for Other Vendor Pages*/
		foreach($vendor_data as $key=>$value)
		{
			$nextSheet = $objPHPExcel->createSheet();
			
			$objDrawing2 = new PHPExcel_Worksheet_Drawing();
			$objDrawing2->setName('Logo');
			$objDrawing2->setDescription('Logo');
			$objDrawing2->setPath(ASSETS_PATH.'images/logo_report.png');
			$objDrawing2->setHeight(50);
			$objDrawing2->setOffsetX(5);
			$objDrawing2->setOffsetY(5);
			
			
			$objDrawing2->setWorksheet($nextSheet);
			
			
			$nextSheet->getColumnDimension('A')->setWidth(50);
			$nextSheet->setCellValue("A5","Vendor ID: ".$value["vendor_id"]);
			$nextSheet->setCellValue("A6","Vendor Name: ".$value["name"]);
			
			$nextSheet->setCellValue("A8","Respondent Status");
			
			$nextSheet->getStyle('A8:B8')->applyFromArray($styleArray1);
			$nextSheet->getStyle('A8:B13')->applyFromArray($styleArray2);
			
			$nextSheet->setCellValue("A9","Completes")->setCellValue("B9",$value["respondent_details"]["completes"]);
			$nextSheet->setCellValue("A10","Terminates")->setCellValue("B10",$value["respondent_details"]["teminates"]);
			$nextSheet->setCellValue("A11","Over Quota")->setCellValue("B11",$value["respondent_details"]["overquotas"]);
			$nextSheet->setCellValue("A12","Incompletes")->setCellValue("B12",$value["respondent_details"]["incompletes"]);
			
			$total=$value["respondent_details"]["completes"]+$value["respondent_details"]["teminates"]+$value["respondent_details"]["overquotas"]+$value["respondent_details"]["incompletes"];
			
			$nextSheet->setCellValue("A13","Total")->setCellValue("B13",$total);
			
			$nextSheet->getStyle('A13:B13')->applyFromArray($styleArray1);
			
			
			$nextSheet->setCellValue("A15","Vendor Identifiers Involved");
			$nextSheet->getStyle('A15')->applyFromArray($styleArray1);
			
			$nextSheet->setCellValue("B16","Identifier")->setCellValue("C16","Status")->setCellValue("D16","Start Date")->setCellValue("E16","End Date")->setCellValue("F16","LOI (In Minutes)")->setCellValue("G16","IP Address");
			$count_row=17;
			while($row=$value["identifiers_array"]->fetch_array())
			{
				$loi='';
				if($row["survey_end_date"]!="" && $row["survey_start_date"]!="")
				{
					$loi=number_format((strtotime($row["survey_end_date"])-strtotime($row["survey_start_date"]))/60);
				}
				$nextSheet->setCellValue("B".$count_row,$row["identifier"])->setCellValue("C".$count_row,$row["respondent_status"])->setCellValue("D".$count_row,$row["survey_start_date"])->setCellValue("E".$count_row,$row["survey_end_date"])->setCellValue("F".$count_row,$loi)->setCellValue("G".$count_row,$row["ip_addr"]);
				$count_row++;
			}
			$nextSheet->getStyle('B16:G16')->applyFromArray($styleArray1);
			$nextSheet->getStyle('B16:G'.($count_row-1))->applyFromArray($styleArray2);
			
			
			
			$nextSheet->setTitle($value["name"]." Report");
			unset($nextSheet,$objDrawing2);
			
			
		}
		
		

		
		
			
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.time()."_Survey_Report_".$survey_id.".xlsx");
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
}


if(isset($_POST))
{
	$generate_report=new Reports();
	$generate_report=$generate_report->generate_Report($_POST["select_survey"],$_POST["report_type"]);
}



?>
