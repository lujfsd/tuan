<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_role_access`;");
E_C(\"CREATE TABLE `fanwe_role_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_role_access` values('11','3','56');");
E_D("replace into `fanwe_role_access` values('12','3','57');");
E_D("replace into `fanwe_role_access` values('14','3','61');");
E_D("replace into `fanwe_role_access` values('15','3','62');");
E_D("replace into `fanwe_role_access` values('16','3','63');");
E_D("replace into `fanwe_role_access` values('17','3','64');");
E_D("replace into `fanwe_role_access` values('18','5','65');");
E_D("replace into `fanwe_role_access` values('19','5','66');");
E_D("replace into `fanwe_role_access` values('20','5','56');");
E_D("replace into `fanwe_role_access` values('88','7','80');");
E_D("replace into `fanwe_role_access` values('85','7','102');");
E_D("replace into `fanwe_role_access` values('84','7','100');");
E_D("replace into `fanwe_role_access` values('83','7','99');");
E_D("replace into `fanwe_role_access` values('82','7','98');");
E_D("replace into `fanwe_role_access` values('81','7','97');");
E_D("replace into `fanwe_role_access` values('80','7','96');");
E_D("replace into `fanwe_role_access` values('79','7','95');");
E_D("replace into `fanwe_role_access` values('78','7','94');");
E_D("replace into `fanwe_role_access` values('77','7','82');");
E_D("replace into `fanwe_role_access` values('76','7','79');");
E_D("replace into `fanwe_role_access` values('75','7','78');");
E_D("replace into `fanwe_role_access` values('74','7','77');");
E_D("replace into `fanwe_role_access` values('73','7','76');");
E_D("replace into `fanwe_role_access` values('72','7','75');");
E_D("replace into `fanwe_role_access` values('71','7','74');");
E_D("replace into `fanwe_role_access` values('70','7','73');");
E_D("replace into `fanwe_role_access` values('69','7','72');");
E_D("replace into `fanwe_role_access` values('68','7','71');");
E_D("replace into `fanwe_role_access` values('67','7','70');");
E_D("replace into `fanwe_role_access` values('44','3','82');");
E_D("replace into `fanwe_role_access` values('66','7','69');");
E_D("replace into `fanwe_role_access` values('65','7','68');");
E_D("replace into `fanwe_role_access` values('64','7','67');");
E_D("replace into `fanwe_role_access` values('63','7','66');");
E_D("replace into `fanwe_role_access` values('62','7','65');");
E_D("replace into `fanwe_role_access` values('61','7','64');");
E_D("replace into `fanwe_role_access` values('60','7','63');");
E_D("replace into `fanwe_role_access` values('59','7','62');");
E_D("replace into `fanwe_role_access` values('58','7','61');");
E_D("replace into `fanwe_role_access` values('57','7','56');");
E_D("replace into `fanwe_role_access` values('56','7','57');");
E_D("replace into `fanwe_role_access` values('89','8','56');");
E_D("replace into `fanwe_role_access` values('90','8','107');");
E_D("replace into `fanwe_role_access` values('91','8','108');");
E_D("replace into `fanwe_role_access` values('92','8','110');");
E_D("replace into `fanwe_role_access` values('93','3','120');");

require("../../inc/footer.php");
?>