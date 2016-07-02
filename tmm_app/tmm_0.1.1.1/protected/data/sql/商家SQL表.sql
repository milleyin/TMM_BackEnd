/*
Navicat MySQL Data Transfer

Source Server         : 120.24.251.67
Source Server Version : 50540
Source Host           : 120.24.251.67:3306
Source Database       : tianmimi

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-07-27 13:38:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tmm_store_content
-- ----------------------------
DROP TABLE IF EXISTS `tmm_store_content`;
CREATE TABLE `tmm_store_content` (
  `id` int(11) unsigned NOT NULL COMMENT '商家ID',  
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '商家名称',
  `push` float(5,2) NOT NULL DEFAULT '0.00' COMMENT '分成比例 % 最大为100  ',
  `income_count` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '收益总额',
  `cash` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已提现总额',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '可用金额（可提现）',
  `deposit` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '保证金',
  `area_id_p` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '省分ID',
  `area_id_m` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '城市ID',
  `area_id_c` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '县(区)ID',
  `address` varchar(100) NOT NULL COMMENT '商家地址',
  `store_tel` varchar(15) NOT NULL DEFAULT '' COMMENT '商家电话',
  `store_postcode` int(10) NOT NULL DEFAULT '0' COMMENT '邮政编码',
  `lx_contacts` varchar(15) NOT NULL DEFAULT '' COMMENT '负责人',
  `lx_identity_code` varchar(32) NOT NULL DEFAULT '' COMMENT '身份证',
  `lx_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '负责人电话',
  `identity_before` varchar(100) NOT NULL DEFAULT '' COMMENT '身份证扫描件（前）',
  `identity_after` varchar(100) NOT NULL DEFAULT '' COMMENT '身份证扫描件（后）',
  `identity_hand` varchar(100) NOT NULL DEFAULT '' COMMENT '手持身份证扫描件',
  `com_contacts` varchar(15) NOT NULL DEFAULT '' COMMENT '公司法人',
  `com_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '公司法人电话',
  `com_identity` varchar(32) NOT NULL DEFAULT '' COMMENT '公司法人身份证',
  `bl_code` varchar(100) NOT NULL DEFAULT '' COMMENT '营业执照编码',
  `bl_img` varchar(100) NOT NULL DEFAULT '' COMMENT '营业执照扫描件',
  `bank_id` int(11) unsigned NOT NULL COMMENT '开户银行',
  `bank_name` varchar(20) NOT NULL DEFAULT '' COMMENT '开户姓名',
  `bank_branch` varchar(100) NOT NULL DEFAULT '' COMMENT '开户支行',
  `bank_code` varchar(50) NOT NULL DEFAULT '' COMMENT '开户银行账号',
  `son_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '子账号数据0=没有子账号',
  `son_limit` int(11) unsigned NOT NULL DEFAULT '5' COMMENT '子账号数量上限',
  `audit` int(11) NOT NULL DEFAULT '0' COMMENT '0未通过 1=通过',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `pass_time` int(10) unsigned NOT NULL COMMENT '通过时间',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tmm_store_user
-- ----------------------------
DROP TABLE IF EXISTS `tmm_store_user`;
CREATE TABLE `tmm_store_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` VARCHAR(15) NOT NULL COMMENT '手机号(用户名)唯一',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `p_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT ' 0=>父账号 ?=>归属商家',
  `agent_id` int(11) unsigned NOT NULL COMMENT '归属代理ID',
  `icon` varchar(100)  NULL DEFAULT '' COMMENT '商家头像',
  `count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `phone_type` int(11) NOT NULL DEFAULT '1' COMMENT '手机类型1=ios2=android',
  `login_token` varchar(128)   NULL DEFAULT '' COMMENT '推送TOKEN',
  `login_mac` varchar(128)   NULL DEFAULT '' COMMENT '当次登录MAC',
  `last_mac` varchar(128)   NULL DEFAULT '' COMMENT '最近登录MAC',
  `login_error` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录错误次数 登录后清零',
  `error_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录错误统计',
  `login_ip` varchar(15) DEFAULT NULL COMMENT '登录ip',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `login_address` varchar(100) DEFAULT NULL COMMENT '登录地址',
  `last_ip` varchar(15) DEFAULT NULL COMMENT '最近登录ip',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最近登录时间',
  `last_address` varchar(100) DEFAULT NULL COMMENT '上次登录地址',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `out_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '过期时间',
  `status` int(10) NOT NULL DEFAULT '0' COMMENT '-1=删除 0=禁用 1=正常2=',
  PRIMARY KEY (`id`),
   UNIQUE INDEX `phone_UNIQUE` (`phone` ASC)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
