<?php
/**
 * Created by PhpStorm.
 * User: Piyush_Sharma5
 * Date: 11/15/2016
 * Time: 6:29 PM
 */

namespace tools;

//Constants
if(!defined("CONTROLLER_PATH"))
    define("CONTROLLER_PATH","../../../controller/");

if(!defined("MODEL_PATH"))
    define("MODEL_PATH","../../../models/");

if(!defined("REPOSITORY_PATH"))
    define("REPOSITORY_PATH","../../../Repository/");

if(!defined("INCLUDES_PATH"))
    define("INCLUDES_PATH","../../../includes/");

if(!defined("EVENT_PATH"))
    define("EVENT_PATH","../../../Event/");

if(!defined("VENDOR_PATH"))
    define("VENDOR_PATH","../../../vendor/");

if(!defined("VIEW_PATH"))
    define("VIEW_PATH","../../../views/");

if(!defined("PLUGIN_PATH"))
    define("PLUGIN_PATH","../../../plugin/");

include_once(PLUGIN_PATH."PHPExcel-develop/Classes/PHPExcel.php");
include_once(MODEL_PATH."get_data/tools/Db_MapIdentifiersToSurveyId.php");

use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_Cell_DataType;
use PHPExcel_IOFactory;

class MapIdentifierToSurveyId
{
    private $file = null;
    public $errors = [];
    private $phpExcelReader;
    private $phpExcelWriter;
    private $hashIds = [];

    public function __construct($fileObj)
    {
        $this->file = $fileObj;
        $this->phpExcelReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpExcelWriter = new PHPExcel();
        $this->validateFileInput($this->file);
    }

    public function validateFileInput($fileForm)
    {
        //Checking is File Uploaded or not
        if(!isset($fileForm))
        {
            array_push($this->errors,"ERR_FILE_NOT_UPLOADED");
        }
        $this->phpExcelReader->setReadDataOnly(true);
        $objPHPExcel = $this->phpExcelReader->load($this->file["tmp_name"]);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        if($highestColumnIndex > 1)
        {
            array_push($this->errors, "ERR_FILE_COLUMN_FORMAT_NOT_CORRECT");
            return;
        }

        for ($row = 1; $row <= $highestRow; ++$row)
        {
            for ($col = 0; $col < $highestColumnIndex; ++$col)
            {
                if(preg_match("/^\s*$/",$objWorksheet->getCellByColumnAndRow($col, $row)->getValue()))
                {
                    array_push($this->errors,"ERR_INVALID_HASH_ID_PRESENT");
                    return;
                }
                else
                {
                    array_push($this->hashIds, $objWorksheet->getCellByColumnAndRow($col, $row)->getValue());
                }
            }
        }
    }

    public function getSurveyIdsFile()
    {
        $mapIdentifierToSurveyIdModel = new Db_MapIdentifiersToSurveyId();
        $hashIdetifierMappedToSurveyIds = $mapIdentifierToSurveyIdModel->db_getSurveyIdsForHashIdentifiers($this->hashIds);

        $fileName = "MapHashIdentifierToSurveyIds_".time();

        // Set document properties
        $this->phpExcelWriter->getProperties()->setCreator($fileName)
            ->setLastModifiedBy($fileName)
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Sheet containing survey report")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("MapHashIdentifierToSurveyIds");

        $this->phpExcelWriter->setActiveSheetIndex(0)
            ->setCellValue("A1","Hash Identifier");
        $this->phpExcelWriter->setActiveSheetIndex(0)->setCellValue("B1","Survey IDs");
        $count = 2;
        foreach($hashIdetifierMappedToSurveyIds as $hash=>$surveyIdArray)
        {
            $cell_value_A="A".$count;
            $cell_value_B="B".$count;

            $this->phpExcelWriter->setActiveSheetIndex(0)
                ->setCellValueExplicit($cell_value_A, $hash, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit($cell_value_B, implode(",",$surveyIdArray), PHPExcel_Cell_DataType::TYPE_STRING);
            $count++;
        }

        $this->phpExcelWriter->getActiveSheet()->setTitle('SurveyId');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->phpExcelWriter->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcelWriter, 'Excel2007');
        //$objWriter->save($survey_id.'_identifier_and_links.xlsx');
        $objWriter->save('php://output');
    }
}