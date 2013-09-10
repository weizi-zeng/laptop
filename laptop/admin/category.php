<?php


define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$_REQUEST['act'] = trim($_REQUEST['act']);
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}


if ($_REQUEST['act'] == 'list')
{
    admin_priv('category_manage');

    $smarty->assign('ur_here',      "菜品类型列表");
    $smarty->assign('action_link', array('href' => 'category.php?act=add', 'text' => "添加菜品类型"));
    $smarty->assign('full_page',    1);

    $cate_list = get_cate_list();
    $smarty->assign('cate_list',     $cate_list['cates']);
    $smarty->assign('filter',       $cate_list['filter']);
    $smarty->assign('record_count', $cate_list['record_count']);
    $smarty->assign('page_count',   $cate_list['page_count']);

    assign_query_info();
    $smarty->display('category_list.htm');
}


elseif($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
{
    admin_priv('category_manage');

    $is_add = $_REQUEST['act'] == 'add';
    $smarty->assign('insert_or_update', $is_add ? 'insert' : 'update');

    if($is_add)
    {
        $cate = array(
            'id' => 0,
            'name' => '',
            'sort' => 10
        );
        $smarty->assign('ur_here',      "添加新菜品类型");
    }
    else
    {
        $cate_id = $_GET['id'];
        $cate = get_cate_info($cate_id);
        $smarty->assign('ur_here',      "编辑菜品类型");
    }
    $smarty->assign('cate', $cate);
    $smarty->assign('action_link', array('href' => 'category.php?act=list', 'text' => "菜品类型列表"));

    assign_query_info();
    $smarty->display('category_edit.htm');
}

/*------------------------------------------------------ */
/*------------------------------------------------------ */

elseif($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    admin_priv('category_manage');

    $is_insert = $_REQUEST['act'] == 'insert';

    $name = empty($_POST['name']) ? '' : trim($_POST['name']);
    $id = intval($_POST['id']);

    if (!cate_is_only($name, $id))
    {
        sys_msg(sprintf($name." 菜品类型已经存在", $name));
    }

    if($is_insert)
    {
        $sql = 'INSERT INTO ' . $ecs->table($_SESSION['admin_name'].'_category') . '(name, sort)' .
               " VALUES('$name', '$_POST[sort]')";
        $db->query($sql);

        admin_log($name, 'add', 'category');

         /* 娓呴櫎缂撳瓨 */
        clear_cache_files();

        $link[0]['text'] = "返回到菜品类型列表";
        $link[0]['href'] = 'category.php?act=list';

        sys_msg("添加菜品类型成功", 0, $link);
    }
    else
    {

        edit_cate($name, $id, $_POST['sort']);

        clear_cache_files();

        $link[0]['text'] = "返回到菜品类型列表";
        $link[0]['href'] = 'category.php?act=list';

        sys_msg("修改菜品类型成功", 0, $link);
    }
}

/*------------------------------------------------------ */
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('category_manage');

    $cate_list = get_cate_list();
    $smarty->assign('cate_list',     $cate_list['cates']);
    $smarty->assign('filter',       $cate_list['filter']);
    $smarty->assign('record_count', $cate_list['record_count']);
    $smarty->assign('page_count',   $cate_list['page_count']);

    $sort_flag  = sort_flag($cate_list['filter']);
    $smarty->assign($sort_flag['cate'], $sort_flag['img']);

    make_json_result($smarty->fetch('cate_manage.htm'), '',
        array('filter' => $cate_list['filter'], 'page_count' => $cate_list['page_count']));
}

/*------------------------------------------------------ */
//-- 鎼滅储
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'search_goods')
{
    check_authz_json('cate_manage');

    include_once(ROOT_PATH . 'includes/cls_json.php');

    $json   = new JSON;
    $filter = $json->decode($_GET['JSON']);
    $arr    = get_goods_list($filter);
    if (empty($arr))
    {
        $arr[0] = array(
            'goods_id'   => 0,
            'goods_name' => ''
        );
    }

    make_json_result($arr);
}

