<?php echo $this->fetch('Inc/header.moban'); ?>
<div id="bdw" class="bdw">
	<div id="bd" class="cf">
		<div id="content">
			<div id="pay-success" class="box">
				<div class="box-top"></div>
				<div class="box-content">
					<div class="head">
						<h2><?php echo $this->_var['lang']['JJ_PAY_SUCCESS']; ?></h2>
					</div>
					<div class="sect">
						<?php echo $this->fetch('Inc/cart/pay_success_info.moban'); ?>
					</div>
				</div>
				<div class="box-bottom"></div>
			</div>
		</div>
	</div>
	<!-- bd  end -->
</div>
<script type="text/javascript">
jQuery(function($){
	$("#mobile_phone").focus(function(){
		if(this.value == "<?php echo $this->_var['lang']['JJ_MOBILE_EMPTY']; ?>")
			this.value="";
	}).blur(function(){
		if(this.value == "")
			this.value="<?php echo $this->_var['lang']['JJ_MOBILE_EMPTY']; ?>";
	});
	
	$("#sms-submit").click(function(){
		if($("#mobile_phone").val() == "")
		{
			$.showErr("<?php echo $this->_var['lang']['JJ_MOBILE_EMPTY']; ?>");
			$("#mobile_phone").focus();
			return false;
		}
		
		if(!$.checkMobilePhone($("#mobile_phone").val()))
		{
			$.showErr("<?php echo $this->_var['lang']['PLEASE_ENTER_CORRECT_MOBILE']; ?>");
			$("#mobile_phone").focus();
			return false;
		}
	});
	
	$.ajax({
		url: "services/ajax.php?run=autoSendList",
		cache:false,
		success:function(data)
		{
			if (data != ''){
				alert(data);
			}
		}/*,
		error:function(a,b,c)
		{
			alert(a.responseText);
		}*/
	});	
});
</script>
<?php echo $this->fetch('Inc/footer.moban'); ?>