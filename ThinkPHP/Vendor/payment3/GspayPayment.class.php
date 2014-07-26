<?php
// +----------------------------------------------------------------------
// | Fanwe 多语商城建站系统 (Build on ThinkPHP)
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = 'Gspay';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'gspay';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '2.0';

    /* 插件的作者 */
    $modules[$i]['author']  = 'FANWE R&D TEAM';

    /* 支付方式：1：在线支付；0：线下支付 */
    $modules[$i]['online_pay'] = '1';
        
    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.fanwe.com';

    return;
}
// 支付宝模型
require_once(VENDOR_PATH.'payment3/Payment.class.php');
class GspayPayment implements Payment{	
	public $config = array(
	    'gspay_siteID'=>'',  //合作者身份ID
        'gspay_test'=>'',  //接口方式
	);
	
    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
    public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
    {
    	
  		$USStates =array(
                "Alabama"=>"AL",
                "Alaska"=>"AK",
                "Alberta"=>"AB",
                "American Samoa"=>"AS",
                "Arizona"=>"AZ",
                "Arkansas"=>"AR",
                "AA"=>"Armed Forces - Americas",
                "AE"=>"Armed Forces - Europe",
                "AP"=>"Armed Forces - Pacific",
                "British Columbia"=>"BC",
                "California"=>"CA",
                "Colorado"=>"CO",
                "Connecticut"=>"CT",
                "Delaware"=>"DE",
                "District of Columbia"=>"DC",
                "Federated States of Micronesia"=>"FM",
                "Florida"=>"FL",
                "Georgia"=>"GA",
                "Guam"=>"GU",
                "Hawaii"=>"HI",
                "Idaho"=>"ID",
                "Illinois"=>"IL",
                "Indiana"=>"IN",
                "Iowa"=>"IA",
                "Kansas"=>"KS",
                "Kentucky"=>"KY",
                "Louisiana"=>"LA",
                "Maine"=>"ME",
                "Manitoba"=>"MB",
                "Marshall Islands"=>"MH",
                "Maryland"=>"MD",
                "Massachusetts"=>"MA",
                "Michigan"=>"MI",
                "Minnesota"=>"MN",
                "Mississippi"=>"MS",
                "Missouri"=>"MO",
                "Montana"=>"MT",
                "Nebraska"=>"NE",
                "Nevada"=>"NV",
                "New Brunswick"=>"NB",
                "New Hampshire"=>"NH",
                "New Jersey"=>"NJ",
                "New Mexico"=>"NM",
                "New York"=>"NY",
                "Newfoundland"=>"NF",
                "North Carolina"=>"NC",
                "North Dakota"=>"ND",
                "Northern Mariana Islands"=>"MP",
                "Northwest Territories"=>"NT",
                "Nova Scotia"=>"NS",
                "Ohio"=>"OH",
                "Oklahoma"=>"OK",
                "Ontario"=>"ON",
                "Oregon"=>"OR",
                "Palau"=>"PW",
                "Pennsylvania"=>"PA",
		"Prince Edward Island"=>"PE",
                "Puerto Rico"=>"PR",
                "Quebec"=>"QC",
                "Rhode Island"=>"RI",
                "Saskatchewan"=>"SK",
                "South Carolina"=>"SC",
                "South Dakota"=>"SD",
                "Tennessee"=>"TN",
                "Texas"=>"TX",
                "Utah"=>"UT",
                "Vermont"=>"VT",
                "Virgin Islands"=>"VI",
                "Virginia"=>"VA",
                "Washington"=>"WA",
                "West Virginia"=>"WV",
                "Wisconsin"=>"WI",
                "Wyoming"=>"WY",
                "Yukon"=>"YT",
        );	
            	
        
        
		$money = round($money,2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		


        $payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		if($payment_log['rec_module']=='Order'){
			$rec_id = $GLOBALS['db']->getOne("select rec_id from ".DB_PREFIX."order_goods where order_id=".intval($payment_log['rec_id'])." limit 1");
			$goods_data = $GLOBALS['db']->getRow("select name_1,goods_short_name from ".DB_PREFIX."goods where id=".intval($rec_id)." limit 1");
			$orderdescription = $goods_data['goods_short_name']==''?$goods_data['name_1']:$goods_data['goods_short_name'];
			
			$order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."goods where id=".intval($rec_id));
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
			$orderdescription = $data_sn;
			
			$user_id = $GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
			$order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where user_id=".intval($user_id));
		}        
        //$data_order_id      = $order['log_id'];
        $amount = $money;
        $province_name = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."region_conf where id = ".intval($order['region_lv2']));
        $country_name = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."region_conf where id = ".intval($order['region_lv1']));
        
        
        $siteID = $payment_info['config']['gspay_siteID'];
		$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Gspay&log_id='.$payment_log_id;
	    $stateCode = $USStates[$province_name];
		$stateCode=!empty($stateCode) ? $stateCode : 'XX';
		if($payment_info['config']['gspay_test'] == '0'){
		   $testmodefiled = "<input type='hidden' name='TranscationMode' value='test'>";
		}

		$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$payment_log_id' where id = ".$payment_log_id);
		
        $def_url  = '<div style="text-align:center"><form style="text-align:center;" action="https://secure.redirect2pay.com/payment/pay.php" method="post" target="_blank">' .   
            "<input type='hidden' name='siteID' value='$siteID'>" .                            
            "<input type='hidden' name='OrderDescription[1]' value='$orderdescription'>" .
            "<input type='hidden' name='ApproveURL' value='$returnUrl'>" .
        	"<input type='hidden' name='returnUrl' value='$returnUrl'>" .
            "<input type='hidden' name='Amount[1]' value='$amount'>" .
            "<input type='hidden' name='Qty[1]' value='1'>" .
        
            "<input type='hidden' name='customerFullName' value='$order[consignee]'>" .
            "<input type='hidden' name='customerAddress' value='$order[address]'>" .
            "<input type='hidden' name='customerCity' value='$order[city_address]'>" .
            "<input type='hidden' name='customerStateCode' value='$stateCode'>" .
            "<input type='hidden' name='customerZip' value='$order[zip]'>" .
            "<input type='hidden' name='customerCountry' value='$country_name'>" .
            "<input type='hidden' name='customerEmail' value='$order[email]'>" .
            "<input type='hidden' name='customerPhone' value='$order[mobile_phone]'>" .
        
	        "<input type='hidden' name='customerShippingFullName' value='$order[consignee]'>" .
	        "<input type='hidden' name='customerShippingAddress' value='$order[address]'>" .
			"<input type='hidden' name='customerShippingCity' value='$order[city_address]'>" .
	        "<input type='hidden' name='customerShippingStateCode' value='$stateCode'>" .
	        "<input type='hidden' name='customerShippingZip' value='$order[zipcode]'>" .
	        "<input type='hidden' name='customerShippingCountry' value='$country_name'>" .
	        "<input type='hidden' name='customerShippingEmail' value='$order[email]'>" .
	        "<input type='hidden' name='customerShippingPhone' value='$order[tel]'>" .

			 "<input type='hidden' name='OrderId' value='$data_sn'>" .
			 $testmodefiled .
            "<input type='submit' class='paybutton' value='Pay by GSPAY'>" .
	        $def_url .= "</form></div></br>";
			$def_url .="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money)."</span>";

        return $def_url;
    }

    /**
     * 响应操作
     */
    public function dealResult($get,$post,$request)
    {
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		
        $Result = $request["transactionStatus"];     //支付结果
		$log_id = intval($request["log_id"]);
		$v_amount = floatval($request["transactionAmount"]);  
				
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($log_id));
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);
    	//var_dump($request);
    	
 	
        //开始初始化参数
        $payment_log_id = $log_id;
    	$money = $v_amount;
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency'];  

        if ($Result == 'approved' || $Result == 'test')
        {
            /* 改变订单状态 */
            return s_order_paid($payment_log_id,$money,$payment_id,$currency_id);
        }
        else
        {
	        $return_res['info'] = a_L("VALID_ERROR");
	        return $return_res;        	
            //return false;
        }

    }//end function

}


?>