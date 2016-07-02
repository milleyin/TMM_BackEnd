--
-- 表的结构 `tmm_refund`
--
DROP TABLE IF EXISTS `tmm_refund`;
CREATE TABLE IF NOT EXISTS `tmm_refund` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `admin_id` int(11) unsigned NOT NULL COMMENT '管理员的id',
  `reason` varchar(200) NOT NULL COMMENT '退款原因',
  `is_organizer` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '是否有组织者',
  `user_refund` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '用户退款比例 %',
  `agent_push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '代理商分成比例 %',
  `store_push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '商家分成比例 %',
  `user_push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '组织者分成比例 %',
  `sys_push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '平台分成比例 %',
  `count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '使用次数',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态1正常0禁用-1删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reason_UNIQUE` (`reason`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='退款理由配置表';

--
-- 表的结构 `tmm_refund`
--
DROP TABLE IF EXISTS `tmm_refund_log`;
CREATE TABLE IF NOT EXISTS `tmm_refund_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `admin_id` int(11) unsigned NOT NULL COMMENT '管理员的id',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户的id',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单的id',
  `refund_id` int(11) unsigned NOT NULL COMMENT '退款理由id',
  `reason` varchar(200) NOT NULL COMMENT '退款原因',
  `is_organizer` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '是否有组织者',
  `user_refund` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '用户退款比例 %',
  `agent_push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '代理商分成比例 %',
  `store_push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '商家分成比例 %',
  `user_push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '组织者分成比例 %',
  `sys_push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '平台分成比例 %',
  `refund_time` int(10) unsigned NOT NULL COMMENT '退款时间',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态1正常0禁用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='退款记录表';




--
-- 表的结构 `tmm_deposit_log`
--
DROP TABLE IF EXISTS `tmm_deposit_log`;
CREATE TABLE IF NOT EXISTS `tmm_deposit_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `admin_id` int(11) unsigned NOT NULL COMMENT '操作者id',
  `deposit_id` int(11) unsigned NOT NULL COMMENT '保证金归属id（代理商、商家、组织者）',
  `deposit_who` tinyint(3) unsigned NOT NULL COMMENT '保证金归属用户表 （代理商、商家、组织者）',
  `deposit` DECIMAL(13,2) NOT NULL DEFAULT 0.00 COMMENT '保证金',
  `deposit_status` tinyint(3) NOT NULL COMMENT '1缴纳-1扣除',
  `reason` varchar(200) NOT NULL DEFAULT '' COMMENT '扣除理由',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL  COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态1正常0禁用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='保证金记录表';