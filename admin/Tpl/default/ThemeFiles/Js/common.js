function start_send()
{
	var time = document.getElementById("send_time").value;
	
	ThinkAjax.send(APP+'?'+VAR_MODULE+'=Email&'+VAR_ACTION+'=doSend','ajax=1&time='+time,doDelete);
}
function use_tmpl(name)
{
	ThinkAjax.send(APP+'?'+VAR_MODULE+'=Email&'+VAR_ACTION+'=useTmpl','name='+name,doDelete);
}
function showTip(info){
	$('tips').innerHTML	=	info;
}
function sendForm(formId,action,response,target,effect){
	// Ajax方式提交表单
	if (CheckForm($(formId),'ThinkAjaxResult'))//表单数据验证
	{
		ThinkAjax.sendForm(formId,action,response);
	}
	//Form.reset(formId);
}
rowIndex = 0;

function prepareIE(height, overflow){
	bod = document.getElementsByTagName('body')[0];
	bod.style.height = height;
	//bod.style.overflow = overflow;

	htm = document.getElementsByTagName('html')[0];
	htm.style.height = height;
	//htm.style.overflow = overflow; 
}

function hideSelects(visibility){
   selects = document.getElementsByTagName('select');
   for(i = 0; i < selects.length; i++) {
		   selects[i].style.visibility = visibility;
	}
}
document.write('<div id="overlay" class="none"></div><div id="lightbox" class="none"></div>');
// 显示light窗口
function showPopWin(content,width,height){
	     //  IE 
		 prepareIE('100%', 'hidden');
		 window.scrollTo(0, 0); 
		 hideSelects('hidden');//隐藏所有的<select>标记
		$('overlay').style.display = 'block';
		var arrayPageSize = getPageSize();
		var arrayPageScroll = getPageScroll();
		$('lightbox').style.display = 'block';
		$('lightbox').style.top = (arrayPageScroll[1] + ((arrayPageSize[3] - 35 - height) / 2) + 'px');
		$('lightbox').style.left = (((arrayPageSize[0] - 25 - width) / 2) + 'px');
		$('lightbox').innerHTML	=	content;
}

function fleshVerify(){
	//重载验证码
	var timenow = new Date().getTime();
	$('verifyImg').src= APP+'?'+VAR_MODULE+'=Public&'+VAR_ACTION+'=verify&rand='+timenow;
}

function allSelect(){
	var	colInputs = document.getElementsByTagName("input");
	for	(var i=0; i < colInputs.length; i++)
	{
		colInputs[i].checked= true;
	}
}
function allUnSelect(){
	var	colInputs = document.getElementsByTagName("input");
	for	(var i=0; i < colInputs.length; i++)
	{
		colInputs[i].checked= false;
	}
}

function InverSelect(){
	var	colInputs = document.getElementsByTagName("input");
	for	(var i=0; i < colInputs.length; i++)
	{
		colInputs[i].checked= !colInputs[i].checked;
	}
}

function WriteTo(id){
	var type = $F('outputType');
	switch (type)
	{
	case 'EXCEL':WriteToExcel(id);break;
	case 'WORD':WriteToWord(id);break;
	
	}
	return ;
}

function build(id){
	window.location = APP+'?'+VAR_MODULE+'=Card&'+VAR_ACTION+'=batch&type='+id;
}

function clearCache(){
	ThinkAjax.send(APP+'?'+VAR_MODULE+'=Public&'+VAR_ACTION+'=clearCache&ajax=1');
	//window.location.reload();
}
function clearAllCache(){
	ThinkAjax.send(APP+'?'+VAR_MODULE+'=Public&'+VAR_ACTION+'=clearAllCache&ajax=1');
	//window.location.reload();
}

function show(){
	if (document.getElementById('menu').style.display!='none')
	{
	document.getElementById('menu').style.display='none';
	document.getElementById('main').className = 'full';
	}else {
	document.getElementById('menu').style.display='inline';
	document.getElementById('main').className = 'main';
	}
}

function CheckAll(strSection)
	{
		var i;
		var	colInputs = document.getElementById(strSection).getElementsByTagName("input");
		for	(i=1; i < colInputs.length; i++)
		{
			colInputs[i].checked=colInputs[0].checked;
		}
	}
function add(id){
	if (id)
	{
		 location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add&id='+id;
	}else{
		 location.href  = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add';
	}
}

function addData(id){
	if (id)
	{
		 location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add&id='+id;
	}else{
		 location.href  = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add';
	}
}

