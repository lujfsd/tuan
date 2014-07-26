<div id="bd" class="cf">
		<form method="post">
		<div id="content">
			<div id="deal-buy" class="box">
				<div class="box-top"></div>
				<div class="box-content">
					<div class="step">
							<img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/step2.jpg" />
						</div>
					<div class="sect">
						<table class="order-table">
							<tr>
								<th class="deal-buy-desc"><?php echo $this->_var['lang']['JJ_PROJECT']; ?></th>
								<th style="width:80px"><?php echo $this->_var['lang']['JJ_PROPERTY']; ?></th>
								<th class="deal-buy-quantity"><?php echo $this->_var['lang']['JJ_QUANTITY']; ?></th>
								<th class="deal-buy-multi"></th>
								<th class="deal-buy-price"><?php echo $this->_var['lang']['JJ_PRICE']; ?></th>
								<th class="deal-buy-equal"></th>
								<th class="deal-buy-total"><?php echo $this->_var['lang']['JJ_TOTAL']; ?></th>
							</tr>
							<?php $_from = $this->_var['cart_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cart_item');if (count($_from)):
    foreach ($_from AS $this->_var['cart_item']):
?>
							<tr>
								<td class="deal-buy-desc"><?php echo $this->_var['cart_item']['data_name']; ?></td>
								<td><?php echo $this->_var['cart_item']['attr']; ?></td>
								<td class="deal-buy-quantity"><?php echo $this->_var['cart_item']['number']; ?></td>
								<td class="deal-buy-multi">x</td>
								<td class="deal-buy-price" id="deal-buy-price">

								<?php if ($this->_var['cart_item']['goods_info']['score_goods'] == 1): ?>
										<?php echo $this->_var['cart_item']['data_score']; ?><?php echo $this->_var['lang']['SCORE_UNIT']; ?>
									<?php else: ?>
										<?php 
$k = array (
  'name' => 'a_formatPrice',
  'value' => $this->_var['cart_item']['data_unit_price'],
);
echo $k['name']($k['value']);
?>
									<?php endif; ?>
								</td>
								<td class="deal-buy-equal">=</td>
								<td id="deal-buy-total">
									<?php if ($this->_var['cart_item']['goods_info']['score_goods'] == 1): ?>
										<?php echo $this->_var['cart_item']['data_total_score']; ?><?php echo $this->_var['lang']['SCORE_UNIT']; ?>
										<?php else: ?>
										<?php 
$k = array (
  'name' => 'a_formatPrice',
  'value' => $this->_var['cart_item']['data_total_price'],
);
echo $k['name']($k['value']);
?>
										<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							<tr class="order-total">
								<td class="deal-buy-desc"><strong><?php echo $this->_var['lang']['JJ_TOTAL_AMOUNT']; ?></strong></td>
								<td></td>
								<td class="deal-buy-quantity"></td>
								<td class="deal-buy-multi"></td>
								<td class="deal-buy-price"></td>
								<td class="deal-buy-equal">=</td>
								<td class="deal-buy-total">
								<?php 
