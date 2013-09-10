<?php

/**
 * ECSHOP 管理中心品牌管理
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: dishes.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

$exc = new exchange($ecs->table($_SESSION['admin_name']."_dishes"), $db, 'id', 'name');

$smarty->assign('rest', $_SESSION['admin_name']);

/*------------------------------------------------------ */
//-- 品牌列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',      "菜品列表");
    $smarty->assign('action_link',  array('text' => '添加新菜品', 'href' => 'dishes.php?act=add'));
    $smarty->assign('full_page',    1);
    
    $category_list = get_categorys();
    $smarty->assign('category_list', $category_list);
    
    $dish_list = get_disheslist();
    
    $smarty->assign('dish_list',   $dish_list['dishes']);
    $smarty->assign('filter',       $dish_list['filter']);
    $smarty->assign('record_count', $dish_list['record_count']);
    $smarty->assign('page_count',   $dish_list['page_count']);

    assign_query_info();
    $smarty->display('dishes_list.htm');
}

/*------------------------------------------------------ */
//-- 添加品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
	
    /* 权限判断 */
    $smarty->assign('ur_here',     "添加新菜品");
    $smarty->assign('action_link', array('text' => "菜品列表", 'href' => 'dishes.php?act=list'));
    $smarty->assign('form_action', 'insert');

    $category_list = get_categorys();
    $smarty->assign('category_list', $category_list);
    
    assign_query_info();
    $smarty->assign('dishe', array('sort'=>10));
    $smarty->display('dishes_info.htm');
}

elseif ($_REQUEST['act'] == 'insert')
{
    /*检查菜品名是否重复*/
    $is_only = $exc->is_only('name', $_POST['name']);

    if (!$is_only)
    {
        sys_msg(sprintf("您添加的菜品名称已经存在，菜品名称不能重复！", stripslashes($_POST['name'])), 1);
    }
    
    $price = '';
    for($i=1;$i<=3;$i++){
    	$k = $_POST['price'.$i.'_key'];
    	$v = $_POST['price'.$i.'_val'];
    	if($k && $v){
    		$price.=  $k.":". $v.";";
    	}
    }
    
     /*处理图片*/
    $img_name = basename($image->upload_image($_FILES['img'],'img'));

    /*插入数据*/
    $sql = "INSERT INTO ".$ecs->table($_SESSION['admin_name'].'_dishes')."(name, cate_id, isRecommend, img, price, sort, description) ".
           "VALUES ('$_POST[name]', '$_POST[cate_id]', '$_POST[isRecommend]', '$img_name', '$price', '$_POST[sort]', '$_POST[description]')";
    
    $db->query($sql);

    /* 清除缓存 */
    clear_cache_files();

    $link[0]['text'] = "继续添加";
    $link[0]['href'] = 'dishes.php?act=add';

    $link[1]['text'] = "菜品列表";
    $link[1]['href'] = 'dishes.php?act=list';

    sys_msg("添加成功", 0, $link);
}

