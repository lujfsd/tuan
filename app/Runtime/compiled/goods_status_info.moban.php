<?php if ($this->_var['goods']['type_id'] != 2): ?>
	<?php if ($this->_var['goods']['promote_begin_time'] > $this->_var['TIME']): ?>
		<p class=deal-buy-tip-top><strong><?php echo $this->_var['lang']['XY_GROUP_NO_START']; ?></strong></p>
		<p class="deal-buy-tip-notice"><?php echo $this->_var['lang']['XY_GROUP_BUY_NOTICE_1']; ?></p>
		<p class="deal-buy-tip-notice">
		<?php 
$k = array (
  'name' => 'sprintf',
  'a1' => $this->_var['lang']['GROUP_BEGIN_FORMAT'],
  'a2' => $this->_var['goods']['promote_begin_time_format'],
);
echo $k['name']($k['a1'],$k['a2']);
?>
		</p>
	<?php elseif ($this->_var['goods']['is_group_fail'] == 1): ?>
		<div class="deal-buy-expired-fail"></div>
	<?php elseif ($this->_var['goods']['is_none']): ?>
		<div class="deal-buy-soldout"></div>
		<p class="deal-buy-tip-total"><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_GROUP_BUY_TOTAL'],
  'value' => $this->_var['goods']['buy_count'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
	<?php elseif ($this->_var['goods']['is_end']): ?>
		<div class="deal-buy-expired-succ"></div>
		<?php if ($this->_var['goods']['buy_count'] > 0): ?>
		<p class="deal-buy-tip-total"><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_GROUP_BUY_TOTAL'],
  'value' => $this->_var['goods']['buy_count'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
		<?php endif; ?>
	<?php elseif ($this->_var['goods']['group_user'] == 0): ?>
		<?php if ($this->_var['goods']['buy_count'] > 0): ?>
		<p class=deal-buy-tip-top><strong><?php echo $this->_var['goods']['buy_count']; ?></strong> <?php echo $this->_var['lang']['XY_GROUP_HAD_BUY']; ?></p>
		<?php endif; ?>
		<p class="deal-buy-tip-notice"><?php echo $this->_var['lang']['XY_GROUP_BUY_NOTICE_2']; ?></p>
		<p class=deal-buy-on><?php echo $this->_var['lang']['XY_GROUP_SUCCESS_BUY']; ?></p>
		<?php if ($this->_var['goods']['complete_time'] > 0): ?>
		<p class=deal-buy-tip-btm><?php echo $this->_var['goods']['complete_time_format']; ?><?php echo $this->_var['lang']['XY_GROUP_COMPLETE_BUY_1']; ?></p>
		<?php endif; ?>
	<?php elseif ($this->_var['goods']['complete_time']): ?>
		<p class=deal-buy-tip-top><strong><?php echo $this->_var['goods']['buy_count']; ?></strong> <?php echo $this->_var['lang']['XY_GROUP_HAD_BUY']; ?></p>
		<?php if ($this->_var['goods']['stock'] > 0): ?>
		<?php if ($this->_var['goods']['stockbfb'] < 20): ?>
		<p class="deal-buy-tip-notice"><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_SURPLUS_COUNT'],
  'value' => $this->_var['goods']['surplusCount'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
		<?php else: ?>
		<p class="deal-buy-tip-notice"><?php echo $this->_var['lang']['XY_GROUP_BUY_NOTICE_2']; ?></p>
		<?php endif; ?>
		<?php endif; ?>
		<p class=deal-buy-on><?php echo $this->_var['lang']['XY_GROUP_SUCCESS_BUY']; ?></p>
		<p class=deal-buy-tip-btm><?php echo $this->_var['goods']['complete_time_format']; ?><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_GROUP_COMPLETE_BUY'],
  'value' => $this->_var['goods']['group_user'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
	<?php else: ?>
		<p class=deal-buy-tip-top><strong><?php echo $this->_var['goods']['buy_count']; ?></strong> <?php echo $this->_var['lang']['XY_GROUP_HAD_BUY']; ?></p>
		<?php if ($this->_var['goods']['stock'] > 0): ?>
		<?php if ($this->_var['goods']['stockbfb'] < 20): ?>
		<p class="deal-buy-tip-notice"><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_SURPLUS_COUNT'],
  'value' => $this->_var['goods']['surplusCount'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
		<?php else: ?>
		<p class="deal-buy-tip-notice"><?php echo $this->_var['lang']['XY_GROUP_BUY_NOTICE_2']; ?></p>
		<?php endif; ?>
		<?php endif; ?>
		<div class="progress-pointer" style="padding-left:<?php echo  ($this->_var['goods']['buy_count'] / intval($this->_var['goods']['group_user'])) * 194 - 5;?>px;"><span></span></div>
		<div class="progress-bar">
			<div class="progress-left" style="width:<?php echo  ($this->_var['goods']['buy_count'] / intval($this->_var['goods']['group_user'])) * 194;?>px;"></div>
			<div class="progress-right "></div>
		</div>
		<div class="cf">
			<div class="min">0</div>
			<div class="max"><?php echo $this->_var['goods']['group_user']; ?></div>
		</div>
		<p class="deal-buy-tip-btm"><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_WILL_SUCCESS'],
  'value' => $this->_var['goods']['rest_count'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
	<?php endif; ?>
<?php else: ?>
	<?php if ($this->_var['goods']['promote_begin_time'] > $this->_var['TIME']): ?>
		<p class=deal-buy-tip-top><strong><?php echo $this->_var['lang']['XY_GROUP_NO_START']; ?></strong></p>
		<p class="deal-buy-tip-notice"><?php echo $this->_var['lang']['XY_GROUP_BUY_NOTICE_1']; ?></p>
		<p class="deal-buy-tip-notice">
			<?php 
$k = array (
  'name' => 'sprintf',
  'a1' => $this->_var['lang']['GROUP_BEGIN_FORMAT'],
  'a2' => $this->_var['goods']['promote_begin_time_format'],
);
echo $k['name']($k['a1'],$k['a2']);
?>
		</p>
	<?php elseif ($this->_var['goods']['is_group_fail'] == 1): ?>
		<div class="deal-buy-expired-fail"></div>
	<?php elseif ($this->_var['goods']['is_none']): ?>
		<div class="deal-buy-soldout"></div>
		<p class="deal-buy-tip-total">
			<?php echo sprintf(a_L("XY_GROUP_SIGN_TOTAL"),"<strong>".$this->_var['goods']['buy_count']."</strong>")?>
		</p>
	<?php elseif ($this->_var['goods']['is_end']): ?>
		<div class="deal-buy-expired-succ"></div>
		<?php if ($this->_var['goods']['buy_count'] > 0): ?>
		<p class="deal-buy-tip-total">
			<?php echo sprintf(a_L("XY_GROUP_SIGN_TOTAL"),"<strong>".$this->_var['goods']['buy_count']."</strong>")?>
		<?php endif; ?>
	<?php elseif ($this->_var['goods']['group_user'] == 0): ?>
		<?php if ($this->_var['goods']['buy_count'] > 0): ?>
		<p class=deal-buy-tip-top><strong><?php echo $this->_var['goods']['buy_count']; ?></strong> <?php echo $this->_var['lang']['XY_GROUP_HAD_SIGN']; ?></p>
		<?php endif; ?>
		<p class="deal-buy-tip-notice"><?php echo $this->_var['lang']['XY_GROUP_BUY_NOTICE_2']; ?></p>
		<p class=deal-buy-on><?php echo $this->_var['lang']['XY_GROUP_SUCCESS_SIGN']; ?></p>
		<?php if ($this->_var['goods']['complete_time'] > 0): ?>
		<p class=deal-buy-tip-btm><?php echo $this->_var['goods']['complete_time_format']; ?><?php echo $this->_var['lang']['XY_GROUP_COMPLETE_BUY_1']; ?></p>
		<?php endif; ?>
	<?php elseif ($this->_var['goods']['complete_time']): ?>
		<p class=deal-buy-tip-top><strong><?php echo $this->_var['goods']['buy_count']; ?></strong> <?php echo $this->_var['lang']['XY_GROUP_HAD_SIGN']; ?></p>
		<?php if ($this->_var['goods']['stock'] > 0): ?>
		<?php if ($this->_var['goods']['stockbfb'] < 20): ?>
		<p class="deal-buy-tip-notice"><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_SURPLUS_COUNT'],
  'value' => $this->_var['goods']['surplusCount'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
		<?php else: ?>
		<p class="deal-buy-tip-notice"><?php echo $this->_var['lang']['XY_GROUP_BUY_NOTICE_2']; ?></p>
		<?php endif; ?>
		<?php endif; ?>
		<p class=deal-buy-on><?php echo $this->_var['lang']['XY_GROUP_SUCCESS_SIGN']; ?></p>
		<p class=deal-buy-tip-btm><?php echo $this->_var['goods']['complete_time_format']; ?><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_GROUP_COMPLETE_BUY'],
  'value' => $this->_var['goods']['group_user'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
	<?php else: ?>
		<p class=deal-buy-tip-top><strong><?php echo $this->_var['goods']['buy_count']; ?></strong> <?php echo $this->_var['lang']['XY_GROUP_HAD_BUY']; ?></p>
		<?php if ($this->_var['goods']['stock'] > 0): ?>
		<?php if ($this->_var['goods']['stockbfb'] < 20): ?>
		<p class="deal-buy-tip-notice"><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_SURPLUS_COUNT'],
  'value' => $this->_var['goods']['surplusCount'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
		<?php else: ?>
		<p class="deal-buy-tip-notice"><?php echo $this->_var['lang']['XY_GROUP_BUY_NOTICE_2']; ?></p>
		<?php endif; ?>
		<?php endif; ?>
		<div class="progress-pointer" style="padding-left:<?php echo  ($this->_var['goods']['buy_count'] / intval($this->_var['goods']['group_user'])) * 194 - 5;?>px;"><span></span></div>
		<div class="progress-bar">
			<div class="progress-left" style="width:<?php echo  ($this->_var['goods']['buy_count'] / intval($this->_var['goods']['group_user'])) * 194;?>px;"></div>
			<div class="progress-right "></div>
		</div>
		<div class="cf">
			<div class="min">0</div>
			<div class="max"><?php echo $this->_var['goods']['group_user']; ?></div>
		</div>
		<p class="deal-buy-tip-btm"><?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['lang']['XY_WILL_SUCCESS'],
  'value' => $this->_var['goods']['rest_count'],
);
echo $k['name']($k['format'],$k['value']);
?></p>
	<?php endif; ?>
<?php endif; ?>