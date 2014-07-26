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

// 邮件模板模型
class MailTemplateModel extends CommonModel {
	//用于外部模块调用的邮件发送函数
	public function sendOrderMail($template_name,$order_id)
	{
			if(fanweC("MAIL_ON")==1)
			{
				$mail_template = D("MailTemplate")->where("name='".$template_name."'")->find();
				$order_info = D("Order")->getById($order_id);
				$user_info = D("User")->getById($order_info['user_id']);
				if($user_info)
				{
					$username = $user_info['user_name'];
					if($user_info['nickname']!='')
					{
						$username .= "(".$user_info['nickname'].")";
					}
				}
				else 
				{
					$username = L("NO_USER");
				}
				if($mail_template)
				{
							$mail = new Mail();	
							$mail->IsHTML($mail_template['is_html']); // 设置邮件格式为 HTML
							$mail_title = $mail_template['mail_title'];
							$mail_content = $mail_template['mail_content'];
							$mail_title = str_replace("{\$username}",$username,$mail_title);
							$mail_title = str_replace("{\$order_sn}",$order_info['sn'],$mail_title);
							$mail_content = str_replace("{\$username}",$username,$mail_content);
							$mail_content = str_replace("{\$order_sn}",$order_info['sn'],$mail_content);
							
							$mail->Subject = $mail_title; // 标题					
							$mail->Body =  $mail_content; // 内容
							$mail->AddAddress($order_info['email'],$username);	
							if(!$mail->Send())
							{
								return $mail->ErrorInfo;
							}
							else 
							{
								return L("SEND_SUCCESS");
							}
				}
			}
			else 
			{
				return '';
			}
	}
	//发送相应团购信息的邮件通知
	//$goods_ids 团购商品的ID集合
	public function sendGroupInfoMail($goods_ids,$time)
	{
		if(fanweC("MAIL_ON"))
		{
			if(!$time)
			{
				$time = gmtTime();
			}
			$group_list = M("Goods")->where(array('id'=>array('in',$goods_ids)))->findAll();
			
			if($group_list)
			{
				foreach($group_list as $k=>$v)
				{
					$mail_data['mail_title'] = $v['name_1'];
					$mail_data['is_html'] = 1;
					$mail_data['send_time'] = $time;
					$mail_data['status'] = 0;
					$mail_data['goods_id'] = $v['id'];
					$mail_id = M("MailList")->add($mail_data);
					
					$mail_list = D("MailAddressList")->where("city_id=".$v['city_id'])->findAll();
					foreach($mail_list as $row=>$mail_item)
					{
						$address_link_data['mail_id'] = $mail_id;
						$address_link_data['mail_address_id'] = $mail_item['id'];
						M("MailAddressSendList")->add($address_link_data);							
					}
				}				
							
			}	
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>