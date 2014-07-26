function open_protect(obj)
{
	document.getElementById("protect_radio").value="";
	document.getElementById("protect_price").value="";
	document.getElementById("protect_radio").disabled = (!obj.checked);
	document.getElementById("protect_price").disabled = (!obj.checked);
}

function addDeliveryRegion()
{
	var d = new Date();
	var rand_id = d.getTime();
	var html="<div style='margin-bottom:5px;'><input type='hidden' name='ids[]' />"+FIRST_PRICE+"："+ BASE_CURRENCY_UNIT +" <input type='text' class='bLeft short_size' name='region_first_price[]' /> ";
	html+=CONTINUE_PRICE+"："+ BASE_CURRENCY_UNIT +" <input type='text' class='bLeft short_size' name='region_continue_price[]' /> ";
	html+=ALLOW_COD+"：<input type='checkbox' value='1' onclick='sw_allow_cod(this);' /> <input type='hidden' class='region_allow_cod' name='region_allow_cod[]' />";
	html+=SELECT_REGIONS+"：<a href='javascript:;' onclick='openJqModal(this);' id='a_"+rand_id+"' >"+SELECT+"</a> " +
			"<input type='text' class='bLeft region_names' name='region_names[]' onclick='openJqModal(this);' id='i_"+rand_id+"' /> "+
			"[ <a href='javascript:;' onclick='delDeliveryRegion(this);' >-</a> ]<input type='hidden' name='region_ids[]' class='region_ids' /></div>";

	$("#region_list").append(html);
}
function delDeliveryRegion(obj,id)
{
	if(id)
	{
		if(confirm(CONFIRM_DELETE_REGION))
		{
			$.ajax({
				  url: APP+"?"+VAR_MODULE+"=Delivery&"+VAR_ACTION+"=delRegionItem&id="+id,
				  cache: false,
				  success:function(data)
				  {
					 if(data==1)
					 {
						 $(obj.parentNode).replaceWith("");
					 }
					 else
					 {
						 alert(DELETE_FAILED);
					 }
				  }
				});
		}
	}
	else
	{
		$(obj.parentNode).replaceWith("");
	}

}
function openJqModal(obj)
{	
	var id = obj.id;
	id = id.split("_");
	id = id[1];
	initJqModal();
	$("a.selectbox_trigger").attr("id","t_"+id);
	$("a.selectbox_trigger").click();
	
}
function initJqModal()
{
	var t = $('#selectbox div.jqmdMSG');	  
	$('#selectbox').jqm({
	    trigger: 'a.selectbox_trigger',
	    ajax: SELECT_REGION_URL, /* Extract ajax URL from the 'href' attribute of triggering element */
	    target: t,
	    modal: true, /* FORCE FOCUS */
	    onHide: function(h) { 
		  if($("#is_close").val()==1)
		  {
			  var eventobj_id = $("a.selectbox_trigger").attr("id").split("_");
			  eventobj_id = eventobj_id[1];
			  var eventobj = document.getElementById("a_"+eventobj_id);			  
			  var checkbox = $("#tree").find(".region_ids");
			  
			  var names = '';
			  var ids = '';
			  for(var i=0;i<checkbox.length;i++)
			  {
				  if(checkbox[i].checked)
				  {					  
					  ids+=checkbox[i].value+",";
					  namebox = $(checkbox[i].parentNode).find(".region_names");
					  names+=namebox[0].innerHTML+",";
				  }
				  
			  }
			  if(names!='')names=names.substr(0,names.length-1);
			  if(ids!='')ids=ids.substr(0,ids.length-1);
			  
			  $(eventobj.parentNode).find(".region_names").val(names);
			  $(eventobj.parentNode).find(".region_ids").val(ids);
		  }
		  else
		  {
			  $("#is_close").val(1);
		  }
		  h.w.hide(); // hide window	 
	      h.o.remove(); // remove overlay
	           
	    },	    
	    onShow:function(h){
	    	h.w.show();
	    	$("#loader_region").show();
	    },
	    onLoad:function(h){
	    			  
//	    	$("#tree").treeview({
//	    		collapsed: true,
//	    		persist: "location"
//	    	});

	    	$("#loader_region").hide();
	    },
	    overlay: 10});		
}

function sw_allow_cod(obj)
{
	if(obj.checked)
	{
		$(obj.parentNode).find(".region_allow_cod").val("1");
	}
	else
	{
		$(obj.parentNode).find(".region_allow_cod").val("0");
	}
}

function resetRegionTree()
{
	$("#tree").find(".region_ids").attr("checked",false);
	return false;
}
function loadRegion(id,obj)
{
	if(obj.parentNode.className == 'open')
	{
		obj.parentNode.className = 'close';
		$(obj.parentNode).find("ul").hide();		
	}
	else
	{
		$.ajax({
			  url: APP+"?"+VAR_MODULE+"=RegionConf&"+VAR_ACTION+"=getChildRegion&pid="+id,
			  cache: false,
			  success:function(data)
			  {			
				if(data!='')
				{
					 data = $.evalJSON(data);
					 var html = '';
					 for(var i=0;i<data.length;i++)
					 {
						 html+="<li class='close'><input type='checkbox' class='region_ids' value='"+data[i].id+"' />&nbsp;&nbsp;<span class='region_names' onclick='loadRegion("+data[i].id+",this);'>"+data[i].name+"</span><ul></ul></li>";
					 }
					 if(html!='')
					 html+="<ul></ul>";
					 obj.parentNode.className = 'open';
					 if($.trim($(obj.parentNode).find("ul").html())=='')
					 {
						 $(obj.parentNode).find("ul").html(html);
						 $(obj.parentNode).find("ul").show();
					 }
					 else
					 {
						 $(obj.parentNode).find("ul").show();
					 }
				}
				else
				{
					obj.parentNode.className = 'open';
				}
				 
			  }
			});
	}
}

