<?php
$adv_id = intval ( $_REQUEST ['id'] );
$adv_data = $GLOBALS['db']->getRowCached("select `url` from ".DB_PREFIX."adv where id={$adv_id}");
if ($adv_data ['url'] != '') {
	if (check_ip_operation ( $_SESSION['CLIENT_IP'], "Adv", 5, $adv_id )) //防刷五秒
		$GLOBALS['db']->query("update ".DB_PREFIX."adv set click_count=click_count+1 where id='{$adv_id}' and status = 1 ");
		
	redirect2($adv_data ['url']);
} else {
	a_error ( a_L ( "INVALID_OPERATION" ),"",a_u("Index/index") );
}
?>