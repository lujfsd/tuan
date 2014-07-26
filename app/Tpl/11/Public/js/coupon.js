$(document).ready(function(){
	$(".coupon_sms").bind("click",function(){
		var id = $(this).attr("rel");
		showCouponSms(id);
	});
});
function showCouponSms(id)
{
	var query = new Object();
	query.id = id;
	query.m = "Youhui";
	query.a = "loadSms";
	$.ajax({
		url: ROOT_PATH+"/index.php",
		data:query,
		cache:false,
		success:function(data)
		{		
			$.ShowDialog({"dialog":"coupon_sms_box"});
			$(".coupon_sms_box").find(".coupon_sms_form").html(data);
			$(".coupon_sms_box").show();
		}
	});

}
function send_sms()
{
	var mobile = $(".coupon_form").find("input[name='mobile']").val();
	if(!$.checkMobilePhone(mobile))
	{
		alert("请输入正确的手机号");
		return;
	}
	var content = $(".coupon_form").find("input[name='sms_content']").val();
	var id = $(".coupon_form").find("input[name='id']").val();
	var query = new Object();
	query.mobile = mobile;
	query.content = content;
	query.m = "Youhui";
	query.a = "sendSms";
	query.id = id;
	$.ajax({
		url: ROOT_PATH+"/index.php",
		data:query,
		cache:false,
		dataType:"json",
		success:function(data)
		{		
			if(data.status==1)
			{
				alert("优惠券短信已发送到您的手机，请稍候查收");
			}
			else if(data.status==2)
			{
				alert("请先登录");
				location.href=data.msg;
			}
			else
			{
				alert(data.msg);
			}
		}
	});
		
}

function sort_by(sort)
{
	var query = new Object();
	query.m = "Youhui";
	query.a = "set_sort";
	query.sort = sort;
	$.ajax({
		url: ROOT_PATH+"/index.php",
		data:query,
		cache:false,
		success:function(data)
		{		
			location.href = location.href;
		}
	});
}
