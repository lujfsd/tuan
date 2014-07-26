<?php if ($this->_var['today_list']): ?>
<div class="sbox side-goods-tip">
	<div class="sbox-top"></div>
	<div class="sbox-content">
			<h2><?php echo $this->_var['lang']['XY_STIL_CAN_GROUP']; ?></h2>
		<ul>
			<?php $_from = $this->_var['today_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'today_item');if (count($_from)):
    foreach ($_from AS $this->_var['today_item']):
?>
				<?php if ($this->_var['today_item']['id'] != $this->_var['goods']['id']): ?>
				<li>
					<a href="<?php echo $this->_var['today_item']['url']; ?>" class="title">
					<?php echo $this->_var['today_item']['name_1']; ?>
					</a>
					<a href="<?php echo $this->_var['today_item']['url']; ?>" class="img">
					<img alt="<?php echo $this->_var['today_item']['name_1']; ?>' width="198" src="<?php echo $this->_var['CND_URL']; ?><?php echo $this->_var['today_item']['small_img']; ?>" />
					</a>
					<div class="clear price">
						<span class="mk">原价：<b><?php echo $this->_var['today_item']['market_price_format']; ?></b></span>
						<span class="db">折扣：<b><?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['JJ_DISCOUNT_POINT'],
  'b' => $this->_var['today_item']['discountfb'],
);
echo $k['name']($k['a'],$k['b']);
?></b></span>
						<span class="sp">现价：<b><?php echo $this->_var['today_item']['shop_price_format']; ?></b></span>
						<span class="sav">节省：<b><?php echo $this->_var['today_item']['save']; ?></b></span>
						<div class="clear"></div>
						<a href="<?php echo $this->_var['today_item']['url']; ?>"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/buy.jpg" /></a>
					</div>
				</li>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	<div class="sbox-bottom"></div>
</div>
<div class="blank"></div>
<?php endif; ?>