--
-- 表的结构 `tmm_privilege` 优惠码
--
DROP TABLE IF EXISTS `tmm_privilege`;
CREATE TABLE `tmm_privilege` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',  
  `code` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `use_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '优惠码';