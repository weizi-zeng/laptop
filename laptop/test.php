<?php

/**
 *  rest服务
 */

// phpinfo();return ;

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');


echo "sql server connection test<br>";

$sql = "select * from sysobjects where type='U' order by name";

//执行有结果集的SQL语句
$query = $sqlsrv_db->query($sql);



while($row = $sqlsrv_db->fetch_array($query,SQLSRV_FETCH_NUMERIC))

{

	echo $row[0]."-----".$row[1]."<br/>";

}



?>