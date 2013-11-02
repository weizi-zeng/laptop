<?php 

/**
 * 从sql server 数据库获取菜单<br/>
 * 将菜单信息更新到mysql数据库中去
 */


define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

$sql = "select a.trn_cd,a.item_cd,item_nm,s_price,spell_cd,plu_mcd,plu_scd,unit_nm  from posmenu a,posprice b where a.trn_cd=b.trn_cd and a.item_cd=b.item_cd and a.trn_cd='203'";



?>