function addMailAddress(id){
	if (id)
	{
		 location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=addMailAddress&id='+id;
	}else{
		 location.href  = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=addMailAddress';
	}
}
function addMail(id){
	if (id)
	{
		 location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=addMail&id='+id;
	}else{
		 location.href  = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=addMail';
	}
}
function addAttr(id){
	if (id)
	{
		 location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add&type_id='+id;
	}else{
		 location.href  = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add';
	}
}


function sort(id){
	var keyValue;
	keyValue = getSelectCheckboxValues();
//	location.href = URL+"/sort/sortId/"+keyValue;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=sort&sortId='+keyValue;
}


function sortBy (field,sort,action,ext){
//	location.href = "?_order="+field+"&_sort="+sort;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'='+action+'&_order='+field+'&_sort='+sort+'&'+ext;
}

function forbid(id){
//	location.href = URL+"/forbid/id/"+id;
	//location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=forbid&id='+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=forbid&id='+id+"&ajax=1",'',doDelete);
}
function forbidJq(id){
//	location.href = URL+"/forbid/id/"+id;
	$.ajax({
		  url: APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=forbid&id='+id+'&ajax=1',
		  cache: false,
		  success:function(data)
		  {
			location.href = location.href;
		  }
		}); 
	

}
function resumeJq(id){
//	location.href = URL+"/forbid/id/"+id;
	$.ajax({
		  url: APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=resume&id='+id+'&ajax=1',
		  cache: false,
		  success:function(data)
		  {
			location.href = location.href;
		  }
		}); 
	

}
function forbidIncharge(id){
//	location.href = URL+"/forbid/id/"+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=forbidIncharge&id='+id+"&ajax=1",'',doDelete);
}
function forbidUncharge(id){
//	location.href = URL+"/forbid/id/"+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=forbidUncharge&id='+id+"&ajax=1",'',doDelete);
}
function recycle(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert(CHOOSE_RECYCLE_ITEM);
		return false;
	}
//	location.href = URL+"/recycle/id/"+keyValue;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=recycle&id='+keyValue;
}
function resume(id){
//	location.href = URL+"/resume/id/"+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=resume&id='+id+"&ajax=1",'',doDelete);
}
function resumeIncharge(id){
//	location.href = URL+"/resume/id/"+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=resumeIncharge&id='+id+"&ajax=1",'',doDelete);
}
function resumeUncharge(id){
//	location.href = URL+"/resume/id/"+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=resumeUncharge&id='+id+"&ajax=1",'',doDelete);
}
function trace(id){
//	location.href = URL+"/trace/id/"+id;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=trace&id='+id;
}
function output(){
//	location.href = URL+"/output/";
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=output';
}
function member(id){
//	location.href = URL+"/../Member/edit/id/"+id;
	location.href = APP+'?'+VAR_MODULE+'=Member&'+VAR_ACTION+'=edit&id='+id;
}
function chat(id){
//	location.href = URL+"/../Chat/index/girlId/"+id;
	location.href = APP+'?'+VAR_MODULE+'=Chat&'+VAR_ACTION+'=index&girlId='+id;
}
function login(id){
//	location.href = URL+"/../Login/index/type/4/id/"+id;
	location.href = APP+'?'+VAR_MODULE+'=Login&'+VAR_ACTION+'=index&type=4&id='+id;
}
function child(id){
//	location.href = URL+"/index/pid/"+id;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=index&pid='+id;
}
function action(id){
//	location.href = URL+"/action/groupId/"+id;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=action&groupId='+id;
}

function access(id){
//	location.href= URL+"/access/id/"+id;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=access&id='+id;
}
function app(id){
//	location.href = URL+"/app/groupId/"+id;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=app&groupId='+id;
}

function module(id){
//	location.href = URL+"/module/groupId/"+id;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=module&groupId='+id;
}
function addv(id){
//		 location.href  = URL+"/addv/id/"+id;
		 location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=addv&id='+id;
}

function user(id){
	//location.href = URL+"/user/id/"+id;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=user&id='+id;
}

function goodsTypeAttr(id){
	location.href = APP+'?'+VAR_MODULE+'=GoodsTypeAttr&'+VAR_ACTION+'=index&type_id='+id;
}

	//+---------------------------------------------------
	//|	打开模式窗口，返回新窗口的操作值
	//+---------------------------------------------------
	function PopModalWindow(url,width,height)
	{
		var result=window.showModalDialog(url,"win","dialogWidth:"+width+"px;dialogHeight:"+height+"px;center:yes;status:no;scroll:no;dialogHide:no;resizable:no;help:no;edge:sunken;");
		return result;
	}

function read(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert(SELECT_EDIT_ITEM);
		return false;
	}
//	location.href =  URL+"/read/id/"+keyValue;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=read&id='+keyValue;
}

