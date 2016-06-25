 --
-- 表的结构 `tmm_push`
--

CREATE TABLE IF NOT EXISTS `tmm_push` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `manage_id` int(11) unsigned NOT NULL COMMENT '操作人的id',
  `manage_who` tinyint(3) unsigned NOT NULL COMMENT '操作的用户表 （代理商、商家、组织者等）',
  `push` FLOAT(5,2) NOT NULL DEFAULT 0 COMMENT '代理分成比例 % 最大为100',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `info` text NOT NULL DEFAULT '' COMMENT '备注',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL  COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态1正常0禁用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='提成表';