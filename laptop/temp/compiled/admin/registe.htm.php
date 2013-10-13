<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />

<style type="text/css">
body {
  color: white;
}
</style>

<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js,validator.js')); ?>
<script language="JavaScript">
<!--
// 这里把JS用到的所有语言都赋值到这里
<?php $_from = $this->_var['lang']['js_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

if (window.parent != window)
{
  window.top.location.href = location.href;
}

//-->
</script>
</head>
<body style="background: #278296">
<form method="get" action="privilege.php" name='theForm' enctype="application/x-www-form-urlencoded" onsubmit="return validate()">
  <table cellspacing="0" cellpadding="0" style="margin-top: 100px" align="center">
  <tr>
    <td style="padding-left: 50px">
      <table>
      <tr>
        <td>餐厅名称：</td>
        <td><input type="text" name="name" value="参谋天下"/></td>
      </tr>
      <tr>
        <td>餐厅地址：</td>
        <td><input type="password" name="address" value="天心区侯家塘"/></td>
      </tr>
      <tr>
        <td>手机号码：</td>
        <td><input type="text" name="phone" value="13272003649"/></td>
      </tr>
      <tr>
        <td>邮箱：</td>
        <td><input type="text" name="email" value="wz@oceanwing.com"/></td>
      </tr>
      <tr><td colspan="2"><input type="checkbox" value="1" name="remember" id="remember" /><label for="remember">保存信息。</label></td></tr>
      <tr><td>&nbsp;</td><td><input type="submit" value="注册" class="button" /><input type="button" value="返回登陆" class="button" onclick="history.back();"/></td></tr>
      </table>
    </td>
  </tr>
  </table>
  <input type="hidden" name="act" value="login" />
  <input type="hidden" name="registe" value="sign_in" />
</form>
<script language="JavaScript">
<!--
  document.forms['theForm'].elements['username'].focus();
  
  /**
   * 检查表单输入的内容
   */
  function validate()
  {
    var validator = new Validator('theForm');
    validator.required('name', "餐厅名称不能为空！");
    validator.required('address', "餐厅地址不能为空！");
    validator.required('phone', "手机号码不能为空！");
    validator.required('email', "邮箱不能为空！");
    return validator.passed();
  }
  
//-->
</script>
</body>