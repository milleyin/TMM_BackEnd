
--
-- 表的结构 `tmm_collect`   收集表(点赞，浏览)
--
DROP TABLE IF EXISTS `tmm_collect`;
CREATE TABLE IF NOT EXISTS `tmm_collect` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联商品数据模型表（shops_classliy）主键id',
  `shops_id` INT(11) UNSIGNED NOT NULL COMMENT '商品id',
  `user_id` int(11) unsigned  NOT NULL COMMENT 'APP用户ID',
  `user_ip` VARCHAR(15) DEFAULT '' COMMENT '用户（赞、浏览）的ip',
  `user_address` VARCHAR(100) DEFAULT '' COMMENT '用户（赞、浏览）的地址',
  `collect_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '收集类型',
  `is_collect` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '赞的状态0取消1已赞',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用', 
  PRIMARY KEY (`id`)
 )ENGINE = InnoDB COMMENT = '收集表' DEFAULT CHARSET=utf8;
