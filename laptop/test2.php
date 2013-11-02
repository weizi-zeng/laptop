<?php

/**
 *  rest服务
 */

// phpinfo();return ;

define('IN_ECS', true);

echo "sql server connection test<br>";
$serverName = "localhost,1433"; //数据库服务器地址//ZWT\SQLEXPRESS,1433

$uid = "sa"; //数据库用户名

$pwd = "123456"; //数据库密码

$connectionInfo = array("UID"=>$uid, "PWD"=>$pwd, "Database"=>"testHotel");

$conn = @sqlsrv_connect($serverName, $connectionInfo);


// $conn = mssql_connect('localhost:1433', 'sa', '123456');

echo "conn:"; print_r($conn);

if($conn){
	echo '链接成功<br>';//1973 2013
	
	
	
	
	
//  	sqlsrv_client_info ( $conn );
 	
 	if( $client_info = sqlsrv_server_info( $conn)) {//sqlsrv_client_info
 		foreach( $client_info as $key => $value) {
 			echo $key.": ".$value."<br />";
 		}
 	} else {
 		echo "Error in retrieving client info.<br />";
 	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}else {
	echo '链接失败<br>';
	die( print_r( @sqlsrv_errors(), true));
}
sqlsrv_close( $conn);


?>