$(document).ready(function(){
	 $("select[name='city_id']").bind("change",function(){
		 load_region();
	 });
	load_region();
});

function load_region()
{
	var city_id = $("select[name='city_id']").val();
	var coupon_id = $("input[name='id']").val();
	
	var query = new Object();
	query.m = "Suppliers";
	query.a = "load_region";
	query.city_id = city_id;
	query.coupon_id = coupon_id;
	
	$.ajax({
		url: ROOT_PATH+"/index.php",
		data:query,
		cache:false,
		
		success:function(data)
		{			
			$("#region_list").html(data);
		}
	});

	
}