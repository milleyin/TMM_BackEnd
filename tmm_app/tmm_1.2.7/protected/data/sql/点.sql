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



--
-- 表的结构 `tmm_shops`   商品表
--
DROP TABLE IF EXISTS `tmm_shops`;
CREATE TABLE IF NOT EXISTS `tmm_shops` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目数据模型表（items_classliy）主键id',
  `agent_id` int(11) unsigned NOT NULL COMMENT '代理商ID',
  `name` varchar(24) NOT NULL DEFAULT '' COMMENT '项目名称',  
  `list_img` varchar(128) NOT NULL DEFAULT '' COMMENT '列表头图', 
  `page_img` varchar(128) NOT NULL DEFAULT '' COMMENT '详情头图', 
  `list_info` varchar(128) NOT NULL DEFAULT '' COMMENT '列表简介', 
  `page_info` varchar(128) NOT NULL DEFAULT '' COMMENT '详情简介', 
  `brow` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '浏览量',
  `share` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '分享量',
  `praise` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '点赞量',
  `pub_time` INT(10) NOT NULL COMMENT '发布时间',
  `audit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态0未通过 1=通过',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用', 
  PRIMARY KEY (`id`)
 )ENGINE = InnoDB COMMENT = '项目主表' DEFAULT CHARSET=utf8;

 
 
 
 --
-- 表的结构 `tmm_shops_classliy` 商品表类型
--
DROP TABLE IF EXISTS `tmm_shops_classliy`;
 CREATE TABLE IF NOT EXISTS `tmm_shops_classliy` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` VARCHAR(20) NOT NULL COMMENT '项目数据模型id',
  `info` VARCHAR(200) NULL COMMENT '项目数据模型简介说明',
  `front` VARCHAR(45) NOT NULL COMMENT '前端控制器',
  `tem_index` VARCHAR(45) NOT NULL DEFAULT 'index' COMMENT '前端封面模板',
  `tem_list` VARCHAR(45) NOT NULL DEFAULT 'list' COMMENT '前端列表模板',
  `tem_page` VARCHAR(45) NOT NULL DEFAULT 'page' COMMENT '前端内容模板',
  `admin` VARCHAR(45) NOT NULL COMMENT '后端控制器',
  `main` VARCHAR(45) NOT NULL DEFAULT 'Items' COMMENT '主要表的模型',
  `append` VARCHAR(45) NOT NULL COMMENT '附加表数据模型（数据模型 ：不是表名）',
  `add_time` INT(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `up_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `status` TINYINT(3) NOT NULL DEFAULT 1 COMMENT '记录的状态',
  PRIMARY KEY (`id`)
)ENGINE = InnoDB  DEFAULT CHARSET=utf8 COMMENT = '商品表模型表（核心）';
 
 
-- ----------------------------
-- Table structure for tmm_dot  点
-- ----------------------------
DROP TABLE IF EXISTS `tmm_dot`;
CREATE TABLE `tmm_dot` (
  `id` int(11) unsigned NOT NULL  COMMENT 'ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '点';


-- ----------------------------
-- Table structure for tmm_thrand   线表
-- ----------------------------
DROP TABLE IF EXISTS `tmm_thrand`;
CREATE TABLE `tmm_thrand` (
  `id` int(11) unsigned NOT NULL  COMMENT '自增ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for tmm_group   结伴游
-- ----------------------------
DROP TABLE IF EXISTS `tmm_group`;
CREATE TABLE `tmm_group` (
  `id` int(11) unsigned NOT NULL  COMMENT 'ID',
  `user_id` int(11) unsigned NOT NULL  COMMENT 'APP用户ID',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `remark` text  NOT NULL DEFAULT '' COMMENT '结伴游备注',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `pub_time` INT(10) unsigned NOT NULL COMMENT '发布时间',
  `group_time` INT(10) unsigned NOT NULL COMMENT '出游时间',
  `group` tinyint(3)  NOT NULL DEFAULT '0' COMMENT '团状态',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for tmm_pro   点，线，结伴=>选中项目
-- ----------------------------

DROP TABLE IF EXISTS `tmm_pro`;
CREATE TABLE `tmm_pro` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `shops_id` INT(11) UNSIGNED NOT NULL COMMENT '商品id',
  `agent_id` INT(11) UNSIGNED NOT NULL COMMENT '关联代理商用户表（agent）主键id',
  `store_id` INT(11) UNSIGNED NOT NULL COMMENT '关联商家用户表(store)主键id',
  `shops_c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目数据模型表（shops_classliy）主键id',
  `c_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目数据模型表（items_classliy）主键id',
  `sort` TINYINT(3) UNSIGNED NOT NULL DEFAULT 50 COMMENT '点排序',
  `items_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目主表（items）主键id',
  `dot_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当前项目关联点id',
  `thrand_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当前项目关联线id',
  `info` text  NOT NULL DEFAULT '' COMMENT '项目简介',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `audit` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '审核状态-1不通过 0 未审核 1 通过', 
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '结构表';




-- ----------------------------
-- Table structure for tmm_pro_fare  线=>价格
-- ----------------------------

DROP TABLE IF EXISTS `tmm_pro_fare`;
CREATE TABLE `tmm_pro_fare` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `pro_id` INT(11) UNSIGNED NOT NULL COMMENT '选中项目id',
  `fare_id` INT(11) UNSIGNED NOT NULL COMMENT '项目价格id',
  `group_id` INT(11) UNSIGNED NOT NULL COMMENT '结伴游商品id',
  `items_id` INT(11) UNSIGNED NOT NULL COMMENT '关联项目主表（items）主键id',  
  `thrand_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当前项目关联线商品id',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3)  NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '结构表';










/*
-- ----------------------------
-- Table structure for tmm_thr_dot   线对应
-- ----------------------------
DROP TABLE IF EXISTS `tmm_thr_dot`;
CREATE TABLE `tmm_thr_dot` (
  `thrand_id` int(11) unsigned NOT NULL COMMENT '线ID',
  `dot_id` int(11) unsigned NOT NULL COMMENT '点ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态0禁用1启用',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

*/


