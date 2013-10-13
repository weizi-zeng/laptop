<?php

/**
 *  远程注册服务
 *  act
 *  1、注册接口
    1.1、餐厅注册接口：
        url :  http://184.173.165.75/laptop/restrantant_signe.php
        参数：    餐厅名称 * 
                邮箱 * 
                餐厅缩写 * 
                密码 * 
                确认密码 
 
        返回结果：
            1、注册成功
            2、已经注册了，返回提示“已经注册”
            3、注册失败，密码不一致，餐厅缩写重复
 
    餐厅名称，餐厅地址、手机号码，邮箱， 《立即免费体验 》
 
    注册邮件内容
    1、账户/密码
    2、登录url

 *  
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
/*------------------------------------------------------ */
//-- act 操作项的初始化
/*------------------------------------------------------ */
if (empty($_REQUEST['act']))
{
    die("act errer") ;
}

/*------------------------------------------------------ */
//-- 注册界面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'login')
{
	//跳转到注册页面
	$url = $_SERVER['REQUEST_URI'];
	$url = str_replace("/registe.php?", "/admin/privilege.php?", $url);
	
	ecs_header("Location: $url\n");
	exit;
}

die("act error");

?>