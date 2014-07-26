<?php

/**
 * ECSHOP GSPAY语言文件
 * ============================================================================
 * 版权所有 (C) 2005-2008 康盛创想（北京）科技有限公司，并保留所有权利。
 * 网站地址: http://www.51ecshop.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Id: gspay.php 14481 2009-6-28 14:53 allen $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/gspay.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'gspay_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = '51ecshop';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.51ecshop.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'gspay_siteID','type' => 'text',   'value' => ''),
		array('name' => 'gspay_test',    'type' => 'select', 'value' => '')
    );

    return;
}

class gspay
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function gspay()
    {
    }

    function __construct()
    {
        $this->gspay();
    }

    function get_done_desc($order, $total){   	
    	
    	$tmp = "<font size=2><strong>Visa, MasterCard, Visa Electron, MC Maestro(Less than $500/Order)【<a href='http://www.gspay.com' target='_blank'>www.gspay.com</a>】
    	
    	We are going to process and ship your order within 24-36 hours after we get the above information.</strong></font>";
    	
    	return $tmp;
    	
    }
    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        $USStates=array(
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

        //$data_order_id      = $order['log_id'];
        $amount        = $order['order_amount'];
        
		$order_goods = $GLOBALS['db']->getAll("select goods_name,goods_number from ".$GLOBALS['ecs']->table('order_goods')." where order_id = '$order[order_id]' ");
        $goods_number = 0;
		if(!empty($order_goods)){
           foreach($order_goods as $k=>$v){
		       $orderdescription .= $v['goods_name'].'('.intval($v['goods_number']).')';
			   if(count($order_goods) != ($k+1)){
			         $orderdescription .=',';
			   }
			   $goods_number = $goods_number + intval($v['goods_number']);
		   }
        }//end if
        $province_name = $GLOBALS['db']->getOne("select region_name from ".$GLOBALS['ecs']->table('region')." where region_id = ".intval($order['province']));
        $country_name = $GLOBALS['db']->getOne("select region_name from ".$GLOBALS['ecs']->table('region')." where region_id = ".intval($order['country']));
        
        $siteID = $payment['gspay_siteID'];
        $data_order_id      = $order['log_id'];
        $approveurl    = return_url(basename(__FILE__, '.php')).'&log_id='.$data_order_id;
	    $stateCode = $USStates[$province_name];
		$stateCode=!empty($stateCode) ? $stateCode : 'XX';
		if($payment['gspay_test'] == '0'){
		   
		   $testmodefiled = "<input type='hidden' name='TranscationMode' value='test'>";

		}

        $def_url  = '<br /><form style="text-align:center;" action="https://secure.redirect2pay.com/payment/pay.php" method="post" target="_blank">' .   
            "<input type='hidden' name='siteID' value='$siteID'>" .                            
            "<input type='hidden' name='OrderDescription[1]' value='$orderdescription'>" .
            "<input type='hidden' name='ApproveURL' value='$approveurl'>" .
            "<input type='hidden' name='Amount[1]' value='$amount'>" .
            "<input type='hidden' name='Qty[1]' value='1'>" .
            "<input type='hidden' name='customerFullName' value='$order[consignee]'>" .
            "<input type='hidden' name='customerAddress' value='$order[address]'>" .
            "<input type='hidden' name='customerCity' value='$order[city_address]'>" .
            "<input type='hidden' name='customerStateCode' value='$stateCode'>" .
            "<input type='hidden' name='customerZip' value='$order[zipcode]'>" .
            "<input type='hidden' name='customerCountry' value='$country_name'>" .
            "<input type='hidden' name='customerEmail' value='$order[email]'>" .
            "<input type='hidden' name='customerPhone' value='$order[tel]'>" .
	            "<input type='hidden' name='customerShippingFullName' value='$order[consignee]'>" .
	            "<input type='hidden' name='customerShippingAddress' value='$order[address]'>" .
			   "<input type='hidden' name='customerShippingCity' value='$order[city_address]'>" .
	            "<input type='hidden' name='customerShippingStateCode' value='$stateCode'>" .
	            "<input type='hidden' name='customerShippingZip' value='$order[zipcode]'>" .
	            "<input type='hidden' name='customerShippingCountry' value='$country_name'>" .
	            "<input type='hidden' name='customerShippingEmail' value='$order[email]'>" .
	            "<input type='hidden' name='customerShippingPhone' value='$order[tel]'>" .
			 "<input type='hidden' name='OrderId' value='$order[order_sn]'>" .
			 $testmodefiled .
            "<input type='submit' value='" . $GLOBALS['_LANG']['gspay_button'] . "'>" .
            "</form><br />";

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        $Result = $_REQUEST["transactionStatus"];     //支付结果
		$log_id = $_REQUEST["log_id"];
        if ($Result == 'approved' || $Result == 'test')
        {
            /* 改变订单状态 */
                order_paid($log_id);
                return true;
        }
        else
        {
            return false;
        }

    }//end function

}


?>