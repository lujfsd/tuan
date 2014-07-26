<?php if ($this->_var['other_goods_list']): ?>
	<div class="box-top"></div>
	<div class="box-content">
		<div class="head">
			<h2><?php echo $this->_var['lang']['XY_TODAY_OTHER']; ?></h2>
		</div>
			<div class="sect">
				<div class="cart-other-goods">
				<dl>
				<?php $_from = $this->_var['other_goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'oglist');if (count($_from)):
    foreach ($_from AS $this->_var['oglist']):
?>
				<dd>
					<h3><a href="<?php echo $this->_var['oglist']['url']; ?>" title="<?php echo $this->_var['oglist']['name_1']; ?>"><?php 
$k = array (
  'name' => 'a_msubstr',
  'value' => $this->_var['oglist']['name_1'],
  'a1' => '0',
  'a2' => '40',
);
echo $k['name']($k['value'],$k['a1'],$k['a2']);
?></a></h3>
					<div class="img"><img src="<?php echo $this->_var['CND_URL']; ?><?php echo $this->_var['oglist']['small_img']; ?>" /></div>
					<div class="infos">
						<p class="total"><strong class="count"><?php echo $this->_var['oglist']['buy_count']; ?></strong><?php echo $this->_var['lang']['JJ_PEOPLE_BUY']; ?></p>
						<p class="price"><?php echo $this->_var['lang']['JJ_ORIGINAL_PRICE']; ?>：<strong class="old"><?php 
$k = array (
  'name' => 'a_formatPrice',
  'a' => $this->_var['oglist']['market_price'],
);
echo $k['name']($k['a']);
?></strong><br><?php echo $this->_var['lang']['JJ_SHOP_PRICE']; ?>：<strong><?php 
$k = array (
  'name' => 'a_formatPrice',
  'a' => $this->_var['oglist']['shop_price'],
);
echo $k['name']($k['a']);
?></strong>
						<a href="<?php echo a_u("Cart/index","id-".$this->_var['oglist']['id'])?>" class="formbutton"><?php echo $this->_var['lang']['BUY']; ?></a>
					</div>
				</dd>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</dl>
				</div>
				<div class="clear"></div>
			</div>
	</div>
<div class="box-bottom"></div>
<?php endif; ?>