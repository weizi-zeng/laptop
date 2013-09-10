<?php

/**
 *  rest服务
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- act 操作项的初始化
/*------------------------------------------------------ */
if (empty($_REQUEST['act']) || $_REQUEST['act'] != 'rest')
{
    die("act errer") ;
}

get_data();

return;




/*************************************************************************************
 * PRIVATE FUNCTION
 */
function get_data(){
	$sql = "select id,name from ".$GLOBALS['ecs']->table('category')." order by sort";
	$rs = $GLOBALS['db']->getAll($sql);
	foreach($rs as $k=>$r){
		$sql = "select name from ".$GLOBALS['ecs']->table('dishes')." where cate_id=".$r['id']." order by sort";
		$rs[$k]['dishes'] = $GLOBALS['db']->getAll($sql);
	}
	
	print_r($rs);echo '<br><br><br><br>';
// 	return json_encode($rs);
}
?>