<?php

include_once(MODEL_PATH."db-config.php");


class DB_Logs
{
	public $con;
	
	function __construct()
	{
		$connection=new DB_Connection();
		$this->con=$connection->con;
	}
	
	function db_Get_Logs($filter_type,$filter_user)
	{
		if($filter_user=='')
		{

			switch($filter_type)
			{
				case 'all':		$query=$this->con->query("select * from application_logs");
								break;
								
				case 'create':	$query=$this->con->query("select * from application_logs where log_type='create'");
								break;
				case 'update':	$query=$this->con->query("select * from application_logs where log_type='update'");
								break;
				case 'delete':	$query=$this->con->query("select * from application_logs where log_type='delete'");
								break;
				case 'error':	$query=$this->con->query("select * from application_logs where log_type='error'");
								break;
								
				case 'others':	$query=$this->con->query("select * from application_logs where log_type='others'");
								break;
				default:		$query=$this->con->query("select * from application_logs");
								break;
							
			}
		}
		else if($filter_user>0)
		{
			switch($filter_type)
			{
				case 'all':		$query=$this->con->query("select * from application_logs where user_id=$filter_user");
								break;
								
				case 'create':	$query=$this->con->query("select * from application_logs where log_type='create' AND user_id=$filter_user");
								break;
				case 'update':	$query=$this->con->query("select * from application_logs where log_type='update' AND user_id=$filter_user");
								break;
				case 'delete':	$query=$this->con->query("select * from application_logs where log_type='delete' AND user_id=$filter_user");
								break;
				case 'error':	$query=$this->con->query("select * from application_logs where log_type='error' AND user_id=$filter_user");
								break;
								
				case 'others':	$query=$this->con->query("select * from application_logs where log_type='others' AND user_id=$filter_user");
								break;
				default:		$query=$this->con->query("select * from application_logs where user_id=$filter_user");
								break;
							
			}
		}
		return $query;
		
	}
	
}

?>