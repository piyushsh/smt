<?php
/**
 * Created by PhpStorm.
 * User: Piyush
 * Date: 8/18/2016
 * Time: 11:54 PM
 */

namespace Repository\SurveyRepository;


class SurveyConfig
{
    const MASK_LINKS = "mask_links";

    /**
     * @param $dbResult -> Result of rows from the Table
     * @return array -> array of Configurations
     */
    public static function GetSurveyConfigurations($dbResult)
    {
        $surveyConfigs = [];
        foreach($dbResult as $key=>$value)
        {
            if($value["config_type"] == self::MASK_LINKS)
            {
                $surveyConfigs[self::MASK_LINKS] = 1;
            }
        }
        return $surveyConfigs;
    }
}