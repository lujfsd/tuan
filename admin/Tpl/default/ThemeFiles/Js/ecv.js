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
			
			case "3":
				$("#create_number").show();
			break;
		}
	});
	
	$("#search_user").click(function(){
		var query = new Object();
		query.m="Ecv";
		query.a="getUserList";
		query.usergroup=$("#search_user_group").val();
		query.username = $("#user_name").val();
		
		$.ajax({
			url: APP,
			data:query,
			success:function(data)
			{
				$("#user_search_list").empty();
				userList = $.evalJSON(data);
				if(userList)
				{
					$("#user_search_list").empty();
					var obj = document.getElementById("user_search_list");
					for(var i=0;i<userList.length;i++)
					{
						option = new Option(userList[i].user_name, userList[i].id);
						obj.options.add(option, i);
					}
				}
			}
		});
		
	});
	
	$("#user_search_list").dblclick(function(){
		if($("#user_search_list option").length > 0)
		{
			$("option:selected",this).appendTo($("#user_list"));
			$("#user_list option:selected").attr("selected",false);
		}
	});
	
	$("#user_list").dblclick(function(){
		if($("#user_list option").length > 0)
		{
			$("#user_list option:selected").remove();
		}
	});
	
	$("#submitecv").click(function(){
		if($("#send_type").val() == 3 && ($("#number").val() == '' || parseInt($("#number").val()) < 1))
		{
			$("#number").focus();
			alert("请填写你要发放的数量");
			return false;
		}
		
		var ids = new Array();
		
		if($("#user_list option").length > 0)
		{
			$("#user_list option").each(function(){
				ids.push(this.value);						 
			});
			$("#user_ids").val(ids.join(","));
		}
		else
			$("#user_ids").val("");
			
		if($("#send_type").val() == 2 && $("#user_ids").val() == "")
		{
			$("#user_name").focus();
			alert("请选择你要发放的会员");
			return false;
		}
	});
});

function addUser()
{
	if($("#user_search_list option").length > 0)
	{
		$("#user_search_list option:selected").appendTo($("#user_list"));
		$("#user_list option:selected").attr("selected",false);
	}
}

function addUserAll()
{
	if($("#user_search_list option").length > 0)
	{
		$("#user_search_list option").appendTo($("#user_list"));
		$("#user_list option:selected").attr("selected",false);
	}
}

function resetUser()
{
	if($("#user_list option").length > 0)
	{
		$("#user_list option:selected").appendTo($("#user_search_list"));
		$("#user_search_list option:selected").attr("selected",false);
	}
}

function resetUserAll()
{
	if($("#user_list option").length > 0)
	{
		$("#user_list option").appendTo($("#user_search_list"));
		$("#user_search_list option:selected").attr("selected",false);
	}
}