$(document).ready(function(){
	//绑定模块选择
	$(".module_cbo").bind("click",function(){
		if($(this).attr("checked"))
		{
			$(this.parentNode.parentNode.parentNode).find(".action_cbo").attr("checked",false);
			$(this.parentNode.parentNode.parentNode).find(".checkall_cbo").attr("checked",false);
		}
		$(this.parentNode.parentNode.parentNode).find(".action_cbo").attr("disabled",$(this).attr("checked"));
		$(this.parentNode.parentNode.parentNode).find(".checkall_cbo").attr("disabled",$(this).attr("checked"));
	});
	
	//全选
	$(".checkall_cbo").bind("click",function(){
		$(this.parentNode.parentNode.parentNode).find(".action_cbo").attr("checked",$(this).attr("checked"));
	});
	
	//检测全选
	$(".action_cbo").bind("click",function(){
		if($(this.parentNode.parentNode).find(".action_cbo").length == $(this.parentNode.parentNode).find(".action_cbo:checked").length)
			$(this.parentNode.parentNode.parentNode).find(".checkall_cbo").attr("checked",true);
		else
			$(this.parentNode.parentNode.parentNode).find(".checkall_cbo").attr("checked",false);	
	});
});