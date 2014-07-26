var red_point = ROOT_PATH +"/global/red_point.png";
var marker=[];var infoWindow=[];
var map = null;

/* 添加商户标注对象
包括属性：
1. 图标 icon, 绝对地址
2. 坐标GLatLng对象
3. 分店ID depart_id
*/
	function createSupplier(lat,lng,supplier_title,icon,depart_id,zoom) {
		var myIcon_curron = new BMap.Icon(icon, new BMap.Size(28, 38));
		var point = new BMap.Point(lat,lng);
		marker[depart_id] = new BMap.Marker(point,{icon:myIcon_curron});
		map.centerAndZoom(point, zoom);
		map.enableScrollWheelZoom();  
		map.addOverlay(marker[depart_id]);
		marker[depart_id].addEventListener("click", function(){ 
			var obj = this;
			//开始获取指定优惠券的信息
			var q = new Object();
			q.m = "Youhui";
			q.a = "get_coupon_info";
			q.id = depart_id;
			$.ajax({
				url: ROOT_PATH+"/index.php",
				data:q,
				cache:false,
				success:function(data)
				{	
					var sContent ="<div class='mapshow' id='coupon_info'>"+data+"</div>" ;
					infoWindow[depart_id] = new BMap.InfoWindow(sContent);  /* 创建信息窗口对象*/ 	
					obj.openInfoWindow(infoWindow[depart_id]);
				}
			});
		}); 
		
	}
	function close_coupon()
	{
		document.getElementById("coupon_info").innerHTML  = "";
	}
	

	function load_region(id,obj)
	{
		$(obj).parent().parent().find("a").removeClass("actt");
		$(obj).addClass("actt");
		var q = new Object();
		q.m = "Youhui";
		q.a = "set_region_cookie";
		q.region_id = id;
		$.ajax({
			url: ROOT_PATH+"/index.php",
			data:q,
			cache:false,
			success:function(data)
			{		
				initialize();
			}
		});
	}
	
	function load_cate(id,obj)
	{
		$(obj).parent().parent().find("a").removeClass("actt");
		$(obj).addClass("actt");
		var q = new Object();
		q.m = "Youhui";
		q.a = "set_cate_cookie";
		q.cate_id = id;
		$.ajax({
			url: ROOT_PATH+"/index.php",
			data:q,
			cache:false,
			success:function(data)
			{		
				initialize();
			}
		});
	}
	
    function initialize() {
			map = new BMap.Map("map_canvas");
			map.addControl(new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_RIGHT})); 
			map.addControl(new BMap.ScaleControl());   
			map.addControl(new BMap.OverviewMapControl());

	         
			//开始获取商圈信息
			var query = new Object();
			query.m = "Youhui";
			query.a = "get_current_region";
			$.ajax({
				url: ROOT_PATH+"/index.php",
				data:query,
				cache:false,
				dataType:"json",
				success:function(data)
				{												
					var point = new BMap.Point(data.xpoint,data.ypoint);
					var marker = new BMap.Marker(point);        // 创建标注  
					var zoom=16;
					if(data.zoom>0)
						zoom=data.zoom
					map.centerAndZoom(point, parseInt(zoom)); 
					//var label=create_lable(data.name+"商圈");
					//marker.setLabel(label);  
					//map.addOverlay(marker);
					//开始获取商圈下的所有商户					
					var q = new Object();
					q.m = "Youhui";
					q.a = "get_supplier_list";
					$.ajax({
						url: ROOT_PATH+"/index.php",
						data:q,
						cache:false,
						dataType:"json",
						success:function(data)
						{		
							for(var i=0;i<data.length;i++)
							{	
								if(data[i].icon==ROOT_PATH)
									data[i].icon=red_point;
								createSupplier(data[i].xpoint, data[i].ypoint,data[i].depart_name,data[i].icon,data[i].id,zoom);
							}
						}
					});
					//结束					
				}
			});

			
      }

function create_lable(name){
        var label = new BMap.Label(name,{"offset":new BMap.Size(-8,-20)});
        label.setStyle({
            borderColor:"#808080",
            color:"#333",
            cursor:"pointer"
        });
        return label;
    }

    $(document).ready(function(){
    	initialize();
    });
    
    function close_cate()
    {
    	if(document.getElementById("catalogMain").style.display!="none")
    	$("#catalogMain").slideUp("fast",function(){
    		document.getElementById("tog").className = "togopen";
    	});
    	else
    	$("#catalogMain").slideDown("fast",function(){
    		document.getElementById("tog").className = "tog";
    	});
    }
    function open_cate()
    {
    	if(document.getElementById("catalogMain").style.display!="none")
        	$("#catalogMain").slideUp("fast",function(){
        		document.getElementById("tog").className = "togopen";
        	});
        	else
        	$("#catalogMain").slideDown("fast",function(){
        		document.getElementById("tog").className = "tog";
        	});
    }
	
	
	