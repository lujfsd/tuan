<?php
$ma = strtolower($_REQUEST['m'].'_'.$_REQUEST['a']);
$ma();
function coupon_index($err='')
{
	if($err)
	{
		 $GLOBALS['tpl']->assign("err",$err);
	}
	 $GLOBALS['tpl']->assign("act",'index');
	 $GLOBALS['tpl']->display("Page/coupon.html");
}

function coupon_check()
{
	$sn = trim($_REQUEST['sn']);
	$pwd = trim($_REQUEST['pwd']);
	if(empty($sn))
	{
		coupon_index(a_l("PLASE_ENTER_SN"));
		exit();
	}
	
	if(!empty($sn) && empty($pwd))
	{
		do_check($sn);
	}
	elseif(!empty($sn) && !empty($pwd))
	{
		do_bus($sn,$pwd);
	}
	
	$GLOBALS['tpl']->assign("act",'result');
	$GLOBALS['tpl']->display("Page/coupon.html");
}
function do_check($sn){
		$time = a_gmtTime();
		$group_bond = $GLOBALS['db']->getAll("select goods_name, end_time from ".DB_PREFIX."group_bond where is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." and  sn = '".addslashes($sn)."'");
		$msg = "";
		if($group_bond)
		{
			foreach($group_bond as $kk=>$vv)
		    {	
				$msg = $msg."<br><br>".a_L('JS_GOODS_T').":".$vv['goods_name']."<br>".a_L('JS_GROUP_BOND_007').':'.a_toDate($vv['end_time']);							
		    }
		}
		$GLOBALS['tpl'] -> assign("type",0);
		$GLOBALS['tpl'] -> assign("msg",$msg);
}
function do_bus($sn,$pwd){
		$time = a_gmtTime();
		
		$sql = "update ".DB_PREFIX."group_bond set use_time = ".$time ." where is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." and password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'";
		
		$GLOBALS['db']->query($sql);
		$is_updated = $GLOBALS['db']->affected_rows();
		
		if($is_updated >0)
		{
			$bond_id= $GLOBALS['db']->getOne("select id from ".DB_PREFIX."group_bond where password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'");
			s_send_groupbond_use_sms($bond_id,true);
			$sql = "select goods_name from ".DB_PREFIX."group_bond where is_valid = 1 and status = 1 and password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'";
			$msg = $GLOBALS['db']->getOne($sql);
		}
	$GLOBALS['tpl'] -> assign("type",1);
	$GLOBALS['tpl'] -> assign("msg",$msg);
}
?>