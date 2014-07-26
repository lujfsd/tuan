<div class="sect" style="padding-top:0;">
							<form id="signup-user-form" method="post" action="<?php echo $this->_var['POST_URL']; ?>">
								<div class="field email">
									<label for="signup-email-address">Email</label>
									<input type="text" size="30" name="email" id="signup-email-address" class="f-input" value="" />
									<span class="f-input-tip"></span> <span class="hint"><?php echo $this->_var['lang']['XY_NO_OVERT']; ?></span> </div>
								<div class="field username">
									<label for="signup-username"><?php echo $this->_var['lang']['XY_USER_NAME']; ?></label>
									<input type="text" size="30" name="user_name" id="signup-username" class="f-input" value="" />
									<span class="hint"><?php echo $this->_var['lang']['XY_USER_LEN']; ?></span> </div>
								<div class="field password">
									<label for="signup-password"><?php echo $this->_var['lang']['XY_PASS']; ?></label>
									<input type="password" size="30" name="user_pwd" id="signup-password" class="f-input" value="" />
									<span class="hint"><?php echo $this->_var['lang']['XY_PASS_NEED_LEN']; ?></span> </div>
								<div class="field password">
									<label for="signup-password-confirm"><?php echo $this->_var['lang']['XY_AFFIRM_PASS']; ?></label>
									<input type="password" size="30" name="user_pwd_confirm" id="signup-password-confirm" class="f-input" value="" />
								</div>
								<div class="field mobile">
									<label for="signup-mobile"><?php echo $this->_var['lang']['XY_MOBILE']; ?></label>
									<input type="text" style="width:170px;" name="mobile_phone" id="settings-mobile" class="f-input" value="<?php echo $this->_var['data']['mobile_phone']; ?>" />
									<?php if (a_fanweC ( 'REGISTER_MOBILE_VERIFY' ) == 1): ?>
										<input onclick ="return sms_mobile_verify_click()" type="image" src="<?php echo $this->_var['CND_URL']; ?>/<?php echo $this->_var['TMPL_PATH']; ?>Public/images/ss-1.jpg" />
										<span class="hint"><?php echo $this->_var['lang']['REG_INFO_001']; ?></span>
									<?php else: ?>
										<span class="hint"><?php 
