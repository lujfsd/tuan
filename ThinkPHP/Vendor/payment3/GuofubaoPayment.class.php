<?php

// +----------------------------------------------------------------------
// | Fanwe 多语商城建站系统 (Build on ThinkPHP)
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: jobin.lin(jobin.lin@gmail.com)
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | 国付报 直连银行支付
// +----------------------------------------------------------------------

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE) {
    $i = (isset($modules)) ? count($modules) : 0;

    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $modules[$i]['code'] = 'Guofubao';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name'] = '国付宝人民币支付网关';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '2.1';

    /* 插件的作者 */
    $modules[$i]['author'] = 'FANWE R&D TEAM';

    /* 支付方式：1：在线支付；0：线下支付 */
    $modules[$i]['online_pay'] = '1';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.fanwe.com';

    return;
}

// 国付宝模型
require_once VENDOR_PATH . 'payment3/Payment.class.php';

class GuofubaoPayment implements Payment {

    public $config = array(
        'merchant_id' => '', //商户ID
        'VerficationCode'=>'',  //商户识别码
        'virCardNoIn' => '', //卖家国付宝账户
        'tencentpay_gateway' => array(
            'CCB' => '', //中国建设银行
            'CMB' => '', //招商银行
            'ICBC' => '', //中国工商银行
            'BOC' => '', //中国银行
            'ABC' => '', //中国农业银行
            'BOCOM' => '', //交通银行
            'CMBC' => '', //中国民生银行
            'HXBC' => '', //华夏银行
            'CIB' => '', //兴业银行
            'SPDB' => '', //上海浦东发展银行
            'GDB' => '', //广东发展银行
            'CITIC' => '', //中信银行
            'CEB' => '', //光大银行
            'PSBC' => '', //中国邮政储蓄银行
            'SDB' => '', //深圳发展银行
    		'BOBJ' => '', //北京银行
            'TCCB' => '', //天津银行
        ),
    );
    public $bank_types = array(
        'CCB', //中国建设银行
        'CMB', //招商银行
        'ICBC', //中国工商银行
        'BOC', //中国银行
        'ABC', //中国农业银行
        'BOCOM', //交通银行
        'CMBC', //中国民生银行
        'HXBC', //华夏银行
        'CIB', //兴业银行
        'SPDB', //上海浦东发展银行
        'GDB', //广东发展银行
        'CITIC', //中信银行
        'CEB', //光大银行
        'PSBC', //中国邮政储蓄银行
        'SDB', //深圳发展银行
    	'BOBJ', //北京银行
        'TCCB', //天津银行
    );

