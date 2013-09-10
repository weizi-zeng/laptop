<!-- $Id: dishe_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
<form method="post" action="dishes.php" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
<table cellspacing="1" cellpadding="3" width="100%">
 <tr>
    <td class="label">编号</td>
    <td><input type="text" name="id" maxlength="60" value="<?php echo $this->_var['dishe']['id']; ?>" disabled="disabled"/></td>
  </tr>
  <tr>
    <td class="label">菜名</td>
    <td><input type="text" name="name" maxlength="60" value="<?php echo $this->_var['dishe']['name']; ?>" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  
  <tr>
    <td class="label">类别</td>
    <td>
    <select id="cate_id" name="cate_id" >
    <?php $_from = $this->_var['category_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('id', 'cate');if (count($_from)):
    foreach ($_from AS $this->_var['id'] => $this->_var['cate']):
?>
    	<?php if (cate.id == dishe.cate_id): ?>
    	<option value="<?php echo $this->_var['cate']['id']; ?>" selected="selected"><?php echo $this->_var['cate']['name']; ?></option>
    	<?php else: ?>
    	<option value="<?php echo $this->_var['cate']['id']; ?>"><?php echo $this->_var['cate']['name']; ?></option>
    	<?php endif; ?>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </select>
    <?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <td class="label">是否主厨推荐</td>
    <td><input type="radio" name="isRecommend" value="1" <?php if ($this->_var['dishe']['isRecommend'] == 1): ?>checked="checked"<?php endif; ?> /> <?php echo $this->_var['lang']['yes']; ?>
        <input type="radio" name="isRecommend" value="0" <?php if ($this->_var['dishe']['isRecommend'] == 0): ?>checked="checked"<?php endif; ?> /> <?php echo $this->_var['lang']['no']; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">价格</td>
    <td><input type="text" name="price1_key" style="width:40px;" value="<?php echo $this->_var['dishe']['price1']['key']; ?>" />：<input type="text" name="price1_val" style="width:50px;" value="<?php echo $this->_var['dishe']['price1']['val']; ?>" /> 
    <?php echo $this->_var['lang']['require_field']; ?>(例：大盘：20.5元/盘)</td>
  </tr>
  <tr>
    <td class="label"></td>
    <td><input type="text" name="price2_key" style="width:40px;" value="<?php echo $this->_var['dishe']['price2']['key']; ?>" />：<input type="text" name="price2_val" style="width:50px;" value="<?php echo $this->_var['dishe']['price2']['val']; ?>" /></td>
  </tr>
  <tr>
    <td class="label"></td>
    <td><input type="text" name="price3_key" style="width:40px;" value="<?php echo $this->_var['dishe']['price3']['key']; ?>" />：<input type="text" name="price3_val" style="width:50px;" value="<?php echo $this->_var['dishe']['price3']['val']; ?>" /></td>
  </tr>
  
  <tr>
    <td class="label">排序</td>
    <td><input type="text" name="sort" maxlength="20" value="<?php echo $this->_var['dishe']['sort']; ?>" /></td>
  </tr>
  
  <tr>
    <td class="label">图片</td>
    <td><input type="file" name="img" />
    	<?php if ($this->_var['dishe']['img']): ?>
         <img width="200" height="200"  src="images/disheimg/<?php echo $this->_var['rest']; ?>/<?php echo $this->_var['dishe']['img']; ?>" border="0" />
        <?php else: ?>
          <img width="200" height="200" src="images/disheimg/nopicture.gif" />
        <?php endif; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">简介</td>
    <td><textarea  name="description" cols="60" rows="4"  ><?php echo $this->_var['dishe']['description']; ?></textarea></td>
  </tr>
  
  <tr>
    <td colspan="2" align="center"><br />
      <input type="submit" class="button" value="<?php echo $this->_var['lang']['button_submit']; ?>" />
      <input type="reset" class="button" value="<?php echo $this->_var['lang']['button_reset']; ?>" />
      <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
      <input type="hidden" name="old_dishename" value="<?php echo $this->_var['dishe']['name']; ?>" />
      <input type="hidden" name="id" value="<?php echo $this->_var['dishe']['id']; ?>" />
    </td>
  </tr>
</table>
</form>
</div>
<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js,validator.js')); ?>

<script language="JavaScript">
<!--
document.forms['theForm'].elements['name'].focus();
/**
 * 检查表单输入的数据
 */
function validate()
{
    validator = new Validator("theForm");
    validator.required("name",  "必须填写菜品名称！");
    return validator.passed();
}
//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>