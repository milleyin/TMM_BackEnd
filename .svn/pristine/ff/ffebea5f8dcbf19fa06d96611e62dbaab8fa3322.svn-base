
--
-- 表的结构 `tmm_ad` 广告
--
DROP TABLE IF EXISTS `tmm_ad`;
CREATE TABLE IF NOT EXISTS `tmm_ad` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `p_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '定级',
  `admin_id` int(11) UNSIGNED NOT NULL COMMENT '管理员账号',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '广告类型',
  `link_type` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '链接类型',
  `link` varchar(128) NOT NULL COMMENT '链接',
  `options` varchar(128) NOT NULL DEFAULT '' COMMENT '链接属性',
  `name` varchar(32) NOT NULL COMMENT '名字', 
  `info` varchar(128) NOT NULL DEFAULT '' COMMENT '说明', 
  `img` VARCHAR(128) NOT NULL COMMENT '原图',
  `litimg` VARCHAR(128) NULL DEFAULT '' COMMENT '缩略图',
  `sort` INT(11) UNSIGNED NOT NULL DEFAULT 50 COMMENT '排序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `up_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT 1 COMMENT '记录状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告表';


DROP TABLE IF EXISTS `tmm_select`;
CREATE TABLE IF NOT EXISTS `tmm_select` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(11) UNSIGNED NOT NULL COMMENT '管理员账号',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类型',
  `to_id` int(11) UNSIGNED NOT NULL COMMENT '归属',
  `select_id` int(11) UNSIGNED NOT NULL COMMENT '选中',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT 50 COMMENT '排序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `up_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT 1 COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='选择表';