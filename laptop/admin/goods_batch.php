<?php
/**
 * ECSHOP 商品批量上传、修改
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 批量上传
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
    admin_priv('goods_batch');

    /* 取得可选语言 */
    $dir = opendir('../languages');
    $lang_list = array(
        'UTF8'      => $_LANG['charset']['utf8'],
        'GB2312'    => $_LANG['charset']['zh_cn'],
    );
    $smarty->assign('lang_list',     $lang_list);

    /* 参数赋值 */
    $ur_here = "批量导入菜品";
    $smarty->assign('ur_here', $ur_here);

    /* 显示模板 */
    assign_query_info();
    $smarty->display('goods_batch_add.htm');
}

/*------------------------------------------------------ */
//-- 批量上传：处理
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'upload')
{
    /* 检查权限 */
    admin_priv('goods_batch');

    /* 将文件按行读入数组，逐行进行解析 */
    $line_number = 0;
    $arr = array();
    $goods_list = array();
    
    $data = file($_FILES['file']['tmp_name']);
    
    if($_POST['data_cat'] == 'taobao')
    {
        $id_is = 0;
        foreach ($data AS $line)
        {
            // 跳过第一行
            if ($line_number == 0)
            {
                $line_number++;
                continue;
            }

            //转换编码格式
            $line = ecs_iconv($_POST['charset'], 'UTF8', $line);
            
            // 初始化
            $arr    = array();
            $line_list = explode(",",$line);
            
            
            
            $i=0;
            $arr['sort'] = trim($line_list[$i++],'"');
            $arr['cate_id'] = get_cate_id_by_name(trim($line_list[$i++],'"'));
            $arr['name'] = trim($line_list[$i++],'"');
            
            $arr['unit'] = trim($line_list[$i++]);
            $arr['price'] = trim($line_list[$i++]);
            
            $price_res = "";
            if(strpos($arr['unit'], "/") && strpos($arr['price'], "/")){
            	$unit = explode("/",$arr['unit']);
            	$price = explode("/",$arr['price']);
            	
            	foreach ($unit as $k=>$u){
            		if($price[$k]){
            			$price_res.="常例".$u.":".$price[$k]."/".$u.";";
            		}
            	}
            	
            }else if(strpos($arr['price'], "/")){
            	$unit = explode("/",$arr['unit']);
            	$price = explode("/",$arr['price']);
            	
            	foreach ($unit as $k=>$u){
            		if($price[$k]){
            			$price_res.="常例".$u.":".$price[$k].";";
            		}
            	}
            	
            }else{
            	
            	$price_res.="常例".$arr['unit'].":".$arr['price']."/".$arr['unit'].";";
            	
            }
            
            $arr['price'] = $price_res;
            
            print_r($arr); echo '<br>';

            $sql = "insert into ". $GLOBALS['ecs']->table('dishes') . " (name,cate_id,sort,price)values('$arr[name]','$arr[cate_id]','$arr[sort]','$arr[price]') ";
            $GLOBALS['db']->query($sql);
            
            continue;
            

        }
        
        die("添加成功!");
    }
    
}


