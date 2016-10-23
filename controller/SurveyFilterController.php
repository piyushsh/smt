<?php
/**
 * Created by PhpStorm.
 * User: piyush sharma
 * Date: 24-10-2015
 * Time: 15:16
 */

namespace controller;

if(!defined("CONTROLLER_PATH"))
    define("CONTROLLER_PATH","../controller/");

if(!defined("MODEL_PATH"))
    define("MODEL_PATH","../models/");

if(!defined("REPOSITORY_PATH"))
    define("REPOSITORY_PATH","../Repository/");

if(!defined("INCLUDES_PATH"))
    define("INCLUDES_PATH","../includes/");

if(!defined("EVENT_PATH"))
    define("EVENT_PATH","../Event/");

if(!defined("VENDOR_PATH"))
    define("VENDOR_PATH","../vendor/");

include_once(REPOSITORY_PATH."SurveyRepository.php");
include_once(MODEL_PATH."SurveyFilterModel.php");
include_once(INCLUDES_PATH."geoplugin.php");
include_once(EVENT_PATH."CountryFilterEvent.php");
include_once(EVENT_PATH."CountryFilterEventHandler.php");
require VENDOR_PATH.'autoload.php';

use Events\CountryFilterEvent;
use Events\CountryFilterEventHandler;
use geoPlugin;
use Repository\SurveyRepository;
use model\SurveyFilterModel;

class SurveyFilterController {

    const FILTER_COUNTRY_IP = "country_ip_filter";
    const FILTER_DUPLICATE_IP = "duplicate_ip_filter";

    public $surveyId;
    //Country IP Filter Property
    private $countryFilterApplied = false;
    private $countriesSelected = [];

    //Duplicate IP Filter Property
    private $duplicateIPFilterApplied = false;
    private $duplicateIPLimit = 0;

    /**
     * Constructor to initialize Survey Filter Controller object
     * @param $survey_id
     * @param $country_filter_applied
     */
    public function __construct($survey_id,$country_filter_applied=null,$duplicate_ip_filter_applied=null)
    {
        $this->surveyId = $survey_id;
        $this->countryFilterApplied = $country_filter_applied;
        $this->duplicateIPFilterApplied = $duplicate_ip_filter_applied;
    }


    /**
 * Storing Country filter, if applicable
 * @param $request
 * @return bool
 */
    public function storeCountryFilter($request)
    {
        $surveyFilterModel = new SurveyFilterModel();

        //If Country Filter is selected by the user
        if($this->countryFilterApplied)
        {
            $this->countriesSelected = $request["country_ip_filter_countries"];
            $validCountries = SurveyRepository::getCountryIPFilterOptions();
            foreach($this->countriesSelected as $countryISO)
            {
                if(!in_array($countryISO,array_keys($validCountries)))
                {
                    return ['country_filter_applied'=>true,'result'=>false,'validation_error'=>'ERR_COUNTRY_ISO_CODE_NOT_FOUND'];
                }
            }

            //Saving Country IP filter in the database
            if($surveyFilterModel->storeCountryIPFilter($this))
            {
                return ['country_filter_applied'=>true,'result'=>true,'validation_error'=>''];
            }
            //If any error occurred while saving country IP filter.
            else
            {
                return ['country_filter_applied'=>true,'result'=>false,'validation_error'=>'ERR_COUNTRY_FILTER_DATABASE_OPERATION'];
            }
        }

        //If Country Filter is not selected by the user, validate in database as well
        $result = $surveyFilterModel->removeCountryFilter($this);
        $validation_err = null;
        if(!$result)
        {
            $validation_err="ERR_COUNTRY_FILTER_DATABASE_WHILE_REMOVING";
        }
        return ['country_filter_applied'=>false,'result'=>$result,'validation_error'=>$validation_err];
    }



