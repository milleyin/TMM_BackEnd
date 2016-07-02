
--
-- 表的结构 `tmm_cargo` 货物管理表
--

DROP TABLE IF EXISTS `tmm_cargo`;
CREATE TABLE IF NOT EXISTS `tmm_cargo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',  
  `cargo_no` varchar(128) NOT NULL COMMENT '货物单号',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属订单表',  
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `order_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '订单类型',   
  `sale_id` int(3) unsigned NOT NULL DEFAULT 0 COMMENT '归属售后表',
  `sale_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '售后类型(退款,换货)',
  `cargo_type` tinyint(3)  NOT NULL DEFAULT 0 COMMENT '货物分类',
  `address_id` int(11) unsigned NOT NULL COMMENT '归属地址管理ID',
  `address` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '详细地址',  
  `express_id` int(11) unsigned NOT NULL COMMENT '归属快递公司ID',
  `express_num` VARCHAR(32)  NOT NULL DEFAULT ''  COMMENT '快递公司单号',
  `express_text` text NOT NULL COMMENT '快递公司信息',
  `express_name` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '归属快递公司名称',  
  `pay_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '付款费用',
  `pay_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '付款状态',
  `pay_type` tinyint(3)  NOT NULL DEFAULT 0 COMMENT '付款费用类型',
  `pay_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '付款费用角色',
  `who_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '谁的货 角色id',
  `who_type` tinyint(3)  NOT NULL DEFAULT '0' COMMENT '谁的货 角色类型0其他1=管理员2=商家3代理商4=用户',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作角色id',
  `role_type` tinyint(3)  NOT NULL DEFAULT '0' COMMENT '操作角色类型0其他1=管理员2=商家3代理商4=用户',
  `push_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发给谁 角色id',
  `push_type` tinyint(3)  NOT NULL DEFAULT '0' COMMENT '发给谁 角色类型0其他1=管理员2=商家3代理商4=用户',
  `cargo_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '货物状态',
  `centre_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '核心状态',   
  `cargo_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '确认收货时间',
  `auto_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '自动确认收货时间',  
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='货物管理';

--
-- 表的结构 `tmm_sale`售后管理表
--

DROP TABLE IF EXISTS `tmm_sale`;
CREATE TABLE IF NOT EXISTS `tmm_sale`(
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sale_no` varchar(128) NOT NULL COMMENT '售后单号',
  `order_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '申请订单',
  `order_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '订单类型',  
  `order_no` varchar(128) NOT NULL COMMENT '订单号',
  `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '申请用户',   
  `apply_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属申请ID',    
  `sale_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '售后类型',
  `cargo_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '货物ID',
  `cargo_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '货物类型',  
  `order_sale` tinyint(3) NOT NULL DEFAULT 0 COMMENT '订单售后',
  `order_time` INT(10) UNSIGNED NOT NULL COMMENT '过期时间', 
  `admin_id_first` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(初)',
  `remark_first` varchar(100) DEFAULT '' COMMENT '备注(初)',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(初)时间',  
  `admin_id_double` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(复)',
  `remark_double` varchar(100) DEFAULT '' COMMENT '备注(复)',
  `double_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(复)时间',  
  `admin_id_submit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(确认)',
  `remark_submit` varchar(100) DEFAULT '' COMMENT '备注(确认)',
  `submit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(确认)时间',  
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '实际退款金额',
  `fee_price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '退款费用',
  `fact_price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '实际到账金额',  
  `sale_total` decimal(13,2) NOT NULL DEFAULT 0.00 COMMENT '金额统计服务费',
  `sale_price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '服务费用/人',
  `sale_go_count` int(11) NOT NULL DEFAULT '0' COMMENT '用户出游人数量',  
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '服务费退款角色id',
  `role_type` tinyint(3)  NOT NULL DEFAULT '0' COMMENT '服务费退款角色类型0其他1=管理员2=商家3代理商4=用户',
  `role_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '服务费退款金额',
  `role_remark` varchar(128) NOT NULL DEFAULT '' COMMENT '服务费退款备注',  
  `sale_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '退款状态 0待退款1=已成功2=申请3=失败',
  `audit_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核状态0待审核1=申成2=申失3=初成4=初败5=确成6=确败',
  `centre_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '核心状态',  
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='售后管理表';

--
-- 表的结构 `tmm_sale_detail`售后详细表
--

DROP TABLE IF EXISTS `tmm_sale_detail`;
CREATE TABLE IF NOT EXISTS `tmm_sale_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',  
  `sale_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属售后ID', 
  `sale_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '售后类型',
  `cargo_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '货物类型',  
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
  `total` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '总计总额',  
  `apply_detail_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属售后申请详细ID',  
  `store_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属商家ID',
  `store_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '商家退款金额',
  `store_total` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '商家单价退款总金额',
  `store_remark` varchar(128) DEFAULT '' COMMENT '商家备注',
  `agent_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属代理商ID',
  `agent_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '代理商退款金额',
  `agent_total` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '代理商单价退款总金额',
  `agent_remark` varchar(128) DEFAULT '' COMMENT '代理商备注',  
  `tmm_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属平台ID',
  `tmm_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '平台退款金额',
  `tmm_remark` varchar(128) DEFAULT '' COMMENT '平台备注',  
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='售后详细表';