<?php echo $this->fetch('Inc/header.moban'); ?>
<div id="bdw" class="bdw">
	<div id="bd" class="cf">
		<div id="content">
			<?php if ($this->_var['cart_list']): ?>
			<?php echo $this->fetch('Inc/cart/goods_cart_list.moban'); ?>
			<?php echo $this->fetch('Inc/cart/cart_other_goods_list.moban'); ?>
			<?php else: ?>
			<?php echo $this->fetch('Inc/cart/goods_cart_empty.moban'); ?>
			<?php endif; ?>
		</div>
		<div id=sidebar>
			<!--START-->
			<?php echo $this->fetch('Inc/side/side_referrals.moban'); ?>
			<!--END-->
		</div>
	</div>
	<!-- bd end -->
</div>
<?php echo $this->fetch('Inc/footer.moban'); ?>	