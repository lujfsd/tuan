var SpecGalleryList;

function refresh_img_list_box()
{
	var curr_id = $("#gallery_id").val();
	var thumb_list = document.getElementById("gallery_list").getElementsByTagName("IMG");
	var input_list = document.getElementById("gallery_list").getElementsByTagName("INPUT");
	for(var i=0;i<input_list.length;i++)
	{
		if(input_list[i].value == curr_id)
		{
			thumb_list[i].className="selectborder";
		}
		else
		{
			thumb_list[i].className = "";
		}
	}
}

function setgallery(id)
{		
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=setGallery&id="+id,
		  cache: false,
		  success:function(data)
		  {
			data = $.evalJSON(data); 

			if(data.info)
			{
				alert(data.info);
			}
			else
			{
				$("#gallery_id").val(data.id);
				$("#goods_img_box").attr("src",ROOT_PATH+data.big_img);
				refresh_img_list_box();						
			}
		  }
		}); 
}

function delgallery(id,obj)
{
	if(confirm(CONFIRM_DELETE_IMAGE))
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=delGallery&id="+id,
		  cache: false,
		  success:function(data)
		  {

			
			if(data!='')
			{
				data = $.evalJSON(data); 
				alert(data.info);
			}
			else
			{
				if($("#gallery_id").val()==id)
				{
					$("#gallery_id").val(0);
					$("#goods_img_box").attr("src",PUBLIC+"/Images/nopic.gif");
				}
				obj.parentNode.parentNode.removeChild(obj.parentNode);
			}
		  }
		}); 
}


function setStockVal(obj)
{
	if(obj.checked)
		$(obj).next("input").val(1);
	else
		$(obj).next("input").val(0);	
}

function closeThisSpan(obj)
{
	$(obj.parentNode).fadeOut(200);			
}

function sw_define_img(tag)
{
	if(tag==1)
	{
		$("#define_small_img").show();
	}
	else
	{
		$("#define_small_img").hide();
	}
}

function addReviewsList()
{
	$("#reviewsLis").append($("#goodsReviewsListHtml").html());
}

function delReviewsList(obj,id)
{
	if(id > 0)
	{
		$.ajax({
			  url: APP+"?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=delReviews&id="+id,
			  cache: false,
			  success:function(data)
			  {							
					if(data)
						$(obj).parent().parent().remove();
			  }
		});	
	}
	else
		$(obj).parent().parent().remove();
}

function showDefault(obj)
{	
	saveSpecGallerys();
	var is_default = obj.checked?'1':'0';
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=GoodsSpec&"+VAR_ACTION+"=showDefault&is_default="+is_default+"&goods_id="+goods_id,
		  cache: false,
		  success:function(data)
		  {							
				data = data.split("|");
				if(data.length>0)
				{
					resetUploader(1);
					$("#spec_type_1").html(data[0]);	
				}
				if(data.length>1)
				{
					resetUploader(2);
					$("#spec_type_2").html(data[1]);
				}
				getSpecGallerys();
		  }
	});	
}