/*------------------------------------------------------ */
//-- 批量上传：入库
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'insert')
{
    /* 检查权限 */
    admin_priv('goods_batch');

    if (isset($_POST['checked']))
    {
        include_once(ROOT_PATH . 'includes/cls_image.php');
        $image = new cls_image($_CFG['bgcolor']);

        /* 字段默认值 */
        $default_value = array(
            'brand_id'      => 0,
            'goods_number'  => 0,
            'goods_weight'  => 0,
            'market_price'  => 0,
            'shop_price'    => 0,
            'warn_number'   => 0,
            'is_real'       => 1,
            'is_on_sale'    => 1,
            'is_alone_sale' => 1,
            'integral'      => 0,
            'is_best'       => 0,
            'is_new'        => 0,
            'is_hot'        => 0,
            'goods_type'    => 0,
        );

        /* 查询品牌列表 */
        $brand_list = array();
        $sql = "SELECT brand_id, brand_name FROM " . $ecs->table('brand');
        $res = $db->query($sql);
        while ($row = $db->fetchRow($res))
        {
            $brand_list[$row['brand_name']] = $row['brand_id'];
        }

        /* 字段列表 */
        $field_list = array_keys($_LANG['upload_goods']);
        $field_list[] = 'goods_class'; //实体或虚拟商品

        /* 获取商品good id */
        $max_id = $db->getOne("SELECT MAX(goods_id) + 1 FROM ".$ecs->table('goods'));

        /* 循环插入商品数据 */
        foreach ($_POST['checked'] AS $key => $value)
        {
            // 合并
            $field_arr = array(
                'cat_id'        => $_POST['cat'],
                'add_time'      => gmtime(),
                'last_update'   => gmtime(),
            );

            foreach ($field_list AS $field)
            {
                // 转换编码
                $field_value = isset($_POST[$field][$value]) ? $_POST[$field][$value] : '';

                /* 虚拟商品处理 */
                if ($field == 'goods_class')
                {
                    $field_value = intval($field_value);
                    if ($field_value == G_CARD)
                    {
                        $field_arr['extension_code'] = 'virtual_card';
                    }
                    continue;
                }

                // 如果字段值为空，且有默认值，取默认值
                $field_arr[$field] = !isset($field_value) && isset($default_value[$field]) ? $default_value[$field] : $field_value;

                // 特殊处理
                if (!empty($field_value))
                {
                    // 图片路径
                    if (in_array($field, array('original_img', 'goods_img', 'goods_thumb')))
                    {
                        if(strpos($field_value,'|;')>0)
                        {
                            $field_value=explode(':',$field_value);
                            $field_value=$field_value['0'];
                            @copy(ROOT_PATH.'images/'.$field_value.'.tbi',ROOT_PATH.'images/'.$field_value.'.jpg');
                            if(is_file(ROOT_PATH.'images/'.$field_value.'.jpg'))
                            {
                                $field_arr[$field] =IMAGE_DIR . '/' . $field_value.'.jpg';
                            }
                        }
                        else
                        {
                            $field_arr[$field] = IMAGE_DIR . '/' . $field_value;
                        }
                      }
                    // 品牌
                    elseif ($field == 'brand_name')
                    {
                        if (isset($brand_list[$field_value]))
                        {
                            $field_arr['brand_id'] = $brand_list[$field_value];
                        }
                        else
                        {
                            $sql = "INSERT INTO " . $ecs->table('brand') . " (brand_name) VALUES ('" . addslashes($field_value) . "')";
                            $db->query($sql);
                            $brand_id = $db->insert_id();
                            $brand_list[$field_value] = $brand_id;
                            $field_arr['brand_id'] = $brand_id;
                        }
                    }
                    // 整数型
                    elseif (in_array($field, array('goods_number', 'warn_number', 'integral')))
                    {
                        $field_arr[$field] = intval($field_value);
                    }
                    // 数值型
                    elseif (in_array($field, array('goods_weight', 'market_price', 'shop_price')))
                    {
                        $field_arr[$field] = floatval($field_value);
                    }
                    // bool型
                    elseif (in_array($field, array('is_best', 'is_new', 'is_hot', 'is_on_sale', 'is_alone_sale', 'is_real')))
                    {
                        $field_arr[$field] = intval($field_value) > 0 ? 1 : 0;
                    }
                }

                if ($field == 'is_real')
                {
                    $field_arr[$field] = intval($_POST['goods_class'][$key]);
                }
            }

            if (empty($field_arr['goods_sn']))
            {
                $field_arr['goods_sn'] = generate_goods_sn($max_id);
            }

            /* 如果是虚拟商品，库存为0 */
            if ($field_arr['is_real'] == 0)
            {
                $field_arr['goods_number'] = 0;
            }
            $db->autoExecute($ecs->table('goods'), $field_arr, 'INSERT');

            $max_id = $db->insert_id() + 1;

            /* 如果图片不为空,修改商品图片，插入商品相册*/
            if (!empty($field_arr['original_img']) || !empty($field_arr['goods_img']) || !empty($field_arr['goods_thumb']))
            {
                $goods_img     = '';
                $goods_thumb   = '';
                $original_img  = '';
                $goods_gallery = array();
                $goods_gallery['goods_id'] = $db->insert_id();

                if (!empty($field_arr['original_img']))
                {
                    //设置商品相册原图和商品相册图
                    if ($_CFG['auto_generate_gallery'])
                    {
                        $ext         = substr($field_arr['original_img'], strrpos($field_arr['original_img'], '.'));
                        $img         = dirname($field_arr['original_img']) . '/' . $image->random_filename() . $ext;
                        $gallery_img = dirname($field_arr['original_img']) . '/' . $image->random_filename() . $ext;
                        @copy(ROOT_PATH . $field_arr['original_img'], ROOT_PATH . $img);
                        @copy(ROOT_PATH . $field_arr['original_img'], ROOT_PATH . $gallery_img);
                        $goods_gallery['img_original'] = reformat_image_name('gallery', $goods_gallery['goods_id'], $img, 'source');
                    }
                    //设置商品原图
                    if ($_CFG['retain_original_img'])
                    {
                        $original_img                  = reformat_image_name('goods', $goods_gallery['goods_id'], $field_arr['original_img'], 'source');
                    }
                    else
                    {
                        @unlink(ROOT_PATH . $field_arr['original_img']);
                    }
                }

                if (!empty($field_arr['goods_img']))
                {
                    //设置商品相册图
                    if ($_CFG['auto_generate_gallery'] && !empty($gallery_img))
                    {
                        $goods_gallery['img_url'] = reformat_image_name('gallery', $goods_gallery['goods_id'], $gallery_img, 'goods');
                    }
                    //设置商品图
                    $goods_img                = reformat_image_name('goods', $goods_gallery['goods_id'], $field_arr['goods_img'], 'goods');
                }

                if (!empty($field_arr['goods_thumb']))
                {
                    //设置商品相册缩略图
                    if ($_CFG['auto_generate_gallery'])
                    {
                        $ext           = substr($field_arr['goods_thumb'], strrpos($field_arr['goods_thumb'], '.'));
                        $gallery_thumb = dirname($field_arr['goods_thumb']) . '/' . $image->random_filename() . $ext;
                        @copy(ROOT_PATH . $field_arr['goods_thumb'], ROOT_PATH . $gallery_thumb);
                        $goods_gallery['thumb_url'] = reformat_image_name('gallery_thumb', $goods_gallery['goods_id'], $gallery_thumb, 'thumb');
                    }
                    //设置商品缩略图
                    $goods_thumb = reformat_image_name('goods_thumb', $goods_gallery['goods_id'], $field_arr['goods_thumb'], 'thumb');
                }

                //修改商品图
                $db->query("UPDATE " . $ecs->table('goods') . " SET goods_img = '$goods_img', goods_thumb = '$goods_thumb', original_img = '$original_img' WHERE goods_id='" . $goods_gallery['goods_id'] . "'");

                //添加商品相册图
                if ($_CFG['auto_generate_gallery'])
                {
                    $db->autoExecute($ecs->table('goods_gallery'), $goods_gallery, 'INSERT');
                }
            }
        }
    }

    // 记录日志
    admin_log('', 'batch_upload', 'goods');

    /* 显示提示信息，返回商品列表 */
    $link[] = array('href' => 'goods.php?act=list', 'text' => $_LANG['01_goods_list']);
    sys_msg($_LANG['batch_upload_ok'], 0, $link);
}

