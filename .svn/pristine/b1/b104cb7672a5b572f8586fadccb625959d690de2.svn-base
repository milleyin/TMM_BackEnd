--
-- 表的结构 `tmm_audit_log`
--

CREATE TABLE IF NOT EXISTS `tmm_audit_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `audit_id` int(11) unsigned NOT NULL COMMENT '审核人的id',
  `audit_who` tinyint(3) unsigned NOT NULL COMMENT '审核人的用户表 （商家、组织者等）',
  `audit_element` tinyint(3) unsigned NOT NULL COMMENT '审核的元素（项目、商品、角色）',
  `element_id` int(11) unsigned NOT NULL COMMENT '元素的id',
  `audit` tinyint(1) NOT NULL COMMENT '审核状态 1通过-1拒绝',
  `info` text NOT NULL DEFAULT '' COMMENT '拒绝理由',
  `url` varchar(200) NOT NULL COMMENT '操作链接',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '操作ip',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '操作地址（商家用到）',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '记录状态1正常0禁用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='审核记录';