function getAttrList(obj)
{
	var type_id = obj.value;
	var goods_id = document.getElementById("goods_id")?document.getElementById("goods_id").value:0;	
	
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=getTypeAttr&type_id="+type_id+"&goods_id="+goods_id,
		  cache: false,
		  success:function(data)
		  {		
			  data = $.evalJSON(data); 					  
			  var html = "";
			  var lang_ids_array = lang_ids.split(',');
			  var lang_names_array = lang_names.split(',');
			  for(var i=0;i<data.length;i++)
			  {
				  if(data[i].row_count==0)
				  {
					 m=0;
					 html+= "<div style='padding-bottom:5px;'>"+data[i]['name_'+default_lang_id]+":";
					  if(data[i]['input_type']=='0')
					  {
						  //列表
						  for(var k=0;k<lang_ids_array.length;k++)
						  {
							  html+="<select name='attr_value["+data[i]['id']+"]["+lang_ids_array[k]+"][]'>";

							  for(var j=0;j<data[i]['attr_value_'+lang_ids_array[k]].length;j++)
							  {
								  html+="<option value='"+data[i]['attr_value_'+lang_ids_array[k]][j]+"'";
								  var c_val ='';
								  if(data[i]['value_'+lang_ids_array[k]]!=undefined)
								  {
									  c_val = data[i]['value_'+lang_ids_array[k]][m];
								  }
								 
								  if(c_val==data[i]['attr_value_'+lang_ids_array[k]][j])
								  {
									  html+=" selected='selected' ";
								  }
								  html+=" >"+data[i]['attr_value_'+lang_ids_array[k]][j]+"</option>";
							  }
							  html+="</select>&nbsp;("+lang_names_array[k]+")&nbsp;";
						  }
				
					  }
					  else
					  {
						  for(var k=0;k<lang_ids_array.length;k++)
						  {
							  var c_val ='';
							  if(data[i]['value_'+lang_ids_array[k]]!=undefined)
							  {
								  c_val = data[i]['value_'+lang_ids_array[k]][m];
							  }
							  html+="<input type='text' value='"+c_val+"' name='attr_value["+data[i]['id']+"]["+lang_ids_array[k]+"][]' />&nbsp;("+lang_names_array[k]+")&nbsp;";
						  }
						  
					  }
					  
					  
					  //if(data[i]['attr_type']==1)
					  if(true)
					  {
						  var p_val ='';
						  if(data[i]['price']!=undefined)
						  {
							  p_val = data[i]['price'][m];
						  }
						  html+=ATTR_PRICE+"：<input type='text' name='attr_price["+data[i]['id']+"][]' value='"+p_val+"' />";
						  /*
						  var s_val ='';
						  if(data[i]['stock']!=undefined)
						  {
							  s_val = data[i]['stock'][m];
						  }
						  if(s_val&&s_val==1)
							  html+=ATTR_STOCK+"：<input type='checkbox' value='1' checked='checked' onchange='setStockVal(this);' /><input type='hidden' value='1' name='attr_stock["+data[i]['id']+"][]'  />";
						  else
							  html+=ATTR_STOCK+"：<input type='checkbox' value='1' onchange='setStockVal(this);'  /> <input type='hidden' value='0' name='attr_stock["+data[i]['id']+"][]'  />";
						  */
						  if(m==0)
						  html+="[<a href='javascript:;' onclick='addAttrInputRow(this);'>+</a>]";
						  else
						  html+="[<a href='javascript:;' onclick='delAttrInputRow(this);'>-</a>]";
					  }
					  
					  html+="</div>";
				  }
				  else
				  {
					  for(var m=0;m<data[i].row_count;m++)
					  {
					  html+= "<div style='padding-bottom:5px;'>"+data[i]['name_'+default_lang_id]+":";
					  if(data[i]['input_type']=='0')
					  {
						  //列表
						  for(var k=0;k<lang_ids_array.length;k++)
						  {
							  html+="<select name='attr_value["+data[i]['id']+"]["+lang_ids_array[k]+"][]'>";
							  for(var j=0;j<data[i]['attr_value_'+lang_ids_array[k]].length;j++)
							  {
								  html+="<option value='"+data[i]['attr_value_'+lang_ids_array[k]][j]+"'";
								  var c_val ='';
								  if(data[i]['value_'+lang_ids_array[k]]!=undefined)
								  {
									  c_val = data[i]['value_'+lang_ids_array[k]][m];
								  }
								  
								  if(c_val==data[i]['attr_value_'+lang_ids_array[k]][j])
								  {
									  html+=" selected='selected' ";
								  }
								  html+=" >"+data[i]['attr_value_'+lang_ids_array[k]][j]+"</option>";
							  }
							  html+="</select>&nbsp;("+lang_names_array[k]+")&nbsp;";
						  }
				
					  }
					  else
					  {
						  for(var k=0;k<lang_ids_array.length;k++)
						  {
							  var c_val ='';
							  if(data[i]['value_'+lang_ids_array[k]]!=undefined)
							  {
								  c_val = data[i]['value_'+lang_ids_array[k]][m];
							  }
							  html+="<input type='text' value='"+c_val+"' name='attr_value["+data[i]['id']+"]["+lang_ids_array[k]+"][]' />&nbsp;("+lang_names_array[k]+")&nbsp;";
						  }
						  
					  }
					  
					  
					  //if(data[i]['attr_type']==1)
					  if(true)
					  {
						  var p_val ='';
						  if(data[i]['price']!=undefined)
						  {
							  p_val = data[i]['price'][m];
						  }
						  html+= ATTR_PRICE+"：<input type='text' name='attr_price["+data[i]['id']+"][]' value='"+p_val+"' />";
						  /*
						  var s_val ='';
						  if(data[i]['stock']!=undefined)
						  {
							  s_val = data[i]['stock'][m];
						  }
						  if(s_val&&s_val==1)
							  html+=ATTR_STOCK+"：<input type='checkbox' value='1' checked='checked' onchange='setStockVal(this);' /><input type='hidden' value='1' name='attr_stock["+data[i]['id']+"][]'  />";
						  else
							  html+=ATTR_STOCK+"：<input type='checkbox' value='1' onchange='setStockVal(this);'  /> <input type='hidden' value='0' name='attr_stock["+data[i]['id']+"][]'  />";
						  */
						  if(m==0)
						  html+="[<a href='javascript:;' onclick='addAttrInputRow(this);'>+</a>]";
						  else
						  html+="[<a href='javascript:;' onclick='delAttrInputRow(this);'>-</a>]";
					  }
					  else
					  {
						  if(data[i]['input_type']==1)
						  {
							  html+="("+ATTR_TIPS+")";
						  }
					  }
					  
					  html+="</div>";
					  } //end row_count loop
				  } // end if count
			  }
			  $("#attr_list").html(html);
		  }
		}); 
}

