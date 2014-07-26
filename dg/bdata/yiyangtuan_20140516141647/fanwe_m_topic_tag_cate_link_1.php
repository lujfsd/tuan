<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_m_topic_tag_cate_link`;");
E_C("CREATE TABLE `fanwe_m_topic_tag_cate_link` (
  `cate_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`cate_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('1','1');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('1','5');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('1','11');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('2','3');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('2','4');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('2','5');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('2','6');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('3','2');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('3','11');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('4','5');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('4','7');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('4','8');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('4','9');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('4','10');");
E_D("replace into `fanwe_m_topic_tag_cate_link` values('4','11');");

require("../../inc/footer.php");
?>