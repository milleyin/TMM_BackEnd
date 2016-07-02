--
-- 表的结构 `tmm_order_organizer`   组织者订单详情表
--
DROP TABLE IF EXISTS `tmm_order_organizer`;
CREATE TABLE IF NOT EXISTS `tmm_order_organizer` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_no` VARCHAR(128)  NOT NULL COMMENT '结伴 id 号',  
  `organizer_id` int(11) unsigned NOT NULL COMMENT '组织者id',
  `group_id` int(11) unsigned NOT NULL COMMENT '结伴游商品id',  
  `shops_name` varchar(24) NOT NULL DEFAULT '' COMMENT '商品名称',  
  `shops_list_img` varchar(128) NOT NULL DEFAULT '' COMMENT '列表头图', 
  `shops_page_img` varchar(128) NOT NULL DEFAULT '' COMMENT '详情头图', 
  `shops_list_info` varchar(128) NOT NULL DEFAULT '' COMMENT '列表简介', 
  `shops_page_info` varchar(128) NOT NULL DEFAULT '' COMMENT '详情简介', 
  `shops_pub_time` INT(10) NOT NULL COMMENT '发布时间',
  `shops_up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',  
  `shops_add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',  
  `group_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `group_remark` text  NOT NULL DEFAULT '' COMMENT '结伴游备注',
  `group_start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名开始时间',
  `group_end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名结束时间',
  `group_group_time` INT(10) unsigned NOT NULL COMMENT '出游时间',
  `group_group` tinyint(3)  NOT NULL DEFAULT '0' COMMENT '团状态',  
  `user_order_count` int(11) unsigned NOT NULL COMMENT '用户下单数量',
  `user_pay_count` int(11) unsigned NOT NULL COMMENT '用户支付数量',
  `user_submit_count` int(11) unsigned NOT NULL COMMENT '用户确认出游数量',
  `user_price` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '实际服务费用',
  `user_go_count` int(11) unsigned NOT NULL COMMENT '用户出游人数量',
  `user_price_count` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '下单总额',
  `total` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总计总额',
  `is_organizer` tinyint(3)  NOT NULL DEFAULT '0' COMMENT '组织着确认出游 -1 不去 0 没选择 1去', 	
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间(下单时间)',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用-1删除', 
  PRIMARY KEY (`id`)
 )ENGINE = InnoDB COMMENT = '组织者订单详情表' DEFAULT CHARSET=utf8;