function addAttrInputRow(obj)
{
	var contentbox = obj.parentNode;
	var innerhtml = contentbox.innerHTML;
	innerhtml = innerhtml.replace(/addAttrInputRow/g,"delAttrInputRow");
	innerhtml = innerhtml.replace(/\+/g,"-");	
	var html = "<div style='padding-bottom:5px;'>"+innerhtml+"</div>";
	$(contentbox).after(html);
}

function delAttrInputRow(obj)
{
	var contentbox = obj.parentNode;
	$(contentbox).remove();
}

jQuery(function($){
	$("#form").submit(function(){
		$(".select_goods_spec:checked").each(function(i){
			var parent = $(this).parent();
			var galleryArr = new Array();
			$("ul.sortable li",parent).each(function(j){
				galleryArr.push(this.getAttribute("galleryID"));
			});
			$("input.spec_gallery",parent).val(galleryArr.join(","));
		});
	});
	
	$("#send_sms_td").click(function(){
		if($("#send_sms_td input:checked").val() == 1)
			$("#sms_send_time_tr").show();
		else
			$("#sms_send_time_tr").hide();
	});
	
	$("#goodsSmsInfo").click(function(){
		getGoodsSmsInfo();			   
	});
	
	if($("select[name='city_id']").val()!="0"){
		load_quan_list();
	}
	$("select[name='city_id']").bind("change",function(){
		load_quan_list();
	});
});

function getGoodsSmsInfo()
{
	var query = new Object();
	query.name = $(".goods_name").val();
	query.short_name = $(".goods_short_name").val();
	query.begin_time = $("#promote_begin_time").val();
	
	if(query.name == "")
	{
		alert("请填写商品名称");
		return false;
	}
	
	if(query.begin_time == "")
	{
		alert("请选择团购开始时间");
		return false;
	}
		
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=Goods&"+VAR_ACTION+"=getGoodsSmsContent",
		  cache: false,
		  data:query,
		  success:function(data)
		  {
			  var str = "短信内容："+data;
			  str +="\r\n内容字数："+data.length;
			  str +="\r\n内容条数："+Math.ceil(data.length/70);
			  str +="\r\n（提示：你可以设置“商品缩略名称”或修改“商品短信通知模板，调整短信字数”）";
			  alert(str);
		  }
	});	
		
}

