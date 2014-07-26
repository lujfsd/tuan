<?php 
$k = array (
  'name' => 'advLayout',
  'id' => '右侧广告',
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>
<?php echo $this->fetch('Inc/side/side_subcity.moban'); ?>
<?php echo $this->fetch('Inc/side/side_notice.moban'); ?>
<?php echo $this->fetch('Inc/side/side_today_list.moban'); ?>
<?php echo $this->fetch('Inc/side/side_message.moban'); ?>
<?php echo $this->fetch('Inc/side/side_czhi.moban'); ?>
<?php 
$k = array (
  'name' => 'advLayout',
  'id' => '右侧中部广告',
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>
<?php echo $this->fetch('Inc/side/side_referrals.moban'); ?>
<?php echo $this->fetch('Inc/side/side_sellermsg.moban'); ?>
<?php echo $this->fetch('Inc/side/side_vote.moban'); ?>
<?php 
$k = array (
  'name' => 'advLayout',
  'id' => '右侧底部广告',
);
echo $this->_hash . $k['name'] . '|' . serialize($k) . $this->_hash;
?>