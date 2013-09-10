<?php

/**
 *  rest服务
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

echo get_data($_REQUEST['act']);

return;




/*************************************************************************************
 * PRIVATE FUNCTION
 */
function get_data($restaurant){
	$sql = "select id,name,sort from ".$GLOBALS['ecs']->table($restaurant.'_category')." order by sort";
	$rs = $GLOBALS['db']->getAll($sql);
	foreach($rs as $k=>$r){
		$sql = "select * from ".$GLOBALS['ecs']->table($restaurant.'_dishes')." where cate_id=".$r['id']." order by sort";
		$rs[$k]['dishes'] = $GLOBALS['db']->getAll($sql);
	}
	return json_encode($rs);
}
?>