function change_referrals(obj)
{
	if(obj.value==0)
	{
		$("#referrals").removeClass("disabledbox");
		$("#referrals").attr("disabled",false);
	}
	else
	{
		$("#referrals").addClass("disabledbox");
		$("#referrals").attr("disabled",true);
	}
}
function sw_freedelivery_amount(obj){
	if(obj.value==0)
	{
		document.getElementById("free_delivery_amount_label").style.display="none";
		$("#free_delivery_amount_input").val("");
	}
	if(obj.value==1)
	{
		document.getElementById("free_delivery_amount_label").style.display="";
	}
}
function sw_goods_type(obj)
{
	if($(obj).val() == 0)
	{
		//团购券
		$("#free_delivery_tr").hide();
		$("#allow_combine_delivery").hide();
		//$("#goods_weight_tr").hide();
		//$("#goods_weight_unit_tr").hide();
		$("#goods_weight_tr").show();
		$("#goods_weight_unit_tr").show();
		$("#gb_expire_tr").show();
		$("#ding_price_tr").hide();
		$("#allow_sms_tr").show();
	}
	if($(obj).val() == 1)
	{
		//实体
		$("#free_delivery_tr").show();
		$("#allow_combine_delivery").show();
		$("#goods_weight_tr").show();
		$("#goods_weight_unit_tr").show();
		$("#gb_expire_tr").hide();
		$("#ding_price_tr").hide();
		$("#allow_sms_tr").hide();
	}
	if($(obj).val() == 2)
	{
		//线下
		$("#free_delivery_tr").hide();
		$("#allow_combine_delivery").hide();
		$("#goods_weight_tr").hide();
		$("#goods_weight_unit_tr").hide();
		$("#gb_expire_tr").show();
		$("#ding_price_tr").show();
		$("#allow_sms_tr").show();
	}
	if($(obj).val() == 3)
	{
		//实体
		$("#free_delivery_tr").show();
		$("#allow_combine_delivery").show();
		$("#goods_weight_tr").show();
		$("#goods_weight_unit_tr").show();
		$("#gb_expire_tr").show();
		$("#ding_price_tr").hide();
		$("#allow_sms_tr").hide();
	}	
	
}
$(document).ready(function(){
	$("select[name='score_goods']").bind("change",function(){change_goods_score();});
	change_goods_score();
	sw_goods_type($("select[name='type_id']"));
});
function change_goods_score()
{
	var is_score = $("select[name='score_goods']").val();
	if(is_score==0)
	{
		$(".score_row").hide();
		$(".price_row").show();
		$("#ding_price_tr").hide();
	}
	else
	{
		$(".score_row").show();
		$(".price_row").hide();
		$("input[name='earnest_money']").val("0");
		$("input[name='shop_price']").val("0");
	}
}
function search_suppliers()
{	
	var query = new Object();
	query.suppliers_name = $("#suppliers_name").val();
	if (query.suppliers_name == "") 
	{
		alert("请填写商家名称");
		return false;
	}		
	$.ajax({
	  url: APP+"?"+VAR_MODULE+"=Public&"+VAR_ACTION+"=getSuppliersName",
	  data:query,
	  dataType:"json",
	  success:function(result)
	  {	 
	  	 if(result!= null)
		 {
		 	var selecthtml="";
			 for (i = 0; i < result.length; i++)
			 {
			 	selecthtml+="<option value='" + result[i].id + "'>" + result[i].name + "</option>";
			 }		 
			 $("#shangjia_list").html(selecthtml);
		 }
		 else
		 {
		 	alert("没有该商家！");
			return false;
		 }
	  }
	});	
}

function load_quan_list()
{
	var city_id = $("select[name='city_id']").val();
	if($("input[name='id']").length>0)
		var id = $("input[name='id']").val();
	else
		id = 0;
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=Public&"+VAR_ACTION+"=quan_list&city_id="+city_id+"&id="+id,
		  cache: false,
		  success:function(data)
		  {
			$("#quan_list").html(data);
		  }
		}); 
}