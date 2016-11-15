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

use PHPExcel;
use PHPExcel_Cell;
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
                if(preg_match("/^/\s*$/",$objWorksheet->getCellByColumnAndRow($col, $row)->getValue()))
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
        return false;
    }

    public function getSurveyIdsFile()
    {

    }
}