<?php
class DB_Connection
{
	public $con;
	
	function __construct()
	{
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