/*------------------------------------------------------ */
//-- 批量修改：选择商品
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'select')
{
    /* 检查权限 */
    admin_priv('goods_batch');

    /* 取得分类列表 */
    $smarty->assign('cat_list', cat_list());

    /* 取得品牌列表 */
    $smarty->assign('brand_list', get_brand_list());

    /* 参数赋值 */
    $ur_here = $_LANG['15_batch_edit'];
    $smarty->assign('ur_here', $ur_here);

    /* 显示模板 */
    assign_query_info();
    $smarty->display('goods_batch_select.htm');
}

/*------------------------------------------------------ */
//-- 批量修改：修改
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
    admin_priv('goods_batch');

    /* 取得商品列表 */
    if ($_POST['select_method'] == 'cat')
    {
        $where = " WHERE goods_id " . db_create_in($_POST['goods_ids']);
    }
    else
    {
        $goods_sns = str_replace("\n", ',', str_replace("\r", '', $_POST['sn_list']));
        $sql = "SELECT DISTINCT goods_id FROM " . $ecs->table('goods') .
                " WHERE goods_sn " . db_create_in($goods_sns);
        $goods_ids = join(',', $db->getCol($sql));
        $where = " WHERE goods_id " . db_create_in($goods_ids);
    }
    $sql = "SELECT DISTINCT goods_id, goods_sn, goods_name, market_price, shop_price, goods_number, integral, give_integral, brand_id, is_real FROM " . $ecs->table('goods') . $where;
    $smarty->assign('goods_list', $db->getAll($sql));

    /* 取编辑商品的货品列表 */
    $product_exists = false;
    $sql = "SELECT * FROM " . $ecs->table('products') . $where;
    $product_list = $db->getAll($sql);

    if (!empty($product_list))
    {
        $product_exists = true;
        $_product_list = array();
        foreach ($product_list as $value)
        {
            $goods_attr = product_goods_attr_list($value['goods_id']);
            $_goods_attr_array = explode('|', $value['goods_attr']);
            if (is_array($_goods_attr_array))
            {
                $_temp = '';
                foreach ($_goods_attr_array as $_goods_attr_value)
                {
                     $_temp[] = $goods_attr[$_goods_attr_value];
                }
                $value['goods_attr'] = implode('，', $_temp);
            }

            $_product_list[$value['goods_id']][] = $value;
        }
        $smarty->assign('product_list', $_product_list);

        //释放资源
        unset($product_list, $sql, $_product_list);
    }

    $smarty->assign('product_exists', $product_exists);

    /* 取得会员价格 */
    $member_price_list = array();
    $sql = "SELECT DISTINCT goods_id, user_rank, user_price FROM " . $ecs->table('member_price') . $where;
    $res = $db->query($sql);
    while ($row = $db->fetchRow($res))
    {
        $member_price_list[$row['goods_id']][$row['user_rank']] = $row['user_price'];
    }
    $smarty->assign('member_price_list', $member_price_list);

    /* 取得会员等级 */
    $sql = "SELECT rank_id, rank_name, discount " .
            "FROM " . $ecs->table('user_rank') .
            " ORDER BY discount DESC";
    $smarty->assign('rank_list', $db->getAll($sql));

    /* 取得品牌列表 */
    $smarty->assign('brand_list', get_brand_list());

    /* 赋值编辑方式 */
    $smarty->assign('edit_method', $_POST['edit_method']);

    /* 参数赋值 */
    $ur_here = $_LANG['15_batch_edit'];
    $smarty->assign('ur_here', $ur_here);

    /* 显示模板 */
    assign_query_info();
    $smarty->display('goods_batch_edit.htm');
}

