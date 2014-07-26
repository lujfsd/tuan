<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_goods_gallery`;");
E_C("CREATE TABLE `fanwe_goods_gallery` (
  `id` int(11) NOT NULL auto_increment,
  `small_img` varchar(255) NOT NULL,
  `big_img` varchar(255) NOT NULL,
  `origin_img` varchar(255) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `session_id` varchar(255) NOT NULL COMMENT '用于临时上传的图片标识session所有者',
  `supplier_goods_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `inx_goods_gallery_001` (`goods_id`),
  KEY `inx_goods_gallery_002` (`is_default`)
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_goods_gallery` values('63','/Public/upload/goods/small/201003/4ba9dc4902560.jpg','/Public/upload/goods/big/201003/4ba9dc4902560.jpg','/Public/upload/goods/origin/201003/4ba9dc4902560.jpg','86','1','93d65641ff3f1586614cf2c1ad240b6c','0');");
E_D("replace into `fanwe_goods_gallery` values('64','/Public/upload/goods/small/201003/4bae3d279f75c.jpg','/Public/upload/goods/big/201003/4bae3d279f75c.jpg','/Public/upload/goods/origin/201003/4bae3d279f75c.jpg','87','1','0cdf61037d7053ca59347ab230818335','0');");
E_D("replace into `fanwe_goods_gallery` values('65','/Public/upload/goods/small/201003/4baf5a5c34824.jpg','/Public/upload/goods/big/201003/4baf5a5c34824.jpg','/Public/upload/goods/origin/201003/4baf5a5c34824.jpg','88','1','ce059ef4192cbdcb40df4422c090f1c3','0');");
E_D("replace into `fanwe_goods_gallery` values('68','/Public/upload/goods/small/201312/529dd8b7cef6f.jpg','/Public/upload/goods/big/201312/529dd8b7cef6f.jpg','/Public/upload/goods/origin/201312/529dd8b7cef6f.jpg','89','1','eef6f4457ee96f8bae1893f5b234d238','0');");
E_D("replace into `fanwe_goods_gallery` values('126','/Public/upload/goods/small/201403/5325af0cdbfa9.jpg','/Public/upload/goods/big/201403/5325af0cdbfa9.jpg','/Public/upload/goods/origin/201403/5325af0cdbfa9.jpg','90','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('69','/Public/upload/attachment/201312/52a5a6f24b6d3.jpg','/Public/upload/attachment/201312/52a5a6f24b6d3.jpg','/Public/upload/attachment/201312/52a5a6f24b6d3.jpg','0','0','','2');");
E_D("replace into `fanwe_goods_gallery` values('125','/Public/upload/goods/small/201403/5325aee1c3cd0.jpg','/Public/upload/goods/big/201403/5325aee1c3cd0.jpg','/Public/upload/goods/origin/201403/5325aee1c3cd0.jpg','91','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('71','/Public/upload/goods/small/201312/52a5acc3aa7cb.jpg','/Public/upload/goods/big/201312/52a5acc3aa7cb.jpg','/Public/upload/goods/origin/201312/52a5acc3aa7cb.jpg','92','0','831b342d8a83408e5960e9b0c5f31f0c','0');");
E_D("replace into `fanwe_goods_gallery` values('124','/Public/upload/goods/small/201403/5325adca6da2d.jpg','/Public/upload/goods/big/201403/5325adca6da2d.jpg','/Public/upload/goods/origin/201403/5325adca6da2d.jpg','93','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('123','/Public/upload/goods/small/201403/5325ad87ca7b6.jpg','/Public/upload/goods/big/201403/5325ad87ca7b6.jpg','/Public/upload/goods/origin/201403/5325ad87ca7b6.jpg','94','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('122','/Public/upload/goods/small/201403/5325ad50ec899.jpg','/Public/upload/goods/big/201403/5325ad50ec899.jpg','/Public/upload/goods/origin/201403/5325ad50ec899.jpg','95','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('121','/Public/upload/goods/small/201403/5325ad3208cfe.jpg','/Public/upload/goods/big/201403/5325ad3208cfe.jpg','/Public/upload/goods/origin/201403/5325ad3208cfe.jpg','96','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('120','/Public/upload/goods/small/201403/5325acfe3b081.jpg','/Public/upload/goods/big/201403/5325acfe3b081.jpg','/Public/upload/goods/origin/201403/5325acfe3b081.jpg','97','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('119','/Public/upload/goods/small/201403/5325accdd099b.jpg','/Public/upload/goods/big/201403/5325accdd099b.jpg','/Public/upload/goods/origin/201403/5325accdd099b.jpg','98','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('118','/Public/upload/goods/small/201403/5325acad991b6.jpg','/Public/upload/goods/big/201403/5325acad991b6.jpg','/Public/upload/goods/origin/201403/5325acad991b6.jpg','99','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('117','/Public/upload/goods/small/201403/5325ac7183127.jpg','/Public/upload/goods/big/201403/5325ac7183127.jpg','/Public/upload/goods/origin/201403/5325ac7183127.jpg','100','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('116','/Public/upload/goods/small/201403/5325ac5433ebc.jpg','/Public/upload/goods/big/201403/5325ac5433ebc.jpg','/Public/upload/goods/origin/201403/5325ac5433ebc.jpg','101','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('115','/Public/upload/goods/small/201403/5325ac358f6c6.jpg','/Public/upload/goods/big/201403/5325ac358f6c6.jpg','/Public/upload/goods/origin/201403/5325ac358f6c6.jpg','102','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('89','/Public/upload/goods/small/201312/52ae8ee9202a6.jpg','/Public/upload/goods/big/201312/52ae8ee9202a6.jpg','/Public/upload/goods/origin/201312/52ae8ee9202a6.jpg','103','1','402cac3dacf2ef35050ca72743ae6ca7','0');");
E_D("replace into `fanwe_goods_gallery` values('96','/Public/upload/goods/small/201312/52aeaaffdb839.jpg','/Public/upload/goods/big/201312/52aeaaffdb839.jpg','/Public/upload/goods/origin/201312/52aeaaffdb839.jpg','106','1','402cac3dacf2ef35050ca72743ae6ca7','0');");
E_D("replace into `fanwe_goods_gallery` values('95','/Public/upload/goods/small/201312/52aea75b7ac2d.jpg','/Public/upload/goods/big/201312/52aea75b7ac2d.jpg','/Public/upload/goods/origin/201312/52aea75b7ac2d.jpg','104','1','402cac3dacf2ef35050ca72743ae6ca7','0');");
E_D("replace into `fanwe_goods_gallery` values('92','/Public/upload/goods/small/201312/52aea5487f15e.jpg','/Public/upload/goods/big/201312/52aea5487f15e.jpg','/Public/upload/goods/origin/201312/52aea5487f15e.jpg','105','1','402cac3dacf2ef35050ca72743ae6ca7','0');");
E_D("replace into `fanwe_goods_gallery` values('97','/Public/upload/goods/small/201312/52aeacf71114c.jpg','/Public/upload/goods/big/201312/52aeacf71114c.jpg','/Public/upload/goods/origin/201312/52aeacf71114c.jpg','107','1','2d13d901966a8eaa7f9c943eba6a540b','0');");
E_D("replace into `fanwe_goods_gallery` values('114','/Public/upload/goods/small/201403/5325ac088d786.jpg','/Public/upload/goods/big/201403/5325ac088d786.jpg','/Public/upload/goods/origin/201403/5325ac088d786.jpg','108','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('113','/Public/upload/goods/small/201403/5325abe493669.jpg','/Public/upload/goods/big/201403/5325abe493669.jpg','/Public/upload/goods/origin/201403/5325abe493669.jpg','109','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('112','/Public/upload/goods/small/201403/5325abc8f1458.jpg','/Public/upload/goods/big/201403/5325abc8f1458.jpg','/Public/upload/goods/origin/201403/5325abc8f1458.jpg','110','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('101','/Public/upload/goods/small/201312/52b0fc9101c11.gif','/Public/upload/goods/big/201312/52b0fc9101c11.gif','/Public/upload/goods/origin/201312/52b0fc9101c11.gif','111','1','ee0b86d2e127f776eaaa97d77e078e41','0');");
E_D("replace into `fanwe_goods_gallery` values('111','/Public/upload/goods/small/201403/5325aba6b7dac.jpg','/Public/upload/goods/big/201403/5325aba6b7dac.jpg','/Public/upload/goods/origin/201403/5325aba6b7dac.jpg','112','1','eea5d933e9dce59c7dd0f6532f9ea81b','0');");
E_D("replace into `fanwe_goods_gallery` values('104','/Public/upload/goods/small/201312/52b280fc1bb8f.jpg','/Public/upload/goods/big/201312/52b280fc1bb8f.jpg','/Public/upload/goods/origin/201312/52b280fc1bb8f.jpg','113','1','ba7e36c43aff315c00ec2b8625e3b719','0');");
E_D("replace into `fanwe_goods_gallery` values('105','/Public/upload/goods/small/201312/52b28a7f43522.jpg','/Public/upload/goods/big/201312/52b28a7f43522.jpg','/Public/upload/goods/origin/201312/52b28a7f43522.jpg','113','0','ba7e36c43aff315c00ec2b8625e3b719','0');");
E_D("replace into `fanwe_goods_gallery` values('108','/Public/upload/goods/small/201312/52b28e490ebfb.jpg','/Public/upload/goods/big/201312/52b28e490ebfb.jpg','/Public/upload/goods/origin/201312/52b28e490ebfb.jpg','113','0','ba7e36c43aff315c00ec2b8625e3b719','0');");
E_D("replace into `fanwe_goods_gallery` values('109','/Public/upload/goods/small/201312/52b28e565211e.jpg','/Public/upload/goods/big/201312/52b28e565211e.jpg','/Public/upload/goods/origin/201312/52b28e565211e.jpg','113','0','ba7e36c43aff315c00ec2b8625e3b719','0');");

require("../../inc/footer.php");
?>