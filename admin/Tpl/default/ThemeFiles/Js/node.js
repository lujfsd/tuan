function getNodeList(obj)
{
	var auth_type = obj.value;
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=RoleNode&"+VAR_ACTION+"=getNodeList&auth_type="+auth_type,
		  cache: false,
		  success:function(data)
		  {
			data = $.evalJSON(data); 
			var html = "";
			for(var i=0;i<data.length;i++)
			{
				switch(auth_type)
				{
					case '1':
						html+="<option value='"+data[i].id+"'>"+data[i].module_name+"("+data[i].module+")</option>";
						break;
					case '2':
						html+="<option value='"+data[i].id+"'>"+data[i].action_name+"("+data[i].action+")</option>";
						break;
					case '0':
						html+="<option value='"+data[i].id+"'>"+data[i].module_name+"("+data[i].module+")"+data[i].action_name+"("+data[i].action+")</option>";
						break;
					
				}
				
			}
			$("#node_id").html(html);
		  }
		}); 
}