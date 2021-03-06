<!-- $Id: privilege_info.htm 16616 2009-08-27 01:56:35Z liuhui $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
<form name="theForm" method="post" enctype="multipart/form-data" onsubmit="return validate();">
<table width="100%">
  <tr>
    <td class="label">餐厅名称</td>
    <td>
      <input type="text" name="restaurant" maxlength="20" value="<?php echo htmlspecialchars($this->_var['user']['restaurant']); ?>" size="34"/><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <td class="label">邮箱</td>
    <td>
      <input type="text" name="email" value="<?php echo htmlspecialchars($this->_var['user']['email']); ?>" size="34" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <td class="label">餐厅缩写</td>
    <td>
      <input type="text" name="user_name" maxlength="20" value="<?php echo htmlspecialchars($this->_var['user']['user_name']); ?>" size="34" <?php if ($this->_var['action'] != "add"): ?> disabled="disabled" <?php endif; ?>/><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
 <?php if ($this->_var['action'] == "add"): ?>
  <tr>
    <td class="label">密码</td>
    <td>
      <input type="password" name="password" maxlength="32" size="34" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <td class="label">确认密码</td>
    <td>
      <input type="password" name="pwd_confirm" maxlength="32" size="34" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_var['action'] != "add"): ?>
  <tr>
    <td class="label">旧密码</td>
    <td>
      <input type="password" name="old_password" size="34" /><?php echo $this->_var['lang']['require_field']; ?>
      <br /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="passwordNotic"><?php echo $this->_var['lang']['password_notic']; ?></span></td>
  </tr>
  <tr>
    <td class="label">新密码</td>
    <td>
      <input type="password" name="new_password" maxlength="32" size="34" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <td class="label">确认新密</td>
    <td>
      <input type="password" name="pwd_confirm" value="" size="34" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <?php if ($this->_var['user']['agency_name']): ?>
  <tr>
    <td class="label"><?php echo $this->_var['lang']['agency']; ?></td>
    <td><?php echo $this->_var['user']['agency_name']; ?></td>
  </tr>
  <?php endif; ?>
  
  <?php endif; ?>
  
  
  <?php if ($this->_var['action'] == "modif"): ?>
  <tr>
  <td align="left" class="label"><?php echo $this->_var['lang']['edit_navi']; ?></td>
  <td>
      <table style="width:300px" cellspacing="0">
        <tr>
        <td valign="top">
          <input type="hidden" name="nav_list[]" id="nav_list[]" />
          <select name="menus_navlist" id="menus_navlist" multiple="true" style="width: 120px; height: 180px" onclick="setTimeout('toggleButtonSatus()', 1);">
          <?php echo $this->html_options(array('options'=>$this->_var['nav_arr'])); ?>
          </select></td>
        <td align="center">
         <input type="button" class="button" value="<?php echo $this->_var['lang']['move_up']; ?>" id="btnMoveUp" onclick="moveOptions('up')" disabled="true" />
         <input type="button" class="button" value="<?php echo $this->_var['lang']['move_down']; ?>" id="btnMoveDown" onclick="moveOptions('down')" disabled="true" />
         <input type="button" value="<?php echo $this->_var['lang']['add_nav']; ?>" id="btnAdd" onclick="JavaScript:addItem(theForm.all_menu_list,theForm.menus_navlist); this.disabled=true; " class="button" disabled="true" /><br />
         <input type="button" value="<?php echo $this->_var['lang']['remove_nav']; ?>" onclick="JavaScript:delItem(theForm.menus_navlist); toggleButtonSatus()" class="button" disabled="true" id="btnRemove" /></td>
        <td>
          <select id="all_menu_list" name="all_menu_list" size="15" multiple="true" style="width:150px; height: 180px" onchange="toggleAddButton()">
            <?php $_from = $this->_var['menus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'menu');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['menu']):
?>
              <?php if ($this->_var['key'] != "admin_home" && $this->_var['menus'][$this->_var['key']]): ?>
              <option value="" style="font-weight:bold;"><?php echo $this->_var['lang'][$this->_var['key']]; ?></option>
              <?php $_from = $this->_var['menus'][$this->_var['key']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['item']):
?>
              <option value="<?php echo $this->_var['item']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_var['lang'][$this->_var['k']]; ?></option>
              <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
              <?php endif; ?>
             <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
           </select></td>
        </tr>
      </table></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" />&nbsp;&nbsp;&nbsp;
      <input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button" />
      <input type="hidden" name="act" value="<?php echo $this->_var['form_act']; ?>" />
      <input type="hidden" name="id" value="<?php echo $this->_var['user']['user_id']; ?>" /></td>
  </tr>
</table>
</form>
</div>
<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js,validator.js')); ?>
<script language="JavaScript">
var action = "<?php echo $this->_var['action']; ?>";
<!--

document.forms['theForm'].elements['user_name'].focus();

/**
 * 切换增加按钮的状态
 */
function toggleAddButton()
{
    var sel = document.getElementById("all_menu_list");
    document.getElementById("btnAdd").disabled = (sel.selectedIndex > -1) ? false : true;
}

/**
 * 切换移出，上移，下移按钮状态
 */
function toggleButtonSatus()
{
    var sel = document.getElementById("menus_navlist");
    document.getElementById("btnRemove").disabled = (sel.selectedIndex > -1) ? false : true;
    document.getElementById("btnMoveUp").disabled = (sel.selectedIndex > -1) ? false : true;
    document.getElementById("btnMoveDown").disabled = (sel.selectedIndex > -1) ? false : true;
}

/**
 * 移动选定的列表项
 */
function moveOptions(direction)
{
    var sel = document.getElementById('menus_navlist');
    if (sel.selectedIndex == -1)
    {
        return;
    }

    len = sel.length
    for (i = 0; i < len; i++)
    {
        if (sel.options[i].selected)
        {
            if (i == 0 && direction == 'up')
            {
                return;
            }

            newOpt = sel.options[i].cloneNode(true);

            sel.removeChild(sel.options[i]);
            tarOpt = (direction == "up") ? sel.options[i-1] : sel.options[i+1]
            sel.insertBefore(newOpt, tarOpt);
            newOpt.selected = true;
            break;
        }
    }
}

/**
* 检查表单输入的数据
*/
function validate()
{
  get_navlist();

  validator = new Validator("theForm");
  validator.password = function (controlId, msg)
  {
    var obj = document.forms[this.formName].elements[controlId];
    obj.value = Utils.trim(obj.value);
    if (!(obj.value.length >= 6 && /\d+/.test(obj.value) && /[a-zA-Z]+/.test(obj.value)))
    {
      this.addErrorMsg(msg);
    }

  }

  validator.required("user_name", "餐厅缩写不能为空！");
  validator.required("email", "邮箱不能为空！", 1);
  validator.isEmail("email", "邮箱填写错误！");

  if (action == "add")
  {
    if (document.forms['theForm'].elements['password'])
    {
      validator.password("password", "密码不合法");
      validator.eqaul("password", "pwd_confirm", "确认密码不一致");
    }
  }
  if (action == "edit" || action == "modif")
  {
    if (document.forms['theForm'].elements['old_password'].value.length > 0)
    {
      validator.password("new_password", "密码不合法");
      validator.eqaul("new_password", "pwd_confirm", "确认密码不一致");
    }
  }

  return validator.passed();
}

function get_navlist()
{
  if (!document.getElementById('nav_list[]'))
  {
    return;
  }

  document.getElementById('nav_list[]').value = joinItem(document.getElementById('menus_navlist'));
  //alert(document.getElementById('nav_list[]').value);
}
//-->

</script>
<?php echo $this->fetch('pagefooter.htm'); ?>