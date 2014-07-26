jQuery(function(){
	$("#addVoteGroupBtn").click(function(){
		var title = $.trim($("#voteGroupTitle").val());
		var groupSort = $.trim($("#voteGroupSort").val());
		
		if(title == "")
		{
			alert("请输入调查分组标题");
			return false;
		}
		else
		{
			var bln = false;
			$("#group_id option").each(function(){
				if(this.text == title)
				{
					alert("此调查分组标题已存在");
					this.selected = true;
					bln = true;
					voteGroupChange();	
					return false;
				}
			});
			
			if(bln)
				return false;
		}
		
		$.ajax({
			url: APP+"?"+VAR_MODULE+"=VoteOption&"+VAR_ACTION+"=addVoteGroup",
			cache: false,
			data:{"title":title,"item_id":itemID,"sort":groupSort},
			dataType:"json",
			success:function(data)
			{
				if(data.status == 1)
				{
					$("#group_id").empty();
					var oOptionDefault = new Option(voteGroupDefault,0);
					$("#group_id").get(0).options.add(oOptionDefault);	
					
					for(var i=0;i<data.groups.length;i++)
					{
						var oOption = new Option(data.groups[i].title,data.groups[i].id);
						if(title == data.groups[i].title)
							oOption.selected = true;
						oOption.setAttribute("sort",data.groups[i].sort);
						$("#group_id").get(0).options.add(oOption);	
					}
					
					voteGroupSort = data.sort;
					voteGroupChange();
				}
				else
				{
					alert("添加调查分组失败");
					return false;
				}
			}
		});
	});
	
	$("#updateVoteGroupBtn").click(function(){
		var oldTitle = $("#group_id option:selected").get(0).text;
		var title = $.trim($("#voteGroupTitle").val());
		var groupSort = $.trim($("#voteGroupSort").val());

		if(title == "")
		{
			alert("请输入调查分组标题");
			return false;
		}
		else
		{
			var bln = false;
			$("#group_id option").each(function(){
				if(this.text == title && title != oldTitle)
				{
					alert("此调查分组标题已存在");
					this.selected = true;
					bln = true;
					voteGroupChange();	
					return false;
				}
			});
			
			if(bln)
				return false;
		}
		
		$.ajax({
			url: APP+"?"+VAR_MODULE+"=VoteOption&"+VAR_ACTION+"=updateVoteGroup",
			cache: false,
			data:{"id":$("#group_id").val(),"title":title,"item_id":itemID,"sort":groupSort},
			dataType:"json",
			success:function(data)
			{
				if(data.status == 1)
				{
					alert("成功修改调查分组");
					$("#group_id").empty();
					var oOptionDefault = new Option(voteGroupDefault,0);
					$("#group_id").get(0).options.add(oOptionDefault);
					
					for(var i=0;i<data.groups.length;i++)
					{
						var oOption = new Option(data.groups[i].title,data.groups[i].id);
						if(title == data.groups[i].title)
							oOption.selected = true;
						oOption.setAttribute("sort",data.groups[i].sort);
						$("#group_id").get(0).options.add(oOption);	
					}
					
					voteGroupSort = data.sort;
					voteGroupChange();
				}
			}
		});
	});
	
	$("#removeVoteGroupBtn").click(function(){
		if($("#group_id").val() > 0)
		{
			$.ajax({
				url: APP+"?"+VAR_MODULE+"=VoteOption&"+VAR_ACTION+"=removeVoteGroup",
				cache: false,
				data:{"id":$("#group_id").val(),"item_id":itemID},
				dataType:"json",
				success:function(data)
				{
					if(data.status == 1)
					{
						$("#group_id").empty();
						var oOptionDefault = new Option(voteGroupDefault,0);
						$("#group_id").get(0).options.add(oOptionDefault);	
						
						for(var i=0;i<data.groups.length;i++)
						{
							var oOption = new Option(data.groups[i].title,data.groups[i].id);
							oOption.setAttribute("sort",data.groups[i].sort);
							$("#group_id").get(0).options.add(oOption);	
						}
						
						voteGroupSort = data.sort;
						voteGroupChange();
					}
					else
					{
						alert("删除调查分组失败");
						return false;
					}
				}
			});
		}
	});
	
	$("#group_id").change(function(){
		voteGroupChange();
	});
});

function voteGroupChange()
{
	if($("#group_id").val() > 0)
	{
		$("#updateVoteGroupSpan").show();
		$group = $("#group_id option:selected").get(0);
		$("#voteGroupTitle").val($group.text);
		$("#voteGroupSort").val($group.getAttribute("sort"));
	}
	else
	{
		$("#updateVoteGroupSpan").hide();
		$("#voteGroupTitle").val("");
		$("#voteGroupSort").val(voteGroupSort);
	}
}