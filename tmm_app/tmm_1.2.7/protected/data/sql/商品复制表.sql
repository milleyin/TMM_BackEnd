
--
-- 表的结构 `tmm_order_shops`
--

DROP TABLE IF EXISTS `tmm_order_shops`;
CREATE TABLE IF NOT EXISTS `tmm_order_shops` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` INT(11) UNSIGNED NOT NULL COMMENT '归属订单id',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户ID', 
  `shops_c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联商品数据模型表（shops_classliy）主键id',
  `shops_c_name` varchar(20) NOT NULL COMMENT '归属商品分类名称',  
  `shops_agent_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '代理商ID',
  `shops_name` varchar(24) NOT NULL DEFAULT '' COMMENT '商品名称',  
  `shops_list_img` varchar(128) NOT NULL DEFAULT '' COMMENT '列表头图', 
  `shops_page_img` varchar(128) NOT NULL DEFAULT '' COMMENT '详情头图', 
  `shops_list_info` varchar(128) NOT NULL DEFAULT '' COMMENT '列表简介', 
  `shops_page_info` varchar(128) NOT NULL DEFAULT '' COMMENT '详情简介', 
  `shops_pub_time` int(10) NOT NULL COMMENT '通过时间',
  `shops_add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `shops_up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',  
  `group_organizer_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '组织者id',
  `group_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `group_remark` text  NOT NULL DEFAULT '' COMMENT '结伴游备注',
  `group_start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名开始时间',
  `group_end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名结束时间',
  `group_time` INT(10) unsigned NOT NULL COMMENT '出游时间',
  `group` tinyint(3)  NOT NULL DEFAULT '0' COMMENT '团状态',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用', 
  PRIMARY KEY (`id`)
 )ENGINE = InnoDB COMMENT = '复制商品表' DEFAULT CHARSET=utf8;