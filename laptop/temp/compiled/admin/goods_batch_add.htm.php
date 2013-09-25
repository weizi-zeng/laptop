<!-- $Id: goods_batch_add.htm 16544 2009-08-13 07:55:57Z liuhui $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
<form action="goods_batch.php?act=upload" method="post" enctype="multipart/form-data" name="theForm" onsubmit="return formValidate()">
<table cellspacing="1" cellpadding="3" width="100%">
	<tr>
		<td colspan="2"><div style="color:red">
			<p>说明：批量导入菜品必须找技术员来支持导入！<br />
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 文件说明：<br />
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 1、必须是 *.csv 格式的文件<br />
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 2、所有条目没有合并行与合并列<br />
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 3、excel格式（请参照下面的模板）：&nbsp; 菜品类型，菜品类型排序，菜品名称，菜品排序，是否为推荐，规格， 价格， 照片， 描述&nbsp; <br />
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 4、字段说明：<br />
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 4.1、 除开照片与描述可填可不填外，其他列的数据必须填写<br />
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 4.2、如果填写了照片里面的数据，需要将名称命名规范，并且将菜品名称与需要上传的图片名称命名一致，在批量导入数据之后，将菜品图片发给技术员，由技术员上传图片<br />
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 4.3、是否为推荐只能填&ldquo;是&rdquo;或者&ldquo;否&rdquo;</p>
<p>&nbsp;</p>
<br><a href="template.csv" target="_blank">下载模版</a>
		</div></td>
	</tr>
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