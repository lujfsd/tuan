<?php echo $this->fetch('Inc/header.moban'); ?>
<div id="bdw" class="bdw">
	<div id="bd" class="cf">
		<div id="content">
			<div class="box">
				<div class="box-top"></div>
				<div class="box-content">
					<div class="head">
						<h2><?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['GROUP_BOND_CHECK_BUG'],
  'b' => $this->_var['CFG']['GROUPBOTH'],
);
echo $k['name']($k['a'],$k['b']);
?></h2>
					</div>
					<div class="sect">
						<div class="item" style="width:550px; margin:0px auto;">
							<div class="blank"></div>
							<strong><?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['GROUP_BOND_INFO_001'],
  'b' => $this->_var['CFG']['GROUPBOTH'],
);
echo $k['name']($k['a'],$k['b']);
?></strong>
							<div class="blank"></div>
							<div class="blank"></div>
							<div>
								<form method="post">
									<?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['GROUP_BOND_INFO_002'],
  'b' => $this->_var['CFG']['GROUPBOTH'],
);
echo $k['name']($k['a'],$k['b']);
?>：<input id="group_bond_sn" name="group_bond_sn" class="f-input" value=""> <input class="formbutton" onclick = "return query_coupon()" value="<?php echo $this->_var['lang']['GROUP_BOND_INFO_003']; ?>" type="button">
									<div class="blank"></div>
									<div class="blank"></div>
									<?php 
$k = array (
  'name' => 'sprintf',
  'a' => $this->_var['lang']['GROUP_BOND_INFO_004'],
  'b' => $this->_var['CFG']['GROUPBOTH'],
);
echo $k['name']($k['a'],$k['b']);
?>：<input id="group_bond_pwd" name="group_bond_pwd" class="f-input" value=""> <input class="formbutton" onclick = "return query_coupon_bus()" value="<?php echo $this->_var['lang']['GROUP_BOND_INFO_005']; ?>" type="button">
								</form>
							</div>
						</div>
					</div>
					
					<div class="sect">
						<span style="color: rgb(255, 0, 0);">
							<?php 
$k = array (
  'name' => 'str_replace',
  'a' => '%s',
  'b' => $this->_var['CFG']['GROUPBOTH'],
  'c' => $this->_var['lang']['GROUP_BOND_INFO_006'],
);
echo $k['name']($k['a'],$k['b'],$k['c']);
?>
						</span>	
					</div>
				
				</div>
				<div class="box-bottom"></div>
			</div>
		</div>
	</div>
	<!-- bd  end -->
</div>
<script type="text/javascript">
	function query_coupon()
	{
		var group_bond_sn = $.trim($("#group_bond_sn").val());
		if (group_bond_sn == ''){
			alert(LANG.JS_GROUP_BOND_001);
		}else{		
			var url = ROOT_PATH+"/index.php?m=Coupon&a=index&do=coupon_check2&sn="+group_bond_sn;
				
			$.ajax({
				  url: url,
			  	  cache: false,
			  	  type: "POST",
			  	  dataType: "json",
				  success:function (data)
				  {
					 if (data.type == 0){
					 	alert(LANG.JS_GROUP_BOND_004 + ":" + group_bond_sn + " " + LANG.JS_GROUP_BOND_003);
					 }
                                        if (data.type == 1)
                                         {
					 	alert(LANG.JS_GROUP_BOND_004 + ":" + group_bond_sn + "\n" + data.msg);
					 }
                                        if (data.type == 2)
                                         {
					 	alert(LANG.JS_GROUP_BOND_004 + ":" + group_bond_sn + "            " + LANG.JS_GROUP_BOND_010 + "\n" + data.msg);
					 }
				  },
				  error:function(a,b,c)
					{
						alert(a.responseText);
					}
			});			
		}
	}
	
	function query_coupon_bus()
	{
		var group_bond_sn = $.trim($("#group_bond_sn").val());
		var group_bond_pwd = $.trim($("#group_bond_pwd").val());
		var msg = '';
		if (group_bond_sn == ''){
			msg = LANG.JS_GROUP_BOND_001;
		}
		
		
		if (msg != ''){
			alert(msg);
		}else{
			$.ajax({
				  type: "POST",	
				  url: ROOT_PATH+"/index.php?m=Coupon&a=index&do=coupon_bus2&sn="+group_bond_sn+"&pwd="+group_bond_pwd,
				  cache: false,
				  dataType:'json',
				  success:function (data)
				  {
					 if (data.type == 1){
					 	alert(LANG.JS_GROUP_BOND_004 + ":" + group_bond_sn + " " + LANG.JS_GROUP_BOND_005 + "\n" +data.msg);
					 }else{
					 	alert(LANG.JS_GROUP_BOND_006 + ":" + LANG.JS_GROUP_BOND_003);
					 }
				  },
				  error:function(a,b,c)
					{
						alert(a.responseText);
					}
			});			
		}			
	}	

</script>
<?php echo $this->fetch('Inc/footer.moban'); ?>