function edit(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert(SELECT_EDIT_ITEM);
		return false;
	}
//	location.href =  URL+"/edit/id/"+keyValue;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=edit&id='+keyValue;
}
function editUserField(id)
{
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=editUserField&id='+id;
}
function delUserField(id)
{
	if(confirm("确定要删除吗？"))
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=delUserField&id='+id+"&ajax=1",'',doDelete);

}
function edit_tmpl(id)
{
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=edit_tmpl&id='+id;
}
//modify by 
function editData(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert(SELECT_EDIT_ITEM);
		return false;
	}
//	location.href =  URL+"/edit/id/"+keyValue;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=edit&id='+keyValue;
}

function showCateGoods(id)
{
	location.href = APP+'?'+VAR_MODULE+'=Goods&'+VAR_ACTION+'=index&status=1&cate_id='+id;
}
function showCateArticle(id)
{
	location.href = APP+'?'+VAR_MODULE+'=Article&'+VAR_ACTION+'=index&status=1&cate_id='+id;
}
function editMailAddress(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert(SELECT_EDIT_ITEM);
		return false;
	}
//	location.href =  URL+"/edit/id/"+keyValue;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=editMailAddress&id='+keyValue;
}
function editMail(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert(SELECT_EDIT_ITEM);
		return false;
	}
//	location.href =  URL+"/edit/id/"+keyValue;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=editMail&id='+keyValue;
}
function sendAddressList(id){
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=sendAddressList&id='+id;
}
function spec(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert(SELECT_EDIT_ITEM);
		return false;
	}
	location.href = APP+'?'+VAR_MODULE+'=GoodsSpec&'+VAR_ACTION+'=index&id='+keyValue;
}
function grades(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert(SELECT_EDIT_ITEM);
		return false;
	}
//	location.href =  URL+"/grades/id/"+keyValue;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=grades&id='+keyValue;
}
function addmoney(id){
//	location.href =  URL+"/addmoney/id/"+id;
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=addmoney&id='+id;
}
var selectRowIndex = Array();
function del(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}
	if (window.confirm(CONFIRM_DELETE))
	{
//		ThinkAjax.send(URL+"/delete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=delete&id='+keyValue+'&ajax=1','',doDelete);
	}
}

function delData(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}
	if (window.confirm(CONFIRM_DELETE))
	{
//		ThinkAjax.send(URL+"/delete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=delete&id='+keyValue+'&ajax=1','',doDelete);
	}
}

function sendMail(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert("请选择要邮件通知的团购活动");
		return false;
	}
	if (window.confirm("立即发送吗"))
	{
//		ThinkAjax.send(URL+"/delete/","id="+keyValue+'&ajax=1',doDelete);
		var sendTime = document.getElementById("sendTime").value;
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=sendMail&id='+keyValue+'&ajax=1&sendTime='+sendTime,'');
	}
}
function moveToCate()
{
	var keyValue = getSelectCheckboxValues();
	var cate_id = document.getElementById("move_to_cate_id").value;
	var city_id = document.getElementById("move_to_city_id").value;
	var suppliers_id = document.getElementById("move_to_suppliers_id").value;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=moveGoods&id='+keyValue+'&cate_id='+cate_id+'&city_id='+city_id+'&suppliers_id='+suppliers_id+'&ajax=1','',doDelete);
}

function moveToArticleCate()
{
	var keyValue = getSelectCheckboxValues();
	var cate_id = document.getElementById("move_to_cate_id").value;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=moveArticle&id='+keyValue+'&cate_id='+cate_id+'&ajax=1','',doDelete);
}

function delcontent(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}
	if (window.confirm(CONFIRM_DELETE))
	{
//		ThinkAjax.send(URL+"/delcontent/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=delcontent',"id="+keyValue+'&ajax=1',doDelete);

	}
}
function delsummary(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}
	if (window.confirm(CONFIRM_DELETE))
	{
//		ThinkAjax.send(URL+"/delsummary/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=delsummary',"id="+keyValue+'&ajax=1',doDelete);

	}
}

