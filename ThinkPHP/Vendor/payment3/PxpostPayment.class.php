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
    $modules[$i]['code']    = 'Pxpost';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'DPS_Pxpost';

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

// 余额支付模型
require_once(VENDOR_PATH.'payment3/Payment.class.php');
class PxpostPayment implements Payment {
	public $config = array(
	    'pxpost_postusername'=>'SnatchItUpDev',  //商户编号
        'pxpost_postuassword'=>'test1234',  //商户密钥
        'pxpost_inputcurrency'=>'USD',  //商户密钥
	);
		
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		$PostUsername = trim($payment_info['config']['pxpost_postusername']);
		$PostPassword = trim($payment_info['config']['pxpost_postuassword']);
		$InputCurrency = trim($payment_info['config']['pxpost_inputcurrency']);
		$amount = number_format(round($money,2),2);
				
				
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");		
		if($payment_log['rec_module']=='Order'){
			$info = $GLOBALS['db']->getRow("select sn,card_info from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$info = $GLOBALS['db']->getRow("select sn,card_info from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
		}
		
		$card_info = unserialize($info['card_info']);
		$card_info[cc_expiry_year] = substr($card_info[cc_expiry_year], -2);
		//var_dump($card_info);
						   						
		$cmdDoTxnTransaction .= "<Txn>";
		$cmdDoTxnTransaction .= "<PostUsername>$PostUsername</PostUsername>"; #Insert your DPS Username here
		$cmdDoTxnTransaction .= "<PostPassword>$PostPassword</PostPassword>"; #Insert your DPS Password here
		$cmdDoTxnTransaction .= "<Amount>$amount</Amount>";
		$cmdDoTxnTransaction .= "<InputCurrency>$InputCurrency</InputCurrency>";
		$cmdDoTxnTransaction .= "<CardHolderName>$card_info[cc_card_owner]</CardHolderName>";
		$cmdDoTxnTransaction .= "<CardNumber>$card_info[cc_card_number]</CardNumber>";
		$cmdDoTxnTransaction .= "<DateExpiry>$card_info[cc_expiry_month]$card_info[cc_expiry_year]</DateExpiry>";
		$cmdDoTxnTransaction .= "<TxnType>Purchase</TxnType>";
		$cmdDoTxnTransaction .= "<MerchantReference>$info[sn]</MerchantReference>";
		$cmdDoTxnTransaction .= "</Txn>";
			
		$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$info[sn]' where id = ".$payment_log_id);	
		//var_dump($cmdDoTxnTransaction);
	
		$dps_url = "https://www.paymentexpress.com/pxpost.aspx";
				 
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $dps_url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $cmdDoTxnTransaction);
        curl_setopt($curl, CURLOPT_POSTFIELDSIZE, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSLVERSION, 3);

