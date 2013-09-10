<div id="footer">
<?php echo $this->_var['query_info']; ?><?php echo $this->_var['gzip_enabled']; ?><?php echo $this->_var['memory_info']; ?><br />
<?php echo $this->_var['lang']['copyright']; ?>
</div>
<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js')); ?>
<!-- 新订单提示信息 -->
<div id="popMsg">
  <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#cfdef4" border="0">
  <tr>
    <td style="color: #0f2c8c" width="30" height="24"></td>
    <td style="font-weight: normal; color: #1f336b; padding-top: 4px;padding-left: 4px" valign="center" width="100%"> <?php echo $this->_var['lang']['order_notify']; ?></td>
    <td style="padding-top: 2px;padding-right:2px" valign="center" align="right" width="19"><span title="关闭" style="cursor: hand;cursor:pointer;color:red;font-size:12px;font-weight:bold;margin-right:4px;" onclick="Message.close()" >×</span><!-- <img title=关闭 style="cursor: hand" onclick=closediv() hspace=3 src="msgclose.jpg"> --></td>
  </tr>
  <tr>
    <td style="padding-right: 1px; padding-bottom: 1px" colspan="3" height="70">
    <div id="popMsgContent">
      <p><?php echo $this->_var['lang']['new_order_1']; ?><strong style="color:#ff0000" id="spanNewOrder">1</strong><?php echo $this->_var['lang']['new_order_2']; ?>
      <strong style="color:#ff0000" id="spanNewPaid">0</strong><?php echo $this->_var['lang']['new_order_3']; ?></p>
      <p align="center" style="word-break:break-all"><a href="order.php?act=list"><span style="color:#ff0000"><?php echo $this->_var['lang']['new_order_link']; ?></span></a></p>
    </div>
    </td>
  </tr>
  </table>
</div>

<!--
<embed src="images/online.wav" width="0" height="0" autostart="false" name="msgBeep" id="msgBeep" enablejavascript="true"/>
-->
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0" id="msgBeep" width="1" height="1">
  <param name="movie" value="images/online.swf">
  <param name="quality" value="high">
  <embed src="images/online.swf" name="msgBeep" id="msgBeep" quality="high" width="0" height="0" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?p1_prod_version=shockwaveflash">
  </embed>
</object>

</body>
</html>