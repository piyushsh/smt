<?php
class DB_Connection
{
	public $con;
	
	function __construct()
	{
//		$con=mysqli_connect("nextonsmt.db.12030833.hostedresource.com","nextonsmt","Nexton@123","nextonsmt");
//        $con=mysqli_connect("localhost","hopstwhj_profile","Hops@2012","hopstwhj_portfolio_smt");
		$con=mysqli_connect("localhost","root","","nexton_smt");
		if(! $con)
		{
			die("Connection could be established".mysql_error());
			exit;
		}
		$this->con=$con;
	}
}
?>