    public function storeDuplicateIPFilter($request)
    {
        $surveyFilterModel = new SurveyFilterModel();

        //If Duplicate IP Filter is selected by the user
        if($this->duplicateIPFilterApplied)
        {
            $this->duplicateIPLimit = $request["duplicate_ip_limit"];
            if($this->duplicateIPLimit != '' && !is_int((int)$this->duplicateIPLimit))
            {
                return ['duplicate_ip_filter_applied'=>true,'result'=>false,'validation_error'=>'ERR_DUPLICATE_IP_LIMIT_INTEGER'];
            }

            //Saving Duplicate IP filter in the database
            if($surveyFilterModel->storeDuplicateIPFilter($this))
            {
                return ['duplicate_ip_filter_applied'=>true,'result'=>true,'validation_error'=>''];
            }
            //If any error occurred while saving Duplicate IP filter.
            else
            {
                return ['duplicate_ip_filter_applied'=>true,'result'=>false,'validation_error'=>'ERR_DUPLICATE_IP_FILTER_DATABASE_OPERATION'];
            }
        }

        //If Duplicate IP Filter is not selected by the user, validate in database as well
        $result = $surveyFilterModel->removeDuplicateIPFilter($this);
        $validation_err = null;
        if(!$result)
        {
            $validation_err="ERR_DUPLICATE_IP_FILTER_DATABASE_WHILE_REMOVING";
        }
        return ['duplicate_ip_filter_applied'=>false,'result'=>$result,'validation_error'=>$validation_err];
    }



    /**
     * Retrieve countries selected for IP filtering
     * @return array
     */
    public function getCountriesSelectedIPFilter()
    {
        return $this->countriesSelected;
    }

    /**
     *Retrieve duplicate IP filter limit.
     * @return int
     */
    public function getDuplicateIPFilterLimit()
    {
        return $this->duplicateIPLimit;
    }

    /**
     * Providing Survey Filter Information
     * @return array
     */
    public function getSurveyFilterInfo()
    {
        $surveyFilterModel = new SurveyFilterModel();

        return $surveyFilterModel->getSurveyFilterInfo($this->surveyId);
    }


    /**Validates a respondent, whether his/her IP address is allowed to take the survey or not.
 * @param $respondent_ip
 * @return bool
 * True -> means that there is no problem with the respondent IP and country location.
 * False -> means that respondent IP address falls under a country, from which survey is not required.
 */
    public function validateRespondentCountryFilter($respondent_ip)
    {
        $surveyFilterModel = new SurveyFilterModel();

        $countriesFilter = $surveyFilterModel->getSurveyFilterInfo($this->surveyId);

        //If country IP Filter is applied in the survey.
        if($countriesFilter["countryIPFilter"]["applied"]) {
            $countriesAllowed = $countriesFilter["countryIPFilter"]["countriesSelected"];

            $geoplugin = new geoPlugin();
            $geoplugin->locate($respondent_ip);
            //            echo "IP: $respondent_ip";

            //Checking if Third Party API - GeoPlugin, is working or not
            if($geoplugin->countryCode == null)
            {
                $countryNotFoundEvent = new CountryFilterEvent($this->surveyId,CountryFilterEvent::COUNTRY_FILTER_NOT_WORKING);
                $countryNotFoundEventHandler = new CountryFilterEventHandler($countryNotFoundEvent);
                $countryNotFoundEvent->raiseEvent($countryNotFoundEventHandler);
            }

            //If the IP address is among one the country specified by the user, then user will be redirected to the survey.
            if (in_array($geoplugin->countryCode, $countriesAllowed)) {
                return true;
            }
            //If IP address is not among any of the country, then respondent will not be able to proceed.
            else
            {
                return false;
            }
        }

        //If country IP filter is not applied, return true.
        return true;
    }

    /**Validates a respondent, whether in the survey duplicate IP is allowed or not, if allowed then the limit should not be reached.
     * @param $respondent_ip
     * @return bool
     * True -> means that there is no problem with the duplicate IP filter.
     * False -> means that duplicate IP limit has been reached.
     */
    public function validateRespondentDuplicateIPFilter($respondent_ip)
    {
        $surveyFilterModel = new SurveyFilterModel();

        $duplicateIPFilter = $surveyFilterModel->getSurveyFilterInfo($this->surveyId);

        //If country IP Filter is applied in the survey.
        if($duplicateIPFilter["duplicateIPFilter"]["applied"]) {
            $duplicateIPLimit = $duplicateIPFilter["duplicateIPFilter"]["duplicateIPLimit"];

            $currentDuplicateIPCount = $surveyFilterModel->getCurrentIPCount($this->surveyId,$respondent_ip);
            if($currentDuplicateIPCount > $duplicateIPLimit)
            {
                return false;
            }
            return true;

        }

        //If Duplicate IP filter is not applied, return true.
        return true;
    }

}