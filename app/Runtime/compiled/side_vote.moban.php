<?php if ($this->_var['vote']): ?>
<div class="sbox side-vote-tip">
	<div class="sbox-top"></div>
	<div class="sbox-content">
		<div class="tip">
			<h2><?php echo $this->_var['lang']['XY_VOTE_TIT']; ?></h2>
			<div class="text">
				<p class="mark"><a href="<?php 
$k = array (
  'name' => 'a_u',
  'a' => 'Vote/index',
);
echo $k['name']($k['a']);
?>" target="_blank"><?php echo $this->_var['vote']['title']; ?></a></p>
				<p><?php echo $this->_var['vote']['desc']; ?></p>
			</div>
			<div class="link"><a href="<?php 
$k = array (
  'name' => 'a_u',
  'a' => 'Vote/index',
);
echo $k['name']($k['a']);
?>" target="_blank"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/button-deal-vote.gif" /></a></div>
		</div>
	</div>
	<div class="sbox-bottom"></div>
</div>
<div class="blank"></div>
<?php endif; ?>