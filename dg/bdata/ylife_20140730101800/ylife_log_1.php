<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_log`;");
E_C("CREATE TABLE `ylife_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_module` varchar(255) NOT NULL,
  `log_action` varchar(255) NOT NULL,
  `data_id` int(11) NOT NULL COMMENT '操作的相关数据主键',
  `log_time` int(11) NOT NULL,
  `adm_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `log_result` tinyint(4) NOT NULL COMMENT '0:失败 1:成功',
  `log_msg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=401 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_log` values('343','Log','clearSysLog','0','1406227915','8','127.0.0.1','1','清除了6个月前后台日志');");
E_D("replace into `ylife_log` values('279','Public','checkLogin','0','1394944715','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('280','Public','checkLogin','0','1394944716','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('281','Goods','delete','113','1394945381','8','127.0.0.1','1','删除团购:正品雅斯汀胶原蛋白量子修复整形喷雾');");
E_D("replace into `ylife_log` values('282','Goods','update','112','1394945426','8','127.0.0.1','1','修改团购3w水晶灯专用灯系列蜡尾灯');");
E_D("replace into `ylife_log` values('283','Goods','update','110','1394945468','8','127.0.0.1','1','修改团购零度科技 7WLED灯（暖光白光）节能灯');");
E_D("replace into `ylife_log` values('284','Article','update','77','1394945641','8','127.0.0.1','1','修改文章手机客户端下载');");
E_D("replace into `ylife_log` values('285','Goods','update','109','1394946013','8','127.0.0.1','1','修改团购零度科技过双亮度LED');");
E_D("replace into `ylife_log` values('286','Goods','update','108','1394946053','8','127.0.0.1','1','修改团购零度科技LED3w单零度球泡灯系列（暖光 白光）');");
E_D("replace into `ylife_log` values('287','Goods','update','105','1394946089','8','127.0.0.1','1','修改团购负离子空气净化灯 3w');");
E_D("replace into `ylife_log` values('288','Goods','update','102','1394946129','8','127.0.0.1','1','修改团购法国纯植物DNA配方 索珞化妆品高级眼霜');");
E_D("replace into `ylife_log` values('289','Goods','update','101','1394946171','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞养生活肤膏');");
E_D("replace into `ylife_log` values('290','Goods','update','100','1394946215','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞美体护手霜');");
E_D("replace into `ylife_log` values('291','Goods','update','97','1394946266','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞营养粉底液');");
E_D("replace into `ylife_log` values('292','Goods','update','99','1394946305','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞多用途洗宝王');");
E_D("replace into `ylife_log` values('293','Goods','update','96','1394946346','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞洁面洗宝王');");
E_D("replace into `ylife_log` values('294','Goods','update','95','1394946404','8','127.0.0.1','1','修改团购纯植物 DNA系列产品 高级抗皱眼霜');");
E_D("replace into `ylife_log` values('295','Goods','update','98','1394946477','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞复合多用精油');");
E_D("replace into `ylife_log` values('296','Goods','update','86','1394946562','8','127.0.0.1','1','修改团购仅售35元！原价70元的西夏部落酱香羊蝎子+养生八宝茶');");
E_D("replace into `ylife_log` values('297','Goods','update','87','1394946619','8','127.0.0.1','1','修改团购仅售12元！乐淘网上鞋城80元现金消费券');");
E_D("replace into `ylife_log` values('298','Goods','delete','88','1394946644','8','127.0.0.1','1','删除团购:仅售50元！原价100元的梵雅葡萄酒品尝套餐');");
E_D("replace into `ylife_log` values('299','Goods','delete','89','1394946661','8','127.0.0.1','1','删除团购:索珞水嫩润肤乳  ');");
E_D("replace into `ylife_log` values('300','Goods','update','94','1394946700','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞置换精华');");
E_D("replace into `ylife_log` values('301','Goods','update','90','1394946742','8','127.0.0.1','1','修改团购索珞洁面洗宝王');");
E_D("replace into `ylife_log` values('302','Goods','update','91','1394946788','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞深层洁肤凝胶');");
E_D("replace into `ylife_log` values('303','Goods','update','92','1394946942','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞魔幻隔离乳');");
E_D("replace into `ylife_log` values('304','Goods','update','93','1394947006','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞平皱精华霜  养白精华霜');");
E_D("replace into `ylife_log` values('305','Goods','delete','105','1394947523','8','127.0.0.1','1','删除团购:负离子空气净化灯 3w');");
E_D("replace into `ylife_log` values('306','Goods','delete','92','1394947557','8','127.0.0.1','1','删除团购:纯生物制剂 DNA系列产品 索珞魔幻隔离乳');");
E_D("replace into `ylife_log` values('307','Goods','update','112','1394948908','8','127.0.0.1','1','修改团购3w水晶灯专用灯系列蜡尾灯');");
E_D("replace into `ylife_log` values('308','Goods','update','110','1394948941','8','127.0.0.1','1','修改团购零度科技 7WLED灯（暖光白光）节能灯');");
E_D("replace into `ylife_log` values('309','Goods','update','109','1394948969','8','127.0.0.1','1','修改团购零度科技过双亮度LED');");
E_D("replace into `ylife_log` values('310','Goods','update','108','1394949005','8','127.0.0.1','1','修改团购零度科技LED3w单零度球泡灯系列（暖光 白光）');");
E_D("replace into `ylife_log` values('311','Goods','update','102','1394949049','8','127.0.0.1','1','修改团购法国纯植物DNA配方 索珞化妆品高级眼霜');");
E_D("replace into `ylife_log` values('312','Goods','update','101','1394949081','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞养生活肤膏');");
E_D("replace into `ylife_log` values('313','Goods','update','100','1394949109','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞美体护手霜');");
E_D("replace into `ylife_log` values('314','Goods','update','99','1394949170','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞多用途洗宝王');");
E_D("replace into `ylife_log` values('315','Goods','update','98','1394949203','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞复合多用精油');");
E_D("replace into `ylife_log` values('316','Goods','update','97','1394949251','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞营养粉底液');");
E_D("replace into `ylife_log` values('317','Goods','update','96','1394949303','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞洁面洗宝王');");
E_D("replace into `ylife_log` values('318','Goods','update','95','1394949334','8','127.0.0.1','1','修改团购纯植物 DNA系列产品 高级抗皱眼霜');");
E_D("replace into `ylife_log` values('319','Goods','update','94','1394949393','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞置换精华');");
E_D("replace into `ylife_log` values('320','Goods','update','93','1394949456','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞平皱精华霜  养白精华霜');");
E_D("replace into `ylife_log` values('321','Goods','update','91','1394949736','8','127.0.0.1','1','修改团购纯生物制剂 DNA系列产品 索珞深层洁肤凝胶');");
E_D("replace into `ylife_log` values('322','Goods','update','90','1394949778','8','127.0.0.1','1','修改团购索珞洁面洗宝王');");
E_D("replace into `ylife_log` values('323','Article','update','64','1394950392','8','127.0.0.1','1','修改文章玩转');");
E_D("replace into `ylife_log` values('324','Public','checkLogin','0','1406078564','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('325','Public','checkLogin','0','1406136851','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('326','Public','checkLogin','0','1406136974','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('327','Public','checkLogin','0','1406143137','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('328','Public','checkLogin','0','1406150646','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('329','Public','checkLogin','0','1406155998','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('330','Public','checkLogin','0','1406159107','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('331','Public','checkLogin','0','1406226732','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('332','Goods','delete','112','1406226743','8','127.0.0.1','1','删除团购:3w水晶灯专用灯系列蜡尾灯,零度科技 7WLED灯（暖光白光）节能灯,零度科技过双亮度LED,零度科技LED3w单零度球泡灯系列（暖光 白光）,法国纯植物DNA配方 索珞化妆品高级眼霜,纯生物制剂 DNA系列产品 索珞养生活肤膏,纯生物制剂 DNA系列产品 索珞美体护手霜,纯生物制剂 DNA系列产品 索珞多用途洗宝王,纯生物制剂 DNA系列产品 索珞复合多用精油,纯生物制剂 DNA系列产品 索珞营养粉底液,纯生物制剂 DNA系列产品 索珞洁面洗宝王,纯植物 DNA系列产品 高级抗皱眼霜,纯生物制剂 DNA系列产品 索珞置换精华,纯生物制剂 DNA系列产品 索珞平皱精华霜  养白精华霜,纯生物制剂 DNA系列产品 索珞深层洁肤凝胶,索珞洁面洗宝王,仅售12元！乐淘网上鞋城80元现金消费券,仅售35元！原价70元的西夏部落酱香羊蝎子+养生八宝茶');");
E_D("replace into `ylife_log` values('333','Goods','delete','112','1406226758','8','127.0.0.1','1','删除团购:3w水晶灯专用灯系列蜡尾灯,零度科技 7WLED灯（暖光白光）节能灯,零度科技过双亮度LED,零度科技LED3w单零度球泡灯系列（暖光 白光）,法国纯植物DNA配方 索珞化妆品高级眼霜,纯生物制剂 DNA系列产品 索珞养生活肤膏,纯生物制剂 DNA系列产品 索珞美体护手霜,纯生物制剂 DNA系列产品 索珞多用途洗宝王,纯生物制剂 DNA系列产品 索珞复合多用精油,纯生物制剂 DNA系列产品 索珞营养粉底液,纯生物制剂 DNA系列产品 索珞洁面洗宝王,纯植物 DNA系列产品 高级抗皱眼霜,纯生物制剂 DNA系列产品 索珞置换精华,纯生物制剂 DNA系列产品 索珞平皱精华霜  养白精华霜,纯生物制剂 DNA系列产品 索珞深层洁肤凝胶,索珞洁面洗宝王,仅售12元！乐淘网上鞋城80元现金消费券,仅售35元！原价70元的西夏部落酱香羊蝎子+养生八宝茶');");
E_D("replace into `ylife_log` values('334','Goods','foreverdelete','113','1406226793','8','127.0.0.1','1','彻底删除团购:正品雅斯汀胶原蛋白量子修复整形喷雾,3w水晶灯专用灯系列蜡尾灯,14111111111111111,零度科技 7WLED灯（暖光白光）节能灯,零度科技过双亮度LED,零度科技LED3w单零度球泡灯系列（暖光 白光）,零度科技LED3w单零度球泡灯系列（暖光 白光）,5w双亮度球泡灯,负离子空气净化灯 3w,零度科技LED3w单零度球泡灯系列（暖光 白光）,5w双亮度球泡灯,法国纯植物DNA配方 索珞化妆品高级眼霜,纯生物制剂 DNA系列产品 索珞养生活肤膏,纯生物制剂 DNA系列产品 索珞美体护手霜,纯生物制剂 DNA系列产品 索珞多用途洗宝王,纯生物制剂 DNA系列产品 索珞复合多用精油,纯生物制剂 DNA系列产品 索珞营养粉底液,纯生物制剂 DNA系列产品 索珞洁面洗宝王,纯植物 DNA系列产品 高级抗皱眼霜,纯生物制剂 DNA系列产品 索珞置换精华ID:113,112,111,110,109,108,107,106,105,104,103,102,101,100,99,98,97,96,95,94');");
E_D("replace into `ylife_log` values('335','Goods','foreverdelete','93','1406226803','8','127.0.0.1','1','彻底删除团购:纯生物制剂 DNA系列产品 索珞平皱精华霜  养白精华霜,纯生物制剂 DNA系列产品 索珞魔幻隔离乳,纯生物制剂 DNA系列产品 索珞深层洁肤凝胶,索珞洁面洗宝王,索珞水嫩润肤乳  ,仅售50元！原价100元的梵雅葡萄酒品尝套餐,仅售12元！乐淘网上鞋城80元现金消费券,仅售35元！原价70元的西夏部落酱香羊蝎子+养生八宝茶ID:93,92,91,90,89,88,87,86');");
E_D("replace into `ylife_log` values('336','GoodsCate','foreverdelete','46','1406226814','8','127.0.0.1','1','删除团购分类:商品类,瑜伽馆,美发店,SPA,KTV,酒吧,餐厅ID:46,45,44,43,42,41,40');");
E_D("replace into `ylife_log` values('337','GoodsCate','foreverdelete','46','1406226817','8','127.0.0.1','1','删除团购分类:,ID:46,40');");
E_D("replace into `ylife_log` values('338','Suppliers','foreverdelete','9','1406226865','8','127.0.0.1','1','删除供应商:临沂零度科技,索珞化妆品,梵雅红酒体验坊,西夏部落,悦和越南料理,星巴克咖啡,南国醉湘');");
E_D("replace into `ylife_log` values('339','User','foreverdelete','58','1406227073','8','127.0.0.1','1','删除会员:yeamu,爱在我心,ai黑天鹅,白雪,醉清风');");
E_D("replace into `ylife_log` values('340','Message','foreverdelete','14','1406227107','8','127.0.0.1','1','删除留言:QQ54879241');");
E_D("replace into `ylife_log` values('341','Message','foreverdelete','13','1406227109','8','127.0.0.1','1','删除留言:139999999');");
E_D("replace into `ylife_log` values('342','Message','foreverdelete','11','1406227111','8','127.0.0.1','1','删除留言:QQ1345678');");
E_D("replace into `ylife_log` values('344','Public','checkLogin','0','1406228487','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('345','Public','checkLogin','0','1406229990','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('346','Public','checkLogin','0','1406247511','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('347','GoodsCate','insert','49','1406249388','8','127.0.0.1','1','添加团购分类陶艺');");
E_D("replace into `ylife_log` values('348','Suppliers','insert','10','1406249847','8','127.0.0.1','1','添加供应商缘始陶艺');");
E_D("replace into `ylife_log` values('349','Goods','insert','114','1406250082','8','127.0.0.1','1','添加团购缘始陶艺单人体验套餐');");
E_D("replace into `ylife_log` values('350','Goods','update','114','1406250242','8','127.0.0.1','1','修改团购缘始陶艺单人体验套餐');");
E_D("replace into `ylife_log` values('351','Goods','insert','115','1406251410','8','127.0.0.1','1','添加团购缘始陶艺双人');");
E_D("replace into `ylife_log` values('352','Goods','update','114','1406251454','8','127.0.0.1','1','修改团购缘始陶艺单人体验套餐');");
E_D("replace into `ylife_log` values('353','Goods','update','115','1406251477','8','127.0.0.1','1','修改团购缘始陶艺双人');");
E_D("replace into `ylife_log` values('354','Goods','update','115','1406251501','8','127.0.0.1','1','修改团购缘始陶艺双人');");
E_D("replace into `ylife_log` values('355','Suppliers','insert','11','1406251550','8','127.0.0.1','1','添加供应商无形陶艺');");
E_D("replace into `ylife_log` values('356','Goods','insert','116','1406251765','8','127.0.0.1','1','添加团购无形单人');");
E_D("replace into `ylife_log` values('357','Goods','update','116','1406251794','8','127.0.0.1','1','修改团购无形单人');");
E_D("replace into `ylife_log` values('358','Goods','update','116','1406251864','8','127.0.0.1','1','修改团购无形单人');");
E_D("replace into `ylife_log` values('359','Goods','update','116','1406251894','8','127.0.0.1','1','修改团购无形单人');");
E_D("replace into `ylife_log` values('360','Goods','update','116','1406251914','8','127.0.0.1','1','修改团购无形单人');");
E_D("replace into `ylife_log` values('361','Goods','insert','117','1406251983','8','127.0.0.1','1','添加团购无形双人');");
E_D("replace into `ylife_log` values('362','Goods','update','117','1406252037','8','127.0.0.1','1','修改团购无形双人');");
E_D("replace into `ylife_log` values('363','Goods','update','117','1406252284','8','127.0.0.1','1','修改团购无形双人');");
E_D("replace into `ylife_log` values('364','Goods','update','117','1406252319','8','127.0.0.1','1','修改团购无形双人');");
E_D("replace into `ylife_log` values('365','Goods','update','114','1406253615','8','127.0.0.1','1','修改团购缘始陶艺单人体验套餐');");
E_D("replace into `ylife_log` values('366','Public','checkLogin','0','1406485158','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('367','Public','checkLogin','0','1406499303','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('368','Public','checkLogin','0','1406499455','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('369','Public','checkLogin','0','1406499565','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('370','Public','checkLogin','0','1406499842','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('371','Article','foreverdelete','76','1406499852','8','127.0.0.1','1','删除文章:团购券验证ID:76');");
E_D("replace into `ylife_log` values('372','Public','checkLogin','0','1406500582','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('373','Goods','update','115','1406503777','8','127.0.0.1','1','修改团购缘始陶艺双人');");
E_D("replace into `ylife_log` values('374','Goods','update','117','1406507811','8','127.0.0.1','1','修改团购无形双人');");
E_D("replace into `ylife_log` values('375','Goods','update','116','1406507876','8','127.0.0.1','1','修改团购无形单人');");
E_D("replace into `ylife_log` values('376','Goods','update','115','1406507902','8','127.0.0.1','1','修改团购缘始陶艺双人');");
E_D("replace into `ylife_log` values('377','Goods','update','114','1406507933','8','127.0.0.1','1','修改团购缘始陶艺单人体验套餐');");
E_D("replace into `ylife_log` values('378','Goods','update','117','1406508666','8','127.0.0.1','1','修改团购无形双人');");
E_D("replace into `ylife_log` values('379','Suppliers','update','1','1406509021','8','127.0.0.1','1','修改供应商无形陶艺');");
E_D("replace into `ylife_log` values('380','Suppliers','update','1','1406509030','8','127.0.0.1','1','修改供应商缘始陶艺');");
E_D("replace into `ylife_log` values('381','Suppliers','update','1','1406509052','8','127.0.0.1','1','修改供应商无形陶艺');");
E_D("replace into `ylife_log` values('382','Goods','update','117','1406511291','8','127.0.0.1','1','修改团购无形双人');");
E_D("replace into `ylife_log` values('383','Goods','update','116','1406511301','8','127.0.0.1','1','修改团购无形单人');");
E_D("replace into `ylife_log` values('384','Goods','update','115','1406511312','8','127.0.0.1','1','修改团购缘始陶艺双人');");
E_D("replace into `ylife_log` values('385','Goods','update','114','1406511330','8','127.0.0.1','1','修改团购缘始陶艺单人体验套餐');");
E_D("replace into `ylife_log` values('386','Public','checkLogin','0','1406571347','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('387','Public','checkLogin','0','1406583687','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('388','Goods','update','115','1406583702','8','127.0.0.1','1','修改团购缘始陶艺双人');");
E_D("replace into `ylife_log` values('389','Goods','update','115','1406583747','8','127.0.0.1','1','修改团购缘始陶艺双人');");
E_D("replace into `ylife_log` values('390','Public','checkLogin','0','1406587601','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('391','Public','checkLogin','0','1406596449','8','127.0.0.1','1','');");
E_D("replace into `ylife_log` values('392','Database','dump','0','1406596455','8','127.0.0.1','0','数据库备份失败');");
E_D("replace into `ylife_log` values('393','Database','dump','0','1406596459','8','127.0.0.1','0','数据库备份失败');");
E_D("replace into `ylife_log` values('394','Database','dump','0','1406596461','8','127.0.0.1','0','数据库备份失败');");
E_D("replace into `ylife_log` values('395','Database','dump','0','1406596467','8','127.0.0.1','0','数据库备份失败');");
E_D("replace into `ylife_log` values('396','Public','checkLogin','0','1406616144','8','116.229.181.54','1','');");
E_D("replace into `ylife_log` values('397','Public','checkLogin','0','1406655589','8','180.166.203.26','1','');");
E_D("replace into `ylife_log` values('398','Public','checkLogin','0','1406661579','8','180.166.203.26','1','');");
E_D("replace into `ylife_log` values('399','Public','checkLogin','0','1406673054','8','180.166.203.26','1','');");
E_D("replace into `ylife_log` values('400','Public','checkLogin','0','1406673988','8','180.166.203.26','1','');");

require("../../inc/footer.php");
?>