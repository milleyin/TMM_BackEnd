DROP TABLE IF EXISTS `tmm_farm_outer`;
CREATE TABLE IF NOT EXISTS `tmm_farm_outer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `dot_id` int(11) unsigned NOT NULL COMMENT '归属点ID',
  `name` varchar(64) NOT NULL COMMENT '名称',
  `info` varchar(128) NOT NULL COMMENT '简介',
  `img` varchar(128) NOT NULL COMMENT '图片',
  `link` varchar(255) NOT NULL COMMENT '外部链接',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '农产品外部链接';