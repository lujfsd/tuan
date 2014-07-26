<?php

// +----------------------------------------------------------------------
// | 财付通担保交易
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: jobin.lin(jobin.lin@gmail.com)
// +----------------------------------------------------------------------

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE) {
    $i = (isset($modules)) ? count($modules) : 0;

    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $modules[$i]['code'] = 'TenpayModel2';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name'] = '(新版)财付通担保交易支付';

    /* 支付方式：1：在线支付；0：线下支付 */
    $modules[$i]['online_pay'] = '1';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '3.0';

    /* 插件的作者 */
    $modules[$i]['author'] = 'FANWE R&D TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.fanwe.com';

    return;
}

//模型
require_once(VENDOR_PATH . 'payment3/Payment.class.php');

class TenpayModel2Payment implements Payment {

    //后台用户定义参数
    public $config = array(
        'tenpaymodel2_key' => '', //平台商密钥
        'tenpaymodel2_id' => '', //商户号
    );

    /*
     * 设置请求参数
     */

    public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id) {
        require_once ROOT_PATH . 'ThinkPHP/Vendor/payment3/tenpay/RequestHandler.class.php';

        $money = round($money, 2);
        $payment_info = $GLOBALS['db']->getRow("select id,config,logo from " . DB_PREFIX . "payment where id=" . intval($payment_id));
        $payment_info['config'] = unserialize($payment_info['config']);
        /* 订单描述，用订单号替代 */
        $payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module,create_time from " . DB_PREFIX . "payment_log where id=" . intval($payment_log_id) . " limit 1");


        //系统级别输入参数
        $key = $payment_info['config']['tenpaymodel2_key'];
        $partner = $payment_info['config']['tenpaymodel2_id'];


        if (!file_exists(ROOT_PATH . 'Public/tenpay_config.txt')) {
            $key_partner = array();
            $key_partner['key'] = $key;
            $key_partner['partner'] = $partner;
            $tenpay_config = json_encode($key_partner);
            $fp = fopen(ROOT_PATH . 'Public/tenpay_config.txt', "w+"); //fopen()的其它开关请参看相关函数
            fputs($fp, $tenpay_config);
            fclose($fp);
        }else{
            $json_tenpay_config = file_get_contents(ROOT_PATH . 'Public/tenpay_config.txt');
            $tenpay_config = json_decode($json_tenpay_config);
            $tenpay_config = (array) $tenpay_config;
            if($key !=$tenpay_config['key'] && $partner !=$tenpay_config['partner']){
                $key_partner = array();
                $key_partner['key'] = $key;
                $key_partner['partner'] = $partner;
                $tenpay_config = json_encode($key_partner);
                $fp = fopen(ROOT_PATH . 'Public/tenpay_config.txt', "w+"); //fopen()的其它开关请参看相关函数
                fputs($fp, $tenpay_config);
                fclose($fp);
            }
        }

//应用级输入参数

        /* 订单号 */
        if ($payment_log['rec_module'] == 'Order') {
            $data_sn = $GLOBALS['db']->getOne("select sn from " . DB_PREFIX . "order where id=" . intval($payment_log['rec_id']));
        } elseif ($payment_log['rec_module'] == 'UserIncharge') {
            $data_sn = $GLOBALS['db']->getOne("select sn from " . DB_PREFIX . "user_incharge where id=" . intval($payment_log['rec_id']));
        }
        //系统订单号
        $out_trade_no = $payment_log_id;
        //内容
        $desc = $data_sn;

        /* 编码标准
          if (String::is_utf8($desc))
          {
          $desc = iconv('utf-8', 'gbk', $desc);
          }
         */
        $desc = a_utf8ToGB($desc);


        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];

        /* 交易日期 */
        $today = a_toDate($payment_log['create_time'], 'YmdHsm');

        /* 返回的路径 */
        $return_url = 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . '/tenpay_back.php';

        /* 通知路径 */
        $notify_url = 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . '/tenpay_notify.php';

        /* 总金额 */
        $total_fee = $money * 100;    //分为单位

        /* 货币类型 */
        $fee_type = '1';


        /* 创建支付请求对象 */
        $reqHandler = new RequestHandler();
        $reqHandler->init();
        $reqHandler->setKey($key);
        $reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

