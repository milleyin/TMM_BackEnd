/*
Navicat MySQL Data Transfer

Source Server         : localhsot
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : tmm

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-07-28 15:50:11
*/

SET FOREIGN_KEY_CHECKS=0;


CREATE TABLE IF NOT EXISTS `tmm`.`items` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目数据模型表（items_classliy）主键id',
  `agent_id` INT(11) UNSIGNED NOT NULL COMMENT '关联代理商用户表（agent）主键id',
  `store_id` INT(11) UNSIGNED NOT NULL COMMENT '关联商家用户表(merchant)主键id',
  `manager_id` INT(11) UNSIGNED NOT NULL COMMENT '项目管理者(关联商家用户表（merchant）主键 id',
  `name` VARCHAR(100) NOT NULL COMMENT '项目名称',
  `area_id_p` INT(11) UNSIGNED NOT NULL COMMENT '关联地名表(aera)主键id (p_id=0) 省(市)',
  `area_id_m` INT(11) UNSIGNED NOT NULL COMMENT '关联地名表(aera)主键id (p_id=t.area_id_p) 市(区)',
  `area_id_c` INT(11) UNSIGNED NOT NULL COMMENT '关联地名表(aera)主键id (p_id=t.area_id_m) 县(区)',
  `address` VARCHAR(100) NOT NULL COMMENT '详细地址',
  `fee` FLOAT(5,2) NOT NULL DEFAULT 0.00 COMMENT '平台对项目的抽成 %',
  `info` VARCHAR(100) NULL COMMENT '简介',
  `phone` VARCHAR(20) NULL COMMENT '联系电话',
  `weixin` VARCHAR(20) NULL COMMENT '微信号',
  `content` TEXT NULL COMMENT '项目详细内容',
  `audit` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '审核状态',
  `start_work` TIME NOT NULL DEFAULT '00:00:00' COMMENT '工作开始时间',
  `end_work` TIME NOT NULL DEFAULT '23:59:59' COMMENT '工作结束时间',
  `pub_time` INT(10) NOT NULL COMMENT '发布时间',
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '最后一次更新时间',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '记录状态',
  PRIMARY KEY (`id`)
 )ENGINE = InnoDB COMMENT = '项目主表' DEFAULT CHARSET=utf8;






/*

-- ----------------------------
-- Table structure for tmm_items  项目表
-- ----------------------------
DROP TABLE IF EXISTS `tmm_items`;
CREATE TABLE `tmm_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT 'APP用户ID',
  `agent_id` int(11) unsigned NOT NULL COMMENT '代理商ID',
  `area_id_p` int(11) unsigned NOT NULL COMMENT '省分ID',
  `area_id_m` int(11) unsigned NOT NULL COMMENT '城市ID',
  `area_id_c` int(11) unsigned NOT NULL COMMENT '县(区)ID',
  `name` varchar(24) NOT NULL DEFAULT '' COMMENT '项目名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '出行类型1=点2=线3=结伴游',
  `audit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态0未通过 1=通过',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

*/

-- ----------------------------
-- Table structure for tmm_dot  点
-- ----------------------------
DROP TABLE IF EXISTS `tmm_dot`;
CREATE TABLE `tmm_dot` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `agent_id` int(11) unsigned NOT NULL COMMENT '代理商ID',
  `store_id` int(11) unsigned NOT NULL COMMENT '店铺ID',
  `name` varchar(24) NOT NULL DEFAULT '' COMMENT '项目名称',
  `down` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '下单量',
  `brow` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '浏览量',
  `share` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '分享量',
  `praise` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '点赞量',
  `audit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态0未通过 1=通过',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tmm_dot_pro  点与项目关联表
-- ----------------------------
DROP TABLE IF EXISTS `tmm_dot_pro`;
CREATE TABLE `tmm_dot_pro` (
  `dot_id` int(11) unsigned NOT NULL COMMENT '点的ID',
  `pro_id` int(11) unsigned NOT NULL COMMENT '项目ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;





-- ----------------------------
-- Table structure for tmm_thr_dot   线对应点关系表
-- ----------------------------
DROP TABLE IF EXISTS `tmm_thr_dot`;
CREATE TABLE `tmm_thr_dot` (
  `thrand_id` int(11) unsigned NOT NULL COMMENT '线ID',
  `dot_id` int(11) unsigned NOT NULL COMMENT '点ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tmm_thrand   线表
-- ----------------------------
DROP TABLE IF EXISTS `tmm_thrand`;
CREATE TABLE `tmm_thrand` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `agent_id` int(11) unsigned NOT NULL COMMENT '代理商ID',
  `store_id` int(11) unsigned NOT NULL COMMENT '店铺ID',
  `name` varchar(24) NOT NULL DEFAULT '' COMMENT '项目名称',
  `down` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '下单量',
  `brow` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '浏览量',
  `share` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '分享量',
  `praise` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '点赞量',
  `audit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态0未通过 1=通过',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*

