var AJAX_UPDATE_STATUS = 1;

jQuery(function(){
	
	$("#smsSubscribe").click(function(){
		smsSubscribeDialog();							
	});
	
	$("#unSmsSubscribe").click(function(){
		unSmsSubscribeDialog();							
	});
	
	$("#show_map").click(function(){
		showMapWindow();							
	});
	
	$("#ss1-submit").click(function(){
		var mobile = $.trim($("#sms-subscribe-mobile").val());
		var verify = $.trim($("#sms-subscribe-verify").val());
		
		if(!$.checkMobilePhone(mobile))
		{
			alert(LANG.JS_ELEVEN_MOBILE_EMPTY);
			return false;
		}
		
		if(!$.minLength(verify,4))
		{
			alert(LANG.JS_VERIFY_ERROR);
			return false;
		}
		
		var query = new Object();
		query.m = "Ajax"
		query.a = "smsSubscribe";
		query.mobile = mobile;
		query.verify = verify;
		query.city = cityID;
		
		$.ajax({
			url: ROOT_PATH+"/index.php",
			data:query,
			cache:false,
			dataType:"json",
			success:function(data)
			{			
				if(data.type == 0)
				{
					alert(data.message);
					$("#sms-subscribe-verify-img").attr("src","index.php?m=Ajax&a=verify&rand="+ Math.random());
				}
				else if(data.type == 1)
				{
					$("#smssubscribe-1").hide();
					$("#smssubscribe-2").show();
					$("#smssubscribe-2 .mobile").html(mobile);
					$(".smssubscribe-dialog-box .shadow").height($(".smssubscribe-dialog-box dl").height());
				}
				else if(data.type == 2)
				{
					$("#smssubscribe-1").hide();
					$("#smssubscribe-3").show();
					$(".smssubscribe-dialog-box .shadow").height($(".smssubscribe-dialog-box dl").height());
				}
			}
		});
		
		return false;
	});
	
	$("#ss2-submit").click(function(){
		var mobile = $.trim($("#sms-subscribe-mobile").val());
		var code = $.trim($("#sms-subscribe-code").val());
		
		if(!$.minLength(code,4))
		{
			alert(LANG.JS_CODE_ERROR);
			return false;
		}
		
		var query = new Object();
		query.m = "Ajax"
		query.a = "smsSubscribeCode";
		query.mobile = mobile;
		query.code = code;
		query.city = cityID;
		
		$.ajax({
			url: ROOT_PATH+"/index.php",
			data:query,
			cache:false,
			dataType:"json",
			success:function(data)
			{
				if(data.type == 0)
				{
					alert(data.message);
				}
				else if(data.type == 1)
				{
					$("#smssubscribe-2").hide();
					$("#smssubscribe-3").show();
					$(".smssubscribe-dialog-box .shadow").height($(".smssubscribe-dialog-box dl").height());
				}
			}
		});
		
		return false;
	});
	
	$("#unss1-submit").click(function(){
		var mobile = $.trim($("#unsms-subscribe-mobile").val());
		var verify = $.trim($("#unsms-subscribe-verify").val());
		
		if(!$.checkMobilePhone(mobile))
		{
			alert(LANG.JS_ELEVEN_MOBILE_EMPTY);
			return false;
		}
		
		if(!$.minLength(verify,4))
		{
			alert(LANG.JS_VERIFY_ERROR);
			return false;
		}
		
		var query = new Object();
		query.m = "Ajax";
		query.a = "unSmsSubscribe";
		query.mobile = mobile;
		query.verify = verify;
		query.city = cityID;
		
		$.ajax({
			url: ROOT_PATH+"/index.php",
			data:query,
			cache:false,
			dataType:"json",
			success:function(data)
			{
				if(data.type == 0)
				{
					alert(data.message);
					$("#unsms-subscribe-verify-img").get(0).src = "index.php?m=Ajax&a=verify&rand="+ Math.random();
				}
				else if(data.type == 1)
				{
					$("#unsmssubscribe-1").hide();
					$("#unsmssubscribe-2").show();
					$("#unsmssubscribe-2 .mobile").html(mobile);
					$(".unsmssubscribe-dialog-box .shadow").height($(".unsmssubscribe-dialog-box dl").height());
				}
			}
		});
		
		return false;
	});
	
	$("#unss2-submit").click(function(){
		var mobile = $.trim($("#unsms-subscribe-mobile").val());
		var code = $.trim($("#unsms-subscribe-code").val());
		
		if(!$.minLength(code,4))
		{
			alert(LANG.JS_UNCODE_ERROR);
			return false;
		}
		
		var query = new Object();
		query.m ="Ajax";
		query.a = "unSmsSubscribeCode";
		query.mobile = mobile;
		query.code = code;
		query.city = cityID;
		
		$.ajax({
			url: ROOT_PATH+"/index.php",
			data:query,
			cache:false,
			dataType:"json",
			success:function(data)
			{
				if(data.type == 0)
				{
					alert(data.message);
				}
				else if(data.type == 1)
				{
					$("#unsmssubscribe-2").hide();
					$("#unsmssubscribe-3").show();
					$(".unsmssubscribe-dialog-box .shadow").height($(".unsmssubscribe-dialog-box dl").height());
				}
			}
		});
		
		return false;
	});
});


