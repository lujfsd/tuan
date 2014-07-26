function addflash(obj)
{
			var contentbox = obj.parentNode;
			var innerhtml = $("#flashlistitem").html();
			$(contentbox).after(innerhtml);
}

function delflash(obj)
{
			var contentbox = obj.parentNode;
			var id = $(contentbox).find(".flash_id").val();
			if(id==0)
			{
				$(contentbox).remove(".flash_row");
			}
			else
			{
				if(confirm(CONFIRM_DELETE))
				$.ajax({
					  url: APP+"?"+VAR_MODULE+"=Flash&"+VAR_ACTION+"=delFlash&id="+id,
					  cache: false,
					  success:function(data)
					  {
						$(contentbox).remove(".flash_row");
					  }
					}); 
			}
}