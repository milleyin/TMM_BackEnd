--
-- 表的结构 `tmm_order`
--
DROP TABLE IF EXISTS `tmm_order`;
CREATE TABLE `tmm_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `son_order_count` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '子订单总费用',
  `order_no` VARCHAR(128) NOT NULL  COMMENT '订单号',
  `user_id` int(11) unsigned NOT NULL COMMENT ' 用户ID',  
  `order_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '订单总价',
  `pay_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '支付回调总价',
  `price` DECIMAL(13,2) unsigned NOT NULL COMMENT '实际支付总价', 
  `trade_no` varchar(255) DEFAULT '' COMMENT '支付回调',
  `trade_name` VARCHAR(128) NOT NULL  COMMENT '支付账号',
  `service_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '第三方支付服务费',
  `service_fee` DECIMAL(13,2) unsigned NOT NULL COMMENT '第三方支付服务费率%',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '支付类型1=支付宝2=微信...',  
  `user_go_count` int(11) unsigned NOT NULL COMMENT '用户出游人数量', 
  `user_price` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '服务费用/人',
  `user_price_fact` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '实际服务费用/人',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付成功时间',
  `status_go` tinyint(3)  NOT NULL DEFAULT 0 COMMENT '记录状态0没选择 1去 -1不去',  
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态1=已支付0=未支付2=已过期',
  `order_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态1=已支付0=未支付2=已退款....',
  `centre_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单主表 ';

--
-- 表的结构 `tmm_rebate`
--
DROP TABLE IF EXISTS `tmm_rebate`;
CREATE TABLE `tmm_rebate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `order_no` VARCHAR(128) NOT NULL  COMMENT '订单号',
  `admin_id_first` int(20) unsigned NOT NULL DEFAULT 0 COMMENT '管理员ID(初)',
  `remark_first` VARCHAR(100) DEFAULT '' COMMENT '备注(初)',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(初)时间',
  `admin_id_double` int(20) unsigned NOT NULL DEFAULT 0 COMMENT '管理员ID(复)',
  `remark_double` VARCHAR(100) DEFAULT '' COMMENT '备注(复)',
  `double_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(复)时间',
  `admin_id_submit` int(20) unsigned NOT NULL DEFAULT 0 COMMENT '管理员ID(确认)',
  `remark_submit` VARCHAR(100) DEFAULT '' COMMENT '备注(确认)',
  `submit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(确认)时间',
  `user_id` int(11) unsigned NOT NULL COMMENT ' 用户ID',
  `old_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '退款前价格',
  `out_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '需退款价格',
  `fact_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '实际退款价格',
  `user_name` varchar(128) NOT NULL DEFAULT '' COMMENT '退款姓名',
  `bank_code` VARCHAR(100) DEFAULT '' COMMENT '退款账号',
  `refund_id` int(11) unsigned NOT NULL COMMENT '退款原因ID',
  `info` VARCHAR(100) DEFAULT '' COMMENT '退款原因',
  `bank_info` VARCHAR(100) DEFAULT '' COMMENT '账号分行',
  `error_info` VARCHAR(100) DEFAULT '' COMMENT '退款失败原因',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '付款状态 0待付款1=已成功2=申请3=失败',
  `audit_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态0待审核1=申成2=申失3=初成4=初败5=确成6=确败',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '退款类型1=支付宝2=微信...',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '退款成功时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='退款订单';


--
-- 表的结构 `tmm_order_retinue`  
--
DROP TABLE IF EXISTS `tmm_order_retinue`;
CREATE TABLE `tmm_order_retinue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `order_no` varchar(128)  NOT NULL COMMENT '订单号',
  `son_order_id` int(11) unsigned NOT NULL COMMENT '子订单ID',
  `son_order_no` varchar(128)  NOT NULL COMMENT '子订单号',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户ID', 
  `retinue_name` VARCHAR(20) NOT NULL COMMENT '被保人姓名',
  `retinue_gender` TINYINT(3) UNSIGNED NOT NULL COMMENT '被保人性别(1、男 2、女)',
  `retinue_identity` VARCHAR(18) NOT NULL COMMENT '被保人身份证号',
  `retinue_phone` VARCHAR(15) NOT NULL COMMENT '被保人手机号码',
  `insure_name` VARCHAR(20) NOT NULL COMMENT '投保人姓名',
  `insure_gender` TINYINT(3) UNSIGNED NOT NULL COMMENT '投保人性别(1、男 2、女)',
  `insure_identity` VARCHAR(18) NOT NULL COMMENT '投保人身份证号',
  `insure_phone` VARCHAR(15) NOT NULL DEFAULT '' COMMENT '投保人手机号码',
  `insure_age` int(3) unsigned COMMENT '投保年龄', 
  `is_main` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '是否是主要的联系人（0 不是1 是）',
  `insure_no` varchar(128)  DEFAULT NULL COMMENT '保单号',
  `insure_verify` varchar(128)  DEFAULT NULL COMMENT '验真码',
  `start_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '结束时间',  
  `insure_price` DECIMAL(13,2) unsigned DEFAULT 0.00 COMMENT '保险金额',
  `fact_price` DECIMAL(13,2) unsigned DEFAULT 0.00 COMMENT '实际金额',
  `insure_number` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '保险份数', 
  `beneficiary` VARCHAR(15) NOT NULL DEFAULT '法定' COMMENT '收益人', 
  `add_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单随行人员表';


