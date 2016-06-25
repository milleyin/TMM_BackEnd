
--
-- 表的结构 `tmm_ad` 广告
--
DROP TABLE IF EXISTS `tmm_ad`;
CREATE TABLE IF NOT EXISTS `tmm_ad` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(11) UNSIGNED NOT NULL COMMENT '管理员账号',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '广告类型',
  `link_type` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '链接类型',
  `link` varchar(128) NOT NULL COMMENT '链接',
  `options` varchar(128) NOT NULL DEFAULT '' COMMENT '链接属性',
  `name` varchar(32) NOT NULL COMMENT '名字', 
  `info` varchar(128) NOT NULL DEFAULT '' COMMENT '说明', 
  `img` VARCHAR(128) NOT NULL COMMENT '原图',
  `litimg` VARCHAR(128) NULL DEFAULT '' COMMENT '缩略图',
  `sort` TINYINT(3) UNSIGNED NOT NULL DEFAULT 50 COMMENT '排序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `up_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT 1 COMMENT '记录状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告表';

--
-- 表的结构 `tmm_ad` 类型值
--
DROP TABLE IF EXISTS `tmm_type`;
CREATE TABLE IF NOT EXISTS `tmm_type` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_type` tinyint(3) UNSIGNED NOT NULL COMMENT '角色类型',
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色账号',
  `type` int(11) UNSIGNED NOT NULL COMMENT '类型用途',
  `name` varchar(32) NOT NULL COMMENT '类型名',
  `value` varchar(128) NOT NULL COMMENT '类型值',
  `info` varchar(128) NOT NULL DEFAULT '' COMMENT '类型说明',
  `options` varchar(128) NOT NULL DEFAULT '' COMMENT '类型属性',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT 50 COMMENT '排序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `up_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT 1 COMMENT '记录状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='类型值表';
