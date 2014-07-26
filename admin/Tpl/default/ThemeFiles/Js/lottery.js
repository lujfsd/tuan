jQuery(function(){
	$("#frequency_type").change(function(){
		$("#frequency_unit,#frequency_unit .group,#frequency_unit .bond").hide();
		
		if(this.value > 0)
			$("#frequency_unit").show();
		
		switch(this.value)
		{
			case "1":
			{
				$("#frequency_unit .group").show();
			}
			break;
			
			case "2":
			{
				$("#frequency_unit .bond").show();
			}
			break;
		}
	});
	
	
	$("#lottery_type").change(function(){
		if(this.value == "")
			return false;
			
		var query = new Object();
		query.m="Lottery";
		query.a="getLotterySettings";
		query.type=$("#lottery_type").val();
		query.id = 0;
		
		$.ajax({
			url: APP,
			data:query,
			dataType:"json",
			success:function(data)
			{
				$("#lottery-item-box").html(data.html);
				$("#lottery-tpl").val(data.tpl);
			}
		});
	});
	
	$("#search_goods").click(function(){
		var query = new Object();
		query.m="Lottery";
		query.a="getGoods";
		query.key=$("#goods_key").val();
		
		$.ajax({
			url: APP,
			data:query,
			dataType:"json",
			success:function(data)
			{
				$("#goods_search_list").empty();
				if(data)
				{
					var obj = document.getElementById("goods_search_list");
					
					var option;;
					
					for(var i=0;i<data.length;i++)
					{
						option = new Option(data[i].name, data[i].id);
						obj.options.add(option, i + 1);
					}
				}
			}
		});
	});
	
	$("#goods_search_list").dblclick(function(){
		if($("#goods_search_list option").length > 0)
		{
			$("option:selected",this).appendTo($("#select_goods_list"));
			$("#select_goods_list option:selected").attr("selected",false);
		}
	});
	
	$("#select_goods_list").dblclick(function(){
		if($("#select_goods_list option").length > 0)
		{
			$("#select_goods_list option:selected").remove();
		}
	});
	
	$("#lottery-submit").click(function(){
		var goodsids = new Array();
		
		if($("#name").val() == "")
		{
			alert("请设置抽奖活动名称");
			return false;
		}
		
		if($("#lottery_type").val() == "")
		{
			alert("请选择抽奖类型");
			return false;
		}
		
		if($("#user_group_select").val())
			$("#user_group").val($("#user_group_select").val());
		else
			$("#user_group").val("");
		
		if($("#select_goods_list option").length > 0)
		{
			$("#select_goods_list option").each(function(){
				goodsids.push(this.value);					 
			});
			$("#goods_ids").val(goodsids.join(","));
		}
		else
			$("#goods_ids").val("");
	});
});

function addGoods()
{
	if($("#goods_search_list option").length > 0)
	{
		$("#goods_search_list option:selected").appendTo($("#select_goods_list"));
		$("#select_goods_list option:selected").attr("selected",false);
	}
}

function addGoodsAll()
{
	if($("#goods_search_list option").length > 0)
	{
		$("#goods_search_list option").appendTo($("#select_goods_list"));
		$("#select_goods_list option:selected").attr("selected",false);
	}
}

function resetGoods()
{
	if($("#select_goods_list option").length > 0)
	{
		$("#select_goods_list option:selected").appendTo($("#goods_search_list"));
		$("#goods_search_list option:selected").attr("selected",false);
	}
}

function resetGoodsAll()
{
	if($("#select_goods_list option").length > 0)
	{
		$("#select_goods_list option").appendTo($("#goods_search_list"));
		$("#goods_search_list option:selected").attr("selected",false);
	}
}