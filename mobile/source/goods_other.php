<?php
	require ROOT_PATH.'mobile/source/class/Pager.php';
	//初始化分页
    $page = intval($_REQUEST["p"]);
   	if($page==0)
    		$page = 1;
    $tpl->assign("page",$page);
 	$cate_id = intval($_REQUEST['id']);
    	
    //查询当前页商品数据
    $goods_result = searchGoodsList($page,0,0,0,0,$cate_id,0);
	$tpl->assign('goods_list',$goods_result['list']);
    //分页
	$page = new Pager($goods_result['total'],a_fanweC("GOODS_PAGE_LISTROWS"));   //初始化分页对象 
	$p = $page->show ();
	$p  =  $page->show();
   	$tpl->assign('pages',$p);        //end 分页  
    $tpl->display("Page/goods_other.html");
?>