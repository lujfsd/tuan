$(document).ready(function(){
	//绑定提交按钮
	$("input[name='adm_name']").focus();
	$(".submit").bind("click",function(){ do_login();});

	
	//绑定提交结束
});

function do_login(){

	$(this).attr("disabled",true);
	//alert('aaa');
	//验证密码
	if($.trim($(".adm_password").val())=='')
	{
		$(".adm_password").val("");
		$(".adm_password").focus();
		$("#login_msg").html(ADM_PASSWORD_EMPTY);
		$("#login_msg").oneTime(2000, function() {
		    $(this).html("");
		    $(".submit").attr("disabled",false);
		    
		 });
		return;
	}	
	
	//表单参数
	param = $("form").serialize();
	url = $("form").attr("action");
	$(".adm_password").attr("disabled",true);
	$.ajax({ 
		url: url, 
		data: param+"&ajax=1",
		dataType: "json",
		success: function(obj){
			if(obj.status)
			{
				location.href = obj.info; 
				/*
				$("#login_msg").html(obj.info);
				$("#login_msg").oneTime(2000, function() {
				    $(this).html("");
				    location.href = location.href;
				 });
				*/
			}
			else
			{
				$("#login_msg").html(obj.info);
				$("#login_msg").oneTime(1000, function() {
				    $(this).html("");
				    $(".submit").attr("disabled",false);
					$(".adm_password").attr("disabled",false);
				 });
			}
	}});
}