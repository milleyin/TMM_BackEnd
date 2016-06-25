
--
-- 表的结构 `tmm_apply` 售后申请
--
DROP TABLE IF EXISTS `tmm_apply`;
CREATE TABLE IF NOT EXISTS `tmm_apply` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',    
  `order_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请订单',
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `order_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '订单类型',     
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请用户',  
  `total` decimal(13,2) NOT NULL DEFAULT 0.00 COMMENT '金额统计',
  `price` decimal(13,2) NOT NULL DEFAULT 0.00 COMMENT '服务费用/人',
  `go_count` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户出游人数量',
  `sale_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '售后类型(退款,换货)',
  `cargo_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '货物类型',  
  `apply_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请角色',
  `apply_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '角色类型',
  `apply_time` INT(10) UNSIGNED NOT NULL COMMENT '申请时间',  
  `apply_reason_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请原因',  
  `apply_reason` varchar(128) NOT NULL DEFAULT '' COMMENT '售后原因',
  `apply_explain` text NOT NULL COMMENT '申请说明',
  `apply_money` decimal(13,2) NOT NULL DEFAULT 0.00 COMMENT '申请金额',
  `apply_image1` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '凭证图片1',
  `apply_image2` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '凭证图片2',
  `apply_image3` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '凭证图片3',  
  `reply_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '答复角色',
  `reply_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '角色类型',
  `reply_time` INT(10) UNSIGNED NOT NULL COMMENT '答复时间',
  `reply_manage` tinyint(3) NOT NULL DEFAULT 0 COMMENT '答复操作',
  `reply_reason_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '答复原因',
  `reply_reason` varchar(128) NOT NULL DEFAULT '' COMMENT '售后原因',
  `reply_explain` text NOT NULL COMMENT '答复说明',
  `judge_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '审判角色',
  `judge_type` tinyint(3) DEFAULT NULL DEFAULT 0 COMMENT '角色类型',
  `judge_time` INT(10) UNSIGNED NOT NULL COMMENT '审判时间',
  `judge_manage` tinyint(10)  NOT NULL DEFAULT 0 COMMENT '审判操作',
  `judge_reason_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '审判原因',
  `judge_reason` varchar(128) NOT NULL DEFAULT '' COMMENT '售后原因',
  `judge_explain` text NOT NULL COMMENT '审判说明',   
  `order_sale` tinyint(3) NOT NULL DEFAULT 0 COMMENT '订单售后',
  `order_time` INT(10) UNSIGNED NOT NULL COMMENT '过期时间',  
  `order_explain` text NOT NULL COMMENT '订单说明',
  `apply_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '申请状态',
  `centre_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '核心状态', 
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
 )ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT='售后申请';
 
 
--
-- 表的结构 `tmm_apply_detail` 售后申请详细
--
 DROP TABLE IF EXISTS `tmm_apply_detail`;
CREATE TABLE IF NOT EXISTS `tmm_apply_detail` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',  
  `order_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '申请订单',
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `order_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '订单类型',
  `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '申请用户',
  `apply_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属申请',  
  `order_shops_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属商品',
  `shops_c_id` int(11) unsigned NOT NULL COMMENT '归属商品分类',  
  `order_items_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属项目',
  `items_c_id` int(11) unsigned NOT NULL COMMENT '归属项目分类',  
  `order_fare_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属价格', 
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '付款单价',
  `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `start_date` varchar(10) NOT NULL DEFAULT '0' COMMENT '入住时间',
  `end_date` varchar(10) NOT NULL DEFAULT '0' COMMENT '离店时间',
  `hotel_number` int(11) NOT NULL DEFAULT '1' COMMENT '入住几晚',
  `total` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总计总额',
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '状态0禁用1正常-1删除',
   PRIMARY KEY (`id`)
 )ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT='售后申请详细';
--
-- 表的结构 `tmm_reason` 售后原因
--
DROP TABLE IF EXISTS `tmm_reason`;
CREATE TABLE IF NOT EXISTS `tmm_reason` (  
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',	
	`admin_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '管理员',
	`order_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '订单类型',
	`sale_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '售后类型',
	`cargo_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '货物类型',
	`role_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '角色类型',
	`name` varchar(64) DEFAULT '' COMMENT '名称',
	`reason` varchar(128) NOT NULL DEFAULT '' COMMENT '原因',
	`remark` text NOT NULL COMMENT '备注',
	`sort` INT(11) NOT NULL DEFAULT 0 COMMENT '排序',
	`add_time` INT(10) UNSIGNED NOT NULL COMMENT '添加时间',
	`up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
	`status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '状态',
	PRIMARY KEY (`id`)
 )ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT='售后原因';
 
--
-- 表的结构 `tmm_address` 地址管理
--
DROP TABLE IF EXISTS `tmm_address`;
CREATE TABLE IF NOT EXISTS `tmm_address` (  
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',	
	`role_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属角色',
	`role_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '角色类型',
	`default_address` tinyint(3) NOT NULL DEFAULT 0 COMMENT '默认地址',
	`name` VARCHAR(64) NOT NULL COMMENT '收件人',
	`phone` VARCHAR(15) NOT NULL COMMENT '手机号',
	`area_p` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '省',
	`area_m` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '市(区)',
	`area_c` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '县(区)',	
	`address` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '详细地址',
	`postcode` VARCHAR(10) NOT NULL DEFAULT '' COMMENT '邮政编码',
	`remark` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '备注',
	`sort` INT(11) NOT NULL DEFAULT 0 COMMENT '排序',
	`add_time` INT(10) UNSIGNED NOT NULL COMMENT '添加时间',
	`up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
	`status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '状态',
	PRIMARY KEY (`id`)
 )ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT='地址管理';
 