function foreverdel(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}

	if (window.confirm(CONFIRM_FOREVER_DELETE))
	{
//		ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=foreverdelete&id='+keyValue+"&ajax=1",'',doDelete);
	}
}
function foreverdelorder(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}

	if (window.confirm("删除订单将会删除订单关联的所有数据，包括订单留言, 团购券所有记录，请确定订单是否彻底作废。\n确定删除吗？"))
	{
//		ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=foreverdelete&id='+keyValue+"&ajax=1",'',doDelete);
	}
}
function foreverdelJq(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}

	if (window.confirm(CONFIRM_FOREVER_DELETE))
	{
		$.ajax({
			  url: APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=foreverdelete&id='+keyValue+"&ajax=1",
			  cache: false,
			  success:function(data)
			  {
				location.href = location.href;
			  }
			}); 
	}
}
function foreverdelMailAddress(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}

	if (window.confirm(CONFIRM_FOREVER_DELETE))
	{
//		ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=foreverdeleteMailAddress&id='+keyValue+"&ajax=1",'',doDelete);

	}
}
function foreverdelMail(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}

	if (window.confirm(CONFIRM_FOREVER_DELETE))
	{
//		ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=foreverdeleteMail&id='+keyValue+"&ajax=1",'',doDelete);

	}
}
function foreverdelIncharge(id){

	if (window.confirm(CONFIRM_FOREVER_DELETE))
	{
//		ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=foreverdeleteIncharge&id='+id+"&ajax=1",'',doDelete);

	}
}
function foreverdelUncharge(id){

	if (window.confirm(CONFIRM_FOREVER_DELETE))
	{
//		ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=foreverdeleteUncharge&id='+id+"&ajax=1",'',doDelete);

	}
}
function userforeverdel(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}

	if (window.confirm(CONFIRM_DELETE_USER_DATA))
	{
//		ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=foreverdelete&id='+keyValue+"&ajax=1",'',doDelete);

	}
}




function getTableRowIndex(obj){ 
	selectRowIndex[0] =obj.parentElement.parentElement.rowIndex;/*当前行对象*/
}

function getSelectCheckboxValues(){
	var obj_o = document.getElementsByName('key');
	var obj = new Array();
	for(var i=0; i<obj_o.length;i++)
	{
		if(obj_o[i].name=="key")
		{
			obj.push(obj_o[i]);
		}
	}
	var result ='';
	var j= 0;
	for (var i=0;i<obj.length;i++)
	{
		if (obj[i].checked==true){
				selectRowIndex[j] = i+2; //IE下+1，火狐下加：+2 add by chenfq 2010-04-21
				//alert("selectRowIndex[" + j +"]:" +selectRowIndex[j] + ";" + obj[i].value);
				result += obj[i].value+",";
				j++;
		}
	}
	return result.substring(0, result.length-1);
}

function doDelete(data,status){
	if (status==1)
	{
		var Table = $('checkList');
		var len	= selectRowIndex.length - 1;//modify by chenfq 2010-04-20 selectRowIndex.length ==> selectRowIndex.length - 1;;
		//alert("selectRowIndex.length:" + selectRowIndex.length);
		if(len<=0){
			window.location.reload();
		}
		for (var i = len;i >= 0;i-- )
		{
			//删除表格行
			//alert("selectRowIndex[" + i +"]:" +selectRowIndex[i]);
			Table.deleteRow(selectRowIndex[i]);
		}
		selectRowIndex = Array();
	}
	
}
function doRefresh(data,status){
	if (status==1)
	{
		var Table = $('checkList');
		var len	=	selectRowIndex.length - 1;//modify by chenfq 2010-04-20 selectRowIndex.length ==> selectRowIndex.length - 1;;
		if(len<=0){
			window.location.reload();
		}
		for (var i=len;i>=0;i-- )
		{
			//删除表格行
			Table.deleteRow(selectRowIndex[i]);
		}
		selectRowIndex = Array();
	}
	else
	{
		window.location.reload();
	}
}
	function delAttach(id,showId){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert(SELECT_DEL_ITEM);
		return false;
	}

	if (window.confirm(CONFIRM_DELETE))
	{
		$('result').style.display = 'block';
//		ThinkAjax.send(URL+"/delAttach/","id="+keyValue+'&_AJAX_SUBMIT_=1');
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=delAttach',"id="+keyValue+'&_AJAX_SUBMIT=1');

		if (showId != undefined)
		{
			$(showId).innerHTML = '';
		}
	}
}




