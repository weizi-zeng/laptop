<?php

/**
 * ECSHOP 管理中心菜单数组
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$modules['02_cat_and_goods']['01_goods_list']       = 'dishes.php?act=list';         // 商品列表
$modules['02_cat_and_goods']['02_goods_add']        = 'dishes.php?act=add';          // 添加商品
$modules['02_cat_and_goods']['03_category_list']       = 'category.php?act=list';         // 菜品类型列表
$modules['02_cat_and_goods']['04_category_add']        = 'category.php?act=add';          // 添加新类型
$modules['02_cat_and_goods']['05_batch_add']        = 'goods_batch.php?act=add';          // 批量上传

$modules['10_priv_admin']['01_rest_list']       = 'privilege.php?act=list';         // 餐厅列表

?>
