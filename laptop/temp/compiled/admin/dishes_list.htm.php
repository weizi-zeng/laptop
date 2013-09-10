<!-- $Id: dish_list.htm 15898 2009-05-04 07:25:41Z liuhui $ -->

<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js,listtable.js')); ?>
<!-- 品牌搜索 -->
<?php echo $this->fetch('dishes_search.htm'); ?>
<form method="post" action="" name="listForm">
<!-- start dish list -->
<div class="list-div" id="listDiv">
<?php endif; ?>

  <table cellpadding="3" cellspacing="1">
    <tr>
      <th>菜品名称</th>
      <th>类别</th>
      <th>价格</th>
      <th>主厨推荐</th>
      <th>排序</th>
      <th>图片</th>
      <th>描述</th>
      <th>操作</th>
    </tr>
    <?php $_from = $this->_var['dish_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'dish');if (count($_from)):
    foreach ($_from AS $this->_var['dish']):
?>
    <tr>
      <td class="first-cell">
        <span style="float:center"><?php echo $this->_var['dish']['name']; ?></span>
      </td>
      <td><?php echo $this->_var['dish']['cate']; ?></td>
      <td><?php echo $this->_var['dish']['price']; ?></td>
      <td align="center"><img src="images/<?php if ($this->_var['dish']['isRecommend']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_recommend', <?php echo $this->_var['dish']['id']; ?>)" /></td>
      <td><?php echo $this->_var['dish']['sort']; ?></td>
      <?php if ($this->_var['dish']['img']): ?>
      <td><img src="images/disheimg/<?php echo $this->_var['rest']; ?>/<?php echo $this->_var['dish']['img']; ?>"  style="height:60px;width:60px;"/></td>
      <?php else: ?>
      <td><img src="images/disheimg/nopicture.gif"  style="height:60px;width:60px;"/></td>
      <?php endif; ?>
      <td><?php echo $this->_var['dish']['description']; ?></td>
      <td align="center">
        <a href="dishes.php?act=edit&id=<?php echo $this->_var['dish']['id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>"><?php echo $this->_var['lang']['edit']; ?></a> |
        <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['dish']['id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['edit']; ?>"><?php echo $this->_var['lang']['remove']; ?></a> 
      </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="11"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <tr>
      <td align="right" nowrap="true" colspan="6">
      <?php echo $this->fetch('page.htm'); ?>
      </td>
    </tr>
  </table>

<?php if ($this->_var['full_page']): ?>
<!-- end dish list -->
</div>
</form>

<script type="text/javascript" language="javascript">
  <!--
  listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
  listTable.pageCount = <?php echo $this->_var['page_count']; ?>;

  <?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
  listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

  
  
  //-->
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>