<!-- $Id: goods_batch_add.htm 16544 2009-08-13 07:55:57Z liuhui $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
<form action="goods_batch.php?act=upload" method="post" enctype="multipart/form-data" name="theForm" onsubmit="return formValidate()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label"><?php echo $this->_var['lang']['file_charset']; ?></td>
    <td><select name="charset" id="charset">
      <?php echo $this->html_options(array('options'=>$this->_var['lang_list'])); ?>
    </select></td>
  </tr>
  
  <tr>
    <td class="label">
      <a href="javascript:showNotice('noticeFile');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a>
      <?php echo $this->_var['lang']['csv_file']; ?></td>
    <td><input name="file" type="file" size="40">
    <br />
      <span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeFile"><?php echo $this->_var['lang']['notice_file']; ?></span></td>
  </tr>
  
  
  <tr align="center">
    <td colspan="2"><input name="submit" type="submit" id="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" />
    <input name="data_cat" type="hidden" value="taobao"/>
    </td>
  </tr>
</table>
</form>
<table width="100%">
  <tr>
    <td>&nbsp;</td>
    <td width="80%"><?php echo $this->_var['lang']['use_help']; ?></td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js,validator.js')); ?>

<script language="JavaScript">

    /**
     * 检查输入是否完整
     */
    function formValidate()
    {
        return true;
    }
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>