function getSelectCheckboxValue(){
	var obj = document.getElementsByName('key');
	var result ='';
	for (var i=0;i<obj.length;i++)
	{
		if (obj[i].checked==true)
				return obj[i].value;

	}
	return false;
}


 function   change(e)   
  {   
	  if (!document.all)
	  {return ;
	  }
	var e = e || event;
	var   oObj   =   e.srcElement   ||   e.target;  
	  //if(oObj.tagName.toLowerCase()   ==   "td")   
	 // {   
		  	  /*
	  var   oTable   =   oObj.parentNode.parentNode;   
	  for(var   i=1;   i<oTable.rows.length;   i++)   
	  {   
	  oTable.rows[i].className   =   "out";   
	  oTable.rows[i].tag   =   false;   
	  }   */
	var obj= document.getElementById('checkList').getElementsByTagName("input");
	  var   oTr   =   oObj.parentNode; 
	  var row = oObj.parentElement.rowIndex-1;
	  if (oTr.className == 'down')
	  {
		  	oTr.className   =   'out';   
			obj[row].checked = false;
		    oTr.tag   =   true;  
	  }else {
			oTr.className   =   'down';   
			if(obj[row])
				obj[row].checked = true;
		    oTr.tag   =   true;  
	  }
 	  //}   
  }   
    
  function   out(e)   
  {   
	var e = e || event;
  var   oObj   =   e.srcElement   ||   e.target;   


  
  var   oTr   =   oObj.parentNode;   
  if(!oTr.tag)   
  oTr.className   =   "out";   
 
  }   
    
  function   over(e)   
  {   
	var e = e || event;
  var   oObj   =   e.srcElement   ||   e.target;   
  
  var   oTr   =   oObj.parentNode;   
  if(!oTr.tag)   
  oTr.className   =   "over";   
  
  }   

  function swAttrvalue(obj)
  {
	  var ipts = document.getElementsByTagName("TEXTAREA");
	  var value_box = new Array();
	  for(var i=0;i<ipts.length;i++)
	  {
		  if((ipts[i].name).substr(0,10)=="attr_value")
		  {
			  value_box.push(ipts[i]);
		  }
	  }
	  if(obj.value=='1')
	  {
		  for(var i=0;i<value_box.length;i++)
		  {
			  value_box[i].value = "";
			  value_box[i].disabled = true;
		  }
	  }
	  else
	  {
		  for(var i=0;i<value_box.length;i++)
		  {			  
			  value_box[i].disabled = false;
		  }
	  }
  }
//---------------------------------------------------------------------
// 多选改进方法 by Liu21st at 2005-11-29
// 
//
//-------------------------begin---------------------------------------

function searchItem(item){
	for(i=0;i<selectSource.length;i++)
		if (selectSource[i].text.indexOf(item)!=-1)
		{selectSource[i].selected = true;break;}
}

function addItem(){
	for(i=0;i<selectSource.length;i++)
		if(selectSource[i].selected){
			selectTarget.add( new Option(selectSource[i].text,selectSource[i].value));
			}
		for(i=0;i<selectTarget.length;i++)
			for(j=0;j<selectSource.length;j++)
				if(selectSource[j].text==selectTarget[i].text)
					selectSource[j]=null;
}

function delItem(){
	for(i=0;i<selectTarget.length;i++)
		if(selectTarget[i].selected){
		selectSource.add(new Option(selectTarget[i].text,selectTarget[i].value));
		
		}
		for(i=0;i<selectSource.length;i++)
			for(j=0;j<selectTarget.length;j++)
			if(selectTarget[j].text==selectSource[i].text) selectTarget[j]=null;
}

function delAllItem(){
	for(i=0;i<selectTarget.length;i++){
		selectSource.add(new Option(selectTarget[i].text,selectTarget[i].value));
		
	}
	selectTarget.length=0;
}
function addAllItem(){
	for(i=0;i<selectSource.length;i++){
		selectTarget.add(new Option(selectSource[i].text,selectSource[i].value));
		
	}
	selectSource.length=0;
}

function getReturnValue(){
	for(i=0;i<selectTarget.length;i++){
		selectTarget[i].selected = true;
	}
}

function loadBar(fl)
//fl is show/hide flag
{
  var x,y;
  if (self.innerHeight)
  {// all except Explorer
    x = self.innerWidth;
    y = self.innerHeight;
  }
  else 
  if (document.documentElement && document.documentElement.clientHeight)
  {// Explorer 6 Strict Mode
   x = document.documentElement.clientWidth;
   y = document.documentElement.clientHeight;
  }
  else
  if (document.body)
  {// other Explorers
   x = document.body.clientWidth;
   y = document.body.clientHeight;
  }

    var el=document.getElementById('loader');
	if(null!=el)
	{
		var top = (y/2) - 50;
		var left = (x/2) - 150;
		if( left<=0 ) left = 10;
		el.style.visibility = (fl==1)?'visible':'hidden';
		el.style.display = (fl==1)?'block':'none';
		el.style.left = left + "px"
		el.style.top = top + "px";
		el.style.zIndex = 2;
	}
}


//留言的查看与回复
function editMsg(id){
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=edit&id='+id;
}

//留言的查看与回复
function editForum(id){
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=editforum&id='+id;
}

