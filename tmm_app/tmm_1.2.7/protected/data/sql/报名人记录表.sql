--
-- 表的结构 `tmm_attend` 活动报名表
--
DROP TABLE IF EXISTS `tmm_attend`;
CREATE TABLE `tmm_attend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `actives_id` INT(11) UNSIGNED NOT NULL COMMENT '活动',
  `founder_id` int(11) unsigned NOT NULL COMMENT '创办者',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户',
  `p_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '归属上级',
  `number` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '报名总数',
  `people` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '成人数量',
  `children` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '儿童数量',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '姓名',
  `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `is_people` tinyint(3) NOT NULL DEFAULT 1 COMMENT '是否成人',
  `gender` tinyint(3) NOT NULL DEFAULT 0 COMMENT '未知性别',
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '活动报名表';