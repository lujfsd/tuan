jQuery(function(){
	$("#send_type").change(function(){
		$("#user_group,#usertr,#create_number").hide();
		switch(this.value)
		{
			case "1":
				$("#user_group").show();
			break;
			
			case "2":
				$("#usertr").show();
			break;
		}
	});
	
	$("#search_user").click(function(){
		var query = new Object();
		query.m="SmsSend";
		query.a="getUserList";
		query.usergroup=$("#search_user_group").val();
		query.username = $("#user_name").val();
		
		$.ajax({
			url: APP,
			data:query,
			success:function(data)
			{
				$("#mobile_search_list").empty();
				userList = $.evalJSON(data);
				if(userList)
				{
					$("#mobile_search_list").empty();
					var obj = document.getElementById("mobile_search_list");
					for(var i=0;i<userList.length;i++)
					{
						option = new Option(userList[i].mobile_phone+"("+userList[i].user_name+")", userList[i].id);
						obj.options.add(option, i);
					}
				}
			}
		});
	});
	
	$("#mobile_search_list").dblclick(function(){
		if($("#mobile_search_list option").length > 0)
		{
			$("option:selected",this).appendTo($("#custom_user_list"));
			$("#custom_user_list option:selected").attr("selected",false);
		}
	});
	
	$("#custom_user_list").dblclick(function(){
		if($("#custom_user_list option").length > 0)
		{
			$("#custom_user_list option:selected").remove();
		}
	});
	
	$("#type").change(function(){
		$("#type_rec_id").hide();
		$("#sendContent").show();
		$("#sent_type_row").show();
		if($("select#send_type").val() == '2')
			$("#usertr").show();
		else
			$("#usertr").hide();
		if(this.value > 1)
		{
			$("#type_rec_id").show();
			$("#sendContent").hide();
			/*$("#sent_type_row").hide();*/
		}

	});
	
	$("#submitsms").click(function(){
		var ids = new Array();
		
		if($("#type").val() > 1)
		{
			if($("#rec_id").val() == 0)
			{
				$("#rec_id").focus();
				alert("请选择你要发送的商品编号");
				return false;
			}
			
			if($.trim($("#send_content").val()) == "")
				$("#send_content").val("商品通知短信，发送时根据模板自动生成内容");
		}
		else
		{
			if($.trim($("#send_content").val()) == "")
			{
				$("#send_content").focus();
				alert("请填写你要发送的短信内容");
				return false;
			}
			$("#rec_id").val("0");
		}
		
		if($("#custom_user_list option").length > 0)
		{
			$("#custom_user_list option").each(function(){
				ids.push(this.value);					 
			});
			$("#custom_users").val(ids.join(","));
		}
		else
			$("#custom_users").val("");
			
		if($("#send_type").val() == 1)
			$("#custom_users").val("");
			
		if($("#send_type").val() == 2 && $("#custom_users").val() == "" && $("textarea[name='custom_mobiles']").val() == "")
		{
			$("#user_name").focus();
			alert("请选择你要发送的会员，或填写自定义发送号码");
			return false;
		}
	});
	
	$("#send_content").keyup(function(){
		var content=this.value;
		var length = content.length;
		var smscount = Math.ceil(length / 70);
		$("#smslength").html(length);
		$("#smscount").html(smscount);
	}).blur(function(){
		var content=this.value;
		var length = content.length;
		var smscount = Math.ceil(length / 70);
		$("#smslength").html(length);
		$("#smscount").html(smscount);
	});
	
	var smscontent=$("#send_content").val();
	var smslength = smscontent.length;
	var smscount = Math.ceil(smslength / 70);
	$("#smslength").html(smslength);
	$("#smscount").html(smscount);
});

function addMobile()
{
	if($("#mobile_search_list option").length > 0)
	{
		$("#mobile_search_list option:selected").appendTo($("#custom_user_list"));
		$("#custom_user_list option:selected").attr("selected",false);
	}
}

function addMobileAll()
{
	if($("#mobile_search_list option").length > 0)
	{
		$("#mobile_search_list option").appendTo($("#custom_user_list"));
		$("#custom_user_list option:selected").attr("selected",false);
	}
}

function resetMobile()
{
	if($("#custom_user_list option").length > 0)
	{
		$("#custom_user_list option:selected").appendTo($("#mobile_search_list"));
		$("#mobile_search_list option:selected").attr("selected",false);
	}
}

function resetMobileAll()
{
	if($("#custom_user_list option").length > 0)
	{
		$("#custom_user_list option").appendTo($("#mobile_search_list"));
		$("#mobile_search_list option:selected").attr("selected",false);
	}
}