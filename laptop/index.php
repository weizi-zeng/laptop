<?php

/**
 *  rest服务
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);


//跳转到登陆，注册页面
ecs_header("Location: ./admin/index.php\n");
exit;


?>