DROP TABLE IF EXISTS `%DB_PREFIX%m_adv`;
CREATE TABLE `%DB_PREFIX%m_adv` (
  `id` smallint(6) NOT NULL auto_increment,
  `name` varchar(100) default '',
  `img` varchar(255) default '',
  `page` enum('sharelist','index') default 'sharelist',
  `type` tinyint(1) default '0' COMMENT '1.标签集,2.url地址,3.分类排行,4.最亮达人,5.搜索发现,6.一起拍,7.热门单品排行,8.直接显示某个分享',
  `data` text,
  `sort` smallint(5) default '10',
  `status` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%m_config`;
CREATE TABLE `%DB_PREFIX%m_config` (
  `id` int(10) NOT NULL auto_increment,
  `code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `val` text NOT NULL,
  `type` tinyint(1) NOT NULL default '0' COMMENT '配置输入的类型 0:文本输入 1:下拉框输入 2:图片上传 3:编辑器',
  `value_scope` text NOT NULL COMMENT '取值范围',
  `title_scope` text NOT NULL,
  `sort` int(11) NOT NULL default '0',
  `is_effect` tinyint(1) NOT NULL default '1',
  `group_id` int(11) NOT NULL default '0',
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%m_config_list`;
CREATE TABLE `%DB_PREFIX%m_config_list` (
  `id` int(10) NOT NULL auto_increment,
  `pay_id` varchar(50) default NULL,
  `group` int(10) default NULL,
  `code` varchar(50) default NULL,
  `title` varchar(255) default NULL,
  `has_calc` int(1) default NULL,
  `money` float(10,2) default NULL,
  `is_verify` int(1) default '0' COMMENT '0:无效；1:有效',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%m_index`;
CREATE TABLE `%DB_PREFIX%m_index` (
  `id` mediumint(6) NOT NULL auto_increment,
  `name` varchar(100) default '',
  `vice_name` varchar(100) default NULL,
  `desc` varchar(100) default '',
  `img` varchar(255) default '',
  `type` tinyint(1) default '0' COMMENT '1.标签集,2.url地址,3.分类排行,4.最亮达人,5.搜索发现,6.一起拍,7.热门单品排行,8.直接显示某个分享',
  `data` text,
  `sort` smallint(5) default '10',
  `status` tinyint(1) default '1',
  `is_hot` tinyint(1) default '0',
  `is_new` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%m_topic_tag`;
CREATE TABLE `%DB_PREFIX%m_topic_tag` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `is_recommend` tinyint(1) NOT NULL COMMENT '是否推荐',
  `count` int(11) NOT NULL COMMENT '是否为预设标签',
  `is_preset` tinyint(1) NOT NULL,
  `color` varchar(10) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%m_topic_tag_cate`;
CREATE TABLE `%DB_PREFIX%m_topic_tag_cate` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `sub_name` varchar(255) NOT NULL COMMENT '附标题',
  `mobile_title_bg` varchar(255) NOT NULL COMMENT '手机分类背景图',
  `sort` int(11) NOT NULL,
  `showin_mobile` tinyint(1) NOT NULL,
  `showin_web` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `%DB_PREFIX%m_topic_tag_cate_link`;
CREATE TABLE `%DB_PREFIX%m_topic_tag_cate_link` (
  `cate_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`cate_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `%DB_PREFIX%m_adv` VALUES ('8', '广告3', './public/attachment/201203/16/01/4f629a31dc8d0.jpg', 'index', '2', 'a:1:{s:3:\"url\";s:20:\"http://www.fanwe.com\";}', '1', '1');
INSERT INTO `%DB_PREFIX%m_adv` VALUES ('11', '广告2', './public/attachment/201203/16/01/4f62948c32575.jpg', 'index', '9', 'a:1:{s:7:\"cate_id\";i:0;}', '4', '1');
INSERT INTO `%DB_PREFIX%m_adv` VALUES ('12', '方维团购', './public/attachment/201203/16/01/4f6293aea2488.jpg', 'index', '2', 'a:1:{s:3:\"url\";s:20:\"http://www.fanwe.com\";}', '5', '1');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('1', 'catalog_id', '默认分类id', '0', '1', '', '', '3', '1', '0', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('19', 'index_logo', '首页logo', './public/attachment/201203/16/01/4f629256ce697.png', '2', '', '', '2', '1', '0', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('8', 'region_version', '配送地区版本', '1', '0', '', '', '8', '1', '0', '默认填写为1');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('9', 'only_one_delivery', '只有一个配送地区', '0', '1', '0,1', '否,是', '9', '1', '0', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('10', 'kf_phone', '客服电话', '400-000-0000', '0', '', '', '5', '1', '0', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('11', 'kf_email', '客服邮箱', 'qq@fanwe.com', '0', '', '', '4', '1', '0', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('12', 'select_payment_id', '默认支付方式id', '19', '1', '', '', '5', '1', '1', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('15', 'delivery_id', '默认配送方式id', '1', '1', '', '', '5', '1', '1', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('16', 'page_size', '分页大小', '10', '0', '', '', '6', '1', '0', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('17', 'about_info', '关于我们', '方维团购商业系统', '3', '', '', '10', '1', '0', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('18', 'program_title', '程序标题名称', '方维团购商业系统', '0', '', '', '1', '1', '0', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('31', 'has_delivery_time', '有配送日期选择', '0', '1', '0,1', '否,是', '13', '1', '1', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('32', 'has_ecv', '有优惠券', '0', '1', '0,1', '否,是', '9', '1', '1', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('33', 'has_invoice', '有发票', '0', '1', '0,1', '否,是', '10', '1', '1', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('34', 'has_message', '有留言框', '1', '1', '0,1', '否,是', '11', '1', '1', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('35', 'has_region', '有配送地区选择项', '1', '1', '0,1', '否,是', '7', '1', '0', null);
INSERT INTO `%DB_PREFIX%m_config` VALUES ('36', 'select_delivery_time_id', '默认配送日期id', '21', '1', '', '', '6', '1', '1', 'dsads');
INSERT INTO `%DB_PREFIX%m_config` VALUES ('43', 'admin_pwd', '后台管理帐户密码', '6714ccb93be0fda4e51f206b91b46358', '0', '', '', '0', '1', '0', ' 默认值为:fanwe');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('1', '19', '1', 'Malipay', '支付宝/各银行', '0', null, '1');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('2', '20', '1', 'Mcod', '现金支付/货到付款', '1', null, '1');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('3', '', '5', '1', '家', null, null, '1');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('4', '', '5', '2', '公司', null, null, '1');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('6', '', '4', '新闻公告', '方维团购商业系统', null, null, '1');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('7', '', '6', '2', '办法用品', null, null, '1');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('8', '', '6', '1', '服装', null, null, '1');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('10', '', '2', '1', '周末配送', null, null, '1');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('11', '', '2', '21', '周一至周五', null, null, '1');
INSERT INTO `%DB_PREFIX%m_config_list` VALUES ('19', null, '2', '3', '不限', null, null, '1');
INSERT INTO `%DB_PREFIX%m_index` VALUES ('11', '方维团购', '方维团购', '方维团购', './public/attachment/201203/16/02/4f62a0967a067.png', '2', 'a:1:{s:3:\"url\";s:20:\"http://www.fanwe.com\";}', '1', '1', '1', '0');
INSERT INTO `%DB_PREFIX%m_index` VALUES ('15', '公告列表', '公告列表', '公告列表', './public/attachment/201203/16/02/4f62a07dd5cd2.png', '21', '', '5', '1', '0', '0');
INSERT INTO `%DB_PREFIX%m_index` VALUES ('16', '休闲娱乐', '休闲娱乐', '休闲娱乐', './public/attachment/201203/16/02/4f62a05f85d75.png', '9', 'a:1:{s:7:\"cate_id\";i:0;}', '6', '1', '0', '0');
INSERT INTO `%DB_PREFIX%m_index` VALUES ('17', '餐饮美食', '餐饮美食', '餐饮美食', './public/attachment/201203/16/02/4f62a02b74f38.png', '10', 'a:1:{s:7:\"cate_id\";i:0;}', '7', '1', '0', '0');
INSERT INTO `%DB_PREFIX%m_index` VALUES ('18', '生活类', '生活类', '生活类', './public/attachment/201203/16/02/4f629fa1400d6.png', '10', 'a:1:{s:7:\"cate_id\";i:0;}', '8', '1', '0', '0');
INSERT INTO `%DB_PREFIX%m_index` VALUES ('19', '儿童游艺', '儿童游艺', '', './public/attachment/201203/16/01/4f629dc432837.png', '9', 'a:1:{s:7:\"cate_id\";i:0;}', '9', '1', '0', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('1', '电影', '1', '2', '1', '', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('2', '自助游', '1', '0', '1', '', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('3', '闽菜', '1', '0', '1', '', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('4', '川菜', '1', '0', '1', '', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('5', '咖啡', '1', '0', '1', '#fff100', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('6', '牛排', '1', '0', '1', '#a1410d', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('7', '包包', '1', '0', '0', '#ed008c', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('8', '复古', '1', '0', '0', '#a36209', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('9', '甜美', '1', '0', '0', '', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('10', '日系', '1', '0', '0', '#a4d49d', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag` VALUES ('11', '欧美', '1', '10', '0', '#ee1d24', '0');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate` VALUES ('1', '休闲娱乐', '', './public/attachment/201203/16/02/4f62a42fb3721.png', '0', '1', '1');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate` VALUES ('2', '乐享美食', '', './public/attachment/201203/16/02/4f62a3f0193ef.png', '0', '1', '1');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate` VALUES ('3', '旅游酒店', '', './public/attachment/201203/16/02/4f62a3c2e24f4.png', '0', '1', '1');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate` VALUES ('4', '都市购物', '', './public/attachment/201203/16/02/4f62a3a573b4c.png', '0', '1', '1');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate` VALUES ('5', '幸福居家', '', './public/attachment/201203/16/02/4f62a32f87588.png', '1', '0', '1');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate` VALUES ('6', '浪漫婚恋', '', './public/attachment/201203/16/02/4f62a30682ef5.png', '2', '0', '1');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate` VALUES ('7', '玩乐帮派', '', './public/attachment/201203/16/02/4f62a2e937356.png', '3', '0', '1');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('1', '1');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('1', '5');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('1', '11');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('2', '3');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('2', '4');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('2', '5');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('2', '6');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('3', '2');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('3', '11');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('4', '5');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('4', '7');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('4', '8');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('4', '9');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('4', '10');
INSERT INTO `%DB_PREFIX%m_topic_tag_cate_link` VALUES ('4', '11');