/*------------------------------------------------------ */
//-- 鎵归噺鍒犻櫎鏍囩?
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch_drop')
{
    admin_priv('cate_manage');

    if (isset($_POST['checkboxes']))
    {
        $count = 0;
        foreach ($_POST['checkboxes'] AS $key => $id)
        {
            $sql = "DELETE FROM " .$ecs->table('cate'). " WHERE cate_id='$id'";
            $db->query($sql);

            $count++;
        }

        admin_log($count, 'remove', 'cate_manage');
        clear_cache_files();

        $link[] = array('text' => $_LANG['back_list'], 'href'=>'category.php?act=list');
        sys_msg(sprintf($_LANG['drop_success'], $count), 0, $link);
    }
    else
    {
        $link[] = array('text' => $_LANG['back_list'], 'href'=>'category.php?act=list');
        sys_msg($_LANG['no_select_cate'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 鍒犻櫎鏍囩?
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('category_manage');

    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;

    $id = intval($_GET['id']);

    $goods_num = $db->getOne("SELECT count(1) FROM " .$ecs->table($_SESSION['admin_name'].'_dishes'). " WHERE cate_id = '$id'");
    if($goods_num>0){
    	make_json_error("此菜品类型下面已经存在$goods_num个菜品，必须删除掉这些菜品，才能删除此菜品类型");
    }
    
    $cate_name = $db->getOne("SELECT name FROM " .$ecs->table($_SESSION['admin_name'].'_category'). " WHERE id = '$id'");

    $sql = "DELETE FROM " .$ecs->table($_SESSION['admin_name'].'_category'). " WHERE id = '$id'";
    $result = $GLOBALS['db']->query($sql);
    if ($result)
    {
        /* 绠＄悊鍛樻棩蹇 */
        admin_log(addslashes($cate_name), 'remove', 'cate_manage');

        $url = 'category.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
        ecs_header("Location: $url\n");
        exit;
    }
    else
    {
       make_json_error($db->error());
    }
}

/*------------------------------------------------------ */
/*------------------------------------------------------ */

elseif($_REQUEST['act'] == "edit_cate_name")
{
    check_authz_json('category_manage');

    $name = json_str_iconv(trim($_POST['val']));
    $id = intval($_POST['id']);

    if (!cate_is_only($name, $id))
    {
        make_json_error($name." 菜品类型名称已经存在", $name);
    }
    else
    {
        edit_cate($name, $id);
        make_json_result(stripslashes($name));
    }
}

/*------------------------------------------------------ */
/*------------------------------------------------------ */

elseif($_REQUEST['act'] == "edit_cate_sort")
{
	check_authz_json('category_manage');

	$sort = json_str_iconv(trim($_POST['val']));
	$id = intval($_POST['id']);

	$sql = "update " . $GLOBALS['ecs']->table($_SESSION['admin_name'].'_category') ." set sort=$sort where id=$id ";
	$GLOBALS['db']->query($sql);
	
	make_json_result(stripslashes($sort));
}

/**
 * 鍒ゆ柇鍚屼竴鍟嗗搧鐨勬爣绛炬槸鍚﹀敮涓€
 *
 * @param $name  鏍囩?鍚
 * @param $id  鏍囩?id
 * @return bool
 */
function cate_is_only($name, $cate_id)
{

    $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table($_SESSION['admin_name'].'_category') . " WHERE name = '$name'" .
           " AND id != '$cate_id'";

    if($GLOBALS['db']->getOne($sql) > 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}

/**
 * 鏇存柊鏍囩?
 *
 * @param  $name
 * @param  $id
 * @return void
 */
function edit_cate($name, $id, $sort='')
{
    $db = $GLOBALS['db'];
    $sql = 'UPDATE ' . $GLOBALS['ecs']->table($_SESSION['admin_name'].'_category') . " SET name = '$name' ";
    if(!empty($sort)){
    	$sql.= ", sort='$sort' ";
    }
    $sql .= " WHERE id = '$id'";
    
    $GLOBALS['db']->query($sql);

    admin_log($name, 'edit', 'category');
}

/**
 * 鑾峰彇鏍囩?鏁版嵁鍒楄〃
 * @access  public
 * @return  array
 */
function get_cate_list()
{
    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'c.sort' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'asc' : trim($_REQUEST['sort_order']);

    $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table($_SESSION['admin_name'].'_category');
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);

    $sql = "SELECT c.* ".
            "FROM " .$GLOBALS['ecs']->table($_SESSION['admin_name'].'_category'). " AS c ".
            "ORDER by $filter[sort_by] $filter[sort_order] LIMIT ". $filter['start'] .", ". $filter['page_size'];
    $row = $GLOBALS['db']->getAll($sql);
    foreach($row as $k=>$v)
    {
        $row[$k]['words'] = htmlspecialchars($v['words']);
    }

    $arr = array('cates' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/**
 * return array
 */

function get_cate_info($cate_id)
{
    $sql = 'SELECT c.* FROM ' . $GLOBALS['ecs']->table($_SESSION['admin_name'].'_category') . ' AS c' .
           " WHERE id = '$cate_id'";
    $row = $GLOBALS['db']->getRow($sql);

    return $row;
}

?>
