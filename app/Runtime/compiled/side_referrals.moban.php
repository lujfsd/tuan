<div class="sbox side-inner side-invite">
	<div class=sbox-top></div>
	<div class=sbox-content>
		<div class="tip ctip" style="padding-right:8px;">
			<H2><?php 
$k = array (
  'name' => 'a_L',
  'value' => 'XY_INVITE_REWARDS',
);
echo $k['name']($k['value']);
?></H2>
			<div class="text">
				<?PHP echo sprintf(a_L("XY_INVITE_ONE_REWARDS"),'<strong>'.$GLOBALS['tpl']->_var['referralsMoney'].'</strong>');?>
			</div>
			<div class="link">
				<a href="<?php echo a_u("Referrals/index","id-".intval($GLOBALS['tpl']->_var['goods']['id'])."|tid-".intval($this->_var['tid']));?>">&raquo; <?php 
$k = array (
  'name' => 'a_L',
  'value' => 'XY_INVITE_GAIN_LINKS',
);
echo $k['name']($k['value']);
?></a>
			</div>
		</div>
	</div>
	<div class=sbox-bottom></div>
</div>
<div class="blank"></div>