-- ----------------------------
-- Table structure for tmm_project  对应票价项目表
-- ----------------------------
DROP TABLE IF EXISTS `tmm_item_classify`;
CREATE TABLE `tmm_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) unsigned NOT NULL COMMENT '商家ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '项目名称',
  `project_type` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '商家类型1=吃2=玩3=住',
  `img_list` varchar(256) NOT NULL DEFAULT '' COMMENT '图片集合',
  `info` text COMMENT '详情',
  `work_begin` varchar(12) NOT NULL DEFAULT '' COMMENT '工作开始时间',
  `work_end` varchar(12) NOT NULL DEFAULT '' COMMENT '工作结束时间',
  `audit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态0未通过 1=通过',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

*/
-- ----------------------------
-- Table structure for tmm_faretype  票价类型表
-- ----------------------------
DROP TABLE IF EXISTS `tmm_fare`;
CREATE TABLE `tmm_faretype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) unsigned NOT NULL COMMENT '商家ID',
  `agent_id` int(11) unsigned NOT NULL COMMENT '代理商ID',
  `item_id` int(11) unsigned NOT NULL COMMENT '项目ID',
  `classify_id` int(11) unsigned NOT NULL COMMENT '项目分类',
  `name` varchar(24) NOT NULL DEFAULT '' COMMENT '名称',
  `info` varchar(64)  NULL DEFAULT '' COMMENT '说明',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态0禁用1启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;









-- ----------------------------
-- Table structure for tmm_tags   标签详情表
-- ----------------------------
DROP TABLE IF EXISTS `tmm_tags`;
CREATE TABLE `tmm_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL  COMMENT '创建人',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '标签名',
  `weight` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '权重',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tmm_tags_select   标签记录表  
-- ----------------------------
DROP TABLE IF EXISTS `tmm_tags_select`;
CREATE TABLE `tmm_tags_select` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL COMMENT '创建人',
  `tags_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签类型ID',
  `type_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签类型ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tmm_tags_element   元素(商品,角色)选择标签  
-- ----------------------------
DROP TABLE IF EXISTS `tmm_tags_element`;
CREATE TABLE `tmm_tags_element` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `select_id` int(11) unsigned NOT NULL COMMENT '选择人ID',
  `select_type` int(11) unsigned NOT NULL COMMENT '选择人类型',
  `element_id` int(11) unsigned NOT NULL COMMENT '元素ID',
  `element_type` int(11) unsigned NOT NULL COMMENT '元素类型',
  `tags_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签类型ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tmm_tags_type   标签类型表（无限级分类） 
-- ----------------------------
DROP TABLE IF EXISTS `tmm_tags_type`;
CREATE TABLE `tmm_tags_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `p_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID 0',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '标签名',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- 表的结构 `tmm_collect`   收集表(点赞，分享)
--
DROP TABLE IF EXISTS `tmm_collect`;
CREATE TABLE IF NOT EXISTS `tmm_collect` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目数据模型表（items_classliy）主键id',
  `shops_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品id',
  `items_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目主表（items）主键id',
  `col_type_id` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '收集类型ID',
  `user_id` int(11) unsigned  NULL DEFAULT '0'  COMMENT 'APP用户ID',
  `login_token` varchar(128) NOT  NULL DEFAULT '' COMMENT '推送TOKEN',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用', 
  PRIMARY KEY (`id`)
 )ENGINE = InnoDB COMMENT = '收集表' DEFAULT CHARSET=utf8;
 
 --
-- 表的结构 `tmm_collect_type`   收集分类(点赞，分享)
--
DROP TABLE IF EXISTS `tmm_collect_type`;
CREATE TABLE IF NOT EXISTS `tmm_collect_type` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '收集名称',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用', 
  PRIMARY KEY (`id`)
 )ENGINE = InnoDB COMMENT = '收集分类' DEFAULT CHARSET=utf8;
 

