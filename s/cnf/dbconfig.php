<?php
ini_set('display_errors','true');
error_reporting(E_ALL);

$messages = array(
	1=>'Record deleted successfully',
	2=>'Error occured. Please try again.',
	3=>'Record saved successfully.',
  	4=>'Record updated successfully.',
  	5=>'All fields are required.'
);


class Database
{
	public $conn;
	public function dbConnection()
	{
		$config = include('config.php');
	    	$this->conn = null;
	        try
		{
			$this->conn = new PDO($config['dbtype'].":host=".$config['dbhost'].";port=".$config['dbport'].";dbname=".$config['dbname'],$config['dbuser'],$config['dbpass']);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $exception) {
			       echo "Connection error: " . $exception->getMessage();
		}
	        return $this->conn;
	}
}
?>