//----------------------------------------
//设置支付参数
//----------------------------------------
        $reqHandler->setParameter("partner", $partner);     //商户号
        $reqHandler->setParameter("out_trade_no", $out_trade_no);
        $reqHandler->setParameter("total_fee", $total_fee);  //总金额
        $reqHandler->setParameter("return_url", $return_url);
        $reqHandler->setParameter("notify_url", $notify_url);
        $reqHandler->setParameter("body", $desc);
        $reqHandler->setParameter("bank_type", "DEFAULT");     //银行类型，默认为财付通
//用户ip
        $reqHandler->setParameter("spbill_create_ip", $spbill_create_ip); //客户端IP
        $reqHandler->setParameter("fee_type", $fee_type);               //币种
        $reqHandler->setParameter("subject", $desc);          //商品名称，（中介交易时必填）
//系统可选参数
        $reqHandler->setParameter("sign_type", "MD5");       //签名方式，默认为MD5，可选RSA
        $reqHandler->setParameter("service_version", "1.0");    //接口版本号
        $reqHandler->setParameter("input_charset", "GBK");      //字符集
        $reqHandler->setParameter("sign_key_index", "1");       //密钥序号
//业务可选参数
        $reqHandler->setParameter("attach", $payment_log_id);                //附件数据，原样返回就可以了
        $reqHandler->setParameter("product_fee", "");           //商品费用
        $reqHandler->setParameter("transport_fee", "0");         //物流费用
        $reqHandler->setParameter("time_start", $today);  //订单生成时间
        $reqHandler->setParameter("time_expire", "");             //订单失效时间
        $reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
        $reqHandler->setParameter("goods_tag", "");               //商品标记
        $reqHandler->setParameter("trade_mode", "2");              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
        $reqHandler->setParameter("transport_desc", "");              //物流说明
        $reqHandler->setParameter("trans_type", "2");              //交易类型
        $reqHandler->setParameter("agentid", "");                  //平台ID
        $reqHandler->setParameter("agent_type", "");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
        $reqHandler->setParameter("seller_id", "");                //卖家的商户号

        $reqHandler->createSign();
        $parameter = $reqHandler->getAllParameters();

        $GLOBALS['db']->query("update " . DB_PREFIX . "payment_log set pay_code = '$data_sn' where id = " . $payment_log_id);

        $def_url = '<br /><form style="text-align:center;" action="' . $reqHandler->getGateURL() . '" target="_blank" style="margin:0px;padding:0px" method="post">';

        foreach ($parameter AS $key => $val) {
            $def_url .= "<input type='hidden' name='$key' value='$val' />";
        }

        if (!empty($payment_info['logo']))
            $def_url .= "<input type='image' src='" . __ROOT__ . $payment_info['logo'] . "' style='border:solid 1px #ccc;'><div class='blank'></div>";

        $def_url .= "<input type='submit' class='paybutton' value='前往财付通支付'>
         </form>";

        $def_url.="<br /><span class='red'>" . a_L("PAY_TOTAL_PRICE") . ":" . a_formatPrice($money) . "</span>";
        return $def_url;
    }

    /**
     * 返回支付结果
     * @param type $get
     * @param type $post
     * @param type $request
     * @return string
     */
    public function dealResult($get, $post, $request) {
        require_once ROOT_PATH . 'ThinkPHP/Vendor/payment3/tenpay/ResponseHandler.class.php';
        require_once ROOT_PATH . 'ThinkPHP/Vendor/payment3/tenpay/function.php';
        log_result("进入前台回调页面");
        $return_res = array(
            'info' => '',
            'status' => false,
        );

        if (file_exists(ROOT_PATH . 'Public/tenpay_config.txt')) {
            $json_tenpay_config = file_get_contents(ROOT_PATH . 'Public/tenpay_config.txt');
            $tenpay_config = json_decode($json_tenpay_config);
            $tenpay_config = (array) $tenpay_config;
        }

        //系统级别输入参数
        $key = $tenpay_config['key'];
        $partner = $tenpay_config['partner'];

        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($key);


        //判断签名
        if ($resHandler->isTenpaySign()) {

            //通知id
            $notify_id = $resHandler->getParameter("notify_id");
            //商户订单号
            $out_trade_no = $resHandler->getParameter("out_trade_no");
            //财付通订单号
            $transaction_id = $resHandler->getParameter("transaction_id");
            //金额,以分为单位
            $total_fee = $resHandler->getParameter("total_fee");
            //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
            $discount = $resHandler->getParameter("discount");
            //支付结果
            $trade_state = $resHandler->getParameter("trade_state");
            //交易模式,1即时到账
            $trade_mode = $resHandler->getParameter("trade_mode");

            //开始初始化参数
            $payment_log_id = $resHandler->getParameter("attach"); //payment_log唯一编号
            $payment_id = $GLOBALS['db']->getOne("select payment_id from " . DB_PREFIX . "payment_log where id=" . intval($payment_log_id));
            $payment_info = $GLOBALS['db']->getRow("select id,config,currency from " . DB_PREFIX . "payment where id =" . $payment_id);
            $payment_info['config'] = unserialize($payment_info['config']);
            //$payment_id = $payment['id'];
            $currency_id = $payment_info['currency'];


            if ("2" == $trade_mode) {
                if ("0" == $trade_state) {
                    //记录财付通返回的 财付通订单号
                    $GLOBALS['db']->query("update " . DB_PREFIX . "payment_log set pay_back_code = '$transaction_id' where id = " . $payment_log_id);
                    return s_order_paid($payment_log_id, $total_fee, $payment_id, $currency_id);
                } else {
                    //当做不成功处理
                    $return_res['info'] = "中介担保支付失败";
                    log_result($return_res['info']);
                }
            }
        } else {
            $return_res['info'] = "认证签名失败" . $resHandler->getDebugInfo();
        }
        log_result($return_res['info']);
        return $return_res;
    }

    //自动对账
    public function autoNotice() {
        require_once VENDOR_PATH.'payment3/tenpay/ResponseHandler.class.php';
        require_once VENDOR_PATH.'payment3/tenpay/RequestHandler.class.php';
        require_once VENDOR_PATH.'payment3/tenpay/client/ClientResponseHandler.class.php';
        require_once VENDOR_PATH.'payment3/tenpay/client/TenpayHttpClient.class.php';
        require_once VENDOR_PATH.'payment3/tenpay/function.php';
        file_put_contents(VENDOR_PATH."/payment3/1.txt",print_r($_REQUEST,1));
        log_result("==============【开始】===================");
        log_result("1--进入后台回调页面");

        $return_res = array(
            'info' => '',
            'status' => false,
        );
        if (file_exists(ROOT_PATH . 'Public/tenpay_config.txt')) {
            $json_tenpay_config = file_get_contents(ROOT_PATH . 'Public/tenpay_config.txt');
            $tenpay_config = json_decode($json_tenpay_config);
            $tenpay_config = (array) $tenpay_config;
        }
        log_result("2--用户配置：" . $json_tenpay_config);
		log_result("tenpay_config：" . print_r($tenpay_config,1));

        //系统级别输入参数
        $key = $tenpay_config['key'];
        $partner = $tenpay_config['partner'];
        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($key);
        log_result("3--pra：" . print_r($resHandler->getAllParameters(),1));
		log_result("3--sign：" . print_r($resHandler->isTenpaySign(),1));
//判断签名
        if ($resHandler->isTenpaySign()) {
			log_result("123456：123" );
            //通知id
            $notify_id = $resHandler->getParameter("notify_id");

            //通过通知ID查询，确保通知来至财付通
            //创建查询请求
            $queryReq = new RequestHandler();
            $queryReq->init();
            $queryReq->setKey($key);
            $queryReq->setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml");
            $queryReq->setParameter("partner", $partner);
            $queryReq->setParameter("notify_id", $notify_id);

            //通信对象
            $httpClient = new TenpayHttpClient();
            $httpClient->setTimeOut(5);
            //设置请求内容
            log_result("getRequestURL：" . $queryReq->getRequestURL());
            log_result("setReqContent：" . $httpClient->setReqContent($queryReq->getRequestURL()));
            log_result("setReqContent2：" . print_r($httpClient->setReqContent($queryReq->getRequestURL())));
            $httpClient->setReqContent($queryReq->getRequestURL());
			log_result("call：" . $httpClient->call());
			log_result("end：endendendendend");
            //后台调用
            if ($httpClient->call()) {
                //设置结果参数
                $queryRes = new ClientResponseHandler();
                $queryRes->setContent($httpClient->getResContent());
                $queryRes->setKey($key);

                if ($resHandler->getParameter("trade_mode") == "2") {
                    //判断签名及结果（中介担保）
                    //只有签名正确,retcode为0，trade_state为0才是支付成功
                    if ($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0") {
                        log_result("3--中介担保验签ID成功");
                        //取结果参数做业务处理
                        $out_trade_no = $resHandler->getParameter("out_trade_no");
                        //财付通订单号
                        $transaction_id = $resHandler->getParameter("transaction_id");
                        
                        $pars = $resHandler->getAllParameters();
                        foreach($pars as $k=>$v){
                            $pars_str .= $k.'='.$v.'&';
                        }
                        log_result("getAllParameters:".$pars_str);
                        //开始初始化参数
                        $payment_log_info = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "payment_log where id=" . intval($out_trade_no));
                        $payment_log_id = $payment_log_info['id'];
                        $payment_id = $payment_log_info['payment_id'];
                        $total_fee = $payment_log_info['money'];
                        
                        $payment_info = $GLOBALS['db']->getRow("select id,config,currency from " . DB_PREFIX . "payment where id =" . $payment_id);
                        $payment_info['config'] = unserialize($payment_info['config']);
                        //$payment_id = $payment['id'];
                        $currency_id = $payment_info['currency'];
                        //------------------------------
                        //处理业务开始
                        //------------------------------
                        //处理数据库逻辑
                        //注意交易单不要重复处理
                        //注意判断返回金额
                        log_result("4--中介担保后台回调，trade_state=" + $resHandler->getParameter("trade_state"));
                        switch ($resHandler->getParameter("trade_state")) {
                            case "0": //付款成功
                                log_result("【付款成功】");
                                if($payment_log_info['is_paid'] == 0 && $payment_log_info['pay_back_code']==''&& $total_fee>0){
                                    $GLOBALS['db']->query("update " . DB_PREFIX . "payment_log set pay_back_code = '$transaction_id' where  id=" . intval($payment_log_id));
                                    log_result("订单参数：payment_log_id=".$payment_log_id."; total_fee=".$total_fee." ; payment_id=".$payment_id."; currency_id=".$currency_id);
                                    s_order_paid($payment_log_id, $total_fee, $payment_id, $currency_id);
                                }
                                break;
                            case "1": //交易创建

                                break;
                            case "2": //收获地址填写完毕

                                break;
                            case "4": //卖家发货成功

                                break;
                            case "5": //买家收货确认，交易成功

                                break;
                            case "6": //交易关闭，未完成超时关闭

                                break;
                            case "7": //修改交易价格成功

                                break;
                            case "8": //买家发起退款

                                break;
                            case "9": //退款成功

                                break;
                            case "10": //退款关闭			

                                break;
                            default:
                                //nothing to do
                                break;
                        }
                        log_result("5--success");
                        //------------------------------
                        //处理业务完毕
                        //------------------------------
                        echo "success";
                    } else {
                        //错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
                        //echo "验证签名失败 或 业务错误信息:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes->

                        log_result("6--中介担保后台回调失败");
                        log_result("7--验证签名失败 或 业务错误信息:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes->getParameter("retcode") . "retmsg=" . $queryRes->getParameter("retmsg"));
                        echo "fail";
                    }
                }



                //获取查询的debug信息,建议把请求、应答内容、debug信息，通信返回码写入日志，方便定位问题
                /*
                  echo "<br>------------------------------------------------------<br>";
                  echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
                  echo "query req:" . htmlentities($queryReq->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
                  echo "query res:" . htmlentities($queryRes->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
                  echo "query reqdebug:" . $queryReq->getDebugInfo() . "<br><br>" ;
                  echo "query resdebug:" . $queryRes->getDebugInfo() . "<br><br>";
                 */
            } else {
                //通信失败
                echo "fail";
            }
        } else {
            log_result("8--认证签名失败" . $resHandler->getDebugInfo());
            echo "<br/>" . "认证签名失败" . "<br/>";
            echo $resHandler->getDebugInfo() . "<br>";
        }
        log_result(">>>>>>>>>>>>>>>>【结束】<<<<<<<<<<<<<<<<<<<<<");
    }

}

?>
