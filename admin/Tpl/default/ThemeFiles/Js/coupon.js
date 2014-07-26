$(document).ready(function(){
	load_supplier_info();
	load_city_info();
	$("select[name='supplier_id']").bind("change",function(){ load_supplier_info();});
	$("select[name='city_id']").bind("change",function(){ load_city_info();});
});

function load_supplier_info()
{
	var supplier_id = $("select[name='supplier_id']").val();
	var coupon_id = $("input[name='id']").val();
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=Coupon&"+VAR_ACTION+"=supplier_info&id="+supplier_id+"&coupon_id="+coupon_id,
		  cache: false,
		  success:function(data)
		  {

				$("#supplier_row").html(data);
		  }
		}); 
}


function load_city_info()
{
	var city_id = $("select[name='city_id']").val();
	var coupon_id = $("input[name='id']").val();
	$.ajax({
		  url: APP+"?"+VAR_MODULE+"=Coupon&"+VAR_ACTION+"=city_info&id="+city_id+"&coupon_id="+coupon_id,
		  cache: false,
		  success:function(data)
		  {
			$("#city_row").html(data);

		  }
		}); 
}