    public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id) {
    	require_once(VENDOR_PATH."payment3/guofubao/HttpClient.class.php");
        $money = round($money, 2);
        $payment_info = $GLOBALS['db']->getRow("select id,config,logo from " . DB_PREFIX . "payment where id=" . intval($payment_id));
        $payment_info['config'] = unserialize($payment_info['config']);

     
        /* 订单描述，用订单号替代 */
        $payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module,create_time from " . DB_PREFIX . "payment_log where id=" . intval($payment_log_id) . " limit 1");
        $frontMerUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/guofubao_frontback.php';//付款完成后的跳转页面(前台通知)
		$backgroundMerUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/guofubao_back.php';//后台通知页面

        $tranCode = '8888';

        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];

        /* 交易日期 */
        $today = a_toDate($payment_log['create_time'], 'YmdHms');

        
        $bank_id = 0;
        if ($payment_log['rec_module'] == 'Order') {
            $Order = $GLOBALS['db']->getRow("select sn,bank_id from " . DB_PREFIX . "order where id=" . intval($payment_log['rec_id']));
            $data_sn = $Order['sn'];
            $bank_id = $Order['bank_id'];
        } elseif ($payment_log['rec_module'] == 'UserIncharge') {
            $Order = $GLOBALS['db']->getRow("select sn,bank_id from " . DB_PREFIX . "user_incharge where id=" . intval($payment_log['rec_id']));
            $data_sn = $Order['sn'];
            $bank_id = $Order['bank_id'];
        }
        $desc = $data_sn;
        //更新日志表订单号
        $GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$data_sn' where id = ".$payment_log_id);

        /* 编码标准 
          if (String::is_utf8($desc))
          {
          $desc = iconv('utf-8', 'gbk', $desc);
          }
         */
        $desc = a_utf8ToGB($desc);


        /* 货币类型 */
        $currencyType = '156';


        /* 数字签名 */
        $version = '2.1';   
        $tranCode = $tranCode; 
        $merchant_id = $payment_info['config']['merchant_id'];
        $merOrderNum = $data_sn;    
        $tranAmt = $money;     // 总金额 
        $feeAmt = '';  
        $tranDateTime = $today;      
        $frontMerUrl = $frontMerUrl;      
        $backgroundMerUrl = $backgroundMerUrl;   //返回的路径   
        $tranIP = $spbill_create_ip != ""?$spbill_create_ip:''; 
        $gopayServerTime = HttpClient::getGopayServerTime();
        //商户识别码
        $verficationCode = $payment_info['config']['VerficationCode'];    

		$signValue='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchant_id.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=[]gopayOutOrderId=[]tranIP=['.$tranIP.']respCode=[]gopayServerTime=['.$gopayServerTime.']VerficationCode=['.$verficationCode.']';

        $signValue = md5($signValue);

        /*交易参数*/
        $parameter = array(
            'version'=>'2.1',//版本号
            'charset'=>'2',//字符集
            'language'=>'1',//语言种类
            'signType'=>'1',//签名类型
            'tranCode'=>'8888', //交易代码
            
            //用户账户信息
            'merchantID'=>$merchant_id,//商户ID
            'virCardNoIn'=>$payment_info['config']['virCardNoIn'],//转入账户
            
            //订单信息
            'merOrderNum'=>$merOrderNum,//订单号
            'tranAmt'=>$tranAmt,//交易金额
            //'feeAmt'=>'',//手续费  
            'currencyType'=>$currencyType,//币种 default
            'tranDateTime'=> $tranDateTime ,//交易时间
            'tranIP'=>$spbill_create_ip,//用户IP
            
            'goodsName'=>$desc,//商品名称
            'goodsDetail'=>'',//商品描述
            'buyerName'=>'',//买方姓名
            'buyerContact'=>'',//买方联系方式
            
            
            //通知配置
            'frontMerUrl'=>$frontMerUrl,//前台通知地址
            'backgroundMerUrl'=>$backgroundMerUrl,//后台通知地址
            
            //生成信息
            'signValue'=>$signValue,//密文串
            'gopayServerTime'=>  $gopayServerTime ,//服务器时间
            
            //银行直连必填
            'bankCode'=>$bank_id,//银行代码
            'userType'=>1,//用户类型1（为个人支付）；2（为企业支付）

            //可选参数
            'feeAmt'=>'',
            'isRepeatSubmit'=>'',
            'merRemark1'=>$payment_log_id,  //系统订单号
            'merRemark2'=>'',
            
        );
        
        $def_url = "<style type='text/css'>.bank_types{float:left; display:block; background:url(./global/banklist_hnapay.jpg); font-size:0px; width:160px; height:10px; text-align:left; padding:15px 0px;}";
        $def_url .=".bk_type_CCB{background-position:10px -80px; }"; //中国建设银行
        $def_url .=".bk_type_CMB{background-position:10px -200px; }"; //招商银行
        $def_url .=".bk_type_ICBC{background-position:10px -2px; }"; //中国工商银行
        $def_url .=".bk_type_BOC{background-position:10px -121px; }"; //中国银行
        $def_url .=".bk_type_ABC{background-position:10px -40px; }"; //中国农业银行
        $def_url .=".bk_type_BOCOM{background-position:10px -160px; }"; //交通银行
        $def_url .=".bk_type_CMBC{background-position:10px -240px; }"; //中国民生银行
        $def_url .=".bk_type_HXBC{background-position:10px -360px; }"; //华夏银行
        $def_url .=".bk_type_CIB{background-position:10px -280px; }"; //兴业银行
        $def_url .=".bk_type_SPDB{background-position:10px -317px; }"; //上海浦东发展银行
        $def_url .=".bk_type_GDB{background-position:10px -480px; }"; //广东发展银行
        $def_url .=".bk_type_CITIC{background-position:10px -400px; }"; //中信银行
        $def_url .=".bk_type_CEB{background-position:10px -440px; }"; //光大银行
        $def_url .=".bk_type_PSBC{background-position:10px -520px; }"; //中国邮政储蓄银行
        $def_url .=".bk_type_SDB{background-position:10px -565px; }"; //深圳发展银行
        $def_url .=".bk_type_BOBJ{background-position:10px -698px; }"; //北京银行
        $def_url .=".bk_type_TCCB{background:url(./global/TCCB.gif) no-repeat 24px 0px;padding:18px 0px 13px 0;}"; //天津发展银行
        $def_url .="</style>";
        $def_url .= '<form style="text-align:center;" action="http://www.gopay.com.cn/PGServer/Trans/WebClientAction.do" target="_blank" style="margin:0px;padding:0px" method="get" >';
		
        foreach ($parameter AS $key => $val) {
            $def_url .= "<input type='hidden' name='$key' value='$val' />";
        }
        $def_url .= "<input type='submit' class='paybutton' value=" . a_L('TENCENT_' . $bank_id) . "支付></form>";
        $def_url .= "</form><br clear='both' />";
        $def_url .= "<br clear='both' />";
        $def_url.="<br /><span class='red'>" . a_L("PAY_TOTAL_PRICE") . ":" . a_formatPrice($money) . "</span>";
        return $def_url;
    }

    public function dealResult($get, $post, $request) {
//        //回调日志
//      $request["frontMerUrl"] = urldecode($request["frontMerUrl"]);
//	$request["backgroundMerUrl"] = urldecode($request["backgroundMerUrl"]);
//        $request_log = addslashes(serialize($request));
//
//      需要创建表
//      $GLOBALS['db']->query("insert into ".DB_PREFIX."payment_request_log values('".$request["merchantID"]."','".$request_log."','".(time() - date('Z'))."')");
        $return_res = array(
            'info' => '',
            'status' => false,
        );
		
        /* 取返回参数 */
        $version = $request["version"];
        $charset = $request["charset"];
        $language = $request["language"];
        $signType = $request["signType"];
        $tranCode = $request["tranCode"];
        $merchantID = $request["merchantID"];
        $merOrderNum = $request["merOrderNum"];
        $tranAmt = $request["tranAmt"];
        $feeAmt = $request["feeAmt"];
        $frontMerUrl = $request["frontMerUrl"];
        $backgroundMerUrl = $request["backgroundMerUrl"];
        $tranDateTime = $request["tranDateTime"];
        $tranIP = $request["tranIP"];
        $respCode = $request["respCode"];
        $msgExt = $request["msgExt"];
        $orderId = $request["orderId"];
        $gopayOutOrderId = $request["gopayOutOrderId"];
        $bankCode = $request["bankCode"];
        $tranFinishTime = $request["tranFinishTime"];
        $merRemark1 = $request["merRemark1"];
        $merRemark2 = $request["merRemark2"];
        $signValue = $request["signValue"];

        //参数转换
        $log_id = $merRemark1;  //系统订单号
        $total_price = $tranAmt;//总价
	
        /*获取支付信息*/
        $payment_id = $GLOBALS['db']->getOne("select payment_id from " . DB_PREFIX . "payment_log where id=" . intval($log_id));
        $payment = $GLOBALS['db']->getRow("select id,config,currency from " . DB_PREFIX . "payment where id=" . $payment_id);
        $payment['config'] = unserialize($payment['config']);
        $currency_id = $payment['currency'];
		
        /*比对连接加密字符串*/
		$signValue2='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=['.$orderId.']gopayOutOrderId=['.$gopayOutOrderId.']tranIP=['.$tranIP.']respCode=['.$respCode.']gopayServerTime=[]VerficationCode=['.$payment['config']['VerficationCode'].']';
        $signValue2 = md5(htmlspecialchars_decode($signValue2));
		
        if ($signValue !=$signValue2) {
            $return_res['info'] = "验证失败";
            return $return_res;
        } 
        if($respCode=='0000' && $orderId!=''){
            return s_order_paid($log_id, $total_price, $payment_id, $currency_id,$orderId);
            /* 改变订单状态 */
        }
        else{
		   return false;
		}   
    }

