<div class="deal-consult sbox">
	<div class=sbox-bubble></div>
	<div class=sbox-top></div>
	<div class=sbox-content>
		<div class=deal-consult-tip>
			<H2><?php echo $this->_var['lang']['MESSAGE_BOARD']; ?></H2>
			<?php 
$k = array (
  'name' => 'getMessageList',
  'limit' => '3',
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>
			<div class=custom-service>
				<p class=im >
				<?php if ($this->_var['CFG']['MSN_SERVICES']): ?>
				<a id=service-msn-help href="msnim:chat?contact=<?php echo $this->_var['CFG']['MSN_SERVICES']; ?>" target=_blank>
					<img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/button-custom-msn.gif">
				</a>
				<?php endif; ?>
				<?php if ($this->_var['currentCity']['qq_1'] == '' && $this->_var['currentCity']['qq_2'] == '' && $this->_var['currentCity']['qq_3'] == '' && $this->_var['currentCity']['qq_4'] == '' && $this->_var['currentCity']['qq_5'] == '' && $this->_var['currentCity']['qq_6'] == ''): ?>
				<?php if ($this->_var['CFG']['QQ_SERVICES']): ?>
				<a href="<?php echo $this->_var['CFG']['QQ_SERVICES']; ?>" target=_blank><img alt="" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/button-custom-qq.gif"></a>
				<?php endif; ?>
				<?php else: ?>
					<?php if ($this->_var['currentCity']['qq_1'] != ''): ?>
					<a href="<?php echo $this->_var['currentCity']['qq_1']; ?>" target=_blank><img alt="" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/button-custom-qq.gif"></a>
					<?php endif; ?>
					<?php if ($this->_var['currentCity']['qq_2'] != ''): ?>
					<a href="<?php echo $this->_var['currentCity']['qq_2']; ?>" target=_blank><img alt="" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/button-custom-qq.gif"></a>
					<?php endif; ?>
					<?php if ($this->_var['currentCity']['qq_3'] != ''): ?>
					<a href="<?php echo $this->_var['currentCity']['qq_3']; ?>" target=_blank><img alt="" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/button-custom-qq.gif"></a>
					<?php endif; ?>
					<?php if ($this->_var['currentCity']['qq_4'] != ''): ?>
					<a href="<?php echo $this->_var['currentCity']['qq_4']; ?>" target=_blank><img alt="" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/button-custom-qq.gif"></a>
					<?php endif; ?>
					<?php if ($this->_var['currentCity']['qq_5'] != ''): ?>
					<a href="<?php echo $this->_var['currentCity']['qq_5']; ?>" target=_blank><img alt="" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/button-custom-qq.gif"></a>
					<?php endif; ?>
					<?php if ($this->_var['currentCity']['qq_6'] != ''): ?>
					<a href="<?php echo $this->_var['currentCity']['qq_6']; ?>" target=_blank><img alt="" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/button-custom-qq.gif"></a>
					<?php endif; ?>
				<?php endif; ?>
				</p>
				<p class="tel">
					<?php echo $this->_var['CFG']['TEL']; ?>
					<span class=time><?php echo $this->_var['CFG']['WORK_TIMES']; ?></span>
				</p>
			</div>
		</div>
	</div>
	<div class=sbox-bottom></div>
</div>
<div class="blank"></div>