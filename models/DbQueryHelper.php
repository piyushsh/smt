<?php
/**
 * Created by PhpStorm.
 * User: Piyush
 * Date: 8/22/2016
 * Time: 9:50 AM
 */

class DbQueryHelper
{
    /**
     * @param $query -> Query Executed
     * @return array -> returns associative array of all rows
     */
    public static function fetchAll($query)
    {
        $result = [];
        while ($row = $query->fetch_assoc())
        {
            array_push($result,$row);
        }
        return $result;
    }
}
?>