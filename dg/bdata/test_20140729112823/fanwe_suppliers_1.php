<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_suppliers`;");
E_C(\"CREATE TABLE `fanwe_suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `web` varchar(200) NOT NULL,
  `img` varchar(200) NOT NULL,
  `address` varchar(250) NOT NULL,
  `map` mediumtext NOT NULL,
  `tel` varchar(200) NOT NULL,
  `operating` varchar(200) NOT NULL,
  `bus` mediumtext NOT NULL,
  `brief` mediumtext NOT NULL,
  `desc` mediumtext NOT NULL,
  `status` tinyint(1) NOT NULL,
  `codebar` varchar(50) NOT NULL DEFAULT 'BCGcode39',
  `resolution` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1,2,3',
  `pwd` varchar(255) NOT NULL,
  `last_ip` varchar(50) NOT NULL,
  `bond_tmpl` text NOT NULL,
  `h_pwd_groupbond` tinyint(1) DEFAULT '0',
  `api_address` varchar(255) NOT NULL,
  `xpoint` varchar(255) NOT NULL,
  `ypoint` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL DEFAULT '',
  `address_2` varchar(255) NOT NULL DEFAULT '',
  `address_3` varchar(255) NOT NULL DEFAULT '',
  `address_4` varchar(255) NOT NULL DEFAULT '',
  `address_5` varchar(255) NOT NULL DEFAULT '',
  `map_1` varchar(255) NOT NULL DEFAULT '',
  `map_2` varchar(255) NOT NULL DEFAULT '',
  `map_3` varchar(255) NOT NULL DEFAULT '',
  `map_4` varchar(255) NOT NULL DEFAULT '',
  `map_5` varchar(255) NOT NULL DEFAULT '',
  `cate_id` int(10) DEFAULT '0',
  `is_brand` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `cate_id` (`cate_id`),
  KEY `is_brand` (`is_brand`),
  KEY `sort` (`sort`),
  KEY `index_1` (`cate_id`,`is_brand`),
  KEY `index_2` (`cate_id`,`is_brand`,`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_suppliers` values('10','缘始陶艺','','/Public/upload/attachment/201407/53d21bf788a22.png','江桥万达广场','','15316550058','9:00--19:00','地铁13号线','一剖粘土~创出无限魅力！','<br />','1','BCGcode39','1','e10adc3949ba59abbe56e057f20f883e','127.0.0.1','<div style=\"margin:0px auto;font-size:14px;\">\r\n	<table cellspacing=\"0\" cellpadding=\"0\">\r\n		<tbody>\r\n			<tr>\r\n				<td width=\"57%\">\r\n					<br />\r\n				</td>\r\n				<td style=\"font-family:verdana;font-size:22px;font-weight:bolder;\" width=\"43%\">\r\n					#{\$bond.sn}<br />\r\n{\$bond.password}\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td height=\"8\" colspan=\"2\">\r\n					<br />\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td bgcolor=\"#000000\" height=\"1\" colspan=\"2\">\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td height=\"8\" colspan=\"2\">\r\n					<br />\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"font-family:微软雅黑;font-size:28px;font-weight:bolder;\" colspan=\"2\">\r\n					{\$bond.goods_name}\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td colspan=\"2\">\r\n					&nbsp;\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td width=\"400\">\r\n					贵宾<br />\r\n{\$user.user_name}<br />\r\n有效期<br />\r\n					<p>\r\n						截止至:{\$bond.end_time_format}\r\n					</p>\r\n商家名称：<br />\r\n{\$suppliers.name}<br />\r\n商家电话：<br />\r\n{\$suppliers.tel}<br />\r\n商家地址:<br />\r\n{\$suppliers.address}<br />\r\n交通路线:<br />\r\n{\$suppliers.bus}<br />\r\n营业时间：<br />\r\n{\$suppliers.operating}<br />\r\n				</td>\r\n				<td>\r\n					<div>\r\n					</div>\r\n<br />\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<br />\r\n				</td>\r\n				<td align=\"middle\">\r\n					<br />\r\n条码：<br />\r\n{\$suppliers.barcode}\r\n				</td>\r\n			</tr>\r\n		</tbody>\r\n	</table>\r\n</div>','0','上海江桥万达广场缘始陶艺吧','121.330723','31.247015','','','','','','','','','','','0','1','1');");
E_D("replace into `fanwe_suppliers` values('11','无形陶艺','','','上海陆家嘴','','','','','这里是商家简介','这里是商家描述','1','BCGcode39','1','e10adc3949ba59abbe56e057f20f883e','127.0.0.1','<div style=\"margin:0px auto;font-size:14px;\">\r\n	<table cellspacing=\"0\" cellpadding=\"0\">\r\n		<tbody>\r\n			<tr>\r\n				<td width=\"57%\">\r\n					<br />\r\n				</td>\r\n				<td style=\"font-family:verdana;font-size:22px;font-weight:bolder;\" width=\"43%\">\r\n					#{\$bond.sn}<br />\r\n{\$bond.password}\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td height=\"8\" colspan=\"2\">\r\n					<br />\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td bgcolor=\"#000000\" height=\"1\" colspan=\"2\">\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td height=\"8\" colspan=\"2\">\r\n					<br />\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"font-family:微软雅黑;font-size:28px;font-weight:bolder;\" colspan=\"2\">\r\n					{\$bond.goods_name}\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td colspan=\"2\">\r\n					&nbsp;\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td width=\"400\">\r\n					贵宾<br />\r\n{\$user.user_name}<br />\r\n有效期<br />\r\n					<p>\r\n						截止至:{\$bond.end_time_format}\r\n					</p>\r\n商家名称：<br />\r\n{\$suppliers.name}<br />\r\n商家电话：<br />\r\n{\$suppliers.tel}<br />\r\n商家地址:<br />\r\n{\$suppliers.address}<br />\r\n交通路线:<br />\r\n{\$suppliers.bus}<br />\r\n营业时间：<br />\r\n{\$suppliers.operating}<br />\r\n				</td>\r\n				<td>\r\n					<div>\r\n					</div>\r\n<br />\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td>\r\n					<br />\r\n				</td>\r\n				<td align=\"middle\">\r\n					<br />\r\n条码：<br />\r\n{\$suppliers.barcode}\r\n				</td>\r\n			</tr>\r\n		</tbody>\r\n	</table>\r\n</div>','0','上海市陆家嘴','121.5099','31.244555','','','','','','','','','','','0','1','2');");

require("../../inc/footer.php");
?>