$k = array (
  'name' => 'a_fanweC',
  'value' => 'GROUPBOTH',
);
echo $k['name']($k['value']);
?><?php echo $this->_var['lang']['XY_MOBILE_NOTICE']; ?></span>
									<?php endif; ?>
								</div>
								
								<?php if (a_fanweC ( 'REGISTER_MOBILE_VERIFY' ) == 1): ?>
									<div class="field mobile">
										<label for="signup-mobile"><?php echo $this->_var['lang']['XY_MOBILE']; ?><?php echo $this->_var['lang']['XY_VERIFY']; ?></label>
										<input type="text" size="30" name="mobile_verify" id="mobile_verify" class="f-input" style="width:170px;" />
										<span class="hint"><?php echo $this->_var['lang']['REG_INFO_002']; ?></span>
									</div>								
								<?php endif; ?>								
								
								<?php $_from = $this->_var['extend_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'field_item');if (count($_from)):
    foreach ($_from AS $this->_var['field_item']):
?>
									<div class="field <?php echo $this->_var['field_item']['field_name']; ?>">
										<label for="signup-<?php echo $this->_var['field_item']['field_name']; ?>"><?php echo $this->_var['field_item']['field_show_name']; ?></label>
										<?php if ($this->_var['field_item'] [ 'type' ] == 0): ?>
											<input type="text" size="30" name="<?php echo $this->_var['field_item']['field_name']; ?>" id="settings-<?php echo $this->_var['field_item']['field_name']; ?>" class="f-input" />
										<?php endif; ?>
										
										<?php if ($this->_var['field_item'] [ 'type' ] == 1): ?>
												<select name="<?php echo $this->_var['field_item']['field_name']; ?>" id="settings-<?php echo $this->_var['field_item']['field_name']; ?>">
												<?php $_from = $this->_var['field_item']['val_scope']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?>
													<option value="<?php echo $this->_var['val']; ?>"><?php echo $this->_var['val']; ?></option>
												<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
												</select>
										<?php endif; ?>
										<span class="hint"><?php echo $this->_var['field_item']['field_show_desc']; ?></span>
									</div>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								
								<div class="field city">
									<label id="enter-address-city-label" for="signup-city"><?php echo $this->_var['lang']['XY_WHERE_CITY']; ?></label>
									<select name="city_id" autocomplete="off" class="f-city" id="signup-city">
										<?php $_from = $this->_var['city_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'city');if (count($_from)):
    foreach ($_from AS $this->_var['city']):
?>
											<option value="<?php echo $this->_var['city']['id']; ?>" <?php if ($this->_var['city'] [ 'id' ] == $this->_var['data'] [ 'city_id' ]): ?> selected = 'selected' <?php endif; ?> ><?php echo $this->_var['city']['name']; ?></option>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
										<option value="0"><?php echo $this->_var['lang']['XY_OHTER']; ?></option>
									</select>
								</div>
								
								<?php if (a_fanweC ( 'REGISTER_VERIFY' ) == 1): ?>
									<div class="field mobile">
										<label for="signup-mobile"><?php echo $this->_var['lang']['XY_VERIFY']; ?></label>
										<input type="text" size="20" name="verify" id="register_verify" class="f-input" style="width:100px;" />
										<img id="register-verify-img" style="cursor:pointer;height:auto; float:left; margin:3px 2px 3px 6px;" align="absmiddle" title="<?php echo $this->_var['lang']['XY_VERIFY_RESET']; ?>" onclick="this.src='<?php echo $this->_var['HTTP_URL']; ?>/index.php?m=Ajax&a=verify&rand='+ Math.random();" src='<?php echo $this->_var['HTTP_URL']; ?>/index.php?m=Ajax&a=verify&rand='+ Math.random();/>
									</div>								
								<?php endif; ?>
																
								<div class="field subscribe" <?php if ($_REQUEST['m'] == 'Cart'): ?>style="padding-left:120px;"<?php endif; ?>>
								<label style="width:auto;line-height:normal;"><input tabindex="3" type="checkbox" value="1" name="subscribe" id="subscribe" class="f-check" checked="checked"  />
									<?php echo $this->_var['lang']['XY_EVERY_SUBS']; ?></label>
								</div>
								<div class="clear"></div>
								<div class="act">
								
									<input type="hidden" id="user_api_field_name" name="user_api_field_name" value="<?php echo $this->_var['user_api_field_name']; ?>" />
									<input type="hidden" id="user_api_field_value" name="user_api_field_value" value="<?php echo $this->_var['user_api_field_value']; ?>" />
																
									<input type="hidden" name="redirect" id="redirect" value="<?php echo $this->_var['redirect']; ?>" />
									<input type="hidden" name="goods_id" id="goods_id" value="<?php echo $this->_var['goods_id']; ?>" />
									<input type="button" value="<?php echo $this->_var['lang']['XY_USER_REG']; ?>" name="commit" id="signup-submit" onclick="user_reg()" class="formbutton"/>
									<input type="checkbox" value="1" checked="checked" id="agreement_box" /><a href='<?php echo $this->_var['agreement_url']; ?>'> <?php echo $this->_var['lang']['REG_AGREEMENT']; ?> </a>
								</div>
							</form>
						</div>


<script type="text/javascript">
ROOT_PATH=ROOT_PATH.replace('/api','');
jQuery(function($){
//邮箱
$("#signup-email-address").blur(function(){
	
	  $(".notice-top-box .email").removeClass("succss");
	  $(".notice-top-box .email").css("display","block");
	  if($.trim($(this).val()).length == 0)
	  {
	  	 $(".notice-top-box span.email").html("<?php echo $this->_var['lang']['XY_PLEASE_MAIL']; ?>");
		 return "";
	  }
	  
	  if(!$.checkEmail($(this).val()))
	  {
	  	 $(".notice-top-box span.email").html("<?php echo $this->_var['lang']['XY_ERROR_MAIL']; ?>");
	  }
	  else
	  {
	  	var query = new Object();
		query.email=this.value;
		$.ajax({
					url: ROOT_PATH+"/services/ajax.php?run=checkUser",
					data:query,
					cache:false,
					dataType:"text",
					success:function(data)
					{
						if(parseInt(data)>0)
						{
							$(".notice-top-box span.email").html("邮箱已使用");
							return "";
						}
						else
						{
							$(".notice-top-box .email").addClass("succss");
	     					$(".notice-top-box span.email").html("非常好");
						}
					}
				});
	  	  
	  }
});
	
	//用户名
	$("#signup-username").blur(function(){
	  $(".notice-top-box .username").removeClass("succss");
	  $(".notice-top-box .username").css("display","block");
	  if(!$.minLength($("#signup-username").val(),4,true))
	  {
	  	 $(".notice-top-box span.username").html("用户名太短");
		 return "";
	  }
	  if(!$.maxLength($("#signup-username").val(),16,true))
	  {
	    $(".notice-top-box span.username").html("用户名太长");
	    return "";
	  }
	  
	    var query = new Object();
		query.user=this.value;
		$.ajax({
				url:  ROOT_PATH+"/services/ajax.php?run=checkUser",
				data:query,
				cache:false,
				dataType:"text",
				success:function(data)
				{
				  if(parseInt(data)>0)
				  {
					   $(".notice-top-box span.username").html("用户名已使用");
					  return "";
				   }
				   else
				  {
					 $(".notice-top-box .username").addClass("succss");
					 $(".notice-top-box span.username").html("非常好");
					}
				}
			});
	  
	 
	})
	
	//密码
	
	$("#signup-password").blur(function(){
	  $(".notice-top-box .password").removeClass("succss");
	  $(".notice-top-box .password").css("display","block");
	  if(!$.minLength($("#signup-password").val(),4,false))
		{
			$(".notice-top-box span.password").html("<?php echo $this->_var['lang']['XY_USER_PASS_SHORT_NOTICE']; ?>");
			return "";
		}
	  
	  $(".notice-top-box .password").addClass("succss");
	  $(".notice-top-box span.password").html("非常好");
	 
	})
	
	//确认密码
	
	$("#signup-password-confirm").blur(function(){
		
	  $(".notice-top-box .cfpassword").removeClass("succss");
	  $(".notice-top-box .cfpassword").css("display","block");
	  if($("#signup-password-confirm").val()!=$("#signup-password").val())
		{
			$(".notice-top-box span.cfpassword").html("<?php echo $this->_var['lang']['XY_TOW_DIF_PASS']; ?>");
			return "";
		}
	  
	  $(".notice-top-box .cfpassword").addClass("succss");
	  $(".notice-top-box span.cfpassword").html("非常好");
	 
	})
	
	
	//手机
	$("#settings-mobile").blur(function()
	{
		$(".notice-top-box .phone").removeClass("succss");
	    $(".notice-top-box .phone").css("display","block");
		var query = new Object();
		query.phone=this.value;
		<?php if ($this->_var['CFG']['MOBILE_PHONE_MUST'] == 1): ?>
		if(!$.checkMobilePhone($("#settings-mobile").val()))
		{
			$(".notice-top-box span.phone").html("<?php echo $this->_var['lang']['XY_PLEASE_ENTER_TRUE_PHONE']; ?>");
			return "";
		}
		
		$.ajax({
				url:  ROOT_PATH+"/services/ajax.php?run=checkUser",
				data:query,
				cache:false,
				dataType:"text",
				success:function(data)
				{
				  if(parseInt(data)>0)
				  {
					   $(".notice-top-box span.phone").html("手机已使用");
					  return "";
				   }
				   else
				  {
					 $(".notice-top-box span.phone").addClass("succss");
					 $(".notice-top-box span.phone").html("非常好");
					}
				}
			});
			
		<?php else: ?>
		if($("#settings-mobile").val()!="")
		{
			if(!$.checkMobilePhone($("#settings-mobile").val()))
			{
				$(".notice-top-box span.phone").html("<?php echo $this->_var['lang']['XY_PLEASE_ENTER_TRUE_PHONE']; ?>");
			}
			else
			{
				$.ajax({
				url:  ROOT_PATH+"/services/ajax.php?run=checkUser",
				data:query,
				cache:false,
				dataType:"text",
				success:function(data)
				{
				  if(parseInt(data)>0)
				  {
					   $(".notice-top-box span.phone").html("手机已使用");
					  return "";
				   }
				   else
				  {
					 $(".notice-top-box span.phone").addClass("succss");
					 $(".notice-top-box span.phone").html("非常好");
					}
				}
			});
			}
			return "";
		}
		else
		{
		 	$(".notice-top-box .phone").css("display","none");
		}
		<?php endif; ?>
	});
	
	
	$("#signup-city").change(function(){
		if(this.value == 0)
			$(".enter-city").show();
		else
			$(".enter-city").hide();
	});
	
	$("#enter-address-city").focus(function(){
		var address = $.trim($("#enter-address-city").val());
		if(address == "<?php echo $this->_var['lang']['XY_PLEASE_ENT_CITY']; ?>")
			$("#enter-address-city").val("");
	}).blur(function(){
		var address = $.trim($("#enter-address-city").val());
		if(address == "<?php echo $this->_var['lang']['XY_PLEASE_ENT_CITY']; ?>" || address == "")
			$("#enter-address-city").val("<?php echo $this->_var['lang']['XY_PLEASE_ENT_CITY']; ?>");
	});
	
});
</script>

<script type="text/javascript">
var user_reg_lock = false;
function user_reg(){

	if (user_reg_lock){
		$.showErr('数据正在处理中，请不要提交太快');
	}

	if($.trim($("#signup-email-address").val()).length == 0)
	{
		$.showErr("<?php echo $this->_var['lang']['XY_PLEASE_MAIL']; ?>");
		$("#signup-email-address").focus();
		return false;
	}
	
	if(!$.checkEmail($("#signup-email-address").val()))
	{
		$.showErr("<?php echo $this->_var['lang']['XY_ERROR_MAIL']; ?>");
		$("#signup-email-address").focus();
		return false;
	}
	
	if(!$.minLength($("#signup-username").val(),4,true))
	{
		$.showErr("<?php echo $this->_var['lang']['XY_USER_NAME_SHORT_NOTICE']; ?>");
		$("#signup-username").focus();
		return false;
	}
	
	if(!$.maxLength($("#signup-username").val(),16,true))
	{
		$.showErr("<?php echo $this->_var['lang']['XY_USER_NAME_LONG_NOTICE']; ?>");
		$("#signup-username").focus();
		return false;
	}
	
	if(!$.minLength($("#signup-password").val(),4,false))
	{
		$.showErr("<?php echo $this->_var['lang']['XY_USER_PASS_SHORT_NOTICE']; ?>");
		$("#signup-password").focus();
		return false;
	}
	
	if($("#signup-password-confirm").val() != $("#signup-password").val())
	{
		$.showErr("<?php echo $this->_var['lang']['XY_TOW_DIF_PASS']; ?>");
		$("#signup-password-confirm").focus();
		return false;
	}
	<?php if ($this->_var['CFG']['MOBILE_PHONE_MUST'] == 1): ?>
	if(!$.checkMobilePhone($("#settings-mobile").val()))
	{
		$.showErr("<?php echo $this->_var['lang']['XY_PLEASE_ENTER_TRUE_PHONE']; ?>");
		$("#settings-mobile").focus();
		return false;
	}
	<?php endif; ?>	
	
	
	<?php $_from = $this->_var['extend_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'field_item');if (count($_from)):
    foreach ($_from AS $this->_var['field_item']):
?>
		<?php if ($this->_var['field_item']['is_must'] == 1): ?>
			if($("#settings-<?php echo $this->_var['field_item']['field_name']; ?>").val()=='')
			{
				$.showErr("<?php echo $this->_var['lang']['XY_PLEASE_ENTER']; ?><?php echo $this->_var['field_item']['field_show_name']; ?>");
				$("#settings-<?php echo $this->_var['field_item']['field_name']; ?>").focus();
				return false;
			}
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							
							
	if($("#agreement_box").attr("checked") == 0)
	{
		$.showErr("<?php echo $this->_var['lang']['XY_PLEASE_ENTER_CHECK_AGREEMENT']; ?>");
		return false;
	}

	var query = new Object();
	
	query.m = "User";
	query.a = "ajax_register";
	
	query.verify = $.trim($("#register_verify").val());
	query.user_name = $.trim($("#signup-username").val());
	query.user_pwd = $.trim($("#signup-password").val());
	query.user_pwd_confirm = $.trim($("#signup-password-confirm").val());
	query.email = $.trim($("#signup-email-address").val());
	query.city_id = $("#signup-city").val();
	query.mobile_phone = $.trim($("#settings-mobile").val());
	query.subscribe = $("#subscribe").attr("checked")?1:0; 
	query.goods_id = $.trim($("#goods_id").val());
	query.redirect = $.trim($("#redirect").val());
	query.user_api_field_name = $.trim($("#user_api_field_name").val());
	query.user_api_field_value = $.trim($("#user_api_field_value").val());
	query.mobile_verify = $.trim($("#mobile_verify").val());
	<?php $_from = $this->_var['extend_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'field_item');if (count($_from)):
    foreach ($_from AS $this->_var['field_item']):
?>
		query.<?php echo $this->_var['field_item']['field_name']; ?> = $("#settings-<?php echo $this->_var['field_item']['field_name']; ?>").val();
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>	
										
	var http_url='<?php echo $this->_var['HTTP_URL']; ?>';									
	var url = "/index.php";
	url=http_url+url;
	
	$.ajax({
		url: url,
		cache: false,
		type: "POST",
		data: query,
		dataType:"json",
		success:function(data)
		{
		   //status,0:错误1:注册成功2:注册成功，但邮件发送失败;3:验证码错误
			var rs = data;
			if (rs.status == 0 || rs.status == 3){
				$.showErr(rs.info);
			}else if(rs.status == 1){
				if (rs.info != ''){
					$.showErr(rs.info);
				}
				location.href = rs.url; 
			}else if(rs.status == 2){
				if (rs.info != ''){
					$.showErr(rs.info);
				}
				location.href = rs.url; 
			}							
		},
		error:function(a,b,c)
		{
			if(a.responseText)
				$.showErr(a.responseText);
		}
	});
	
	user_reg_lock = false;
}

	function sms_mobile_verify_click(){
		var mobile = $.trim($("#settings-mobile").val());
		if(!$.checkMobilePhone(mobile))
		{
			alert(LANG.JS_ELEVEN_MOBILE_EMPTY);
			return false;
		}
		
		var query = new Object();
		query.run = "sms_mobile_verify";
		query.mobile = mobile;
		$.ajax({
			url: "services/ajax.php",
			data:query,
			cache:false,
			dataType:"json",
			success:function(data)
			{
				if(data.type == 0)
				{
					alert(data.message);
				}
				else if(data.type == 3)
				{
					alert('请1分钟后再试!');
				}
				else
				{
					alert('短信已经发送,请查收!');
				}
				return false;
			}
		});
		return false;
	}	
</script>
