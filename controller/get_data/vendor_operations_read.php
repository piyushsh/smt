<?php
/*define("CONTROLLER_PATH","../../controller/");
define("MODEL_PATH","../../models/");
define("VIEW_PATH","../../views/");
define("ASSETS_PATH","../../assets/");
define("INCLUDES_PATH","../../includes/");
define("PLUGIN_PATH","../../plugin/");*/


include_once(MODEL_PATH."get_data/db-vendor-operations-read.php");

class Vendor_Data_Read
{
    //Number of Vendors shown on a single Page
    private $vendor_presented_limit = 100;

	function get_Recent_Vendor_Details($page)
	{
		$get_vendor=new DB_Vendor_Read();
		return $get_vendor->db_Get_Recent_Vendor_Details($page,$this->vendor_presented_limit);
	}

    //Function to get total number of vendors
    function get_Total_Number_Vendors()
    {
        $get_vendor = new DB_Vendor_Read();
        return $get_vendor->db_Get_Total_Number_Vendors();
    }
	
	function get_Vendor_Details()
	{
		$get_vendor=new DB_Vendor_Read();
		return $get_vendor->db_Get_Vendor_Details();
	}
	
	function get_Vendor_Details_by_ID($vendor_id)
	{
		$get_vendor_data=new DB_Vendor_Read();
		return $get_vendor_data->db_Get_Vendor_Details_By_ID($vendor_id);
	}


    //Function to get limit of number of vendors shown on single page
    public function getVendorLimitOnSinglePage()
    {
        return $this->vendor_presented_limit;
    }
		
}





?>
