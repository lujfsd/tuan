						<div class="goods-info">
							<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
								<a href="<?php echo $this->_var['goods']['url']; ?>"><?php echo $this->_var['goods']['goods_name']; ?></a>&nbsp;
								<?php echo sprintf($GLOBALS['Ln']['JJ_ALREADY_APPLY'],$this->_var['goods']['buy_count']); ?>
								<?php if ($this->_var['goods']['complete_time'] > 0 && $this->_var['goods'] [ 'is_group_fail' ] != 1): ?>，<?php echo $this->_var['lang']['JJ_GB_SUCCESS']; ?><?php endif; ?>。<br>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</div>
						
						<?php if ($this->_var['group_bond_count'] > 0): ?>
							<div class="group-bond">
								<div class="tltle">
									<h3>
										<?php echo sprintf($GLOBALS['Ln']['JJ_GET_GROUPBOND'], $this->_var['GROUPBOTH']); ?>
									</h3>
									<p>
										<?php echo sprintf($GLOBALS['Ln']['JJ_USE_GROUPBOND'], $this->_var['GROUPBOTH']); ?>
									</p>
								</div>
								
								<div class="group-bond-box">
									<?php if (a_fanweC ( 'IS_SMS' ) == 1 && $this->_var['group_bond_count_no_send'] > 0 && $this->_var['allow_sms'] == 1): ?>
										<p>
											<?php echo sprintf($GLOBALS['Ln']['JJ_RETRIEVAL_METHOD'], $this->_var['GROUPBOTH']); ?>
										</p>
										<div class="item">
											<strong><?php echo $this->_var['lang']['JJ_CALL_WAVE']; ?></strong>
											<div>
												<form method="post">
													<?php echo sprintf($GLOBALS['Ln']['JJ_PASSWORD_MOBILE'],$this->_var['GROUPBOTH']); ?>
													<input id="mobile_phone" name="mobile_phone" class="f-input" value="<?php if ($this->_var['mobile_phone']): ?><?php echo $this->_var['mobile_phone']; ?><?php else: ?><?php echo $this->_var['lang']['JJ_MOBILE_EMPTY']; ?><?php endif; ?>">
													
													<input class="formbutton" id="sms-submit" value="<?php echo $this->_var['lang']['CONFIRM']; ?>" type="submit">
													<input type="hidden" name="m" value="UcGroupBond" />
													<input type="hidden" name="a" value="order" />
													<input type="hidden" name="sn" value="<?php echo $this->_var['order_sn']; ?>" />
												</form>
											</div>
										</div>
										<div class="item">
											<strong><?php echo $this->_var['lang']['JJ_TWO_PRINT']; ?></strong>
											<div>
												<?php echo sprintf($GLOBALS['Ln']['JJ_GROUPBOND_PRINT'],$this->_var['GROUPBOTH'],$this->_var['ugb_urlweb'], $this->_var['GROUPBOTH']); ?>
											</div>
										</div>
									<?php else: ?>
										<p>
											<?php echo sprintf($GLOBALS['Ln']['JJ_RECEIVE'], $this->_var['GROUPBOTH']); ?>
										</p>
										<div class="item">
											<strong><?php echo $this->_var['lang']['JJ_ONE_PRINT']; ?></strong>
											<div>
												<?php echo sprintf($GLOBALS['Ln']['JJ_GROUPBOND_PRINT'],$this->_var['GROUPBOTH'], $this->_var['ugb_urlweb'], $this->_var['GROUPBOTH']); ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="referrals-box">
							<strong><?php echo $this->_var['lang']['JJ_INVITE']; ?></strong>
							<p>
								<?php echo sprintf($GLOBALS['Ln']['JJ_YOUR_URL'],$this->_var['referralsMoney']); ?>
							</p>
							<div>						
								<input id="share-copy-text" class="f-input" value="<?php echo $this->_var['urlweb']; ?>" size="60">
								<input id="share-copy-button" class="formbutton" value="<?php echo $this->_var['lang']['JJ_COPY']; ?>" type="button">
							</div>
						</div>