//地区列表
function childRegion(id)
{
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=index&pid='+id;
}

//添加地区
function addRegion(pid){
	if (pid)
	{
		 location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add&pid='+pid;
	}else{
		 location.href  = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add';
	}
}

//会员收货人列表
function consignee(id)
{
	location.href = APP+'?'+VAR_MODULE+'=UserConsignee&'+VAR_ACTION+'=index&user_id='+id;
}


//添加会员收货人
function addConsignee(user_id){
	if (user_id)
	{
		 location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add&user_id='+user_id;
	}else{
		 location.href  = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=add';
	}
}

function editRoleNode(role_id)
{
	location.href = APP+'?'+VAR_MODULE+'=RoleAccess&'+VAR_ACTION+'=index&role_id='+role_id;
}
function addAccess(role_id)
{
	location.href = APP+'?'+VAR_MODULE+'=RoleAccess&'+VAR_ACTION+'=add&role_id='+role_id;
}
function backup()
{
	ThinkAjax.send(APP+'?'+VAR_MODULE+'=Database&'+VAR_ACTION+'=dump','ajax=1',doDelete);
}

function restore(file)
{
	if(confirm(CONFIRM_RESTORE))
	ThinkAjax.send(APP+'?'+VAR_MODULE+'=Database&'+VAR_ACTION+'=restore&file='+file,'ajax=1');
}

function delsql(file)
{
	if(confirm(CONFIRM_DELETE))
	ThinkAjax.send(APP+'?'+VAR_MODULE+'=Database&'+VAR_ACTION+'=delete&file='+file,'ajax=1',doDelete);
}

function swBestStatus(id,status){
//	location.href = URL+"/forbid/id/"+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=swBestStatus&status='+status+'&id='+id+"&ajax=1",'',doDelete);
}
function swHotStatus(id,status){
//	location.href = URL+"/forbid/id/"+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=swHotStatus&status='+status+'&id='+id+"&ajax=1",'',doDelete);
}
function swNewStatus(id,status){
//	location.href = URL+"/forbid/id/"+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=swNewStatus&status='+status+'&id='+id+"&ajax=1",'',doDelete);
}
function swTopStatus(id,status){
//	location.href = URL+"/forbid/id/"+id;
	ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=swTopStatus&status='+status+'&id='+id+"&ajax=1",'',doDelete);
}
function changeSort(obj,id)
{
	if(!isNaN(obj.innerHTML))
	{
		var sort = obj.innerHTML;
		var html = "<input type='text' size='3' class='bLeft' value='"+sort+"' onblur='doChangeSort(this,"+id+","+sort+");' id='sort_"+id+"' />";
		obj.innerHTML = html;
		document.getElementById("sort_"+id).focus();
	}
}
function changeScore(obj,id)
{
	if(!isNaN(obj.innerHTML))
	{
		var score = obj.innerHTML;
		var html = "<input type='text' size='3' class='bLeft' value='"+score+"' onblur='doChangeScore(this,"+id+","+score+");' id='score_"+id+"' />";
		obj.innerHTML = html;
		document.getElementById("score_"+id).focus();
	}
}
function changeStock(obj,id)
{
	if(!isNaN(obj.innerHTML))
	{
		var stock = obj.innerHTML;
		var html = "<input type='text' size='3' class='bLeft' value='"+stock+"' onblur='doChangeStock(this,"+id+","+stock+");' id='stock_"+id+"' />";
		obj.innerHTML = html;
		document.getElementById("stock_"+id).focus();
	}
}
function doChangeSort(obj,id,oldsort)
{
	var newsort = obj.value;
	if(isNaN(newsort)||parseInt(newsort)<=0)
	{		
		obj.parentNode.innerHTML = oldsort;
		alert("请输入正整数");		
		return;
	}
	else
	{
		if(typeof(jQuery)=='function')
		{
			$.ajax({
				  url: APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=doChangeSort&sort='+newsort+'&id='+id+"&ajax=1",
				  cache: false,
				  success:function(data)
				  {
					location.href = location.href;
				  }
				});
		}
		else
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=doChangeSort&sort='+newsort+'&id='+id+"&ajax=1",'',doRefresh);
	}
}

function doChangeScore(obj,id,oldscore)
{
	var newscore = obj.value;
	if(isNaN(newscore)||parseInt(newscore)<0)
	{		
		obj.parentNode.innerHTML = oldscore;
		alert("请勿输入负数");		
		return;
	}
	else
	{
		if(typeof(jQuery)=='function')
		{
			$.ajax({
				  url: APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=doChangeScore&score='+newscore+'&id='+id+"&ajax=1",
				  cache: false,
				  success:function(data)
				  {
					location.href = location.href;
				  }
				});
		}
		else
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=doChangeScore&score='+newscore+'&id='+id+"&ajax=1",'',doDelete);
	}
}

