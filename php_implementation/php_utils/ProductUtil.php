<?php
class ProductUtil {
function ConnectSql()
{
	$host = '127.0.0.1';
    	$user = 'root';
    	$database = 'project';
	$mysqli = new mysqli($host, $user);
        $mysqli->select_db($database);
	if ($mysqli->connect_errno)
	{
		return null;
	}
	else
	{
		return $mysqli;
	}
}

function CloseSql(& $mysqli)
{
	$mysqli->Close();
	return "0";
}

function &Product_QueryInfo($mysqli,$ProductId)
{
	$result = $mysqli->query("SELECT * FROM Product WHERE Id = $ProductId ORDER BY Id");
	return $result;
}
}
?>