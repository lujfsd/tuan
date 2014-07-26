<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_log`;");
E_C("CREATE TABLE `fanwe_log` (
  `id` int(11) NOT NULL auto_increment,
  `log_module` varchar(255) NOT NULL,
  `log_action` varchar(255) NOT NULL,
  `data_id` int(11) NOT NULL COMMENT '操作的相关数据主键',
  `log_time` int(11) NOT NULL,
  `adm_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `log_result` tinyint(4) NOT NULL COMMENT '0:失败 1:成功',
  `log_msg` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=324 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_log` values('278','Public','checkLogin','0','1389399843','8','127.0.0.1','1','');");
E_D("replace into `fanwe_log` values('279','Public','checkLogin','0','1394944715','8','127.0.0.1','1','');");
E_D("replace into `fanwe_log` values('280','Public','checkLogin','0','1394944716','8','127.0.0.1','1','');");
E_D("replace into `fanwe_log` values('281','Goods','delete','113','1394945381','8','127.0.0.1','1','删除团购:正品雅斯汀胶原蛋白量子修复整形喷雾');");
E_D("replace into `fanwe_log` values('282','Goods','update','112','1394945426','8','127.0.0.1','1','修改团购3w水晶灯专用灯系列蜡尾灯');");
E_D("replace into `fanwe_log` values('283','Goods','update','110','1394945468','8','127.0.0.1','1','修改团购零度科技 7WLED灯（暖光白光）节能灯');");
E_D("replace into `fanwe_log` values('284','Article','update','77','1394945641','8','127.0.0.1','1','修改文章手机客户端下载');");
E_D("replace into `fanwe_log` values('285','Goods','update','109','1394946013','8','127.0.0.1','1','修改团购零度科技过双亮度LED');");
E_D("replace into `fanwe_log` values('286','Goods','update','108','1394946053','8','127.0.0.1','1','修改团购零度科技LED3w单零度球泡灯系列（暖光 白光）');");
E_D("replace into `fanwe_log` values('287','Goods','update','105','1394946089','8','127.0.0.1','1','修改团购负离子空气净化灯 3w');");
E_D("replace into `fanwe_log` values('288','Goods','update','102','1394946129','8','127.0.0.1','1','修改团购法国纯植物DNA配方 索珞化妆品高级眼霜');");
E_D("replace into `fanwe_log` values('289','Goods','update','101','1394946171','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞养生活肤膏');");
E_D("replace into `fanwe_log` values('290','Goods','update','100','1394946215','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞美体护手霜');");
E_D("replace into `fanwe_log` values('291','Goods','update','97','1394946266','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞营养粉底液');");
E_D("replace into `fanwe_log` values('292','Goods','update','99','1394946305','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞多用途洗宝王');");
E_D("replace into `fanwe_log` values('293','Goods','update','96','1394946346','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞洁面洗宝王');");
E_D("replace into `fanwe_log` values('294','Goods','update','95','1394946404','8','127.0.0.1','1','修改团购纯植物 DNA系列产品 高级抗皱眼霜');");
E_D("replace into `fanwe_log` values('295','Goods','update','98','1394946477','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞复合多用精油');");
E_D("replace into `fanwe_log` values('296','Goods','update','86','1394946562','8','127.0.0.1','1','修改团购仅售35元！原价70元的西夏部落酱香羊蝎子+养生八宝茶');");
E_D("replace into `fanwe_log` values('297','Goods','update','87','1394946619','8','127.0.0.1','1','修改团购仅售12元！乐淘网上鞋城80元现金消费券');");
E_D("replace into `fanwe_log` values('298','Goods','delete','88','1394946644','8','127.0.0.1','1','删除团购:仅售50元！原价100元的梵雅葡萄酒品尝套餐');");
E_D("replace into `fanwe_log` values('299','Goods','delete','89','1394946661','8','127.0.0.1','1','删除团购:索珞水嫩润肤乳  ');");
E_D("replace into `fanwe_log` values('300','Goods','update','94','1394946700','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞置换精华');");
E_D("replace into `fanwe_log` values('301','Goods','update','90','1394946742','8','127.0.0.1','1','修改团购索珞洁面洗宝王');");
E_D("replace into `fanwe_log` values('302','Goods','update','91','1394946788','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞深层洁肤凝胶');");
E_D("replace into `fanwe_log` values('303','Goods','update','92','1394946942','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞魔幻隔离乳');");
E_D("replace into `fanwe_log` values('304','Goods','update','93','1394947006','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞平皱精华霜  养白精华霜');");
E_D("replace into `fanwe_log` values('305','Goods','delete','105','1394947523','8','127.0.0.1','1','删除团购:负离子空气净化灯 3w');");
E_D("replace into `fanwe_log` values('306','Goods','delete','92','1394947557','8','127.0.0.1','1','删除团购:纯生物制剂 DNA系列产品 索珞魔幻隔离乳');");
E_D("replace into `fanwe_log` values('307','Goods','update','112','1394948908','8','127.0.0.1','1','修改团购3w水晶灯专用灯系列蜡尾灯');");
E_D("replace into `fanwe_log` values('308','Goods','update','110','1394948941','8','127.0.0.1','1','修改团购零度科技 7WLED灯（暖光白光）节能灯');");
E_D("replace into `fanwe_log` values('309','Goods','update','109','1394948969','8','127.0.0.1','1','修改团购零度科技过双亮度LED');");
E_D("replace into `fanwe_log` values('310','Goods','update','108','1394949005','8','127.0.0.1','1','修改团购零度科技LED3w单零度球泡灯系列（暖光 白光）');");
E_D("replace into `fanwe_log` values('311','Goods','update','102','1394949049','8','127.0.0.1','1','修改团购法国纯植物DNA配方 索珞化妆品高级眼霜');");
E_D("replace into `fanwe_log` values('312','Goods','update','101','1394949081','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞养生活肤膏');");
E_D("replace into `fanwe_log` values('313','Goods','update','100','1394949109','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞美体护手霜');");
E_D("replace into `fanwe_log` values('314','Goods','update','99','1394949170','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞多用途洗宝王');");
E_D("replace into `fanwe_log` values('315','Goods','update','98','1394949203','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞复合多用精油');");
E_D("replace into `fanwe_log` values('316','Goods','update','97','1394949251','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞营养粉底液');");
E_D("replace into `fanwe_log` values('317','Goods','update','96','1394949303','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞洁面洗宝王');");
E_D("replace into `fanwe_log` values('318','Goods','update','95','1394949334','8','127.0.0.1','1','修改团购纯植物 DNA系列产品 高级抗皱眼霜');");
E_D("replace into `fanwe_log` values('319','Goods','update','94','1394949393','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞置换精华');");
E_D("replace into `fanwe_log` values('320','Goods','update','93','1394949456','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞平皱精华霜  养白精华霜');");
E_D("replace into `fanwe_log` values('321','Goods','update','91','1394949736','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞深层洁肤凝胶');");
E_D("replace into `fanwe_log` values('322','Goods','update','90','1394949778','8','127.0.0.1','1','修改团购索珞洁面洗宝王');");
E_D("replace into `fanwe_log` values('323','Article','update','64','1394950392','8','127.0.0.1','1','修改文章玩转');");

require("../../inc/footer.php");
?>