function smsSubscribeDialog()
{
	$("#smssubscribe-1").show();
	$("#smssubscribe-2").hide();
	$("#smssubscribe-3").hide();
	$(".smssubscribe-dialog-box .txt").val("");
	setTimeout('$("#sms-subscribe-verify-img").get(0).src = "index.php?m=Ajax&a=verify&rand="+ Math.random()',200);
	$.ShowDialog({"dialog":"smssubscribe-dialog-box"});
	$(".smssubscribe-dialog-box .shadow").height($(".smssubscribe-dialog-box dl").height());
}

function unSmsSubscribeDialog()
{
	$("#unsmssubscribe-1").show();
	$("#unsmssubscribe-2").hide();
	$("#unsmssubscribe-3").hide();
	$(".unsmssubscribe-dialog-box .txt").val("");
	setTimeout('$("#unsms-subscribe-verify-img").get(0).src = "index.php?m=Ajax&a=verify&rand="+ Math.random()',200);
	$.ShowDialog({"dialog":"unsmssubscribe-dialog-box"});
	$(".unsmssubscribe-dialog-box .shadow").height($(".unsmssubscribe-dialog-box dl").height());
}

function showMapWindow()
{
	var salerid = $("#saler_id").html();
	$(".saler_map_window .txt").val("");
	$(".saler_map_window").find("iframe").attr("src","index.php?m=Ajax&a=showMap&id="+salerid);
	$(".saler_map_window").fadeIn();
	$.ShowDialog({"dialog":"saler_map_window"},30);
	$(".saler_map_window .shadow").height($(".saler_map_window dl").height());
}

function emailSubscribe()
{
	$("#tip-deal-subscribe-body form,#deal-subscribe-body form").submit(function(){
		var thisform = $(this).parent();
		var email = $.trim($(".f-text",this).val());
		
		if(email.length == 0)
		{
			$.showErr(LANG.JS_EMAIL_ADDRESS_EMPTY);
			$(".f-text",this).focus();
			return false;
		}
		
		if(!$.checkEmail(email))
		{
			$.showErr(LANG.JS_EMAIL_ADDRESS_ERROR_EMPTY);
			$(".f-text",this).focus();
			return false;
		}
		
		$.ajax({
			  url: ROOT_PATH+"/index.php?m=Index&a=malllist&do=subScribe&email="+email+"&cityid="+cityID,
			  cache: false,
			  success:function(data)
			  {
				thisform.html("<p style='padding:7px;'>"+data+"</p>");
			  }
		});
		return false;													  
	});	
}

/*del by chenfq 2010-12-04
var runsend_timer;
function runcheck()
{
	jQuery.ajax({
		url: "ajax.php?run=checkAutoSend",
		success:function(data)
		{
			if(!isNaN(data)&&parseInt(data)>=1)
			{
				runsend();
				runsend_timer = window.setTimeout("runcheck()",5000);
			}
			else
			{
				window.clearTimeout(runsend_timer);
			}
		}
	});
}
function runsend()
{
	$.ajax({
		url: "ajax.php?run=autoSend",
		cache:false,
		success:function(data)
		{

		}
	});
}
$(document).ready(function(){
	$.ajax({
		url: "ajax.php?run=autoSend",
		cache:false,
		success:function(data)
		{
			runcheck();
		}
	});	
});
*/