--
-- 表的结构 `tmm_son_order`
--
DROP TABLE IF EXISTS `tmm_son_order`;
CREATE TABLE `tmm_son_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `son_order_no` VARCHAR(128) NOT NULL  COMMENT '子订单号',
  `order_id` int(11) unsigned NOT NULL COMMENT '归属订单',
  `order_no` VARCHAR(128) NOT NULL  COMMENT '订单号',
  `type_id` tinyint(3) unsigned DEFAULT 1 COMMENT ' 子订单类型 1 保险',  
  `user_id` int(11) unsigned NOT NULL COMMENT ' 用户ID',  
  `order_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '订单总价',
  `pay_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '支付回调总价',
  `price` DECIMAL(13,2) unsigned NOT NULL COMMENT '实际支付总价', 
  `trade_no` varchar(255) DEFAULT '' COMMENT '支付回调',
  `trade_name` VARCHAR(128) NOT NULL  COMMENT '支付账号',
  `service_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '第三方支付服务费',
  `service_fee` DECIMAL(13,2) unsigned NOT NULL COMMENT '第三方支付服务费率%',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '支付类型1=支付宝2=银行...',  
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付成功时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态1=已支付0=未支付2=已过期',
  `order_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态1=已支付0=未支付2=已退款....',
  `centre_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='子订单主表 ';



--
-- 表的结构 `tmm_bills`
--
DROP TABLE IF EXISTS `tmm_bills`;
CREATE TABLE `tmm_bills` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',  
  `order_id` int(11) unsigned NOT NULL COMMENT '归属订单',
  `order_no` VARCHAR(128) NOT NULL  COMMENT '订单号',    
  `order_items_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属订单项目详细', 
  `group_no` VARCHAR(128)  NOT NULL DEFAULT '' COMMENT '结伴 id 号',  
  `organizer_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属组织者表',
  `order_organizer_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属组织者订单详情表',  
  `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属用户',  
  `order_shops_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属订单商品表（复制表）',
  `shops_name` varchar(24) NOT NULL DEFAULT '' COMMENT '商品名称',  
  `agent_id` int(11) unsigned NOT NULL COMMENT '项目归属代理商',
  `shops_id` INT(11) UNSIGNED NOT NULL COMMENT '商品来源',  
  `shops_c_id` int(11) unsigned NOT NULL COMMENT '归属商品分类',
  `shops_c_name` varchar(20) NOT NULL COMMENT '归属商品分类名称',  
  `items_id` INT(11) UNSIGNED NOT NULL COMMENT '项目来源',
  `items_c_id` int(11) unsigned NOT NULL COMMENT '归属项目分类',
  `items_c_name` varchar(20) NOT NULL COMMENT '归属项目分类名称',  
  `items_name` varchar(100) NOT NULL COMMENT '项目名称',  
  `group_price` decimal(13,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '服务费',
  `user_order_count` int(11) unsigned NOT NULL DEFAULT 1 COMMENT '用户下单数量',
  `user_pay_count` int(11) unsigned NOT NULL DEFAULT 1 COMMENT '用户支付数量',
  `user_submit_count` int(11) unsigned NOT NULL DEFAULT 1 COMMENT '用户确认出游数量',
  `user_price` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '实际服务费用',
  `user_go_count` int(11) unsigned NOT NULL DEFAULT 1 COMMENT '用户出游人数量',
  `user_price_count` DECIMAL(13,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '下单总额',
  `items_push` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '平台对项目的抽成 %(生效值)',
  `items_push_orgainzer` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '组织者对项目的抽成 %(生效值)',
  `items_push_store` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '商家对项目的抽成 %(生效值)',
  `items_push_agent` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '代理商平台对项目的抽成 %(生效值)',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '项目价格',  
  `number` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '购买数量',
  `total` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总计总额',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `son_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0没有添加组织着细账 1已添加',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目账单详情';


