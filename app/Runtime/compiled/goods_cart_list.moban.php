<script type="text/javascript">
function modify_cart(id,goods_id,o)
{
	var o_value = $(o.parentNode).find("input[name='o_value']").val();
	var quantity = $("#cart_"+id).find("input[name='quantity']").val();
	var attrs = $("#cart_"+id).find("select[name='goods_attr[]']");
	var url = ROOT_PATH+"/index.php?m=Cart&a=index&act=ajax_count&goods_id="+goods_id+"&quantity="+quantity+"&id="+id;
	
	for(i=0;i<attrs.length;i++)
	{
		url += "&goods_attr[]="+$(attrs[i]).val();
	}

	$.ajax({
			  url: url,
			  cache: false,
			  dataType: "json",
			  success:function(data)
			  {
			  	  if(data.status == 1)
				  {
			　 		$("#content_cart").html(data.html);
					getcartinfo();
				  }
				  else if(data.status==2)
				    location.href = data.info;
				  else
				  {
				    $.showErr(data.info);
					$(o).val(o_value);
					return false;
				  }

			  }
		});
}

function del_cart(id)
{

	var url = ROOT_PATH+"/index.php?m=Cart&a=index&act=del_cart&id="+id;
	$.ajax({
			  url: url,
			  cache: false,
			  dataType: "json",
			  success:function(data)
			  {
			  	  if(data.status == 1)
				  {
			　 		$("#content_cart").html(data.html);
					getcartinfo();
				  }
				  else if(data.status==2)
				    location.href = data.info;
				  else
				  {
				    $.showErr(data.info);
					$(o).val(o_value);
					return false;
				  }

			  }
		});
}
</script>
<div id="content_cart">
			<form method="get" id="deal-buy-form" action="<?php echo $this->_var['ROOT_PATH']; ?>">
				<div id="deal-buy" class="box">
					<div class="box-top"></div>
					<div class="box-content">
						<div class="step">
							<img src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/step1.jpg" />
						</div>
						<div class="sect">
							<table class="order-table">
								<tr>
									<th class="deal-buy-desc">&nbsp;<?php echo $this->_var['lang']['JJ_PROJECT']; ?></th>
									<th style="width:140px;"><?php echo $this->_var['lang']['GOODS_ATTR']; ?></th>
									<th class="deal-buy-quantity"><?php echo $this->_var['lang']['JJ_QUANTITY']; ?></th>									
									<th style="width:60px;"><?php echo $this->_var['lang']['JJ_PRICE']; ?></th>															
									<th class="deal-buy-total"><?php echo $this->_var['lang']['JJ_TOTAL']; ?></th>
									<th ><?php echo $this->_var['lang']['DELETE']; ?></th>
								</tr>
								<?php $_from = $this->_var['cart_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cart_item');if (count($_from)):
    foreach ($_from AS $this->_var['cart_item']):
?>
								<tr id="cart_<?php echo $this->_var['cart_item']['id']; ?>">
									<td class="deal-buy-desc"><?php echo $this->_var['cart_item']['data_name']; ?></td>
									<td >									
									
									<?php $_from = $this->_var['cart_item']['goods_info']['attrlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attrlist');if (count($_from)):
    foreach ($_from AS $this->_var['attrlist']):
?>
									<span style="float:left;"><?php echo $this->_var['attrlist']['attr_info']['name']; ?></span>
									<select name="goods_attr[]" style="float:left; margin:0 10px 0 5px;" onchange="modify_cart(<?php echo $this->_var['cart_item']['id']; ?>,<?php echo $this->_var['cart_item']['rec_id']; ?>,this);">
									
									<?php $_from = $this->_var['attrlist']['attr_value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');if (count($_from)):
    foreach ($_from AS $this->_var['attr']):
?>
									<option value="<?php echo $this->_var['attr']['id']; ?>" price="<?php echo $this->_var['attr']['price']; ?>" <?php if(is_array($this->_var['cart_item']['attr_ids'])&&in_array($this->_var['attr']['id'],$this->_var['cart_item']['attr_ids'])) echo "selected='selected';"?>><?php echo $this->_var['attr']['value']; ?><?php if ($this->_var['attr']['price'] > 0): ?>&nbsp;+&nbsp;<?php echo a_formatPrice(floatval($this->_var['attr']['price']));?><?php endif; ?></option>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>									

									</select>
									<div class="clear"></div>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									
									</td>
									<td class="deal-buy-quantity">
									<input type="text" class="input-text f-input" maxlength="4" name="quantity" value="<?php echo $this->_var['cart_item']['number']; ?>" id="deal-buy-quantity-input" style="ime-mode:disabled"  onblur="modify_cart(<?php echo $this->_var['cart_item']['id']; ?>,<?php echo $this->_var['cart_item']['rec_id']; ?>,this);"/>
									<input type="hidden" name="o_value" value="<?php echo $this->_var['cart_item']['number']; ?>" />
									</td>
									
									<td class="deal-buy-price">
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
									
									<td>
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
									<td><a href='javascript:void(0);' onclick='del_cart(<?php echo $this->_var['cart_item']['id']; ?>)'><?php echo $this->_var['lang']['DELETE']; ?></a></td>
								</tr>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								
									<tr class="order-total">
									<td class="deal-buy-desc"><strong><?php echo $this->_var['lang']['JJ_TOTAL_AMOUNT']; ?></strong></td>
									<td class="deal-buy-attr"></td>
									<td class="deal-buy-quantity"></td>									
									<td class="deal-buy-price"></td>		
															
									<td class="deal-buy-total" colspan=2 ><strong id="deal-buy-total-t"><?php 
$k = array (
  'name' => 'a_formatPrice',
  'value' => $this->_var['total_price'],
);
echo $k['name']($k['value']);
?></strong></td>
									
								</tr>
							</table>
							<div class="form-submit" style="text-align:right;">
								<input type="hidden" name="m" value="Cart" />
								<input type="hidden" name="a" value="check" />
								<a href="./"><?php echo $this->_var['lang']['JJ_STILL_BUY']; ?></a>&nbsp;&nbsp;<input type="button" class="formbutton" name="buy" value="<?php echo $this->_var['lang']['JJ_CONFIRM_BUY']; ?>" onclick="location.href='<?php echo $this->_var['ROOT_PATH']; ?>/index.php?m=Cart&a=check';"/>
							</div>
						</div>
					</div>
					<div class="box-bottom"></div>
				</div>
			</form>
</div>