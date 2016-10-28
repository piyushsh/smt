<?php
/**
 * Created by PhpStorm.
 * User: piyush sharma
 * Date: 24-10-2015
 * Time: 15:48
 */


namespace model;

include_once(MODEL_PATH."db-config.php");

use controller\SurveyFilterController;
use DB_Connection;

class SurveyFilterModel {

    private $con;

    /**
     *Assigning database operation object to $con
     */
    public function __construct()
    {
        $connection=new DB_Connection();
        $this->con=$connection->con;
    }

    /**
     * Storing/Modifying Country IP Filter of a survey
     * @param SurveyFilterController $surveyFilter
     * @return bool
     */
    public function storeCountryIPFilter(SurveyFilterController $surveyFilter)
    {
        $opResult = false;

        //Auto commit is off now
        $this->con->autocommit(false);
        $this->con->begin_transaction();

        //Check if Country IP Filter already exists or not
        $countryFilterExists = $this->con->query("select * from survey_filters where filter_type = '"
            .$surveyFilter::FILTER_COUNTRY_IP."' and survey_id=".$surveyFilter->surveyId);

        //If Country Filter exists, then delete that filter.
        if($countryFilterExists->num_rows > 0)
        {
            $filerData = $countryFilterExists->fetch_array();
            $deleteFilter = $this->con->query("delete from survey_filters where survey_id=".$surveyFilter->surveyId." and filter_type='".$surveyFilter::FILTER_COUNTRY_IP."'");
        }

        $querySaveFilter = $this->con->query("insert into survey_filters values ('',$surveyFilter->surveyId,'".$surveyFilter::FILTER_COUNTRY_IP."',NULL)");


        $filterRow = $this->con->query("select * from survey_filters where filter_type = '".$surveyFilter::FILTER_COUNTRY_IP."' and survey_id=".$surveyFilter->surveyId);
        $filterId = $filterRow->fetch_array()["filter_id"];

        $insertCountryCodeQuery = true;

        foreach($surveyFilter->getCountriesSelectedIPFilter() as $countryIsoCode)
        {
            $querySaveCountries = $this->con->query("insert into survey_country_filter values ('',$filterId,'$countryIsoCode')");

            $insertCountryCodeQuery = $insertCountryCodeQuery && ($querySaveCountries!=false);
        }


        if($querySaveFilter && $insertCountryCodeQuery)
        {
            $opResult=true;
            $this->con->commit();
        }
        else
        {
            $opResult = false;
            $this->con->rollback();
        }
        $this->con->autocommit(true);

        return $opResult;
    }


    /**
     * Removing Country IP Filter, if user deselects the option for Country Filter
     * @param SurveyFilterController $surveyFilterController
     * @return bool
     */
    public function removeCountryFilter(SurveyFilterController $surveyFilterController)
    {
        $checkCountryFilter = $this->con->query("select * from survey_filters where filter_type = '"
            .$surveyFilterController::FILTER_COUNTRY_IP."' and survey_id=".$surveyFilterController->surveyId);

        $deleteCountryFilterResult = true;
        if($checkCountryFilter->num_rows > 0)
        {
            while($row=$checkCountryFilter->fetch_array())
            {
                if(!$this->con->query("delete from survey_filters where survey_id=$surveyFilterController->surveyId and filter_id=".$row["filter_id"]))
                {
                    $deleteCountryFilterResult=false;
                }
            }
        }
        return $deleteCountryFilterResult;
    }



