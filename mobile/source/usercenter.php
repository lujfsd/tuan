<?php
if(intval($GLOBALS['user_info']['id'])==0)
{
	redirect2("m.php?m=User&a=login");
}
else
{
	if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name='".$GLOBALS['user_info']['user_name']."' and email='".$GLOBALS['user_info']['email']."' ") == 0)
	{
		redirect2("m.php?m=User&a=login");
	}
}
require ROOT_PATH.'app/source/func/com_user_center_func.php';
$ma = strtolower($_REQUEST['m'].'_'.$_REQUEST['a']);
$ma();

function usercenter_index()
{
	$page = intval($_REQUEST["p"]);
	if($page==0) $page = 1;
	
	$status =  0;
	
	$GLOBALS['tpl']->assign('status',$status);
	
	$result =getGroupBondList($status,$page,intval($GLOBALS['user_info']['id']));
	
	foreach($result['list'] as $k =>$v)
	{
		if($v['use_time']==0 && $v['end_time'] > a_gmtTime())
			$result['list'][$k]['use_status'] =  a_L("GROUPBOND_NO_USE");
		elseif($v['use_time']>0)
			$result['list'][$k]['use_status'] =  a_L("GROUPBOND_HAD_USE");
		elseif($v['end_time']<= a_gmtTime())
			$result['list'][$k]['use_status'] = a_L("GROUPBOND_BAD_USE");
	}
	
	$GLOBALS['tpl']->assign("groupbond_list",$result['list']);		
		
	//分页
	require ROOT_PATH.'mobile/source/class/Pager.php';
	$page = new Pager($result['total'],a_fanweC("PAGE_LISTROWS"));   //初始化分页对象 		
	$p = $page->show();
	$GLOBALS['tpl']->assign('pages',$p);
	//end 分页  ;
			
	$GLOBALS['tpl']->assign("action_name","index");
	$GLOBALS['tpl']->display("Page/usercenter_index.html");
}

function usercenter_order()
{
	//初始化分页
    	$page = intval($_REQUEST["p"]);
    	if($page==0)
    		$page = 1;
    	
		$res = getOrderList(intval($GLOBALS['user_info']['id']),$page);
			/*foreach($res['list'] as $k=>$v)
			{
				$goods_item = M("Goods")->where("id=".M("OrderGoods")->where("order_id=".$v['id'])->getField("rec_id"))->find();
				if($goods_item['promote_end_time']<gmtTime())
				{
					$res['list'][$k]['stock_is_over'] = 1;
				}
			}*/
		
		$GLOBALS['tpl']->assign('order_list',$res['list']);
		
		//分页
		require ROOT_PATH.'mobile/source/class/Pager.php';
		$page = new Pager($res['total'],a_fanweC("PAGE_LISTROWS"));   //初始化分页对象 		
		$p  =  $page->show();
        $GLOBALS['tpl']->assign('pages',$p);
        $err = urldecode($_REQUEST['err']);
        if(!empty($err))
        	$GLOBALS['tpl']->assign('pay_info',$err);
        	
        $GLOBALS['tpl']->assign("action_name","order");
		$GLOBALS['tpl']->display("Page/usercenter_order.html");
}

function usercenter_account()
{
	$ammount = $GLOBALS['db']->getOne("select `money` from ".DB_PREFIX."user where id=".intval($GLOBALS['user_info']['id']));
  	if($ammount>0)
    	$ammount = a_formatPrice($ammount);
    else
    	$ammount =  a_formatPrice($ammount).sprintf(a_L("PLEASE_GO_TO_INCHARGE"),HTTP_URL);
    		
    $GLOBALS['tpl']->assign('ammount',$ammount);
    $GLOBALS['tpl']->assign("action_name","account");
    $GLOBALS['tpl']->display("Page/usercenter_account.html");
}
?>