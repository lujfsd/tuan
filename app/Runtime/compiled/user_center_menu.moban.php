<?php $_from = $this->_var['user_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'menu_item_0_51716300_1406344910');if (count($_from)):
    foreach ($_from AS $this->_var['menu_item_0_51716300_1406344910']):
?>
      	<li <?php if ($this->_var['menu_item_0_51716300_1406344910']['act'] == 1): ?> class="current" <?php endif; ?>><a href="<?php echo $this->_var['menu_item_0_51716300_1406344910']['url']; ?>"><?php echo $this->_var['menu_item_0_51716300_1406344910']['name']; ?></a><span></span></li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>