    /**
     * Storing/Modifying Duplicate IP Filter of a survey
     * @param SurveyFilterController $surveyFilter
     * @return bool
     */
    public function storeDuplicateIPFilter(SurveyFilterController $surveyFilter)
    {
        $opResult = false;

        //Auto commit is off now
        $this->con->autocommit(false);
        $this->con->begin_transaction();

        //Check if Country IP Filter already exists or not
        $duplicateIPFilterExists = $this->con->query("select * from survey_filters where filter_type = '"
            .$surveyFilter::FILTER_DUPLICATE_IP."' and survey_id=".$surveyFilter->surveyId);

        //If Country Filter exists, then delete that filter.
        if($duplicateIPFilterExists->num_rows > 0)
        {
            $opResult = $this->con->query("update survey_filters set duplicate_ip_limit="
                .$surveyFilter->getDuplicateIPFilterLimit()." where survey_id=".$surveyFilter->surveyId
                ." and filter_type='".$surveyFilter::FILTER_DUPLICATE_IP."'");
        }
        else
        {
            //Saving Filter data in the table
            $opResult = $this->con->query("insert into survey_filters values ('',$surveyFilter->surveyId,'"
                .$surveyFilter::FILTER_DUPLICATE_IP."',".$surveyFilter->getDuplicateIPFilterLimit().")");
        }

        if($opResult)
        {
//            $opResult=true;
            $this->con->commit();
        }
        else
        {
//            $opResult = false;
            $this->con->rollback();
        }
        $this->con->autocommit(true);

        return $opResult;
    }


    /**
     * Removing Duplicate IP Filter, if user deselects the option for Duplicate IP Filter
     * @param SurveyFilterController $surveyFilterController
     * @return bool
     */
    public function removeDuplicateIPFilter(SurveyFilterController $surveyFilterController)
    {
        $checkDuplicateFilter = $this->con->query("select * from survey_filters where filter_type = '".$surveyFilterController::FILTER_DUPLICATE_IP."' and survey_id=".$surveyFilterController->surveyId);

        $deleteDuplicateIPFilterResult = true;

        if($checkDuplicateFilter->num_rows > 0)
        {
            while($row=$checkDuplicateFilter->fetch_array())
            {
                if(!$this->con->query("delete from survey_filters where survey_id=$surveyFilterController->surveyId and filter_id=".$row["filter_id"]))
                {
                    $deleteDuplicateIPFilterResult=false;
                }
            }
        }
        return $deleteDuplicateIPFilterResult;
    }




    /**
     * Extracting Survey Filter information
     * @param $survey_id
     * @return array
     */
    public function getSurveyFilterInfo($survey_id)
    {
        //Extracting country IP Filter, if applied
        $countryFilter = $this->con->query("select * from survey_filters where survey_id=$survey_id and filter_type='".SurveyFilterController::FILTER_COUNTRY_IP."'");

        $countryFilterInfo = $countryFilter->fetch_array();

        $countries = [];

        //If Country IP filter is applied, then extract the countries for which the survey will accept the IP addresses
        if($countryFilter->num_rows>0)
        {
            $countriesSelected = $this->con->query("select * from survey_country_filter where filter_id =".$countryFilterInfo["filter_id"]);

            while($row=$countriesSelected->fetch_array())
            {
                array_push($countries,$row["allowed_country_iso_code"]);
            }
        }


        //Extracting Duplicate IP Filter, if applied
        $duplicateIPFilter = $this->con->query("select * from survey_filters where survey_id=$survey_id and filter_type='".SurveyFilterController::FILTER_DUPLICATE_IP."'");

        $duplicateIPFilterInfo = $duplicateIPFilter->fetch_array();

        $duplicateIPLimit = null;

        //If Duplicate IP filter is applied, then extract the limit of duplicate IP address valid
        if($duplicateIPFilter->num_rows>0)
        {
            $duplicateIPLimit=$duplicateIPFilterInfo["duplicate_ip_limit"];
        }

        return [
            "countryIPFilter"=>[
                "applied"=>$countryFilter->num_rows > 0 ? true : false,
                "countriesSelected"=>$countries
            ],
            "duplicateIPFilter"=>[
                "applied"=>$duplicateIPFilter->num_rows > 0 ? true : false,
                "duplicateIPLimit"=>$duplicateIPLimit
            ]
        ];
    }


    /**
     * Function to get Current IP counts from the database
     * @param $survey_id
     * @return int
     */
    public function getCurrentIPCount($survey_id,$current_ip)
    {
        $queryIPCount = $this->con->query("select count(*) as count from survey_identifiers where survey_id=$survey_id and respondent_status='complete' and ip_addr='$current_ip'");
        $ip_count = $queryIPCount->fetch_array();
        return $ip_count["count"];
    }

}