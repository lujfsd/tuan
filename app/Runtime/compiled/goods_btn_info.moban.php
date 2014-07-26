<?php if ($this->_var['goods']['type_id'] != 2): ?>
	<?php if ($this->_var['goods']['score_goods'] == 1): ?>
		<strong><?php 
$k = array (
  'name' => 'abs',
  'value' => $this->_var['goods']['score'],
);
echo $k['name']($k['value']);
?></strong>
		<?php if ($this->_var['goods']['promote_begin_time'] <= $this->_var['TIME']): ?>
		<?php if ($this->_var['goods']['is_group_fail'] == 1): ?>
		<span class="deal-price-expire"></span>
		<?php elseif ($this->_var['goods']['is_none']): ?>
		<span class="deal-price-soldout"></span>
		<?php elseif ($this->_var['goods']['is_end']): ?>
		<span class="deal-price-expire"></span>
		<?php else: ?>
		<span><a href="<?php echo $this->_var['goods']['buy_url']; ?>"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/button-deal-score.gif"></a></span>
		<?php endif; ?>
		<?php else: ?>
		<span><a href="javascript:void(0);"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/bg-deal-wait-text.gif"></a></span>
		<?php endif; ?>
	<?php else: ?>
		<strong><?php echo $this->_var['goods']['shop_price_format']; ?></strong>
		<?php if ($this->_var['goods']['promote_begin_time'] <= $this->_var['TIME']): ?>
		<?php if ($this->_var['goods']['is_group_fail'] == 1): ?>
		<span class="deal-price-expire"></span>
		<?php elseif ($this->_var['goods']['is_none']): ?>
		<span class="deal-price-soldout"></span>
		<?php elseif ($this->_var['goods']['is_end']): ?>
		<span class="deal-price-expire"></span>
		<?php else: ?>
		<span><a href="<?php echo $this->_var['goods']['buy_url']; ?>"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/button-deal-buy.gif"></a></span>
		<?php endif; ?>
		<?php else: ?>
		<span><a href="javascript:void(0);"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/bg-deal-wait-text.gif"></a></span>
		<?php endif; ?>
	<?php endif; ?>
<?php else: ?>
	<strong><?php echo $this->_var['goods']['earnest_money_format']; ?></strong>
	<?php if ($this->_var['goods']['promote_begin_time'] <= $this->_var['TIME']): ?>
	<?php if ($this->_var['goods']['is_group_fail'] == 1): ?>
	<span class="deal-price-expire"></span>
	<?php elseif ($this->_var['goods']['is_none']): ?>
	<span class="deal-price-soldout"></span>
	<?php elseif ($this->_var['goods']['is_end']): ?>
	<span class="deal-price-expire"></span>
	<?php else: ?>
	<span>
	<a href="<?php echo $this->_var['goods']['buy_url']; ?>">
	<?php if ($this->_var['goods']['earnest_money'] > 0): ?>
	<img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/button-deal-buy-b.gif">
	<?php else: ?>
	<img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/button-deal-buy-b1.gif">
	<?php endif; ?>
	</a>
	</span>
	<?php endif; ?>
	<?php else: ?>
	<span><a href="javascript:void(0);"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/bg-deal-wait-text.gif"></a></span>
	<?php endif; ?>
<?php endif; ?>