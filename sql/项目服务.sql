--
-- 表的结构 `tmm_service` 服务表
--
CREATE TABLE IF NOT EXISTS `tmm_wifi` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `admin_id` INT(11) UNSIGNED NOT NULL COMMENT '归属管理员id',
  `name` VARCHAR(20) NOT NULL COMMENT '服务名称',
  `info` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '服务简介',
  `icon` VARCHAR(100) NOT NULL COMMENT '服务图标',
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '状态0禁用1正常-1删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
 )ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT='服务表';

--
-- 表的结构 `tmm_items_service` 项目服务表
--
CREATE TABLE IF NOT EXISTS `tmm_items_wifi` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `agent_id` INT(11) UNSIGNED NOT NULL COMMENT '关联代理商用户表（agent）主键id',
  `item_id` INT(11) UNSIGNED NOT NULL COMMENT '项目id',
  `wifi_id` INT(11) UNSIGNED NOT NULL COMMENT '服务id',
  `sort` INT(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '状态0禁用1正常-1删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_wifi` (`item_id`,`wifi_id`)
 )ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT='项目服务表';