<?php

/**
 * ECSHOP 公用函数库
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_common.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/**
 * 创建餐厅对应的
 * 菜品表
 * 菜品类型表
 */
function create_table($name){
	delete_table($name);

	$sql = "CREATE TABLE  `laptop`.`ecs_".$name."_category` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(45) NOT NULL,
		  `sort` int(10) unsigned NOT NULL DEFAULT '10',
		  `memo` varchar(450) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

	$GLOBALS['db']->query($sql);

	$sql = "CREATE TABLE  `laptop`.`ecs_".$name."_dishes` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(45) NOT NULL,
	  `cate_id` int(10) unsigned NOT NULL,
	  `isRecommend` tinyint(1) unsigned NOT NULL DEFAULT '0',
	  `sort` int(10) unsigned NOT NULL DEFAULT '10',
	  `img` varchar(45) DEFAULT NULL,
	  `price` varchar(1024) DEFAULT NULL,
	  `description` varchar(1024) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;";

	$GLOBALS['db']->query($sql);
}

/**
 * 删除餐厅对应的
 * 菜品表
 * 菜品类型表
 */
function delete_table($name){
	$sql = "drop table IF EXISTS ".$GLOBALS['ecs']->table($name.'_dishes');
	$GLOBALS['db']->query($sql);

	$sql = "drop table IF EXISTS ".$GLOBALS['ecs']->table($name.'_category');
	$GLOBALS['db']->query($sql);
}

/**
* 判断表中某字段是否重复，若重复则中止程序，并给出错误信息
*
* @access  public
* @param   string  $col    字段名
* @param   string  $name   字段值
* @param   integer $id
*
* @return void
*/
function is_only($table, $col, $name, $sid='', $id = 0, $where='')
{
	$sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table($table). " WHERE $col = '$name'";
	$sql .= empty($id) ? '' : ' AND ' . $sid . " <> '$id'";
	$sql .= empty($where) ? '' : ' AND ' .$where;

	return ($GLOBALS['db']->getOne($sql) == 0);
}


function createName($name,$i=0){
	/* Email地址是否有重复 */
	$is_only = is_only("admin_user", "user_name", $name);
	if (!$is_only)
	{
		$i++;
		$name .= $i;
		return createName($name,$i);		
	}else {
		return $name;
	}
}

/**
 * 注册admin user
 */
function addRestaurant($param, $user_name, $add_time){
	
	$password  = $user_name."123";
	
	$role_id = '';
	
	$action_list = 'dishes_manage,remove_back,category_manage,goods_batch';
	
	$sql = "SELECT nav_list FROM " . $GLOBALS['ecs']->table('admin_user') . " WHERE action_list = 'all'";
	$row = $GLOBALS['db']->getRow($sql);
	
	$sql = "INSERT INTO ".$GLOBALS['ecs']->table('admin_user')." (restaurant, user_name, email, password, add_time, nav_list, action_list, role_id, phone, address) ".
		           "VALUES ('".trim($param['name'])."','".$user_name."', '".trim($param['email'])."', '".md5($password)."', '$add_time', '$row[nav_list]', '$action_list', '$role_id', '".trim($param['phone'])."', '".trim($param['address'])."')";
	
	$GLOBALS['db']->query($sql);
	$id = $GLOBALS['db']->Insert_ID();
	
	create_table($user_name);
	
	return array("id"=>$id,"name"=>$user_name,"password"=>$password, "restaurant"=>$param['name']);
}
?>