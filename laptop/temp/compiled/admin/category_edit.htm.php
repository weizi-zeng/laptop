<!-- $Id: cate_edit.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'validator.js,transport.js')); ?>
<div class="main-div">
<form method="post" action="category.php" name="theForm" onSubmit="return validate()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label">编号</td>
    <td><input name="id" type="text" id="id" value="<?php echo $this->_var['cate']['id']; ?>" disabled="disabled" maxlength="60" />
    </td>
  </tr>
  <tr>
    <td class="label">菜品类型</td>
    <td><input name="name" type="text" id="name" value="<?php echo $this->_var['cate']['name']; ?>" maxlength="60" />
    </td>
  </tr>
  
  <tr>
    <td align="right">排序</td>
    <td><input name="sort" type="text" id="sort" value="<?php echo $this->_var['cate']['sort']; ?>">
  </tr>

  <tr>
    <td class="label">
      <input type="submit" class="button" value="<?php echo $this->_var['lang']['button_submit']; ?>" /></td>
     <td>
      <input type="reset" class="button" value="<?php echo $this->_var['lang']['button_reset']; ?>" />
      <input type="hidden" name="id" value="<?php echo $this->_var['cate']['id']; ?>" />
      <input type="hidden" name="act" value="<?php echo $this->_var['insert_or_update']; ?>" /></td>
  </tr>
</table>
</form>
</div>


<script language="JavaScript">
<!--
/**
 * 检查表单输入的数据
 */
function validate()
{
    validator = new Validator("theForm");
    validator.required('name', "菜品类型不能为空");
    return validator.passed();
}
//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>