$k = array (
  'name' => 'a_formatPrice',
  'value' => $this->_var['cart_total_price'],
);
echo $k['name']($k['value']);
?>
								</td>
							</tr>
						</table>

						<?php if ($this->_var['goods_type'] == 1): ?>
						<!--拼单-->
						<?php if ($this->_var['order_deliverys']): ?>
						<div class="order-check-form  has-credit">
							<div class="order-pay-credit">
								<h3><?php echo $this->_var['lang']['CHOICE_DELIVERY_ORDER']; ?>：</h3>
								<div>
									<table class="table-list">
										<tr>
										<td class='t1'><?php echo $this->_var['lang']['CHOICE']; ?></td>
										<td class='t2'><?php echo $this->_var['lang']['ORDER_SN']; ?></td>
										<td class='t3'><?php echo $this->_var['lang']['CONSIGNEE_INFO']; ?></td>
										<td class='t4'><?php echo $this->_var['lang']['ORDER_DELIVERY']; ?></td>
										</tr>

										<?php $_from = $this->_var['order_deliverys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'order_delivery');if (count($_from)):
    foreach ($_from AS $this->_var['order_delivery']):
?>
										<tr>
										<td class='t1'><input type="radio" name="delivery_refer_order_id" value="<?php echo $this->_var['order_delivery']['id']; ?>" onclick="reset_delivery();" /></td>
										<td class='t2'><?php echo $this->_var['order_delivery']['sn']; ?></td>
										<td class='t3'>[<?php echo $this->_var['order_delivery']['region_lv1_name']; ?><?php echo $this->_var['order_delivery']['region_lv2_name']; ?><?php echo $this->_var['order_delivery']['region_lv3_name']; ?><?php echo $this->_var['order_delivery']['region_lv4_name']; ?>]<?php echo $this->_var['order_delivery']['address']; ?>收货人<?php echo $this->_var['order_delivery']['consignee']; ?></td>
										<td class='t4'><?php echo $this->_var['order_delivery']['delivery_name']; ?></td>
										</tr>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									</table>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<!--拼单-->
						<script type="text/javascript" src="<?php echo $this->_var['TEMP_PATH']; ?>Public/regionConf.js"></script>
						<!--配送地址--><div id="consignee_region_id" class="order-check-form has-credit">
							<div class="consignee-box order-pay-credit">
									<h3> <?php if ($this->_var['alipay_info'] == 1): ?>

                        <a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_alipay_address&oauth_alipay=1" style="float:right;"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/alipay_add_query.png" /></a>

                        <?php endif; ?><?php echo $this->_var['lang']['CONSIGNEE_INFO']; ?>： 

                        </h3>
                        <div class="field consignee">
									<label >  <?php if ($this->_var['alipay_info'] == 1): ?>

                        <a href="<?php echo $this->_var['__ROOT__']; ?>/index.php?m=user&a=login_alipay_address&oauth_alipay=1" style="float:right;"><img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>/Public/images/alipay_add_query.png" /></a>

                        <?php endif; ?></label>
									
						</div>
								<div class="field consignee">
									<label for="settings-consignee"><?php echo $this->_var['lang']['CONSIGNEE']; ?>：</label>
									<input type="text" size="30" name="consignee" id="delivery-consignee" class="f-input" value="<?php echo $this->_var['consignee_info']['consignee']; ?>" /><span class="red">*</span>
								</div>
								<div class="field region">
									<label><?php echo $this->_var['lang']['REGION_INFO']; ?>：</label>
									<?php echo $this->_var['lang']['REGION_LV1_NAME']; ?>：<select name="region_lv1" id="region_lv1_0" onchange="selectRegionDelivery(this,0,1);">
										<option value="0" ><?php echo $this->_var['lang']['NO_SELECT']; ?></option>

										<?php $_from = $this->_var['region_lv1_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region_lv1_item');if (count($_from)):
    foreach ($_from AS $this->_var['region_lv1_item']):
?>
										<option value="<?php echo $this->_var['region_lv1_item']['id']; ?>" <?php if ($this->_var['region_lv1_item']['id'] == $this->_var['consignee_info']['region_lv1']): ?>selected="selected"<?php endif; ?> ><?php echo $this->_var['region_lv1_item']['name']; ?></option>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									</select>

									<?php echo $this->_var['lang']['REGION_LV2_NAME']; ?>：<select name="region_lv2" id="region_lv2_0" onchange="selectRegionDelivery(this,0,2);">
										<option value="0"><?php echo $this->_var['lang']['NO_SELECT']; ?></option>
										<?php $_from = $this->_var['region_lv2_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region_lv2_item');if (count($_from)):
    foreach ($_from AS $this->_var['region_lv2_item']):
?>
										<option value="<?php echo $this->_var['region_lv2_item']['id']; ?>" <?php if ($this->_var['region_lv2_item']['id'] == $this->_var['consignee_info']['region_lv2']): ?>selected="selected"<?php endif; ?> ><?php echo $this->_var['region_lv2_item']['name']; ?></option>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									</select>

									<?php echo $this->_var['lang']['REGION_LV3_NAME']; ?>：<select name="region_lv3" id="region_lv3_0" onchange="selectRegionDelivery(this,0,3);">
										<option value="0" ><?php echo $this->_var['lang']['NO_SELECT']; ?></option>
										<?php $_from = $this->_var['region_lv3_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region_lv3_item');if (count($_from)):
    foreach ($_from AS $this->_var['region_lv3_item']):
?>
										<option value="<?php echo $this->_var['region_lv3_item']['id']; ?>" <?php if ($this->_var['region_lv3_item']['id'] == $this->_var['consignee_info']['region_lv3']): ?>selected="selected"<?php endif; ?> ><?php echo $this->_var['region_lv3_item']['name']; ?></option>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									</select>

									<?php echo $this->_var['lang']['REGION_LV4_NAME']; ?>：<select name="region_lv4" id="region_lv4_0" onchange="selectRegionDelivery(this,0,4);">
										<option value="0" ><?php echo $this->_var['lang']['NO_SELECT']; ?></option>
										<?php $_from = $this->_var['region_lv4_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region_lv4_item');if (count($_from)):
    foreach ($_from AS $this->_var['region_lv4_item']):
?>
										<option value="<?php echo $this->_var['region_lv4_item']['id']; ?>" <?php if ($this->_var['region_lv4_item']['id'] == $this->_var['consignee_info']['region_lv4']): ?>selected="selected"<?php endif; ?> ><?php echo $this->_var['region_lv4_item']['name']; ?></option>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									</select>
									<span class="red">*</span>
								</div>
								<div class="field address">
									<label for="delivery-address"><?php echo $this->_var['lang']['ADDRESS']; ?>：</label>
									<input type="text" size="30" name="address" id="delivery-address" class="f-input" value="<?php echo $this->_var['consignee_info']['address']; ?>" /><span class="red">*</span>
								</div>
								<div class="field zip">
									<label for="delivery-zip"><?php echo $this->_var['lang']['ZIP']; ?>：</label>
									<input type="text" size="30" name="zip" id="delivery-zip" class="f-input" value="<?php echo $this->_var['consignee_info']['zip']; ?>"  /><span class="red">*</span>
								</div>
								<div class="field fix-phone">
									<label for="delivery-fax-phone"><?php echo $this->_var['lang']['FIX_PHONE']; ?>：</label>
									<input type="text" size="30" name="fix_phone" id="delivery-fix-phone" class="f-input" value="<?php echo $this->_var['consignee_info']['fix_phone']; ?>" />
								</div>
								<div class="field mobile-phone">
									<label for="delivery-mobile-phone"><?php echo $this->_var['lang']['MOBILE_PHONE']; ?>：</label>
									<input type="text" size="30" name="mobile_phone" id="delivery-mobile-phone" class="f-input" value="<?php echo $this->_var['consignee_info']['mobile_phone']; ?>"  /><span class="red">*</span>
								</div>
								<div class="clear"></div>
                            </div>
						</div>
						<!--配送地址-->
						<div class="order-check-form  has-credit">
							<div class="delivery order-pay-credit">
								<h3><?php echo $this->_var['lang']['DELIVERY_LIST']; ?>：</h3>
								<div id="cart_delivery">
									<!--配送方式-->
									<table class="table-list">

										<?php $_from = $this->_var['delivery_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'delivery_item');if (count($_from)):
    foreach ($_from AS $this->_var['delivery_item']):
?>
										<tr>
											<td class='t1'><input id="check-<?php echo $this->_var['payment_item']['id']; ?>" type="radio" name="delivery" value="<?php echo $this->_var['delivery_item']['id']; ?>" onclick="deliveryChange(this);" <?php if ($this->_var['delivery_item']['id'] == $this->_var['order']['delivery']): ?>checked="true"<?php endif; ?> /></td>
											<td class='t2'><?php echo $this->_var['delivery_item']['name_1']; ?></td>
											<td class='t3'>

												<?php if ($this->_var['delivery_item']['protect'] == 1): ?>
												<?php echo $this->_var['lang']['PROTECT_RADIO']; ?>:<?php echo $this->_var['delivery_item']['protect_radio']; ?>&nbsp;&nbsp;
												<?php echo $this->_var['lang']['PROTECT_PRICE']; ?>:<?php echo $this->_var['delivery_item']['protect_price']; ?><br />
												<?php endif; ?>

												<?php echo $this->_var['delivery_item']['desc_1']; ?>
											</td>
											<td class='t4'>
												<?php if ($this->_var['delivery_item']['protect'] == 1): ?>
												<nobr><input type="checkbox" name="protect" value="1" class="protect" onclick="countCartTotal();" <?php if ($this->_var['delivery_item']['id'] == $this->_var['order']['delivery'] && $this->_var['order']['protect'] == 1): ?>checked="true"<?php else: ?>disabled="disabled"<?php endif; ?>/>&nbsp;<?php echo $this->_var['lang']['PROTECT']; ?></nobr>
												<?php endif; ?>
											</td>
										</tr>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									</table>
									<!--配送方式-->
								</div>
							</div>
						</div>
						<?php endif; ?>
						<div class="order-check-form  has-credit">
							<div class="delivery order-pay-credit">
								<h3><?php echo $this->_var['lang']['ORDER_MEMO']; ?>：</h3>
								<table class="table-list">
									<tr>
										<td class='t1'>&nbsp;</td>
										<td class='t8'><nobr><?php echo $this->_var['lang']['ORDER_MEMO_CONTENT']; ?>：</nobr></td>
										<td class='t9'><textarea name="memo" id="memo"></textarea></td>
									</tr>
								</table>
							</div>
						</div>
						<!--支付方式-->
						<?php if ($this->_var['SHOW_PAYMENT_LIST']): ?>
							  <?php if ($this->_var['TAX_RADIO'] > 0 || a_fanweC ( 'SHOW_TAX' ) == 1): ?>
								<div class="order-check-form  has-credit">
									<div class="tax order-pay-credit">
										<h3><label><?php echo $this->_var['lang']['IS_TAX']; ?>：<input type="checkbox" value="1" name='tax' id="tax" class="tax" onclick="checkTax(this);" /></label></h3>
										<table class="table-list hidd" id="tax-table">
											<tr>
												<td class='t5'><nobr><?php echo $this->_var['lang']['TAX_TITLE']; ?>：</nobr></td>
												<td class='t6' >
													<input type="text" size="50"  name="tax_title" id="tax_title" /></nobr><?php echo $this->_var['lang']['TAX_RADIO']; ?>：<?php echo $this->_var['TAX_RADIO']; ?>
												</td>
											</tr>											
											<tr>
												<td class='t5'><nobr><?php echo $this->_var['lang']['TAX_CONTENT']; ?>：</nobr></td>
												<td class='t6'>
													<textarea name="tax_content" id="tax_content" disabled="true"></textarea>
												</td>
											</tr>
										</table>
									</div>
								</div>
							<?php endif; ?>
							<div class="paytype">
								<div class="order-check-form has-credit">
									<div class="order-pay-credit">
										<h3><?php echo $this->_var['lang']['JJ_PAYMENT']; ?></h3>
										<?php if ($this->_var['isAccountpay'] == 1 && $this->_var['user_info']['money'] > 0): ?>
										<p><?php echo $this->_var['lang']['JJ_ACCOUNT_BALANCE']; ?>：<strong><?php echo $this->_var['user_info_money']; ?></strong>
											<?php if ($this->_var['PAY_SHOW_TYPE'] == 1): ?>
											<span id="accountpay-desc"></span>
											<input id="credit-text" class="f-input" type="hidden" name="credit" value="0"/>
											<label id="credit-all" style="display:none;"><input type="checkbox" name="credit-all" value="0" id="is-credit-all" checked="checked" /></label>
											<?php else: ?>
											，<?php echo $this->_var['lang']['JJ_USE_BALANCE']; ?> <input id="credit-text" class="f-input" type="text" name="credit" value="0" style="width:50px;"/>，<label id="credit-all"><input type="checkbox" name="credit-all" value="0" id="is-credit-all" checked="checked"/><?php echo $this->_var['lang']['JJ_ALL_PAY']; ?></label>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<div onclick="countCartTotal();" id="payment-list" style="display:none;">
										<table class="table-list" style="margin-bottom:0">
											<?php $_from = $this->_var['payment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment_item');if (count($_from)):
    foreach ($_from AS $this->_var['payment_item']):
?>
												<?php if ($this->_var['payment_item']['class_name'] != 'Accountpay'): ?>
													<tr <?php if ($this->_var['payment_item']['class_name'] == 'Cod'): ?>id="payment_Cod" style="display:none;"<?php endif; ?> >
														<td class='t1' style="vertical-align:middle"><input id="check-<?php echo $this->_var['payment_item']['id']; ?>" type="radio" name="payment" value="<?php echo $this->_var['payment_item']['id']; ?>" /></td>
														<td class='t2' style="vertical-align:middle; width:150px;">
															<?php if ($this->_var['payment_item']['logo'] != ''): ?>
															<img for="check-<?php echo $this->_var['payment_item']['id']; ?>" src="<?php echo $this->_var['CND_URL']; ?><?php echo $this->_var['payment_item']['logo']; ?>" width="150" alt="<?php echo $this->_var['payment_item']['name_1']; ?>" title="<?php echo $this->_var['payment_item']['name_1']; ?>"/>
															<?php else: ?>
															<?php echo $this->_var['payment_item']['name_1']; ?>
															<?php endif; ?>
														</td>
														<td style="vertical-align:middle; padding-left:20px;">

														<?php if ($this->_var['payment_item']['fee'] > 0): ?>
														<?php echo $this->_var['lang']['PAYMENT_FEE']; ?>:<?php echo $this->_var['payment_item']['fee_format']; ?><br />
														<?php endif; ?>
														<?php echo $this->_var['payment_item']['description_1']; ?>
														</td>
													</tr>
												<?php endif; ?>
												<?php if ($this->_var['payment_item']['selection']): ?>
													<tr><td colspan = 3>
														<?php echo $this->_var['payment_item']['selection']; ?>
													</td></tr>
												<?php endif; ?>
											<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
										</table>
										<table class="table-list" style="margin-top:0">
											<tr><td>
											<?php echo $this->_var['Bank_list']; ?></td></tr>
										</table>
									</div>
								</div>
								<div class="clear"></div>
							</div>
						<?php else: ?>
							<div id="accountpay" style="display:none;">
								<input id="check-<?php echo $this->_var['accountpay']['id']; ?>" type="radio" name="payment" value="<?php echo $this->_var['accountpay']['id']; ?>" checked />
							</div>
						<?php endif; ?>

						<!--支付方式-->
						<div id="cart_total_box"></div>
						<?php if ($this->_var['sms_send'] == 1): ?>
						<div class="delivery order-pay-credit" >
							<h3><?php echo $this->_var['lang']['JJ_MOBILEPHONE_CONFIRM']; ?>：</h3>
							<!--手机号码框-->
							<table class="table-list"  style="width:620px;">
								<tr>
									<td class='t1' style="width:620px;">
										<input type="text" size="15" name="user_mobile_phone" id="user-mobile-phone" class="f-input" value="<?php echo $this->_var['user_info']['mobile_phone']; ?>"  />
										<?php echo $this->_var['lang']['JJ_IMPORTANT_TIP']; ?>
									</td>
								</tr>
							</table>
							<!--手机号码框-->
						</div>
						<?php endif; ?>
						<div class="order-check-form ">
							<p class="check-act">
								<input type="hidden" name="m" value="Cart" />
								<input type="hidden" name="a" value="done" />
								<input id="order_done" type="button" value="<?php echo $this->_var['lang']['JJ_ORDERS_PAYMENT']; ?>" class="formbutton" disabled="disabled" />
								<a href="<?php echo $this->_var['ROOT_PATH']; ?>/index.php?m=Cart&a=index" style="margin-left:1em;"><?php echo $this->_var['lang']['JJ_BACK_ORDER']; ?></a>
							</p>
						</div>
					</div>
				</div>
				<div class="box-bottom"></div>
			</div>
		</div>
		<div id="sidebar" class="rail">

			<?php if ($this->_var['cart_total_price'] > 0 && a_fanweC ( "OPEN_ECV" ) == 1): ?>
			<!--代金券-->
			<div class="sbox">
				<div class="sbox-top"></div>
				<div class="sbox-content">
					<div class="cardcode">
						<h2><?php echo $this->_var['lang']['XY_YOU_HAVA_VOUCHER']; ?></h2>
						<a href="javascript:void(0);" id="cardcode-link"><?php echo $this->_var['lang']['XY_HIT_ENTER_VOUCHER']; ?></a>
						<div class="act ecvinput">
						<p id="cardcode-link-t">
							<input id="cardcode-sn" class="f-input" type="text" name="ecvSn" maxlength="20" value="" autocomplete="off" />
						</p>
						<div class="clear"></div>
						<a href="javascript:void(0);" id="cardpass-link"><?php echo $this->_var['lang']['XY_ENTER_VOUCHER_PASS']; ?></a>
						<p id="cardpass-link-t">
							<input id="cardcode-pwd" class="f-input" type="password" name="ecvPassword" maxlength="20" value="" autocomplete="off" />
							<input id="cardcode-verify" type="button" class="formbutton" value="<?php echo $this->_var['lang']['XY_CONFIRM']; ?>" />
						</p>
						</div>
						<div class="act ecvinfo">
							<p><?php echo $this->_var['lang']['XY_TYPE']; ?><span></span></p>
							<p><?php echo $this->_var['lang']['XY_THE_AMOUNT']; ?><span></span></p>
							<p><?php echo $this->_var['lang']['XY_USE_START_TIME']; ?><span></span></p>
							<p><?php echo $this->_var['lang']['XY_USE_END_TIME']; ?><span></span></p>
						</div>
					</div>
				</div>
				<div class="sbox-bottom"></div>
			</div>
			<div class="blank"></div>
			<!--代金券-->
			<div class="blank"></div>
			<?php endif; ?>

			<?php if ($this->_var['isAccountpay'] == 1): ?>
			<div class="sbox">
            	<div class="sbox-top"></div>
            	<div class="sbox-content">
					<div class="side-tip">
						<h3 class="first"><?php echo $this->_var['lang']['JJ_WHAT_ACCOUNT_BALANCE']; ?></h3>
						<p><?php echo $this->_var['lang']['JJ_AMOUNT_PAID']; ?></p>
						<h3><?php echo $this->_var['lang']['JJ_GET_BALANCE']; ?></h3>
						<p>
							<?php
								echo sprintf($this->_var['lang']['JJ_INVITE_FRIENDS'],$this->_var['ROOT_PATH']."/index.php?m=Referrals&a=index");
							?>
						</p>
					</div>
            	</div>
            	<div class="sbox-bottom"></div>
        	</div>
			<?php endif; ?>
   		</div>
		</form>
	</div>
	<!-- bd end -->