function doChangeStock(obj,id,oldstock)
{
	var newstock = obj.value;
	if(isNaN(newstock)||parseInt(newstock)<0)
	{		
		obj.parentNode.innerHTML = oldstock;
		alert("请勿输入负数");		
		return;
	}
	else
	{
		if(typeof(jQuery)=='function')
		{
			$.ajax({
				  url: APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=doChangeStock&stock='+newstock+'&id='+id+"&ajax=1",
				  cache: false,
				  success:function(data)
				  {
					location.href = location.href;
				  }
				});
		}
		else
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=doChangeStock&stock='+newstock+'&id='+id+"&ajax=1",'',doDelete);
	}
}

function getPageList()
{
	$.ajax({
		  url: APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=getPageList&tmpl='+$("#tmpl").val()+'&ajax=1',
		  cache: false,
		  success:function(data)
		  {
			  if(data!=null)
			  {
				  $("#page").empty();
				  $("#pages").empty();
				  data = $.evalJSON(data);
				  var files = data.files;
				  var pages = data.pages;
				  for(var i=0;i<files.length;i++)
				  {
					  $("#page").append("<option value='"+files[i]+"'>"+files[i]+"</option>");
				  }
				  for(var i=0;i<pages.length;i++)
				  {
					  $("#pages").append("<option value='"+pages[i].file+"'>"+pages[i].title+"</option>");
				  }
				  getLayoutList();
			  }
		  }
		}); 
	
}

function getLayoutList()
{
	var tmpl = $("#tmpl").val();
	var page = $("#page").val();
	$.ajax({
		  url: APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=getLayoutList&tmpl='+tmpl+'&page='+page,
		  cache: false,
		  success:function(data)
		  {		
			  if(data!=null)
			  {
				  $("#layout_id").empty();
				  data = $.evalJSON(data);
				  $("#layout_id").append("<option value=''>"+PLEASE_SELECT+"</option>");
				  for(var i=0;i<data.length;i++)
				  {
					  $("#layout_id").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
				  }

				  getCateList();
			  }
		  }
		}); 
}

function getCateList()
{
	var rec_module = $("#rec_module").val();
	if(rec_module == "AdvPosition")
	{
		$("tr[rel='AdvPosition']").show();
		$("tr[rel='Cate']").hide();
	}
	else
	{
		$("tr[rel='AdvPosition']").hide();
		$("tr[rel='Cate']").show();
	}
	
	$.ajax({
		  url: APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=getCateList&rec_module='+rec_module,
		  cache: false,
		  success:function(data)
		  {		
			  var old_rec_id = $("#rec_id").val();
			  if(data!=null)
			  {
				  $("#rec_id").empty();
				  data = $.evalJSON(data);
				  for(var i=0;i<data.length;i++)
				  {
					  if(old_rec_id==data[i].id)
						  $("#rec_id").append("<option value='"+data[i].id+"' selected='selected'>"+data[i].name+"</option>");
					  else
						  $("#rec_id").append("<option value='"+data[i].id+"'>"+data[i].name+"</option>");
				  }
			  }
		  }
		}); 
}

function initLoadLayout()
{
	getPageList();
}


//判断s是否为数字
function isdigit(s)
{
	//alert(typeof(s))
	if (typeof(s)=='string'){
		var r,re;
		re = /\d*/i;    //\d表示数字,*表示匹配多个数字
		r = s.match(re);
		
		return (r==s)?1:0;
	}else{
		return 0;
	}

}

//将字符串转化为数字，不是数字字符串的则返回为：0
function strToFloat(s){
	var r = parseFloat(s);
	if (isNaN(r)){
		return 0;
	}else{
		return r;
		//return round(r, precision);
	}	
}

function round(thisNumber,n){//四舍五入
	thisNumber = strToFloat(thisNumber);
	return Math.round(thisNumber*Math.pow(10,n))/Math.pow(10,n); 
}

function checkAdm(obj)
{
	var adm_name = obj.value;
	$.ajax({
		  url: APP+'?'+VAR_MODULE+'=Admin&'+VAR_ACTION+'=checkAdm&adm_name='+adm_name,
		  cache: false,
		  success:function(data)
		  {		
		      data = $.evalJSON(data);
			  if(data.status)
			  {				 
				  if(confirm("该管理员有效，是否要使用该管理员作为系统管理员"))
				  {
					  obj.value = adm_name;
				  }
				  else
				  {
					  obj.value = data.origin;
				  }
			  }
			  else
			  {
				  alert("不存在的管理员");
				  obj.value = data.origin;
			  }
		  }
		});
}


