$(document).ready(function(){
	var btns = $(".conf_btns").find("li");
	var tabs = $(".conf_tabs").find(".conf_table");
	if(tabs.length==0)
		tabs = $(".conf_tabs").find("table");
	$.each(btns, function(i, btn){
		$(btn).bind("click",function(){
			$(tabs).hide();
			$(tabs[i]).show();
			$(btns).removeClass("current");
			$(this).addClass("current");
		});
	});
	$(btns[0]).click();
});