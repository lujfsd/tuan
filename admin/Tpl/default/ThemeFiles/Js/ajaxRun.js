jQuery(function(){
	if (runtype == 'incOrderIncharge'){
		$.ajax({
			url: "services/ajax.php?run=incOrderIncharge&order_incharge_id=" + order_incharge_id,
			cache:false,
			success:function(data)
			{
				//alert('aa');
			}
		});
	}else if(runtype == 'sendUserInchargeSms'){
		//alert("services/ajax.php?run=sendUserInchargeSms&id=" + id);
		$.ajax({
			url: "services/ajax.php?run=sendUserInchargeSms&id=" + id,
			cache:false,
			success:function(data)
			{
				//alert('aa');
			}
		});		
	}else if(runtype == 'ajaxSendRun'){
		//alert("services/admin.php?act=ajaxSendRun&user_id=" + id);
		$.ajax({
			url: "services/admin.php?act=ajaxSendRun&user_id=" + user_id,
			cache:false,
			success:function(data)
			{
				//alert('aa');
			}
		});			
	}else if(runtype == 'ajaxGoodsBondRun'){
		//alert("services/admin.php?act=ajaxSendRun&user_id=" + id);
		$.ajax({
			url: "services/admin.php?act=ajaxGoodsBondRun&goods_id=" + goods_id,
			cache:false,
			success:function(data)
			{
				//alert('aa');
			}
		});			
	}	else{
		$.ajax({
			url: "services/ajax.php?run=" + runtype,
			cache:false,
			success:function(data)
			{
				//alert('aa');
			}
		});		
	}
});	