<?php
/**
 * Created by PhpStorm.
 * User: Piyush_Sharma5
 * Date: 11/17/2016
 * Time: 4:10 PM
 */

namespace tools;

include_once(MODEL_PATH."db-config.php");

use DB_Connection;

class Db_MapIdentifiersToSurveyId
{
    public $con;

    public function __construct()
    {
        $connection=new DB_Connection();
        $this->con=$connection->con;
    }

    /*Get Survey IDs for Hash Identifiers
     * @param (array) $hashIdentifier
     * @return array
     */
    public function db_getSurveyIdsForHashIdentifiers($hashIdentifier)
    {
        $hashIdentifierToSurveyId = [];
        foreach($hashIdentifier as $value)
        {
            $query = $this->con->query("select * from survey_identifiers where hash_identifier = '$value'");
            $tempArray = [];
            while($row = $query->fetch_array())
            {
                array_push($tempArray, $row["survey_id"]);
            }
            $hashIdentifierToSurveyId[$value]=$tempArray;
        }
        return $hashIdentifierToSurveyId;
    }
}


?>