//自动对账
	public function autoNotice(){
		$res = $this->dealResult($_GET,$_POST,$_REQUEST);
		if($res['status'])
		{	
		     echo 'RespCode=0000|JumpURL=""';
		}
		else
		{
			echo 'RespCode=9999|JumpURL=""';
		}
	}
    
    public function getBackList($payment_id) {
        $payment_info = $GLOBALS['db']->getRow("select id,config,logo,description_1,name_1 from " . DB_PREFIX . "payment where id=" . intval($payment_id));
        $payment_info['config'] = unserialize($payment_info['config']);

        $def_url = "<style type='text/css'>.bank_gfb_types{float:left; display:block; background:url(./global/banklist_hnapay.jpg); font-size:0px; width:160px; height:10px; text-align:left; padding:15px 0px;_padding:11px 0px;}";
        $def_url .=".bk_type_CCB{background-position:10px -80px; }"; //中国建设银行
        $def_url .=".bk_type_CMB{background-position:10px -200px; }"; //招商银行
        $def_url .=".bk_type_ICBC{background-position:10px -2px; }"; //中国工商银行
        $def_url .=".bk_type_BOC{background-position:10px -121px; }"; //中国银行
        $def_url .=".bk_type_ABC{background-position:10px -40px; }"; //中国农业银行
        $def_url .=".bk_type_BOCOM{background-position:10px -160px; }"; //交通银行
        $def_url .=".bk_type_CMBC{background-position:10px -240px; }"; //中国民生银行
        $def_url .=".bk_type_HXBC{background-position:10px -360px; }"; //华夏银行
        $def_url .=".bk_type_CIB{background-position:10px -280px; }"; //兴业银行
        $def_url .=".bk_type_SPDB{background-position:10px -317px; }"; //上海浦东发展银行
        $def_url .=".bk_type_GDB{background-position:10px -480px; }"; //广东发展银行
        $def_url .=".bk_type_CITIC{background-position:10px -400px; }"; //中信银行
        $def_url .=".bk_type_CEB{background-position:10px -440px; }"; //光大银行
        $def_url .=".bk_type_PSBC{background-position:10px -520px; }"; //中国邮政储蓄银行
        $def_url .=".bk_type_SDB{background-position:10px -565px; }"; //深圳发展银行
        $def_url .=".bk_type_BOBJ{background-position:10px -698px; }"; //北京银行
        $def_url .=".bk_type_TCCB{background:url(./global/TCCB.gif) no-repeat 24px 0px;padding:18px 0px 13px 0;}"; //天津发展银行
        $def_url .="</style>";
        //$def_url .= '<form style="text-align:center;" action="https://www.gopay.com.cn/PGServer/Trans/WebClientAction.do" target="_blank" style="margin:0px;padding:0px" method="post" >';


        $ks = 0;
        $def_url .="<p style='background:url(./global/guofubao.gif) no-repeat 0px 0px; padding-left:100px;height:33px;line-height:33px;'><strong>".$payment_info['name_1']."</strong>&nbsp;银行：</p>";
        foreach ($this->bank_types as $key => $bank_type) {
            if (intval($payment_info['config']['tencentpay_gateway'][$bank_type]) == 1) {
                $def_url .="<label class='bank_gfb_types bk_type_" . $bank_type . "'><input id= check-" . $bank_type . " type='radio' name='payment' value='" . $bank_type . '-' . $payment_id . "'";
                if ($ks == 0) {
                    //$def_url .= " checked='checked'";
                }
                $def_url .= " /></label>" . $payment_info['description_1'];
                $ks++;
            }
        }
        $def_url .= "<br clear='both' />";
        $def_url .= "<br clear='both' />";
        return $def_url;
    }
    /**
     * 字符转义
     * @return string
     */
    function fStripslashes($string)
    {
            if(is_array($string))
            {
                    foreach($string as $key => $val)
                    {
                            unset($string[$key]);
                            $string[stripslashes($key)] = fStripslashes($val);
                    }
            }
            else
            {
                    $string = stripslashes($string);
            }

            return $string;
    }

}

?>
