<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_goods_gallery`;");
E_C(\"CREATE TABLE `fanwe_goods_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `small_img` varchar(255) NOT NULL,
  `big_img` varchar(255) NOT NULL,
  `origin_img` varchar(255) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `session_id` varchar(255) NOT NULL COMMENT '用于临时上传的图片标识session所有者',
  `supplier_goods_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `inx_goods_gallery_001` (`goods_id`),
  KEY `inx_goods_gallery_002` (`is_default`)
) ENGINE=MyISAM AUTO_INCREMENT=144 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_goods_gallery` values('69','/Public/upload/attachment/201312/52a5a6f24b6d3.jpg','/Public/upload/attachment/201312/52a5a6f24b6d3.jpg','/Public/upload/attachment/201312/52a5a6f24b6d3.jpg','0','0','','2');");
E_D("replace into `fanwe_goods_gallery` values('143','/Public/upload/goods/small/201407/53d6195e5d3ed.jpg','/Public/upload/goods/big/201407/53d6195e5d3ed.jpg','/Public/upload/goods/origin/201407/53d6195e5d3ed.jpg','114','1','93963474edfd08f1f1e7244f663b4708','0');");
E_D("replace into `fanwe_goods_gallery` values('138','/Public/upload/goods/small/201407/53d60bf331abc.jpg','/Public/upload/goods/big/201407/53d60bf331abc.jpg','/Public/upload/goods/origin/201407/53d60bf331abc.jpg','115','1','93963474edfd08f1f1e7244f663b4708','0');");
E_D("replace into `fanwe_goods_gallery` values('142','/Public/upload/goods/small/201407/53d6193165e51.jpg','/Public/upload/goods/big/201407/53d6193165e51.jpg','/Public/upload/goods/origin/201407/53d6193165e51.jpg','117','1','93963474edfd08f1f1e7244f663b4708','0');");
E_D("replace into `fanwe_goods_gallery` values('137','/Public/upload/goods/small/201407/53d60be0915bc.jpg','/Public/upload/goods/big/201407/53d60be0915bc.jpg','/Public/upload/goods/origin/201407/53d60be0915bc.jpg','116','1','93963474edfd08f1f1e7244f663b4708','0');");

require("../../inc/footer.php");
?>