<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_goods_reviews`;");
E_C("CREATE TABLE `fanwe_goods_reviews` (
  `id` int(11) NOT NULL auto_increment,
  `goods_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL,
  `webname` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `inx_goods_reviews_001` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_goods_reviews` values('1','86','lamafgh','http://www.dianping.com/review/20368664','大众点评网','果然是地道的宁夏人开的，炒出来的味道就是好，和老板了解原料也都是从宁夏运过来的。……现在不用回老家就能在北京吃的正宗的宁夏风味，真是很爽啊！');");
E_D("replace into `fanwe_goods_reviews` values('2','86','我叫小刘','http://www.dianping.com/review/19716418','大众点评网','菜量很大，但价格都不算高。可能两三个人来的话人均十几、二十几也不是什么难事。一人一人臊子面、拉条子12、15就可以吃好一顿呢。一定要来试试哦^_^');");
E_D("replace into `fanwe_goods_reviews` values('4','86','ppllxx111','http://www.dianping.com/review/21393518','大众点评网','今天有幸赶上了这里推出新菜——羊蝎子。味道很好，量很足，我们3个男人竟然都没吃完。这里的羊蝎子跟其他的不一样，肉够嫩，羊肉味很纯正。不会像其他店会有很大的调料的味道，把羊肉本身的香味盖住了，而让人怀疑是不是羊肉不够新鲜。 ');");
E_D("replace into `fanwe_goods_reviews` values('5','87','《创业家》杂志','http://finance.sina.com.cn/leadership/mroll/20090717/19406496190.shtml','','乐淘族上线不久，毕胜（乐淘CEO）就接到一个陌生的电话，对方兴奋地告诉他：“这是我有史以来网购体验最好的一次，1分20秒完成了一次购买。”这是美国一位资深互联网投资人，投资过数十个电子商务公司，显然，他明白缩短购买时间对电子商务而言的重要性。');");
E_D("replace into `fanwe_goods_reviews` values('6','87','少跟我装','http://www.dianping.com/review/21332888','大众点评网','网上鞋城，都是正品的，我买过3次，都很合适的，价格比市场上的便宜很多，物流也很快，服务态度也好。');");
E_D("replace into `fanwe_goods_reviews` values('7','87','看見了','http://blog.sina.com.cn/s/blog_4740b42a0100goii.html','新浪博客','用这样的价格买到正品，的确能省下不少钱，而且还不用邮费，包邮的服务的确是很贴心。……细致的网站图片，实物拍摄，还有鞋子的不同角度……可以让你看个清楚。');");
E_D("replace into `fanwe_goods_reviews` values('8','88','我与食俱进','http://www.dianping.com/review/20534344','大众点评网','很有意思的一个工作室……边品酒边了解红酒知识，是迅速了解红酒的一个有趣之道。');");
E_D("replace into `fanwe_goods_reviews` values('9','102','','','','');");
E_D("replace into `fanwe_goods_reviews` values('10','103','','','','11111111111111');");

require("../../inc/footer.php");
?>