/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    $sql = "SELECT * FROM " .$ecs->table($_SESSION['admin_name'].'_dishes'). " WHERE id='$_REQUEST[id]'";
    $dishe = $db->GetRow($sql);
    
    $price = explode(';', $dishe['price']);
    
    foreach($price as $k=>$p){
    	if($p){
    		$p_arr = explode(':', $p);
    		$dishe['price'.($k+1)]['key'] = $p_arr[0];
    		$dishe['price'.($k+1)]['val'] = $p_arr[1];
    	}
    }
    
    for($i=1;$i<=3;$i++){
    	if(!$dishe['price'.$i]){
    		$dishe['price'.$i]['key'] = '';
    		$dishe['price'.$i]['val'] = '';
    	}
    }
    
    $smarty->assign('ur_here',     "编辑菜品");
    $smarty->assign('action_link', array('text' => "菜品列表", 'href' => 'dishes.php?act=list&' . list_link_postfix()));
    $smarty->assign('dishe',       $dishe);
    $smarty->assign('form_action', 'updata');

    $category_list = get_categorys();
    $smarty->assign('category_list', $category_list);
    
    assign_query_info();
    $smarty->display('dishes_info.htm');
}
elseif ($_REQUEST['act'] == 'updata')
{
    if ($_POST['name'] != $_POST['old_dishesname'])
    {
        /*检查品牌名是否相同*/
        $is_only = $exc->is_only('name', $_POST['name'], $_POST['id']);

        if (!$is_only)
        {
            sys_msg(sprintf("此菜品名称已经存在", stripslashes($_POST['name'])), 1);
        }
    }
    
    $price = '';
    for($i=1;$i<=3;$i++){
    	$k = $_POST['price'.$i.'_key'];
    	$v = $_POST['price'.$i.'_val'];
    	if($k && $v){
    		$price.=  $k.":". $v.";";
    	}
    }

    /* 处理图片 */
    $img_name = basename($image->upload_image($_FILES['img'],'img'));
    
    $param = "name = '$_POST[name]', cate_id='$_POST[cate_id]', isRecommend='$_POST[isRecommend]' , sort='$_POST[sort]'
    		, price='$price', description='$_POST[description]'";
    
    if (!empty($img_name))
    {
        //有图片上传
        $param .= " ,img = '$img_name' ";
    }

    if ($exc->edit($param,  $_POST['id']))
    {
        /* 清除缓存 */
        clear_cache_files();

        admin_log($_POST['name'], 'edit', 'dishes');

        $link[0]['text'] = "返回到菜品列表";
        $link[0]['href'] = 'dishes.php?act=list&' . list_link_postfix();
        $note = vsprintf("更新成功", $_POST['name']);
        sys_msg($note, 0, $link);
    }
    else
    {
        die($db->error());
    }
}


/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_stock')
{

    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("isOutStock='$val'", $id);

    make_json_result($val);
}

elseif ($_REQUEST['act'] == 'toggle_recommend')
{

	$id     = intval($_POST['id']);
	$val    = intval($_POST['val']);

	$exc->edit("isRecommend='$val'", $id);

	make_json_result($val);
}

/*------------------------------------------------------ */
//-- 删除品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{

    $id = intval($_GET['id']);

    /* 删除该品牌的图标 */
    $sql = "SELECT img FROM " .$ecs->table($_SESSION['admin_name'].'_dishes'). " WHERE id = '$id'";
    $img = $db->getOne($sql);
    if (!empty($img))
    {
        @unlink(ROOT_PATH . '/admin/images/disheimg/'.$_SESSION['admin_name'].'/'.$img);
    }

    $exc->drop($id);
    
    $url = 'dishes.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $dishes_list = get_disheslist();
    
    $smarty->assign('dish_list',   $dishes_list['dishes']);
    $smarty->assign('filter',       $dishes_list['filter']);
    $smarty->assign('record_count', $dishes_list['record_count']);
    $smarty->assign('page_count',   $dishes_list['page_count']);

    make_json_result($smarty->fetch('dishes_list.htm'), '',
        array('filter' => $dishes_list['filter'], 'page_count' => $dishes_list['page_count']));
}

/**
 * 获取品牌列表
 *
 * @access  public
 * @return  array
 */
function get_disheslist()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 分页大小 */
        $filter = array();
        
        $qname = empty($_POST['name'])?"":$_POST['name'];
        $qcate = empty($_POST['cate_id'])?0:intval($_POST['cate_id']);
        
        $where = " where 1 ";
        if($qname){
        	$where .= " and d.name like '%$qname%' ";
        }
        if($qcate){
        	$where .= " and d.cate_id=".$qcate." ";
        }
        
        /* 记录总数以及页数 */
        $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table($_SESSION['admin_name'].'_dishes')." d ".$where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 查询记录 */
        {
            if(strtoupper(EC_CHARSET) == 'GBK')
            {
                $keyword = iconv("UTF-8", "gb2312", $_POST['name']);
            }
            else
            {
                $keyword = $_POST['name'];
            }
            $sql = "SELECT  d.*,c.name as cate  FROM ".$GLOBALS['ecs']->table($_SESSION['admin_name'].'_dishes')." d left join ".$GLOBALS['ecs']->table($_SESSION['admin_name'].'_category')." c on d.cate_id=c.id ".$where." ORDER BY d.cate_id,sort ASC";
        }
        
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $arr = array();
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $arr[] = $rows;
    }

    return array('dishes' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

/**
 * 获取所有的菜品类型
 */
function get_categorys(){
	$sql = "select id,name from ".$GLOBALS['ecs']->table($_SESSION['admin_name'].'_category')." order by sort ";
	return $GLOBALS['db']->getAll($sql);
}
?>
