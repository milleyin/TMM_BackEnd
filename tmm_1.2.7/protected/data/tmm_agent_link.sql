-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 04 月 01 日 02:21
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `tianmimi`
--

-- --------------------------------------------------------

--
-- 表的结构 `tmm_agent_link`
--

CREATE TABLE IF NOT EXISTS `tmm_agent_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `p_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id 0为导航 ',
  `name` varchar(20) NOT NULL COMMENT '链接名字',
  `title` varchar(50) NOT NULL COMMENT 'title 标签值',
  `info` varchar(200) DEFAULT NULL COMMENT '说明',
  `url` varchar(100) DEFAULT '#' COMMENT 'url 地址 #表示组是没有链接',
  `params` varchar(100) NOT NULL DEFAULT 'array()' COMMENT 'params 链接参数',
  `target` varchar(20) NOT NULL DEFAULT '_parent' COMMENT '链接目标',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '链接排序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `show` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示导航',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '记录状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='代理商链接管理表' AUTO_INCREMENT=24 ;

--
-- 转存表中的数据 `tmm_agent_link`
--

INSERT INTO `tmm_agent_link` (`id`, `p_id`, `name`, `title`, `info`, `url`, `params`, `target`, `sort`, `add_time`, `up_time`, `show`, `status`) VALUES
(1, 0, '首页', '首页', '首页', '/operator/home/index', 'array()', '_parent', 10, 1459415434, 1459475016, 1, 1),
(2, 1, '我的账号管理', '我的账号管理', '我的账号管理', '#', 'array()', '_parent', 10, 1459415561, 1459422365, 1, 1),
(3, 2, '我的信息', '我的信息', '我的信息', '/operator/agent/own', 'array()', 'admin_right', 10, 1459415674, 1459415674, 1, 1),
(4, 0, '内容管理', '内容管理', '内容管理', '/operator/home/index', 'array()', '_parent', 20, 1459420084, 1459420084, 1, 1),
(5, 4, '项目管理', '项目管理', '项目管理', '#', 'array()', '_parent', 10, 1459420120, 1459420120, 1, 1),
(6, 4, '觅境管理', '觅境管理', '觅境管理', '#', 'array()', '_parent', 20, 1459420171, 1459420171, 1, 1),
(7, 5, '项目管理', '项目管理', '项目管理', '/operator/items/admin', 'array()', 'admin_right', 10, 1459420250, 1459420250, 1, 1),
(8, 5, '创建项目', '创建项目', '创建项目', '/operator/items/select', 'array()', 'admin_right', 20, 1459420465, 1459420641, 1, 1),
(9, 6, '觅境管理', '觅境管理', '觅境管理\r\n', '/operator/shops/admin', 'array()', 'admin_right', 10, 1459420746, 1459420957, 1, 1),
(10, 6, '创建景点', '创建景点', '创建景点', '/operator/dot/create', 'array()', 'admin_right', 20, 1459420807, 1459420807, 1, 1),
(11, 6, '创建线路', '创建线路', '创建线路', '/operator/thrand/create', 'array()', 'admin_right', 30, 1459420931, 1459420931, 1, 1),
(12, 0, '订单管理', '订单管理', '订单管理', '/operator/home/index', 'array()', '_parent', 30, 1459421068, 1459421068, 1, 1),
(13, 12, '我的订单', '我的订单', '我的订单', '#', 'array()', '_parent', 10, 1459421197, 1459421197, 1, 1),
(14, 13, '觅境订单', '觅境订单', '觅境订单', '/operator/order/admin', 'array()', 'admin_right', 10, 1459421261, 1459478783, 1, 1),
(15, 0, '资金管理', '资金管理', '资金管理', '/operator/home/index', 'array()', '_parent', 40, 1459421324, 1459421324, 1, 1),
(16, 15, '我的钱包', '我的钱包', '我的钱包', '#', 'array()', '_parent', 10, 1459421374, 1459421374, 1, 1),
(17, 16, '我的钱包', '我的钱包', '我的钱包', '/operator/account/view', 'array()', 'admin_right', 10, 1459421439, 1459421439, 1, 1),
(18, 16, '我的银行卡', '我的银行卡', '我的银行卡', '/operator/bankCard/view', 'array()', 'admin_right', 20, 1459421683, 1459421683, 1, 1),
(19, 16, '更新银行卡', '更新银行卡', '更新银行卡', '/operator/bankCard/update', 'array()', 'admin_right', 30, 1459421743, 1459421743, 1, 1),
(20, 16, '申请提现', '申请提现', '申请提现', '/operator/cash/create', 'array()', 'admin_right', 40, 1459421858, 1459421858, 1, 1),
(21, 16, '交易记录', '交易记录', '交易记录', '/operator/accountLog/admin', 'array()', 'admin_right', 50, 1459421922, 1459421922, 1, 1),
(22, 16, '提现记录', '提现记录', '提现记录', '/operator/cash/admin', 'array()', 'admin_right', 60, 1459421948, 1459421948, 1, 1),
(23, 2, '修改密码', '修改密码', '修改密码', '/operator/agent/pwd', 'array()', 'admin_right', 20, 1459477073, 1459477073, 1, 1),
(24, 2, '我的供应商', '我的供应商', '我的供应商', '/operator/store/admin', 'array()', 'admin_right', 30, 1459492554, 1459492554, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
