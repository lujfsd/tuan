<?php echo $this->fetch('Inc/header.moban'); ?>
<div id="bdw" class="bdw">
	<div id="bd" class="cf">
		<div id="coupons">
			<div id="content" class="coupons-box">
				<div class="dashboard" id="dashboard">
					<ul>
						<?php echo $this->fetch('Inc/user_center/user_center_menu.moban'); ?>						
					</ul>
				</div>
				<div class="box clear">
					<div class="box-top"></div>
					<div class="box-content">
						<div class="head">
							<h2><?php echo $this->_var['lang']['XY_MY']; ?><?php echo a_fanweC('GROUPBOTH') ?></h2>
							<ul class="filter">
								<li class="label"><?php echo $this->_var['lang']['XY_CLASS_IFICATION']; ?> </li>
								<li <?php if ($this->_var['status'] == 1): ?>class="current"<?php endif; ?>><a href="<?php echo a_u('UcGroupBond/index','status-1') ?>"><?php echo $this->_var['lang']['XY_NO_USE']; ?></a></li>
								<li <?php if ($this->_var['status'] == 2): ?>class="current"<?php endif; ?>><a href="<?php echo a_u('UcGroupBond/index','status-2') ?>"><?php echo $this->_var['lang']['XY_HAD_USE']; ?></a></li>
								<li <?php if ($this->_var['status'] == 3): ?>class="current"<?php endif; ?>><a href="<?php echo a_u('UcGroupBond/index','status-3') ?>"><?php echo $this->_var['lang']['XY_BAD_USE']; ?></a></li>
								<li <?php if ($this->_var['status'] == 0): ?>class="current"<?php endif; ?>><a href="<?php echo a_u('UcGroupBond/index','status-0') ?>"><?php echo $this->_var['lang']['XY_ALL']; ?></a></li>
							</ul>
						</div>
						<div class="sect">
							
							<?php if ($this->_var['groupbond_list']): ?>
							<table id="coupons-table" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
								<tr>
									<th width="auto"><?php echo $this->_var['lang']['XY_GROUP_PROJECT']; ?></th>
									<th width="80"><?php echo $this->_var['GROUPBOTH']; ?></th>
									<th width="80"><?php echo $this->_var['lang']['XY_BUY_DATE']; ?></th>
									<th width="80"><?php echo $this->_var['lang']['XY_EXPIRED_TIMES']; ?></th>
									<th width="80"><?php echo $this->_var['lang']['XY_USE_TIMES']; ?></th>
									<th width="50"><?php echo $this->_var['lang']['XY_USER_OPERATE']; ?></th>
								</tr>
								<?php $_from = $this->_var['groupbond_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'groupbond');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['groupbond']):
?>
								<tr <?php if ($this->_var['key'] % 2 == 0): ?>class="alt"<?php endif; ?>>
									<td style="text-align:left;">
										<table class="deal-info">
											<tr>
												<td class="pic">
												<a href="<?php echo $this->_var['groupbond']['goods']['url']; ?>" target="_blank" title="<?php echo $this->_var['order_item']['goods_name']; ?>"><img src="<?php echo $this->_var['CND_URL']; ?><?php echo $this->_var['groupbond']['goods']['small_img']; ?>" width="75" height="46" /></a></td>
												<td class="text"><a class="deal-title" href="<?php echo $this->_var['groupbond']['goods']['url']; ?>" target="_blank"><?php echo $this->_var['groupbond']['goods_name']; ?></a></td>
											</tr>
										</table>
									</td>
									<td>SN:<?php echo $this->_var['groupbond']['sn']; ?><br/>PW:<?php echo $this->_var['groupbond']['password']; ?></td>
									<td><?php echo $this->_var['groupbond']['buy_time_format']; ?></td>
									<td>
										
										<?php if ($this->_var['groupbond']['end_time_format']): ?>
										<?php echo $this->_var['groupbond']['end_time_format']; ?>
										<?php else: ?>
										<?php echo $this->_var['lang']['XY_NO_EXPIRED_TIMES']; ?>
										<?php endif; ?>
									</td>
									<td>
										<?php if ($this->_var['groupbond']['use_time_format']): ?>
										<?php echo $this->_var['groupbond']['use_time_format']; ?>
										<?php else: ?>
										<?php echo $this->_var['lang']['XY_NO_USE']; ?>
										<?php endif; ?>
									</td>
									<td class="op">									
										<?php if ($this->_var['groupbond']['is_edit']): ?>		
											<?php if ($this->_var['GROUPBOND_PRINTTYPE'] == 0): ?>
											<a href="javascript:void(0);" target="_blank" class="print_btn" rel="<?php echo $this->_var['groupbond']['id']; ?>"><?php echo $this->_var['lang']['XY_PRINT']; ?></a>
											<?php else: ?>
											<a href="javascript:void(0);" target="_blank" class="print_btn" rel="<?php echo $this->_var['groupbond']['id']; ?>"><?php echo $this->_var['lang']['XY_PRINT']; ?></a>
											<a href="javascript:void(0);" target="_blank" class="down_btn" rel="<?php echo $this->_var['groupbond']['id']; ?>"><?php echo $this->_var['lang']['DOWNLOAD']; ?></a>
											<?php endif; ?>
											<?php if ($this->_var['IS_SMS'] == 1 && $this->_var['groupbond']['goods']['allow_sms'] == 1): ?>
											<a href="javascript:void(0);" class="sms_btn" rel="<?php echo $this->_var['groupbond']['id']; ?>" ><?php echo $this->_var['lang']['XY_SMS']; ?></a>
											<?php endif; ?>
										<?php endif; ?>
									</td>
								</tr>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							</table>
							<div style="padding:0 20px 0 0; text-align:right;">
								<?php echo $this->_var['pages']; ?>
							</div>
							<?php else: ?>
							<div class="notice"><?php echo $this->_var['lang']['XY_NOW_NO_USER']; ?><?php echo $this->_var['GROUPBOTH']; ?></div>
							<?php endif; ?>
						</div>
					</div>
					<div class="box-bottom"></div>
				</div>
			</div>
			<div id="sidebar">
				<div class="sbox">
					<div class="sbox-top"></div>
					<div class="sbox-content">
						<div class="side-tip">
							<h3 class="first"><?php echo $this->_var['lang']['XY_WHAT_IS']; ?><?php echo $this->_var['CFG']['GROUPBOTH']; ?>？</h3>
							<p><?php echo a_fanweC('GROUPBOTH') ?><?php echo $this->_var['lang']['XY_WHAT_IS_GROUPBOTH']; ?></p>
							<h3><?php echo $this->_var['lang']['XY_HOW_TO_USE']; ?><?php echo a_fanweC('GROUPBOTH') ?>？</h3>
							<p><?php echo sprintf(a_L('XY_HOW_TO_USE_GROUPBOTH'),a_fanweC('GROUPBOTH'),a_fanweC('GROUPBOTH')) ?></p>
						</div>
					</div>
					<div class="sbox-bottom"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- bd end -->
</div>

<div class="groupbond_sms_box">
		<div style="text-align:right;"><a href="javascript:void(0);" class="close"><?php echo $this->_var['lang']['CLOSE']; ?></a></div>
		<select id="departs"></select>
		<?php if (a_fanweC ( 'SMS_SEND_OTHER' ) == 1): ?><span><?php echo $this->_var['lang']['MOBILE_PHONE']; ?>：</span><?php endif; ?><input <?php if (a_fanweC ( 'SMS_SEND_OTHER' ) == 1): ?>type="text"<?php endif; ?> <?php if (a_fanweC ( 'SMS_SEND_OTHER' ) == 0): ?>type="hidden"<?php endif; ?> name="mobile_phone" id="gb_mobile_phone">
		<input type="button" value="<?php echo $this->_var['lang']['UCGROUPBOND_SMS']; ?>" onclick="send_sms_gb();" />
		<input type="hidden" id="gb_id" value="" />
</div>


<div class="groupbond_down_box">
		<div style="text-align:right;"><a href="javascript:void(0);" class="close"><?php echo $this->_var['lang']['CLOSE']; ?></a></div>
		<select id="departs_list"></select>
		<input type="button" value="<?php echo $this->_var['lang']['DOWNLOAD']; ?>" onclick="down_gb();" />
		<input type="hidden" id="gb_down_id" value="" />
</div>

<div class="groupbond_print_box">
		<div style="text-align:right;"><a href="javascript:void(0);" class="close"><?php echo $this->_var['lang']['CLOSE']; ?></a></div>
		<select id="departs_list_print"></select>
		<input type="button" value="<?php echo $this->_var['lang']['XY_PRINT']; ?>" onclick="print_gb();" />
		<input type="hidden" id="gb_print_id" value="" />
</div>

<script type="text/javascript">
window.onload=function(){
	$(".sms_btn").click(function(){
		var gb_id = $(this).attr("rel");
		$.ajax({
			  url: ROOT_PATH+"/services/ajax.php?run=getGbDownData&gb_id="+gb_id,
			  dataType: "json",
			  success:function(data)
			  {
				  $("#departs").empty();
				  var departs = data.departs;
				  for(var i=0;i<departs.length;i++)
				  {
				  	 $("#departs").append("<option value='"+departs[i].id+"'>"+departs[i].depart_name+"</option>");
				  }
			  	  $("#gb_mobile_phone").val(data.mobile);
			  	  $("#gb_id").val(gb_id);
			　 	  $.ShowDialog({"dialog":"groupbond_sms_box"});
				},
				error:function(a,b,c)
				{
					alert(a.responseText);
				}
		});
		return false;
	});
	
	$(".down_btn").click(function(){
		var gb_id = $(this).attr("rel");
		$.ajax({
			  url: ROOT_PATH+"/services/ajax.php?run=getGbDownData&gb_id="+gb_id,
			  dataType: "json",
			  success:function(data)
			  {
				  $("#departs_list").empty();
				  var departs = data.departs;
				  for(var i=0;i<departs.length;i++)
				  {
				  	 $("#departs_list").append("<option value='"+departs[i].id+"'>"+departs[i].depart_name+"</option>");
				  }
			  	  $("#gb_down_id").val(gb_id);
			　 	  $.ShowDialog({"dialog":"groupbond_down_box"});
				},
				error:function(a,b,c)
				{
					alert(a.responseText);
				}
		});
		return false;
	});
	
	
	$(".print_btn").click(function(){
		var gb_id = $(this).attr("rel");
		$.ajax({
			  url: ROOT_PATH+"/services/ajax.php?run=getGbDownData&gb_id="+gb_id,
			  dataType: "json",
			  success:function(data)
			  {
				  $("#departs_list_print").empty();
				  var departs = data.departs;
				  for(var i=0;i<departs.length;i++)
				  {
				  	 $("#departs_list_print").append("<option value='"+departs[i].id+"'>"+departs[i].depart_name+"</option>");
				  }
			  	  $("#gb_print_id").val(gb_id);
			　 	  $.ShowDialog({"dialog":"groupbond_print_box"});
				},
				error:function(a,b,c)
				{
					alert(a.responseText);
				}
		});
		return false;
	});
}


function send_sms_gb()
{
	var gb_id = $("#gb_id").val();
	var mobile = $("#gb_mobile_phone").val();
	var depart_id = $("#departs").val();
	<?php if (a_fanweC ( 'SMS_SEND_OTHER' ) == 1): ?>
	if(!$.checkMobilePhone(mobile))
	{
	   alert("<?php echo $this->_var['lang']['JS_MOBILE_ERROR']; ?>");
	   return false;			
	}
	<?php endif; ?>
	location.href = ROOT_PATH+"/index.php?m=UcGroupBond&a=sms&id="+gb_id+"&mobile="+mobile+"&depart_id="+depart_id;
}

function down_gb()
{
	var gb_id = $("#gb_down_id").val();
	var depart_id = $("#departs_list").val();
	location.href = ROOT_PATH+"/index.php?m=UcGroupBond&a=download&id="+gb_id+"&depart_id="+depart_id;
}

function print_gb()
{
	var gb_id = $("#gb_print_id").val();
	var depart_id = $("#departs_list_print").val();
	window.open(ROOT_PATH+"/index.php?m=UcGroupBond&a=printbond&id="+gb_id+"&depart_id="+depart_id);
}
</script>
<?php echo $this->fetch('Inc/footer.moban'); ?>