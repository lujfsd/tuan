<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_suppliers_depart`;");
E_C("CREATE TABLE `fanwe_suppliers_depart` (
  `id` int(11) NOT NULL auto_increment,
  `depart_name` varchar(255) NOT NULL,
  `login_name` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `address` text,
  `map` text NOT NULL,
  `tel` varchar(255) NOT NULL,
  `operating` varchar(255) NOT NULL,
  `is_main` tinyint(1) NOT NULL,
  `bus` text NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `api_address` varchar(255) NOT NULL,
  `xpoint` varchar(255) NOT NULL,
  `ypoint` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `is_main` (`is_main`),
  KEY `index_1` (`supplier_id`,`is_main`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_suppliers_depart` values('12','梵雅红酒体验坊总店','hongjiu','b59c67bf196a4758191e42f76670ceba','北京市朝阳区东三环中路39号建外SOHO西区10号楼商铺','http://map.baidu.com/?newmap=1&l=18&c=12964978,4825505&s=s%26wd%3D%E6%A2%B5%E9%9B%85%E7%BA%A2%E9%85%92%E4%BD%93%E9%AA%8C%E5%9D%8A%26c%3D131%26src%3D0%26wd2%3D%26sug%3D0&sc=0','010-51289169','09:00-21:00','1','北京市朝阳区东三环中路39号建外SOHO西区10号楼商铺','6','','北京市朝阳区东三环中路39号建外SOHO西区10号楼商铺','116.4614106','39.913238');");
E_D("replace into `fanwe_suppliers_depart` values('14','南国醉湘','hongjiu_1','','朝阳区曙光西里甲6号院时间国际中心9-22号(浦发银行后)','http://ditu.google.cn/maps?f=q&source=s_q&hl=zh-CN&geocode=&q=%E5%8D%97%E5%9B%BD%E9%86%89%E6%B9%98&sll=39.938133,116.394857&sspn=0.008638,0.019205&g=%E8%A5%BF%E5%9F%8E%E5%8C%BA%E5%89%8D%E6%B5%B7%E4%B8%9C%E6%B2%BF10%E5%8F%B7&brcurrent=3,0x35f1ab433e8791bb:0xc67273dafbbc1aa1,0,0x35f1abee23736947:0xd7bb8b3026d0813a%3B5,0,0&ie=UTF8&hq=%E5%8D%97%E5%9B%BD%E9%86%89%E6%B9%98&hnear=%E5%8C%97%E4%BA%AC%E5%B8%82%E8%A5%BF%E5%9F%8E%E5%8C%BA%E5%89%8D%E6%B5%B7%E4%B8%9C%E6%B2%BF10%E5%8F%B7&ll=39.963241,116.42993&spn=0.034537,0.076818&z=14&iwloc=A','010-84440289','11:00 - 22:00','1','','2','','','','');");
E_D("replace into `fanwe_suppliers_depart` values('15','星巴克咖啡','hngjiu_2','','','','','请咨询各门店','1','','3','','','','');");
E_D("replace into `fanwe_suppliers_depart` values('16','悦和越南料理','hongjiu_3','','朝阳区金汇路8-9号世界城商业街E座122号铺(近世贸天街)','http://ditu.google.cn/maps?q=%E6%82%A6%E5%92%8C%E8%B6%8A%E5%8D%97%E6%B2%B3%E7%B2%89%E5%BA%97&hl=zh-CN&cd=1&ei=dbGpS6agIYOQkQXq972uCA&ie=UTF8&view=map&cid=7180209416326832262&ved=0CBwQpQY&hq=%E6%82%A6%E5%92%8C%E8%B6%8A%E5%8D%97%E6%B2%B3%E7%B2%89%E5%BA%97&hnear=&ll=39.920335,116.452053&spn=0.008228,0.019205&z=16&iwloc=A&brcurrent=3,0x35f1ace74bdf9bf9:0xb5e7099aab3991a6,0,0x35f1abee23736947:0xd7bb8b3026d0813a%3B5,0,0','010-85907981','','1','','4','','','','');");
E_D("replace into `fanwe_suppliers_depart` values('17','西夏部落','hongjiu_4','','宣武区永安路175-4号','http://ditu.google.com/maps?f=q&source=s_q&hl=zh-CN&geocode=&q=%E8%A5%BF%E5%A4%8F%E9%83%A8%E8%90%BD&sll=39.905523,116.408386&sspn=1.011314,1.771545&brcurrent=3,0x35f04d9a47c6dc6b:0x868c7b7da7c153f6,0,0x35f04d998bd7dd77:0x5edd87e0349f18ae%3B5,0,0&ie=UTF8&hq=%E8%A5%BF%E5%A4%8F%E9%83%A8%E8%90%BD&hnear=&ll=39.893802,116.385384&spn=0.032926,0.076818&z=14&iwloc=A','010-63174525','','1','永安路 (149 米)   6路, 15路, 105路电车, 687路空调','5','','','','');");
E_D("replace into `fanwe_suppliers_depart` values('18','索珞化妆品','hongjiu_5','e10adc3949ba59abbe56e057f20f883e','临沂兰山','','9*99','9-12','1','','7','','','','');");

require("../../inc/footer.php");
?>