function checkFile(obj)
{
	var file_name = obj.value;
	$.ajax({
		  url: APP+'?'+VAR_MODULE+'=SysConf&'+VAR_ACTION+'=checkFile&file_name='+file_name,
		  cache: false,
		  success:function(data)
		  {		
		      data = $.evalJSON(data);
			  if(data.status)
			  {				 
				  obj.value = file_name;
			  }
			  else
			  {
				  alert("非法的文件名");
				  obj.value = data.file_name;
			  }
		  }
		});
}

function checkSSL(obj)
{
	if(obj.value==1)
	{
		$.ajax({
			  url: APP+'?'+VAR_MODULE+'=SysConf&'+VAR_ACTION+'=checkSSL',
			  cache: false,
			  success:function(data)
			  {		
			     if(parseInt(data)==0)
			     {
			    	 alert("服务器不支持openssl");
			    	 obj.checked = false;
			    	 document.form.IS_SSL[0].checked = true;
			     }
			  }
			});
	}
}

function loadGeoInfo()
{
	var address = $("#api_address").val();
	 var geocoder = new google.maps.Geocoder();
   	 geocoder.geocode({'address': address}, function(results, status) {
      if(status == google.maps.GeocoderStatus.OK) {
		var xpoint = results[0].geometry.location.lng();
	  	var ypoint = results[0].geometry.location.lat();
		$("#xpoint").val(xpoint.toFixed(6));
		$("#ypoint").val(ypoint.toFixed(6));
		return;
      } 
    });
}

function send_sms(id)
{
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=send_sms&id='+id;
}
function send_mail(id)
{
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=send_mail&id='+id;
}
function depart(id)
{
	location.href = APP+'?'+VAR_MODULE+'=Suppliers&'+VAR_ACTION+'=departList&supplier_id='+id;
}
function addSupplierDepart(id)
{
	location.href = APP+'?'+VAR_MODULE+'=Suppliers&'+VAR_ACTION+'=addDepart&supplier_id='+id;
}
function editSupplierDepart(id)
{
	location.href = APP+'?'+VAR_MODULE+'=Suppliers&'+VAR_ACTION+'=editDepart&id='+id;
}
function delSupplierDepart(id)
{
	if (window.confirm(CONFIRM_FOREVER_DELETE))
	{
//		ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
		ThinkAjax.send(APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=delDepart&id='+id+"&ajax=1",'',doDelete);
	}
}
function setMain(id)
{
	location.href = APP+'?'+VAR_MODULE+'=Suppliers&'+VAR_ACTION+'=setMain&id='+id;
}
function showMailDemo()
{
	var goods_id = $("input[name='goods_id']").val();
	$.ajax({
		  type: "POST", 	
		  url: APP+'?m=Email&a=showMailDemo&id='+goods_id,
		  cache: false,
		  dataType:'json',
		  success:function (res){	
			if(res.status)
			{
				$("#mail_content").show();
				KE.util.setFullHtml("mail_content_editor", res.data);
			}
			else
			{
				alert(res.info);
			}
		  }	  
		});	
}

function chk_exchange_box()
{     
	if($("input[name='exchange']:checked").val()==0)
	{
		$("#exchange_score").find("input[name='exchange_score']").val("");
		$("#exchange_score").find("input[name='exchange_limit']").val("");
		$("#exchange_score").hide();
	}
	else
	{
		$("#exchange_score").show();
	}
}

function edit_money(id)
{
	location.href = APP+'?'+VAR_MODULE+'='+CURR_MODULE+'&'+VAR_ACTION+'=editMoney&id='+id;
}
function load_balance(id)
{ 
	deal_id = $("input[name='hd_deal_id']").val();
        //alert(deal_id);
	if(!id)
	{
		idBox = $(".key:checked");
		if(idBox.length == 0)
		{
			alert("选择不能为空");
			return;
		}
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");	
	}	
	//alert(deal_id);
	$.ajax({ 
		url: APP+"?"+VAR_MODULE+"=Balance&"+VAR_ACTION+"=check_balance&goods_id="+deal_id+"&id="+id, 
		data: "ajax=1",
		dataType: "json",
		success: function(obj){
			if(obj.status)
			{
				$.weeboxs.open(APP+'?m=Balance&a=load_balance&id='+id+"&goods_id="+deal_id, {contentType:'ajax',showButton:false,title:"执行结算",width:600,height:200});
			}
			else
			{
				alert(obj.info);
			}
		}
	});
        
	
	
}