function selectRegion(obj,lvl)
{
	var id=obj.value;
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=RegionConf&"+VAR_ACTION+"=getChildRegion&pid="+id,
		  cache: false,
		  success:function(data)
		  {
			data = $.evalJSON(data); 
			var origin_html = "<option value='0'>"+NO_SELECT+"</option>";
			switch(lvl)
			{				
				case 1:	
					html = origin_html;
					if(data)
					for(var i=0;i<data.length;i++)
					{
						html+="<option value='"+data[i].id+"'>"+data[i].name+"</option>";
					}
					if(id==0) html = origin_html;  //当未作选择时清空
					$("#region_lv2").html(html);
					$("#region_lv3").html(origin_html);
					$("#region_lv4").html(origin_html);
					break;
				case 2:
					html = origin_html;
					if(data)
					for(var i=0;i<data.length;i++)
					{
						html+="<option value='"+data[i].id+"'>"+data[i].name+"</option>";
					}
					if(id==0) html = origin_html;  //当未作选择时清空
					$("#region_lv3").html(html);
					$("#region_lv4").html(origin_html);
					break;
				case 3:
					html = origin_html;
					if(data)
					for(var i=0;i<data.length;i++)
					{
						html+="<option value='"+data[i].id+"'>"+data[i].name+"</option>";
					}
					if(id==0) html = origin_html;  //当未作选择时清空
					$("#region_lv4").html(html);
					break;
			}
		  }
		}); 
}