/*------------------------------------------------------ */
//-- 批量修改：提交
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'update')
{
    /* 检查权限 */
    admin_priv('goods_batch');

    if ($_POST['edit_method'] == 'each')
    {
        // 循环更新每个商品
        if (!empty($_POST['goods_id']))
        {
            foreach ($_POST['goods_id'] AS $goods_id)
            {
                //如果存在货品则处理货品
                if (!empty($_POST['product_number'][$goods_id]))
                {
                    $_POST['goods_number'][$goods_id] = 0;
                    foreach ($_POST['product_number'][$goods_id] as $key => $value)
                    {
                        $db->autoExecute($ecs->table('products'), array('product_number', $value), 'UPDATE', "goods_id = '$goods_id' AND product_id = " . $key);

                        $_POST['goods_number'][$goods_id] += $value;
                    }
                }

                // 更新商品
                $goods = array(
                    'market_price'  => floatval($_POST['market_price'][$goods_id]),
                    'shop_price'    => floatval($_POST['shop_price'][$goods_id]),
                    'integral'      => intval($_POST['integral'][$goods_id]),
                    'give_integral'      => intval($_POST['give_integral'][$goods_id]),
                    'goods_number'  => intval($_POST['goods_number'][$goods_id]),
                    'brand_id'      => intval($_POST['brand_id'][$goods_id]),
                    'last_update'   => gmtime(),
                );
                $db->autoExecute($ecs->table('goods'), $goods, 'UPDATE', "goods_id = '$goods_id'");

                // 更新会员价格
                if (!empty($_POST['rank_id']))
                {
                    foreach ($_POST['rank_id'] AS $rank_id)
                    {
                        if (trim($_POST['member_price'][$goods_id][$rank_id]) == '')
                        {
                            /* 为空时不做处理 */
                            continue;
                        }

                        $rank = array(
                            'goods_id'  => $goods_id,
                            'user_rank' => $rank_id,
                            'user_price'=> floatval($_POST['member_price'][$goods_id][$rank_id]),
                        );
                        $sql = "SELECT COUNT(*) FROM " . $ecs->table('member_price') . " WHERE goods_id = '$goods_id' AND user_rank = '$rank_id'";
                        if ($db->getOne($sql) > 0)
                        {
                            if ($rank['user_price'] < 0)
                            {
                                $db->query("DELETE FROM " . $ecs->table('member_price') . " WHERE goods_id = '$goods_id' AND user_rank = '$rank_id'");
                            }
                            else
                            {
                                $db->autoExecute($ecs->table('member_price'), $rank, 'UPDATE', "goods_id = '$goods_id' AND user_rank = '$rank_id'");
                            }

                        }
                        else
                        {
                            if ($rank['user_price'] >= 0)
                            {
                                $db->autoExecute($ecs->table('member_price'), $rank, 'INSERT');
                            }
                        }
                    }
                }
            }
        }
    }
    else
    {
        // 循环更新每个商品
        if (!empty($_POST['goods_id']))
        {
            foreach ($_POST['goods_id'] AS $goods_id)
            {
                // 更新商品
                $goods = array();
                if (trim($_POST['market_price'] != ''))
                {
                    $goods['market_price'] = floatval($_POST['market_price']);
                }
                if (trim($_POST['shop_price']) != '')
                {
                    $goods['shop_price'] = floatval($_POST['shop_price']);
                }
                if (trim($_POST['integral']) != '')
                {
                    $goods['integral'] = intval($_POST['integral']);
                }
                if (trim($_POST['give_integral']) != '')
                {
                    $goods['give_integral'] = intval($_POST['give_integral']);
                }
                if (trim($_POST['goods_number']) != '')
                {
                    $goods['goods_number'] = intval($_POST['goods_number']);
                }
                if ($_POST['brand_id'] > 0)
                {
                    $goods['brand_id'] = $_POST['brand_id'];
                }
                if (!empty($goods))
                {
                    $db->autoExecute($ecs->table('goods'), $goods, 'UPDATE', "goods_id = '$goods_id'");
                }

                // 更新会员价格
                if (!empty($_POST['rank_id']))
                {
                    foreach ($_POST['rank_id'] AS $rank_id)
                    {
                        if (trim($_POST['member_price'][$rank_id]) != '')
                        {
                            $rank = array(
                                        'goods_id'  => $goods_id,
                                        'user_rank' => $rank_id,
                                        'user_price'=> floatval($_POST['member_price'][$rank_id]),
                                        );

                            $sql = "SELECT COUNT(*) FROM " . $ecs->table('member_price') . " WHERE goods_id = '$goods_id' AND user_rank = '$rank_id'";
                            if ($db->getOne($sql) > 0)
                            {
                                if ($rank['user_price'] < 0)
                                {
                                    $db->query("DELETE FROM " . $ecs->table('member_price') . " WHERE goods_id = '$goods_id' AND user_rank = '$rank_id'");
                                }
                                else
                                {
                                    $db->autoExecute($ecs->table('member_price'), $rank, 'UPDATE', "goods_id = '$goods_id' AND user_rank = '$rank_id'");
                                }

                            }
                            else
                            {
                                if ($rank['user_price'] >= 0)
                                {
                                    $db->autoExecute($ecs->table('member_price'), $rank, 'INSERT');
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // 记录日志
    admin_log('', 'batch_edit', 'goods');

    // 提示成功
    $link[] = array('href' => 'goods_batch.php?act=select', 'text' => $_LANG['15_batch_edit']);
    sys_msg($_LANG['batch_edit_ok'], 0, $link);
}

/*------------------------------------------------------ */
//-- 下载文件
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'download')
{
    /* 检查权限 */
    admin_priv('goods_batch');

    // 文件标签
    // Header("Content-type: application/octet-stream");
    header("Content-type: application/vnd.ms-excel; charset=utf-8");
    Header("Content-Disposition: attachment; filename=goods_list.csv");

    // 下载
    if ($_GET['charset'] != $_CFG['lang'])
    {
        $lang_file = '../languages/' . $_GET['charset'] . '/admin/goods_batch.php';
        if (file_exists($lang_file))
        {
            unset($_LANG['upload_goods']);
            require($lang_file);
        }
    }
    if (isset($_LANG['upload_goods']))
    {
        /* 创建字符集转换对象 */
        if ($_GET['charset'] == 'zh_cn' || $_GET['charset'] == 'zh_tw')
        {
            $to_charset = $_GET['charset'] == 'zh_cn' ? 'GB2312' : 'BIG5';
            echo ecs_iconv(EC_CHARSET, $to_charset, join(',', $_LANG['upload_goods']));
        }
        else
        {
            echo join(',', $_LANG['upload_goods']);
        }
    }
    else
    {
        echo 'error: $_LANG[upload_goods] not exists';
    }
}

function get_cate_id_by_name($cate_name){
	$sql = "select id from " . $GLOBALS['ecs']->table('category') . " WHERE name = '$cate_name' ";
	$cate_id = $GLOBALS['db']->getOne($sql);
	if(!$cate_id){
		$GLOBALS['db']->query("insert into ". $GLOBALS['ecs']->table('category') . " (name,sort,memo)values('$cate_name',50,'批量导入') ");
		$cate_id = $GLOBALS['db']->insert_id();
	}
	return $cate_id;
}

?>