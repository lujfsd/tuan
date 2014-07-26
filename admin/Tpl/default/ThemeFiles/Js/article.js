function loadCateType(obj)
{
	var id = obj.value;
	if(parseInt(id)>0)
	{
		$.ajax({
			  url: APP+"?"+VAR_MODULE+"=ArticleCate&"+VAR_ACTION+"=loadCateType&cate_id="+id,
			  cache: false,
			  success:function(data)
			  {
				  $("#type").val(data);
				  $("#type").attr("disabled",true);
			  }
			}); 
	}
}

function addUploadList()
{
	var html = "<div style='padding-bottom:5px;'><input type='file' name='attachment[]' class='bLeft' />&nbsp;"+DIY_URL+"：<input type='text' class='bLeft' name='attachment[]' class='bLeft' />&nbsp;";
	lang_ids_arr = lang_ids.split(",");
	lang_names_arr = lang_names.split(",");
	for(var i=0;i<lang_ids_arr.length;i++)
	{
		html+="&nbsp;<input type='text' name='attachment_name_"+lang_ids_arr[i]+"[]' class='bLeft' /> ("+lang_names_arr[i]+")"; 
	}
	html+="&nbsp;[<a href='javascript:;' onclick='removeUploadList(this);'>-</a>]</div>";
	$("#uploadList").append(html);
}

function removeUploadList(obj)
{
	obj.parentNode.style.display ="none";
	obj.parentNode.innerHTML = "";	
}
function removeUploadList_ajax(obj)
{
	if(confirm(CONFIRM_DELETE))
	{
		var id=$(obj.parentNode).find(".uploadids").val();
		var article_id = $("#article_id").val();
		if(id)
		{
			$.ajax({
				  url: APP+"?"+VAR_MODULE+"=Article&"+VAR_ACTION+"=delAttachment&attachment_id="+id+"&article_id="+article_id,
				  cache: false,
				  success:function(data)
				  {
						obj.parentNode.style.display ="none";
						obj.parentNode.innerHTML = "";	
				  }
				});
		}
	}
}