        if (strtoupper(substr(@php_uname('s'), 0, 3)) === 'WIN') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }

        if ($result = curl_exec($curl)) {
            $dps_authorized = $this->_dps_attribute_value('Authorized',$result);
			//var_dump($dps_authorized);
			//var_dump($result);
			if ($dps_authorized == 1){
				$result = s_order_paid($payment_log_id,$money,$payment_id,$currency_id);
				$def_url = $result['info'];
				return $def_url;				
			}else{
				return '支付失败，请重试:'.$this->_dps_attribute_value('Transaction',$result);
			}
        } else {
			return '支付失败，请重试';
        }
        curl_close($curl);
	}
	
    // selection
    public function selection($payid) {
		$def_url = "<table class='table-list' style='margin-top:0''>";
		$def_url .="<tr><td>Credit Card Owner:</td><td style='margin-left:1in'><input type='text' payid='$payid' name='dps_cc_owner' id='dps_cc_owner' value=''/></td><tr>";
		$def_url .="<tr><td>Credit Card Number:</td><td style='margin-left:1in'><input type='text' payid='$payid' name='dps_cc_number' id='dps_cc_number' maxLength=16 value=''/></td><tr>";
		$def_url .="<tr><td>Credit Card Expiry Date:</td><td style='margin-left:1in'><select payid='$payid' name='dps_cc_expires_month' id='dps_cc_expires_month' >";
        for ($ii=1; $ii<13; $ii++) {
        	$mm = sprintf('%02d', $ii);
        	$def_url .="<option value=".$mm.">".strftime('%B',mktime(0,0,0,$ii,1,2000))."($mm)</option>";
        }
        $def_url .=" </select>&nbsp;";
       
		$def_url .="<select payid='$payid' name='dps_cc_expires_year' id='dps_cc_expires_year' >";
        $today = getdate();
        
        for ($ii=$today['year']; $ii < $today['year']+10; $ii++) {
        	$def_url .="<option value=".strftime('%Y',mktime(0,0,0,1,1,$ii))." >".strftime('%Y',mktime(0,0,0,1,1,$ii))."</option>";
        }
        $def_url .=" </select></td><tr>";
               
        $def_url .="<tr><td>CVV Number:</td><td style='margin-left:1in'><input payid='$payid' type='text' name='dps_cvv' id='dps_cvv' maxLength=4 value=''/></td><tr>";
        
        $def_url .="</table>";      
        //echo $def_url;
        return $def_url;
    }
    	
    // pre confirmation check
   function pre_confirmation_check() {
    	require_once(VENDOR_PATH.'payment3/classes/cc_validation.php');
     
        $cc_validation = new cc_validation();
        $result = $cc_validation->validate($_REQUEST['dps_cc_number'], $_REQUEST['dps_cc_expires_month'], $_REQUEST['dps_cc_expires_year']);
        $error = '';
        switch ($result) {
            case -1:
                $error = sprintf('The credit card number starting with %s was not entered correctly, or we do not accept that kind of card. Please try again or use another credit card.', substr($cc_validation->cc_number, 0, 4));
                break;
            case -2:
            case -3:
            case -4:
                $error = 'The expiration date entered for the credit card is invalid. Please check the date and try again.';
                break;
            case false:
                $error = 'The credit card number entered is invalid. Please check the number and try again.';
                break;
        }

		//$resultinfo['result'] = false;				   
		//$resultinfo['error'] = $cc_validation->cc_type.';'.$result;
		//return $resultinfo; 
		
		$cc_card_owner = trim($_REQUEST['dps_cc_owner']);
		$cc_card_cvv = trim($_REQUEST['dps_cvv']);
		if (strlen($cc_card_cvv) < 3){
			$error = '* The CVV number must be at least 3 characters.';
			$result = -5;
		}

		$card_info = array("cc_card_type"=>$cc_validation->cc_type,
						   "cc_card_owner"=>$cc_card_owner,
						   "cc_card_number"=>$cc_validation->cc_number,
						   "cc_expiry_month"=>$cc_validation->cc_expiry_month,
						   "cc_expiry_year"=>$cc_validation->cc_expiry_year,
						   "cc_card_cvv"=>$cc_card_cvv,
						   );
						   
		if ( ($result == false) || ($result < 1) ) {
			$resultinfo['result'] = false;
			$resultinfo['error'] = $error;
		}else{
			$resultinfo['result'] = true;
			$resultinfo['card_info'] = 	$card_info;
			//$resultinfo['error'] = serialize($card_info);
		}				   
		//$resultinfo['result'] = empty($error) ? true : false;				   
		return $resultinfo;  
    }    	
    
	public function dealResult($get,$post,$request)
	{
		return L("INVALID_OPERATION");
	}
	
    function _dps_attribute_value($attribute,$string) {
    	list(,$exploded_value) = explode('<'.$attribute.'>',$string);
    	return substr($exploded_value,0,strpos($exploded_value,'</'.$attribute.'>'));
    }	
}
?>