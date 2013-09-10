<!-- $Id: brand_search.htm 2009-05-04 liuhui $ -->
<div class="form-div">
  <form action="javascript:search_brand()" name="searchForm">
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
  
    类别: <select name="cate_id"><option value="0">所有...</option>
   <?php $_from = $this->_var['category_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cate');if (count($_from)):
    foreach ($_from AS $this->_var['cate']):
?>
    	<option value="<?php echo $this->_var['cate']['id']; ?>"><?php echo $this->_var['cate']['name']; ?></option>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </select>
   菜品: <input type="text" name="name" size="15" />
     
    <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>

<script language="JavaScript">
    function search_brand()
    {
        listTable.filter['name'] = Utils.trim(document.forms['searchForm'].elements['name'].value);
        listTable.filter['cate_id'] = Utils.trim(document.forms['searchForm'].elements['cate_id'].value);
        listTable.filter['page'] = 1;
        listTable.loadList();
    }

</script>
