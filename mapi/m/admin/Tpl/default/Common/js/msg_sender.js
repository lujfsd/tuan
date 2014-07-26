function apns_sender_fun()
{
	window.clearInterval(apns_sender);
	$.ajax({
		url: "msg_send.php?act=apns_list",
		success:function(data)
		{
			if(!isNaN(data)&&parseInt(data)>=1)
			{						
				$("#apns_msg").show();			
			}
			else
			{
				$("#apns_msg").hide();
			}
			apns_sender = window.setInterval("apns_sender_fun()",send_span);
		}
	});
}

var apns_sender = window.setInterval("apns_sender_fun()",send_span);	