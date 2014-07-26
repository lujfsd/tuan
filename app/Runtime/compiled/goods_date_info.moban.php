
							<div id="deal-timeleft-box">						
									<?php if ($this->_var['goods']['is_end']): ?>
										<div class="deal-box deal-timeleft deal-off" id="deal-timeleft">
											<h3><?php echo $this->_var['lang']['XY_THIS_GROUP_END_TIME']; ?></h3>
											<div class="limitdate">
												<p class="deal-buy-ended">
													<?php 
$k = array (
  'name' => 'a_toDate',
  'a1' => $this->_var['goods']['promote_end_time'],
  'a2' => $this->_var['lang']['XY_TIMES_MOD_1'],
);
echo $k['name']($k['a1'],$k['a2']);
?><br/>
													<?php 
$k = array (
  'name' => 'a_toDate',
  'a1' => $this->_var['goods']['promote_end_time'],
  'a2' => $this->_var['lang']['XY_TIMES_MOD_2'],
);
echo $k['name']($k['a1'],$k['a2']);
?>
												</p>
											</div>
										</div>
									<?php elseif ($this->_var['goods']['is_group_fail'] != 1): ?>
									<?php if ($this->_var['goods']['promote_begin_time'] > $this->_var['system_time'] && $this->_var['goods']['type_id'] != 2): ?>
										<div id="deal-timeleft" class="deal-box deal-timeleft deal-on">
											<h3><?php echo $this->_var['lang']['XY_TIMES_LEFT']; ?></h3>
											<div class="limitdate">
												<ul id="counter"></ul>
											</div>
										</div>	
									<?php else: ?>
										<div id="deal-timeleft" class="deal-box deal-timeleft deal-on">
											<h3><?php echo $this->_var['lang']['XY_TIMES_LEFT']; ?></h3>
											<div class="limitdate">
												<ul id="counter"></ul>
											</div>
										</div>										
									<?php endif; ?>
								<?php endif; ?>
							</div>		