--
-- 表的结构 `tmm_scan_code`
--
DROP TABLE IF EXISTS `tmm_scan_code`;
CREATE TABLE `tmm_scan_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) unsigned NOT NULL COMMENT '归属订单',
  `order_no` VARCHAR(128) NOT NULL  COMMENT '订单号', 
  `user_id` int(11) unsigned NOT NULL COMMENT ' 用户ID',  
  `is_organizer` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '是否有组织者',
  `store_id` int(11) unsigned NOT NULL COMMENT '归属商家',  
  `agent_id` int(11) unsigned NOT NULL COMMENT '项目归属代理商',
  `store_manage_id` int(11) unsigned NOT NULL COMMENT '扫描商家',  
  `order_items_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '详目ID',
  `code` varchar(255) DEFAULT '' COMMENT '扫描码',
  `error_num` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '扫码错误次数',
  `code_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扫码成功时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `code_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '扫描码状态1=已使用0=未使用2=已过期...',
  `push_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '预留推送消息',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='扫描码';

--
-- 表的结构 `tmm_scancode_log`
--
DROP TABLE IF EXISTS `tmm_scancode_log`;
CREATE TABLE `tmm_scancode_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `store_manage_id` int(11) unsigned NOT NULL COMMENT '扫描商家',  
  `code` varchar(255) DEFAULT '' COMMENT '扫描码',
  `is_error` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '是否扫码错误1=正2错',
  `error_right` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '扫码错误是否清除',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='扫描码日志表';




--
-- 表的结构 `tmm_cash`
--
DROP TABLE IF EXISTS `tmm_cash`;
CREATE TABLE `tmm_cash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cash_type` int(11) unsigned NOT NULL COMMENT '元素类型',
  `cash_id` int(11) unsigned NOT NULL COMMENT '申请人',
  `admin_id_first` int(20) unsigned NOT NULL DEFAULT 0 COMMENT '管理员ID(初)',
  `remark_first` VARCHAR(100) DEFAULT '' COMMENT '备注(初)',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(初)时间',
  `admin_id_double` int(20) unsigned NOT NULL DEFAULT 0 COMMENT '管理员ID(复)',
  `remark_double` VARCHAR(100) DEFAULT '' COMMENT '备注(复)',
  `double_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(复)时间',
  `admin_id_submit` int(20) unsigned NOT NULL DEFAULT 0 COMMENT '管理员ID(确认)',
  `remark_submit` VARCHAR(100) DEFAULT '' COMMENT '备注(确认)',
  `submit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '(确认)时间',
  `money` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '可提现金额',
  `price` DECIMAL(13,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '提现金额',
  `fee_price` DECIMAL(13,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '提现费用',
  `fact_price` DECIMAL(13,2) unsigned NOT NULL COMMENT '实际到账金额',
  `cash_name` varchar(128) NOT NULL DEFAULT '' COMMENT '提现姓名',
  `bank_code` VARCHAR(100) DEFAULT '' COMMENT '提现账号',
  `bank_info` VARCHAR(100) DEFAULT '' COMMENT '提现账号分行',
  `cash_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '付款状态 0待付款1=已成功2=申请3=失败',
  `audit_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态0待审核1=申成2=申失3=初成4=初败5=确成6=确败',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '提现类型1=支付宝2=银行...',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '到账时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现申请记录';

--
-- 表的结构 `tmm_account`
--
DROP TABLE IF EXISTS `tmm_account`;
CREATE TABLE `tmm_account`(
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `account_type` int(11) unsigned NOT NULL COMMENT '角色类型',
  `account_id` int(11) unsigned NOT NULL COMMENT '角色账号',
  `count` DECIMAL(13,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '资金统计总额',
  `total` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '资金总额',
  `money` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '可提现金额',
  `no_money` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '冻结金额',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色钱包';

