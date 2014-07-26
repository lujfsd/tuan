<?php if ($this->_var['currentCity']['notice']): ?>
<div class="sbox side-text-tip">
	<div class="sbox-top"></div>
	<div class="sbox-content">
		<div class="tip">
			<h2><?php 
$k = array (
  'name' => 'a_L',
  'value' => 'XY_GROUP_NOTE',
);
echo $k['name']($k['value']);
?></h2>
			<?php echo $this->_var['currentCity']['notice']; ?>
		</div>
	</div>
	<div class="sbox-bottom"></div>
</div>
<div class="blank"></div>
<?php endif; ?>