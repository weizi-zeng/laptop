<?php

/**
 * ECSHOP 权限对照表
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

//商品管理权限
    $purview['01_goods_list']        = array('dishes_manage', 'remove_back');
    $purview['02_goods_add']         = 'dishes_manage';
    $purview['03_category_list']        = 'category_manage';
    $purview['04_category_add']         = 'category_manage';
    $purview['05_batch_add']         = 'goods_batch';
    
//餐厅管理权限
    $purview['01_rest_list']         = 'admin_manage';
    
?>