<div class="order-check-form ">	
	<p class="choose-type"><?php echo $this->_var['lang']['XY_TOTAL_PRICES']; ?><span class="red"><?php echo $this->_var['cart_total']['all_fee_format']; ?></span>	

	<?php if ($this->_var['cart_total']['total_add_score'] != 0): ?>
	&nbsp;&nbsp;<?php echo $this->_var['lang']['SCORE_UNIT']; ?>：<?php echo $this->_var['cart_total']['total_add_score']; ?> <br />		
	<?php endif; ?>	
	</p>	
	<p style="text-align:right; line-height:24px;">	
	
	<?php if ($this->_var['cart_total']['goods_total_price'] > 0): ?>	
	<?php echo $this->_var['lang']['XY_TOTAL_G_PRICES']; ?><?php echo $this->_var['cart_total']['goods_total_price_format']; ?> <br />	
	<?php endif; ?>	
	
	<?php if ($this->_var['cart_total']['delivery_fee'] != 0): ?>
			+ <?php echo $this->_var['lang']['DELIVERY_FEE']; ?>：<?php echo $this->_var['cart_total']['delivery_fee_format']; ?> 
			<?php if ($this->_var['cart_total']['delivery_free'] == 1): ?><?php echo $this->_var['lang']['DELIVERY_FREE']; ?><?php endif; ?><br />	
	<?php endif; ?>	
	
	<?php if ($this->_var['cart_total']['protect_fee'] != 0): ?>	+ <?php echo $this->_var['lang']['PROTECT_FEE']; ?>：<?php echo $this->_var['cart_total']['protect_fee_format']; ?> <br />		<?php endif; ?>	
	<?php if ($this->_var['cart_total']['tax'] == 1): ?>	+ <?php echo $this->_var['lang']['TAX_MONEY']; ?>：<?php echo $this->_var['cart_total']['tax_money_format']; ?> <br />		<?php endif; ?>		
	<?php if ($this->_var['cart_total']['payment_fee'] != 0): ?>	+ <?php echo $this->_var['lang']['PAY_AMOUNT']; ?>：<?php echo $this->_var['cart_total']['payment_fee_format']; ?> <br />		<?php endif; ?>
	<?php if ($this->_var['cart_total']['discount_price'] != 0): ?>	- <?php echo $this->_var['lang']['XY_RANK_DISCOUNT']; ?><?php echo $this->_var['cart_total']['discount_price_format']; ?> <br />		<?php endif; ?>		
	<?php if ($this->_var['cart_total']['credit'] != 0): ?>	- <?php echo $this->_var['lang']['XY_BALANCE_PAY']; ?><?php echo $this->_var['cart_total']['credit_format']; ?> <br />		<?php endif; ?>		
	<?php if ($this->_var['cart_total']['ecvFee'] != 0): ?>	- <?php echo $this->_var['lang']['XY_VOUCHER']; ?><?php echo $this->_var['cart_total']['ecvFee_format']; ?> <br />		<?php endif; ?>		
	<?php if ($this->_var['cart_total']['incharge'] != 0): ?>	- <?php echo $this->_var['lang']['PAID_AMOUNT']; ?>：<?php echo $this->_var['cart_total']['incharge_format']; ?> <br />		<?php endif; ?>		
	= <?php echo $this->_var['lang']['XY_MUSE_TOTAL_PAY']; ?><span class="red"><?php echo $this->_var['cart_total']['total_price_format']; ?></span>&nbsp;	
	<?php if ($this->_var['cart_total']['payment_name'] && $this->_var['cart_total']['total_price'] != 0): ?> <?php echo $this->_var['lang']['PAY_BY']; ?> [<?php echo $this->_var['cart_total']['payment_name']; ?>]<?php echo $this->_var['lang']['PAY']; ?>	<?php endif; ?>	<br />	
	</p>	
	<div class="blank"></div>
</div>