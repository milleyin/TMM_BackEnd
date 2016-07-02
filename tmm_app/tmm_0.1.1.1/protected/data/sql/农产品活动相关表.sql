--
-- 表的结构 `farm` 农产品
--
DROP TABLE IF EXISTS `tmm_farm`;
CREATE TABLE IF NOT EXISTS `tmm_farm` (
  `id` INT(11) UNSIGNED NOT NULL COMMENT '主键关联项目总表（items）主键id',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联数据模型(items_classliy)主键id',
  PRIMARY KEY (`id`)
)ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT = '项目农产品的附加表';

--
-- 表的结构 `tmm_actives` 活动表
--
DROP TABLE IF EXISTS `tmm_actives`;
CREATE TABLE `tmm_actives` (
  `id` int(11) unsigned NOT NULL  COMMENT 'ID',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联商品数据模型表（shops_classliy）主键id',
  `actives_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '0、1 活动分类',
  `organizer_id` int(11) unsigned NOT NULL COMMENT '组织者发布',
  `tour_type` tinyint(3) NOT NULL DEFAULT -1 COMMENT '活动（旅游）的类型',
  `tour_count` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '旅游活动报名人数',
  `order_count` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '下单量',
  `push` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '平台对项目的抽成 %',
  `push_orgainzer` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '组织者对项目的抽成 %',
  `push_store` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '商家对项目的抽成 %',
  `push_agent` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '代理商平台对项目的抽成 %', 
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '活动单价',
  `number` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '活动初始数量',
  `order_number` int(11) NOT NULL DEFAULT 0 COMMENT '活动剩余数量',
  `tour_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `remark` text  NOT NULL COMMENT '备注',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名结束时间',
  `pub_time` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT '发布时间',
  `go_time` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT '出游时间',
  `actives_status` tinyint(3)  NOT NULL DEFAULT '0' COMMENT '活动状态',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '活动表';


--
-- 表的结构 `tmm_actives` 活动表
--
DROP TABLE IF EXISTS `tmm_order_actives`;
CREATE TABLE IF NOT EXISTS `tmm_order_actives` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `actives_no` varchar(128) NOT NULL DEFAULT '' COMMENT '活动单号',
  `organizer_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '组织者id',
  `actives_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '活动商品id',
  `actives_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '0、1 活动分类',
  `user_order_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户下单数量',
  `user_pay_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户支付数量',
  `user_submit_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户确认出游数量',
  `user_price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '实际服务费用',
  `user_go_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户出游人数量',
  `user_price_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '下单总额',
  `total` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总计总额',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间(下单时间)',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '结构表';

--
-- 表的结构 `tmm_funds_log`
--
DROP TABLE IF EXISTS `tmm_account`;
CREATE TABLE IF NOT EXISTS `tmm_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `account_type` int(11) unsigned NOT NULL COMMENT '角色类型',
  `account_id` int(11) unsigned NOT NULL COMMENT '角色账号',
  `count` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '账户统计总额',
  `total` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '账户总额',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  `no_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `cash_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '提现总额',
  `pay_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '付款总额',
  `refund_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '退款总额',
  `consume_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '消费总额',
  `not_consume_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '待消费总额',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态0禁用1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色钱包';

--
-- 表的结构 `tmm_account_log`
--
DROP TABLE IF EXISTS `tmm_account_log`;
CREATE TABLE IF NOT EXISTS `tmm_account_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `account_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '角色id',
  `account_type` tinyint(3) DEFAULT NULL DEFAULT 0 COMMENT '角色类型',
  `to_account_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '交易类型id',
  `to_account_type` tinyint(3) DEFAULT NULL DEFAULT 0 COMMENT '交易角色类型',
  `manage_account_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '操作类型id 0=系统',
  `manage_account_type` tinyint(3) DEFAULT NULL DEFAULT 0 COMMENT '操作角色类型 5=系统',
  `funds_type` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '资金类型',
  `use_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '操作金额',
  `count` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '账户统计总额',
  `total` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '账户总额',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  `no_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `cash_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '提现总额',
  `pay_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '付款总额',
  `refund_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '退款总额',
  `consume_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '消费总额',
  `not_consume_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '待消费总额',
  `info` text NOT NULL COMMENT '详情',
  `ip` varchar(15) DEFAULT '' COMMENT 'IP',
  `address` varchar(100) DEFAULT '' COMMENT '地址（商家用到）',
  `add_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT 1 COMMENT '记录状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='资金记录表';


--
-- 表的结构 `tmm_order_shops`
--
DROP TABLE IF EXISTS `tmm_order_shops`;
CREATE TABLE IF NOT EXISTS `tmm_order_shops` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) unsigned NOT NULL COMMENT '归属订单id',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户ID',
  `shops_id` int(11) unsigned NOT NULL COMMENT '商品的来源关联商品（shops）主键id',
  `shops_c_id` int(11) unsigned NOT NULL COMMENT '关联商品数据模型表（shops_classliy）主键id',
  `shops_c_name` varchar(20) NOT NULL DEFAULT '' COMMENT '归属商品分类名称',
  `shops_agent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '代理商ID',
  `shops_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商品名称',
  `shops_list_img` varchar(128) NOT NULL DEFAULT '' COMMENT '列表头图',
  `shops_page_img` varchar(128) NOT NULL DEFAULT '' COMMENT '详情头图',
  `shops_list_info` varchar(128) NOT NULL DEFAULT '' COMMENT '列表简介',
  `shops_page_info` varchar(128) NOT NULL DEFAULT '' COMMENT '详情简介',
  `shops_pub_time` int(10) NOT NULL COMMENT '通过时间',
  `shops_add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `shops_up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `actives_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '0、1 活动分类',
  `actives_organizer_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '组织者发布',
  `actives_tour_type` tinyint(3) NOT NULL DEFAULT -1 COMMENT '活动（旅游）的类型',
  `tour_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `remark` text  NOT NULL COMMENT '备注',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名结束时间',
  `pub_time` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT '发布时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='复制商品表' AUTO_INCREMENT=1;

--
-- 表的结构 `tmm_user_bank`
--
DROP TABLE IF EXISTS `tmm_role_bank`;
CREATE TABLE `tmm_user_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL COMMENT '角色ID',
  `type_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '角色类型',
  `bank_type` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '用户银行类型 信用卡 借记卡 ……',
  `bank_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '开户银行',
  `bank_name` varchar(20) NOT NULL DEFAULT '' COMMENT '开户姓名', 
  `bank_identity` varchar(18) NOT NULL DEFAULT '' COMMENT '开户身份证',
  `bank_branch` varchar(100) NOT NULL DEFAULT '' COMMENT '开户支行', 
  `bank_code` varchar(50) NOT NULL DEFAULT '' COMMENT '开户银行账号',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `is_default` tinyint(3)  NOT NULL DEFAULT -1 COMMENT '是否默认 0默认 -1 非默认',
  `is_verify` tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否验证有效 验证无效-1 0没有验证 1验证有效',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户银行卡信息';
