<?php

include_once(MODEL_PATH."db-config.php");


class DB_Vendor_Read
{
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	function db_Get_Recent_Vendor_Details($page,$singlePageLimit)
	{
        //Old Code
//        {
//            if($page!=0)
//            {
//                return $this->con->query("select * from vendor_table where removed=0 limit 0,".$number);
//            }
//            else if($number==0)
//            {
//                return $this->con->query("select * from vendor_table where removed=0");
//            }
//        }

        $record_lower_limit = (($page-1)*$singlePageLimit);
        $record_upper_limit = $singlePageLimit;


        return $this->con->query("select * from vendor_table where removed=0 limit $record_lower_limit,$record_upper_limit");
	}


    //Function to get total number of vendors
    function db_Get_Total_Number_Vendors()
    {
        $query = $this->con->query("select count(*) as total_vendors from vendor_table where removed=0");
        $row = $query->fetch_array();
        return $row["total_vendors"];
    }


	function db_Get_Vendor_Details()
	{
		return $this->con->query("select * from vendor_table ");
	}
	
	function db_Get_Vendor_Details_By_ID($vendor_id)
	{
		$query=$this->con->query("select * from vendor_table where vendor_id=$vendor_id");
		$row=$query->fetch_array();
		return $row;
	}
}

?>